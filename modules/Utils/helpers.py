import os
import json
import requests

RESULTS_DIR_PATH  = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__)))) + r"/Results"

VULNSCAN_RESULTS_PATH = RESULTS_DIR_PATH + r"/Vulnscan"

XSS_RESULTS_PATH = RESULTS_DIR_PATH + r"/XssScan"

CSRF_RESULTS_PATH = RESULTS_DIR_PATH + r"/CsrfScan"

AUTHBYPASS_RESULTS_PATH = RESULTS_DIR_PATH + r"/AuthBypass"

CRAWLER_RESULTS_PATH = RESULTS_DIR_PATH + r"/Crawler"

API_URL = r'http://127.0.0.1:8080/penetrate/helpers/ScansForm.php'

API_DATA = {'table_name': '',
            'ScanID': '',
            'Status': 'Finished',
            'GUID': 'ETAI_ITAY123AA6548'}

DEFAULT_USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36'

def make_results_dir(path):
    if not os.path.exists(path):
        os.mkdir(path)

def save_results_to_json(path, results, user_id):
    """
    Writes the results into a json file.
    The file name will be the UID specified.
    """
    with open(path + r'/{}.json'.format(user_id), 'w') as f:
        json.dump(results, f, ensure_ascii=False, indent=4)

def send_to_api(scan_id, table_name):
    API_DATA['ScanID'] = scan_id 
    API_DATA['table_name'] = table_name 
    resp = requests.post(API_URL, API_DATA)

def get_unauth_cookie(url):
    unauth_cookies = requests.get(url, headers={'User-Agent': DEFAULT_USER_AGENT}).cookies.get_dict()

    if unauth_cookies == {}:
        return None
    return unauth_cookies

