import requests
import os
import random
import sys
import json
import argparse
import fake_useragent 
from time import sleep
from fp.fp import FreeProxy

# Local Consts
RESULTS_DIR_PATH  = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__)))) + r"/Results"
DIRBUST_RESULTS_PATH = RESULTS_DIR_PATH + r"/Dirbust"
ERROR_CODES = [200, 401, 403, 405]

class DirBuster(object):
    SMALL_DIR_LIST_PATH = os.path.join(os.path.dirname(__file__), 'small_dirlist.txt')
    LARGE_DIR_LIST_PATH = os.path.join(os.path.dirname(__file__), 'large_dirlist.txt')
        
    def __init__(self, addr, wordlist_path, uid):
        if wordlist_path == 'large':
            self.wordlist = self.LARGE_DIR_LIST_PATH
        elif wordlist_path == 'small':
            self.wordlist = self.SMALL_DIR_LIST_PATH
        else:
            try:
                wordlist_file = open(wordlist_path, 'r')
                self.wordlist = wordlist_path
            except:
                raise ValueError("[-] Dirlist file not found")
            
        self._check_valid_domain_format(addr)
        self.user_id = uid
        self.make_results_dir(RESULTS_DIR_PATH)
        self.make_results_dir(DIRBUST_RESULTS_PATH)

    def _check_valid_domain_format(self, addr):
        if not ((addr.startswith('https://') or addr.startswith('http://')) and addr.endswith('/')):
            raise ValueError("[-] Address format is invalid!")
        self.addr = addr
        
    def make_results_dir(self, path):
        if not os.path.exists(path):
            os.mkdir(path)

    def _dirbust_results_from_urls(self, path, dictionaryarray):
        headarray = dictionaryarray
        
        for index, element in enumerate(path):
            exists = 0
            for head in headarray:
                if head['name'] == element:
                    head.setdefault('children', [])
                    headarray = head['children']
                    exists = 1
                    break
            if not exists:
                if index == len(path) - 1: 
                    headarray.append({'name': element})
                else:
                    headarray.append({'name': element, 'children': []})
                    headarray = headarray[-1]['children']
                
    def save_results_to_json(self, dirbust_results, crawler_results_file=None):
        """
        Writes the results into a json file.
        The file name will be the UID specified.
        If crawler's results file specified, the output JSON will
        be merged with it.
        """
        total_results = []
        temp_results = []
        
        if crawler_results_file:
            with open(crawler_results_file, 'r') as crawler_results:
                for url in crawler_results.read().splitlines():
                    dirbust_results['Pages'].append({'Page': url, 'Response': 200})

            for url in dirbust_results['Pages']:
                temp_results.append(url['Page'].replace(self.addr, ''))

            for i in temp_results:
                self._dirbust_results_from_urls([j for j in i.split('/') if j != ''] ,total_results)

            data = {'name': self.addr, 'children': total_results}
        else:
            data = dirbust_results['Pages']
                    
        with open(DIRBUST_RESULTS_PATH + r'/{}.json'.format(self.user_id), 'w') as f:
            json.dump(data, f, ensure_ascii=False, indent=4)
            
    def dirbust(self):
        dirs_found = []
        ua = fake_useragent.UserAgent()

        headers = { 'User-Agent': ua.random }

        remove_new_lines = lambda x: x.replace('\n', '')
        dir_list = list(map(remove_new_lines, open(self.wordlist, 'r').readlines()))
        proxy = FreeProxy().get()
            
        for directory in dir_list:
            search_dir = self.addr + '{}'.format(directory)
            resp = None
            while resp is None:
                try:
                    resp = requests.get(search_dir,
                                        headers=headers,
                                        proxies={'http': proxy})
                except requests.exceptions.ProxyError:
                    proxy = FreeProxy().get()

            if resp.status_code in ERROR_CODES:
                dirs_found.append({'Page': search_dir, 'Response': resp.status_code})

        return dirs_found
        
    def scan(self, crawler_results_file=None):
        try:
            dirs_found = self.dirbust()
        except Exception as e:
            dirs_found = {'DirBust': 'Failed to run DirBust module ' + str(e)}
            
        self.save_results_to_json({'Pages' : dirs_found}, crawler_results_file)


def get_args():
    """
    Get arguments for the port scanner script.
    """
    parser = argparse.ArgumentParser(description="Dirbust Module",
                                     usage="dirbust.py -d <domain> -f <WORDLIST> -u <USER_ID>")

    parser.add_argument('-d','--domain', help='The domain to scan', required=True)
    parser.add_argument('-u','--uid', help='User ID', required=True)
    parser.add_argument('-f','--wordlist', help='Wordlist File', required=True)
    parser.add_argument('-c','--crawler', help='Crawler results File', const=None, required=False)

    return vars(parser.parse_args())

def main():
    args = get_args()
    scanner = DirBuster(args['domain'], args['wordlist'], args['uid'])
    scanner.scan(args['crawler'])
    
if __name__ == '__main__':
    main()
