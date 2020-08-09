import os
import sys
import argparse

sys.path.append(os.path.abspath(os.path.join(__file__, os.pardir)) + '/..')
from Utils.helpers import *

class AuthBypassScan(object):
    def __init__(self, uid, unauth_crawler_results_file, auth_crawler_results_file):
        self.uid = uid

        if os.path.exists(unauth_crawler_results_file) and os.path.exists(auth_crawler_results_file):
            self.unauth_crawler_results_file = unauth_crawler_results_file
            self.auth_crawler_results_file = auth_crawler_results_file
        else:
            raise ValueError("Crawler results file does not exist")

        self.unauth_crawler_results = []
        self.auth_crawler_results = []
        self.results = []

    def _read_crawler_results(self):
        if self.unauth_crawler_results_file is not None:
            with open(self.unauth_crawler_results_file, 'r') as crawler_results_file:
                self.unauth_crawler_results = json.load(crawler_results_file)

        if self.auth_crawler_results_file is not None:
            with open(self.auth_crawler_results_file, 'r') as crawler_results_file:
                self.auth_crawler_results = json.load(crawler_results_file)

    def _compare_results(self):
        return list(set(self.unauth_crawler_results['Info']) - (set(self.auth_crawler_results['Info'])))

    def scan(self):
        self._read_crawler_results()
        results = self._compare_results()
        save_results_to_json(AUTHBYPASS_RESULTS_PATH, results, self.uid)
    
def get_args():
    """
    Get arguments for the Authentication Bypass scanner.
    """
    parser = argparse.ArgumentParser(description="AuthBypass Scan Module")

    parser.add_argument('-u','--uid', help='User ID', required=True)
    parser.add_argument('-ru','--unauth-results', help='Unauth crawler results file', required=False)
    parser.add_argument('-ra','--auth-results', help='Unauth crawler results file', required=False)

    return vars(parser.parse_args())

def main():
    """
    Main function.
    """
    args = get_args()
    scanner = AuthBypassScan(args['uid'],
                             args['unauth_results'],
                             args['auth_results'])
    scanner.scan()

if __name__ == "__main__":
    main()
