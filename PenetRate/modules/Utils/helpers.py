import os
import json
import requests

API_URL = r'http://127.0.0.1:8080/penetrate/helpers/ScansForm.php'

API_DATA = {'table_name': 'service_scan',
            'ScanID': '',
            'Status': 'Finished',
            'GUID': 'ETAI_ITAY123AA6548'}

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

def send_to_api(scan_id):
    API_DATA['ScanID'] = scan_id 
    resp = requests.post(API_URL, API_DATA)
