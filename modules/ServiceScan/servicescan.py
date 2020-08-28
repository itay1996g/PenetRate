import os
import re
import sys
import json
import requests
import argparse
import pkg_resources
from bs4 import BeautifulSoup

sys.path.append(os.path.abspath(os.path.join(__file__, os.pardir)) + '/..')
from Utils.helpers import *

# Local Consts
SERVICES_RESULTS_PATH = RESULTS_DIR_PATH + r"/ServiceScan"
SERVICES_TABLE_NAME = r"services_scan"

class OpenServicesScanner(object):
    def __init__(self, url, uid):
        """
        Class variables initialize.
        Creating directories for the results.
        """
        self.user_id = uid
        self.url_to_scan = self._init_webpage(url)
        self.open_services = []
        self.apps_file = json.loads(pkg_resources.resource_string(__name__, "apps.json"))
        self.detected_apps = []
        
        make_results_dir(RESULTS_DIR_PATH)
        make_results_dir(SERVICES_RESULTS_PATH)

    def _init_webpage(self, url):
        webpage={}
        
        response = requests.get(url, headers={'User-Agent': DEFAULT_USER_AGENT})
        
        webpage['url'] = response.url
        webpage['headers'] = response.headers
        webpage['response'] = response.text
        
        webpage['html'] = BeautifulSoup(response.text, 'html.parser')
        
        webpage['scripts'] = [script['src'] for script in webpage['html'].findAll('script', src=True)]
        webpage['metatags'] = {meta['name'].lower(): meta['content']
                    for meta in webpage['html'].findAll('meta', attrs=dict(name=True, content=True))}
        
        return webpage

    def _prepare_app(self, app):
        for key in ['url', 'html', 'script', 'implies']:
            try:
                value = app[key]
            except KeyError:
                app[key] = []
            else:
                if not isinstance(value, list):
                    app[key] = [value]

        for key in ['headers', 'meta']:
            try:
                value = app[key]
            except KeyError:
                app[key] = {}

        obj = app['meta']
        if not isinstance(obj, dict):
            app['meta'] = {'generator': obj}

        for key in ['headers', 'meta']:
            obj = app[key]
            app[key] = {k.lower(): v for k, v in obj.items()}

        for key in ['url', 'html', 'script']:
            app[key] = [self._prepare_pattern(pattern) for pattern in app[key]]

        for key in ['headers', 'meta']:
            obj = app[key]
            for name, pattern in obj.items():
                obj[name] = self._prepare_pattern(obj[name])

    def _prepare_pattern(self, pattern):
        regex, _, rest = pattern.partition('\\;')
        try:
            return re.compile(regex, re.I)
        except re.error as e:
            warnings.warn(
                "Caught '{error}' compiling regex: {regex}"
                .format(error=e, regex=regex))
            
            return re.compile(r'(?!x)x')

    def _has_app(self, app, webpage):
        for regex in app['url']:
            if regex.search(webpage['url']):
                return True
        for name, regex in app['headers'].items():
            if name in webpage['headers']:
                content = webpage['headers'][name]
                if regex.search(content):
                    return True
        for regex in app['script']:
            for script in webpage['scripts']:
                if regex.search(script):
                    return True
        for name, regex in app['meta'].items():
            if name in webpage['metatags']:
                content = webpage['metatags'][name]
                if regex.search(content):
                    return True
        for regex in app['html']:
            if regex.search(webpage['response']):
                return True

    def _get_implied_apps(self, detected_apps, apps1):
        def __get_implied_apps(detect, apps):
            _implied_apps = set()
            for detected in detect:
                try:
                    _implied_apps.update(set(apps[detected]['implies']))
                except KeyError:
                    pass
            return _implied_apps

        implied_apps = __get_implied_apps(detected_apps, apps1)
        all_implied_apps = set()

        while not all_implied_apps.issuperset(implied_apps):
            all_implied_apps.update(implied_apps)
            implied_apps = __get_implied_apps(all_implied_apps, apps1)

        return all_implied_apps
        
    def scan(self):
        """
        Iterate through all open services, and append the service's name
        to a list. Return this list as a result format (JSON).
        """
        category_wise = {}
        results = {}
        apps_in_file = self.apps_file['apps']

        try:
            for app_name, app in apps_in_file.items():
                self._prepare_app(app)
                if self._has_app(app, self.url_to_scan):
                    self.detected_apps.append(app_name)

            self.detected_apps = set(self.detected_apps).union(self._get_implied_apps(self.detected_apps, apps_in_file))

            for app_name in self.detected_apps:
                cats = apps_in_file[app_name]['cats']
                for cat in cats:
                    category_wise[app_name] = self.apps_file['categories'][str(cat)]['name']

            for k, v in category_wise.items():
                results[v] = results.get(v, [])
                results[v].append(k)

        except Exception as e:
            raise e
            results = {'Error while fetching Open Services results':  str(e)}

        save_results_to_json(SERVICES_RESULTS_PATH, results, self.user_id)


def get_args():
    """
    Get arguments for the Services Scan script.
    """
    parser = argparse.ArgumentParser(description="Service Scan Module",
                                     usage="servicescan.py -d <DOMAIN> -u <USER_ID>")

    parser.add_argument('-d','--domain', help='The domain to scan', required=True)
    parser.add_argument('-u','--uid', help='User ID', required=True)

    return vars(parser.parse_args())

def main():
    """
    Main function.
    """
    args = get_args()
    scanner = OpenServicesScanner(args['domain'], args['uid'])
    results = scanner.scan()
    send_to_api(args['uid'], SERVICES_TABLE_NAME)
    
if __name__ == '__main__':
    main()
