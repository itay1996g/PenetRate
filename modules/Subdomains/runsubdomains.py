from getpossiblesubdomains import SmartSubdomainGuesser
import requests
import json
import os
import argparse


API_URL = r'http://127.0.0.1:8080/penetrate/helpers/ScansForm.php'
API_DATA = {'table_name': 'subdomains_scan',
            'ScanID': '',
            'Status': 'Finished',
            'GUID': 'ETAI_ITAY123AA6548'}


class SubdomainScanner(object):
    def __init__(self, url, uid):
        """
        Class variables initialize.
        Creating directories for the results.
        """
        self.user_id = uid
        self.url_to_scan = url

    def scan(self):
        """
        Iterate through all open services, and append the service's name and version
        to a list. Return this list as a result format (JSON).
        """
        
        domain_to_check = self.url_to_scan
        domain_to_check = domain_to_check.replace('https://','').replace('http://','').replace('www','')
        guesses = SmartSubdomainGuesser([''], 15, domain_to_check).guess()
        open_subdomains = []
        for domain in guesses:
            url = domain + '' + domain_to_check
            try:
                if requests.get('https://' + url).status_code == 200:
                    print('Web site exists ' + url)
                    open_subdomains.append({'Subdomain': url})
                else:
                    print('Web site does not exist' + url)
            except:
                continue
                print('Error in ' + url)

        HEADERS_RESULTS_PATH = r"C:\\Users\\User.User-PC\\Dropbox\\UniSchool\\PenetRate\\Results\\Subdomains\\"
        results = {'Subdomain_list': open_subdomains}
        with open(HEADERS_RESULTS_PATH + r'/{}.json'.format(self.user_id), 'w') as f:
            json.dump(results, f, ensure_ascii=False, indent=4)


def send_to_api(scan_id):
    API_DATA['ScanID'] = scan_id
    resp = requests.post(API_URL, API_DATA)


def get_args():
    """
    Get arguments for the port scanner script.
    """
    parser = argparse.ArgumentParser(description="Subdomain Scan Module",
                                     usage="runsubdomains.py -d <DOMAIN> -u <Scan_ID>")

    parser.add_argument(
        '-d', '--domain', help='The domain to scan', required=True)
    parser.add_argument('-u', '--uid', help='Scan ID', required=True)

    return vars(parser.parse_args())


def main():
    """
    Main function.
    """
    args = get_args()
    scanner = SubdomainScanner(args['domain'], args['uid'])
    scanner.scan()
    send_to_api(args['uid'])


if __name__ == '__main__':
    main()
