import os
import argparse
import json
import time
import sys
import requests
import logging

# Local Consts
RESULTS_DIR_PATH  = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__)))) + r"/Results"
SSL_RESULTS_PATH = RESULTS_DIR_PATH + r"/SSLScan"
API = 'https://api.ssllabs.com/api/v2/analyze/'

class SSLScan(object):
    """
    This modules uses free SSLLabs API: https://github.com/TrullJ/ssllabs.
    """
    def __init__(self, domain, uid):
        """
        Class variables initialize.
        Creating directories for the results.
        """
        self.user_id = uid
        self.domain_to_scan = domain
        self.make_results_dir(RESULTS_DIR_PATH)
        self.make_results_dir(SSL_RESULTS_PATH)
        
    def scan(self):
        try:
            payload = {
                        'host': self.domain_to_scan,
                        'publish': 'off',
                        'startNew': 'on',
                        'all': 'done',
                        'ignoreMismatch': 'on'
                      }
            results = self.requestAPI(payload)

            payload.pop('startNew')

            while results['status'] != 'READY' and results['status'] != 'ERROR':
                time.sleep(30)
                results = self.requestAPI(API, payload)
        except Exception as e:
            results = {'Error while fetching SSLScan results ' + str(e)}
            
        self.save_results_to_json(results)

    def requestAPI(self, payload={}):
        '''This is a helper method that takes the path to the relevant
            API call and the user-defined payload and requests the
            data/server test from Qualys SSL Labs.
            Returns JSON formatted data'''

        try:
            response = requests.get(API, params=payload)
        except requests.exception.RequestException:
            logging.exception('Request failed.')
            sys.exit(1)

        data = response.json()
        return data

    def make_results_dir(self, path):
        if not os.path.exists(path):
            os.mkdir(path)

    def save_results_to_json(self, results):
        """
        Writes the results into a json file.
        The file name will be the UID specified.
        """
        with open(SSL_RESULTS_PATH + r'/{}.json'.format(self.user_id), 'a') as f:
            json.dump(results, f, ensure_ascii=False, indent=4)

def get_args():
    """
    Get arguments for the SSL scanner script.
    """
    parser = argparse.ArgumentParser(description="SSL Scan Module",
                                     usage="sslscan.py -d <DOMAIN> -u <USER_ID>")

    parser.add_argument('-d','--domain', help='The domain to scan', required=True)
    parser.add_argument('-u','--uid', help='User ID', required=True)

    return vars(parser.parse_args())

def main():
    """
    Main function.
    """
    args = get_args()
    scanner = SSLScan(args['domain'], args['uid'])
    scanner.scan()

    #resp = requests.get(r'localhost')
    
if __name__ == '__main__':
    main()
