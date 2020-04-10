import requests
import re
import os
import time
import random
import socket
import argparse
from bs4 import BeautifulSoup
from fp.fp import FreeProxy
from urllib.parse import urlparse

# Local Consts
RESULTS_DIR_PATH  = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__)))) + r"/Results"
CRAWLER_RESULTS_PATH = RESULTS_DIR_PATH + r"/Crawler"

class Crawler(object):
    def __init__(self, uid, starting_url):
        self.starting_url = starting_url
        self.visited = set() 
        self.user_agent = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36"
        parsed = urlparse(starting_url)
        self.domain_name = f"{parsed.scheme}://{parsed.netloc}"
        self.proxy = FreeProxy().get()
        self.user_id = uid
        self.make_results_dir(RESULTS_DIR_PATH)
        self.make_results_dir(CRAWLER_RESULTS_PATH)
        
    def random_sleep(self):
        return random.random()
    
    def get_html(self, url):    
        html = None
        
        while html is None:
            try:
                html = requests.get(url,
                                    headers={"User-Agent":self.user_agent},
                                    timeout=5,
                                    proxies={'http': self.proxy})
                time.sleep(self.random_sleep())
            except socket.gaierror:
                self.proxy = FreeProxy().get()
            except Exception as e:
                raise ValueError(str(e))
            
        return html.content.decode('utf-8')
    
    def get_links(self, url):
        try:
            html = self.get_html(url)
            soup = BeautifulSoup(html, "html.parser")
            href_tags = soup.find_all(href=True)
            hrefs = [tag.get('href') for tag in href_tags]
        except:
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

        if base == self.domain_name:
            return True

        return False
        
    def extract_info(self, html):  
        return None    

    def crawl(self, url):
        """
        The loop iterates through the source code of the site for dry dirbust.
        """
        for link in self.get_links(url):
            if link in self.visited:    
                continue
            self.visited.add(link)
            self.crawl(link)

    def make_results_dir(self, path):
        if not os.path.exists(path):
            os.mkdir(path)
            
    def save_results(self):
        try:
            with open(CRAWLER_RESULTS_PATH + r'/{}.txt'.format(self.user_id), 'w') as output_file:
                for link in self.visited:
                    output_file.write(link + '\n')
        except:
            return
            
    def scan(self):             
        self.crawl(self.starting_url)
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
