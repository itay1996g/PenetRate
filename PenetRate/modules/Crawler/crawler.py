import re
import os
import json
import time
import random
import socket
import argparse
import requests
from bs4 import BeautifulSoup
from fp.fp import FreeProxy
from urllib.parse import urlparse

# Local Consts
RESULTS_DIR_PATH  = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__)))) + r"/Results"
CRAWLER_RESULTS_PATH = RESULTS_DIR_PATH + r"/Crawler"
EMAIL_REGEX = '[\w\.=-]+@[\w\.-]+\.[\w]{2,3}'
IP_ADDRESS_REGEX = '(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$'

class Crawler(object):
    def __init__(self, uid, starting_url):
        self._starting_url = starting_url
        self._visited = set() 
        self._user_agent = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36"
        parsed = urlparse(starting_url)
        self._domain_name = f"{parsed.scheme}://{parsed.netloc}"
        self._proxy = FreeProxy().get()
        self._user_id = uid
        self._make_results_dir(RESULTS_DIR_PATH)
        self._make_results_dir(CRAWLER_RESULTS_PATH)
        self._personal_info = []
        
    def _random_sleep(self):
        return random.random()
    
    def get_html(self, url):    
        html = None
        
        while html is None:
            try:
                html = requests.get(url,
                                    headers={"User-Agent":self._user_agent},
                                    timeout=5,
                                    proxies={'http': self._proxy})
                self.extract_info(html)
                time.sleep(self._random_sleep())
            except socket.gaierror:
                self._proxy = FreeProxy().get()
            except Exception as e:
                raise ValueError(str(e))
            
        return html.content.decode('utf-8')
    
    def get_links(self, url):
        try:
            html = self.get_html(url)
            soup = BeautifulSoup(html, "html.parser")
            href_tags = soup.find_all(href=True)
            hrefs = [tag.get('href') for tag in href_tags]
        except Exception as e:
            return set()
        
        parsed = urlparse(url) 
        base = f"{parsed.scheme}://{parsed.netloc}"
        
        same_site_links = []
        
        for i in hrefs:
            if self.check_same_site(i):
                same_site_links.append(i)
                
        for i, link in enumerate(same_site_links):
            if not urlparse(link).netloc:
                link_with_base = base + link
                same_site_links[i] = link_with_base    

        return set(filter(lambda x: 'mailto' not in x, same_site_links))
            
    def check_same_site(self, url):
        parsed = urlparse(url) 
        base = f"{parsed.scheme}://{parsed.netloc}"

        if base == self._domain_name:
            return True

        return False

    def add_regex_results(self, regex, category, html):
        JSON_OUTPUT_FORMAT = {'Name': None,
                              'Value': None,
                              'URL': None}
        
        regex_result = re.findall(regex, html.text)

        if len(regex_result) > 0:
            JSON_OUTPUT_FORMAT['Name'] = category
            JSON_OUTPUT_FORMAT['Value'] = regex_result
            JSON_OUTPUT_FORMAT['URL'] = html.url

            if JSON_OUTPUT_FORMAT not in self._personal_info:
                self._personal_info.append(JSON_OUTPUT_FORMAT)
        
    def extract_info(self, html):
        self.add_regex_results(EMAIL_REGEX, 'Email', html)
        self.add_regex_results(IP_ADDRESS_REGEX, 'IP Address', html)

    def crawl(self, url):
        """
        The loop iterates through the source code of the site for dry dirbust.
        """
        for link in self.get_links(url):
            if link in self._visited:    
                continue
            self._visited.add(link)
            self.crawl(link)

    def _make_results_dir(self, path):
        if not os.path.exists(path):
            os.mkdir(path)
            
    def save_results(self):
        try:            
            # Saving all of the URLs retrived
            with open(CRAWLER_RESULTS_PATH + r'/{}.txt'.format(self._user_id), 'w') as output_file:
                results = {'Info': []}
                for link in self._visited:
                    results['Info'].append(link)
                json.dump(results, output_file, ensure_ascii=False, indent=4)

            # Saving all of the data extracted from source codes
            with open(CRAWLER_RESULTS_PATH + r'/{}_extract_info.txt'.format(self._user_id), 'w') as output_file:
                results = {'Info': []}
                for info in self._personal_info:
                    results['Info'].append(str(info))
                json.dump(results, output_file, ensure_ascii=False, indent=4)
        except Exception as e:
            raise e
            
    def scan(self):             
        self.crawl(self._starting_url)
        self.save_results()

def get_args():
    """
    Get arguments for the port scanner script.
    """
    parser = argparse.ArgumentParser(description="Website Crawler Module",
                                     usage="crawler.py -d <domain> -u <USER_ID>")

    parser.add_argument('-d','--domain', help='The IP Address to scan', required=True)
    parser.add_argument('-u','--uid', help='User ID', required=True)

    return vars(parser.parse_args())

def main():
    """
    Main function.
    """
    args = get_args()
    scanner = Crawler(args['uid'], args['domain'])
    scanner.scan()

if __name__ == "__main__":
    main()
