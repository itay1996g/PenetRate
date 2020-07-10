import os
import sys
import requests
import argparse

sys.path.append(os.getcwd() + '/..')
from Utils.helpers import *

# Local Consts
WAPPALYZER_API_URL = r"https://api.wappalyzer.com/lookup/v1/"
WAPPALYZER_API_KEY = r"wappalyzer.api.demo.key"
RESULTS_DIR_PATH  = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__)))) + r"/Results"
SERVICES_RESULTS_PATH = RESULTS_DIR_PATH + r"/ServiceScan"


class OpenServicesScanner(object):
    def __init__(self, url, uid):
        """
        Class variables initialize.
        Creating directories for the results.
        """
        self.user_id = uid
        self.url_to_scan = url
        self.open_services = []
        make_results_dir(RESULTS_DIR_PATH)
        make_results_dir(SERVICES_RESULTS_PATH)

    def find_services(self):
        """
        Check open services on site using Wapalyzer free API.
        """
        headers = { 'x-api-key': WAPPALYZER_API_KEY }
        
        params = (('url', self.url_to_scan),)
        
        return requests.get(WAPPALYZER_API_URL,
                            headers=headers,
                            params=params).json()
        
    def scan(self):
        """
        Iterate through all open services, and append the service's name and version
        to a list. Return this list as a result format (JSON).
        """
        try:
            for service in self.find_services():
                for app in service['applications']:
                    self.open_services.append( { 'Name': app['name'],
                                                 'Version': app['versions'] } )
            results =  { 'Service Scan': self.open_services }
        except Exception as e:
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
    send_to_api(args['uid'])
    
if __name__ == '__main__':
    main()
