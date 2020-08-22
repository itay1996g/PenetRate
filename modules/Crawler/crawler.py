import re
import os
import sys
import ssl
from bs4 import BeautifulSoup
from queue import Queue, Empty
from urllib.parse import urlparse
from concurrent.futures import ThreadPoolExecutor
from urllib.request import Request, urlopen, urljoin, URLError, HTTPError

sys.path.append(os.path.abspath(os.path.join(__file__, os.pardir)) + '/..')
from VulnScan.xss import XssScanner
from VulnScan.csrf import CsrfScanner
from VulnScan.authbypass import *
from VulnScan.sqli import *
from Utils.helpers import *

EMAIL_REGEX = '[\w\.=-]+@[\w\.-]+\.[\w]{2,3}'
IP_ADDRESS_REGEX = '(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$'

class Crawler(object):
  def __init__(self, base_url, attack_mode=False, auth_cookie=None):
    self.base_url = base_url
    self.cookies = auth_cookie
    self.headers = {}
    self.attack = attack_mode
    self.crawled_links = set([])
    self.links_to_crawl = Queue()
    self._personal_info = []

    self._init_ssl()
    self._init_headers()

    self.pool = ThreadPoolExecutor(max_workers=20)
    self.links_to_crawl.put(self.base_url)

  def __enter__(self):
    return self

  def __exit__(self, exc_type, exc_value, traceback):
    self.base_url = None
    self.cookies = None
    self.headers = None
    self.attack = None
    self.crawled_links = None
    self.links_to_crawl = None
    self._personal_info = None

  def _init_ssl(self):
    self.myssl = ssl.create_default_context()
    self.myssl.check_hostname = False
    self.myssl.verify_mode = ssl.CERT_NONE

  def _init_headers(self):
    if self.cookies is not None:
      self.headers['Cookie'] = self.cookies
    else:
      unauth_cookie = get_unauth_cookie(self.base_url)

      if unauth_cookie:
        self.headers['Cookie'] = "; ".join([str(x) + "=" + str(y) for x, y in unauth_cookie.items()])
    
    self.headers['User-Agent'] = DEFAULT_USER_AGENT

  def _check_same_site(self, url):
    if urlparse(url).netloc == urlparse(self.base_url).netloc:
      return True
    return False

  def _url_request(self, url):
    try:
      request = Request(url, headers=self.headers)
      return urlopen(request, context=self.myssl)
    except Exception as e:
      raise e

  def _valid_url_to_crawl(self, url):
    if self._check_same_site(url) and url not in self.crawled_links:
      return True
    return False

  def _get_hrefs_from_html(self, html):
    soup = BeautifulSoup(html, 'html.parser')
    hrefs = soup.find_all('a', href=True)
    links = soup.find_all('link', href=True)

    all_links = links + hrefs

    for link in all_links:
      url = link['href']
      url_joined = urljoin(self.base_url, url)

      if self._valid_url_to_crawl(url_joined):
        self.links_to_crawl.put(url_joined)

  def _attack_link(self, url):
    xss_result = self.xss_scanner.scan(url)
    csrf_result = self.csrf_scanner.scan(url)

    if xss_result is not None:
      for form in xss_result:
        if form != {} and form not in self.vuln_results['XSS']:
          self.vuln_results['XSS'].append(form)

    if csrf_result is not None:
      for form in csrf_result:
        if form != {} and form not in self.vuln_results['CSRF']:
          self.vuln_results['CSRF'].append(form)

  def post_crawl_callback(self, res):
    result = res.result()

    if result and result.status == 200:
      html = result.read().decode('utf-8', 'ignore')
      self._get_hrefs_from_html(html)
      self.extract_info(html, result.url)

      if self.attack:
        self._attack_link(result.url)

  def crawl_page(self, url):
    try:
      response = self._url_request(url)
      return response
    except Exception as e:
      return

  def crawl(self):
    while True:
      try:
        target_url = self.links_to_crawl.get(timeout=60)

        if target_url not in self.crawled_links:
          self.crawled_links.add(target_url)

          job = self.pool.submit(self.crawl_page, target_url)
          job.add_done_callback(self.post_crawl_callback)

      except Empty:
        return
      except Exception as e:
        print(e)
        continue

  def save_results(self, user_id, attack_mode='unauth'):
    try:            
      with open(CRAWLER_RESULTS_PATH + r'/{}_{}.json'.format(attack_mode, user_id), 'w', encoding='utf8', errors="surrogateescape") as output_file:
        json.dump({'Info': list(self.crawled_links)}, output_file, ensure_ascii=False, indent=4)

      with open(SENSITIVEINFO_RESULTS_PATH + r'/{}_{}_extract_info.json'.format(attack_mode, user_id), 'w', encoding='utf8', errors="surrogateescape") as output_file:
        results_to_file = {'Info': []}
        for info in self._personal_info:
          results_to_file['Info'].append(info)
        json.dump(results_to_file, output_file, ensure_ascii=False, indent=4)
    except Exception as e:
      raise e

  def add_regex_results(self, regex, category, html, response_url):
    JSON_OUTPUT_FORMAT = {'Name': None,
                          'Value': None,
                          'URL': None}
    
    regex_result = re.findall(regex, html)

    if len(regex_result) > 0:
        JSON_OUTPUT_FORMAT['Name'] = category
        JSON_OUTPUT_FORMAT['Value'] = regex_result
        JSON_OUTPUT_FORMAT['URL'] = response_url

        if JSON_OUTPUT_FORMAT not in self._personal_info:
            self._personal_info.append(JSON_OUTPUT_FORMAT)

  def extract_info(self, html, response_url):
    self.add_regex_results(EMAIL_REGEX, 'Email', html, response_url)
    self.add_regex_results(IP_ADDRESS_REGEX, 'IP Address', html, response_url)

  def _config_attack(self):
    self.vuln_results = {}

    self.vuln_results['XSS'] = []
    self.vuln_results['CSRF'] = []

    self.xss_scanner = XssScanner(auth_cookie=self.cookies)
    self.csrf_scanner = CsrfScanner(auth_cookie=self.cookies)

    make_results_dir(RESULTS_DIR_PATH)
    make_results_dir(XSS_RESULTS_PATH)
    make_results_dir(CSRF_RESULTS_PATH)
    make_results_dir(SQLI_RESULTS_PATH)
    make_results_dir(AUTHBYPASS_RESULTS_PATH)
