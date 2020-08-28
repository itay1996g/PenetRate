import os
import sys
import time
import logging
import requests
import argparse

sys.path.append(os.path.abspath(os.path.join(__file__, os.pardir)) + '/..')
from Utils.helpers import *

# Local Consts
SSL_RESULTS_PATH = RESULTS_DIR_PATH + r"/SSLScan"
API = r'https://api.ssllabs.com/api/v2/analyze/'
SSL_TABLE_NAME = r"ssl_scan"

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
        make_results_dir(RESULTS_DIR_PATH)
        make_results_dir(SSL_RESULTS_PATH)
        
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
                results = self.requestAPI(payload)
        except Exception as e:
            results = {'Error while fetching SSLScan results':  str(e)}

        save_results_to_json(SSL_RESULTS_PATH, results, self.user_id)

    def requestAPI(self, payload={}):
        '''This is a helper method that takes the path to the relevant
            API call and the user-defined payload and requests the
            data/server test from Qualys SSL Labs.
            Returns JSON formatted data'''

        try:
            response = requests.get(API, params=payload)
        except requests.exceptions.RequestException:
            logging.exception('Request failed.')
            sys.exit(1)

        data = response.json()
        return data
    
    
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
    send_to_api(args['uid'], SSL_TABLE_NAME)
        
if __name__ == '__main__':
    main()
