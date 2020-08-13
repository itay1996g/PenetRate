import os
import sys
import argparse
from urllib.parse import urljoin, urlparse
from bs4 import BeautifulSoup as bs
from string import ascii_uppercase, ascii_lowercase

sys.path.append(os.path.abspath(os.path.join(__file__, os.pardir)) + '/..')
from Utils.helpers import *

DEFAULT_HEADERS = {}

class VulnScanner(object):

    def __init__(self, auth_cookie=None):
        """
        Class variables initialize.
        Creating directories for the results.
        """
        self.url = ''

        self.results = []
        self.headers = {}

        self.headers['User-Agent'] = DEFAULT_USER_AGENT

        if auth_cookie:
            self.headers['Cookie'] = auth_cookie

    def _check_same_site(self, url):
        if urlparse(url).netloc == urlparse(self.url).netloc or urlparse(url).netloc == '':
            return True

    def get_all_forms(self, url):
        forms_found = []
    
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
            print ("Vuln: " + str(e))
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
