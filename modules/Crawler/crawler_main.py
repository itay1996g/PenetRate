import re
import os
import sys
import ssl
import queue
from bs4 import BeautifulSoup
from urllib.parse import urlparse
from urllib.request import Request, urlopen, urljoin, URLError, HTTPError

sys.path.append(os.path.abspath(os.path.join(__file__, os.pardir)) + '/..')
from VulnScan.xss import XssScanner
from VulnScan.csrf import CsrfScanner
from Utils.helpers import *

EMAIL_REGEX = '[\w\.=-]+@[\w\.-]+\.[\w]{2,3}'
IP_ADDRESS_REGEX = '(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$'

class CheckableQueue(queue.Queue):
  def __contains__(self, item):
    with self.mutex:
      return item in self.queue

  def __len__(self):
    return len(self.queue)

  
class Crawler(object):
  def __init__(self, base_url, cookies=None, attack_mode=False):
    self.base_url = base_url
    
    self.myssl = ssl.create_default_context()
    self.myssl.check_hostname = False
    self.myssl.verify_mode = ssl.CERT_NONE

    self.cookies = cookies 
    self.attack = attack_mode
    self._personal_info = []
    self.headers = {}
  
    self.myssl = ssl.create_default_context()
    self.myssl.check_hostname = False
    self.myssl.verify_mode = ssl.CERT_NONE
    
    self.crawledLinks = set()
    self.errorLinks = set()
    
    if cookies is not None:
      self.headers['Cookie'] = self.cookies
    else:
      unauth_cookie = get_unauth_cookie(base_url)

      if unauth_cookie:
        self.headers['Cookie'] = unauth_cookie
    
    self.headers['User-Agent'] = DEFAULT_USER_AGENT

  def crawl(self, thread_name, url, links_to_crawl, attack=False):
    try:
      link = urljoin(self.base_url, url)

      if (urlparse(link).netloc == urlparse(self.base_url).netloc) and (link not in self.crawledLinks):
        request = Request(link, headers=self.headers)
        response = urlopen(request, context=self.myssl)
        
        self.crawledLinks.add(link)
        html = response.read().decode('utf-8', 'ignore')
        response_url = response.url

        soup = BeautifulSoup(html, "html.parser")
        self.extract_info(html, response_url)
        
        self.enqueueLinks(soup.find_all('a'), links_to_crawl)

        if attack:
          xss_result = self.xss_scanner.scan(link)
          csrf_result = self.csrf_scanner.scan(link)

          if xss_result != []:
            self.vuln_results['XSS'].append(xss_result)

          if csrf_result != []:
            self.vuln_results['CSRF'].append(csrf_result)

        return url, response.getcode()

    except requests.exceptions.SSLError as ssl_error:
      pass
    except HTTPError:
      pass
    except URLError:
      pass
    except Exception as e:
      print (url)
      print (str(e))

  def enqueueLinks(self, links, links_to_crawl): 
    for link in links:
      if (urljoin(self.base_url, link.get('href')) not in self.crawledLinks):
        if (urljoin(self.base_url, link.get('href')) not in links_to_crawl):
          links_to_crawl.put(link.get('href'))

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
    self.vuln_results['SQLI'] = []

    self.xss_scanner = XssScanner(auth_cookie=self.cookies)
    self.csrf_scanner = CsrfScanner(auth_cookie=self.cookies)

    make_results_dir(RESULTS_DIR_PATH)
    make_results_dir(XSS_RESULTS_PATH)
    make_results_dir(CSRF_RESULTS_PATH)
    make_results_dir(AUTHBYPASS_RESULTS_PATH)
