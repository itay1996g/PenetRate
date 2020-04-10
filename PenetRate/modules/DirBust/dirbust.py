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
            
        self.addr = addr
        self.user_id = uid
        self.make_results_dir(RESULTS_DIR_PATH)
        self.make_results_dir(DIRBUST_RESULTS_PATH)

    def make_results_dir(self, path):
        if not os.path.exists(path):
            os.mkdir(path)

    def save_results_to_json(self, results):
        """
        Writes the results into a json file.
        The file name will be the UID specified.
        """
        with open(DIRBUST_RESULTS_PATH + r'/{}.json'.format(self.user_id), 'w') as f:
            json.dump(results, f, ensure_ascii=False, indent=4)
            
    def dirbust(self):
        dirs_found = []
        ua = fake_useragent.UserAgent()

        headers = { 'User-Agent': ua.random }

        remove_new_lines = lambda x: x.replace('\n', '')
        dir_list = list(map(remove_new_lines, open(self.wordlist, 'r').readlines()))
        proxy = FreeProxy().get()
            
        for directory in dir_list:
            search_dir = self.addr + '/{}'.format(directory)
            resp = None
            while resp is None:
                try:
                    resp = requests.get(search_dir,
                                        headers=headers,
                                        proxies={'http': proxy})
                except requests.exceptions.ProxyError:
                    proxy = FreeProxy().get()

            if resp.status_code in ERROR_CODES:
                dirs_found.append({search_dir:resp.status_code})

        return dirs_found
        
    def scan(self):
        try:
            dirs_found = self.dirbust()
        except Exception as e:
            dirs_found = {'DirBust': 'Failed to run DirBust module ' + str(e)}
            
        self.save_results_to_json(dirs_found)


def get_args():
    """
    Get arguments for the port scanner script.
    """
    parser = argparse.ArgumentParser(description="Dirbust Module",
                                     usage="dirbust.py -d <domain> -f <WORDLIST> -u <USER_ID>")

    parser.add_argument('-d','--domain', help='The domain to scan', required=True)
    parser.add_argument('-u','--uid', help='User ID', required=True)
    parser.add_argument('-f','--wordlist', help='Wordlist File', required=True)

    return vars(parser.parse_args())

def main():
    args = get_args()
    scanner = DirBuster(args['domain'], args['wordlist'], args['uid'])
    scanner.scan()
    
if __name__ == '__main__':
    main()
