import copy
import argparse

from .scanner import *

sys.path.append(os.path.abspath(os.path.join(__file__, os.pardir)) + '/..')
from Utils.helpers import *

SPOOFED_REFERER = r'https://some_malicious_site.com'
COMMON_CSRF_NAMES = [
    'csrf-token',
    'csrf_token',
    'CSRFName',
    'CSRFToken',
    'anticsrf',
    '__RequestVerificationToken',
    'token',
    'csrf',
    'YII_CSRF_TOKEN',
    'yii_anticsrf',
    '[_token]',
    '_csrf_token',
    'csrfmiddlewaretoken',
    'authenticity_token',
    'at'
]

class CsrfScanner(VulnScanner):
    def __init__(self, auth_cookie, standalone=False):
        super().__init__(auth_cookie)
        self._vuln_forms = []

        if auth_cookie is None:
            raise ValueError("User must specify auth cookie")
            
        self.headers['Cookie'] = auth_cookie
        self.headers['User-Agent'] = DEFAULT_USER_AGENT

        if standalone:
            make_results_dir(RESULTS_DIR_PATH)
            make_results_dir(CSRF_RESULTS_PATH)

    def _check_csrf_token(self, input_name, input_value):
        for csrf_token_name in COMMON_CSRF_NAMES:
            if csrf_token_name.lower() == input_name.lower():
                return True
        return False
    
    def _check_vuln_form(self, form):
        for input_tag in form.find_all("input"):
            input_name = input_tag.attrs.get("name")
            input_value = input_tag.attrs.get("value")

            if input_value is None or input_name is None:
                return False

            if self._check_csrf_token(input_name, input_value):
                if input_value == "":
                    return True
                else:
                    return False
        return True
    
    def get_all_post_forms(self, url):
        forms_found = []
        resp = requests.get(url, headers=self.headers)
        soup = bs(resp.content, "html.parser")
        
        for form in soup.find_all("form"):
            if form.has_attr('action') and form.has_attr('method'):
                if self._check_same_site(form['action']) and form.attrs.get("method") == "post":
                    forms_found.append(form)

        return forms_found
    
    def _is_refer_checked(self, url):
        resp_clean = requests.get(url, headers=self.headers)
        
        spoofed_headers = copy.deepcopy(resp_clean.headers)
        spoofed_headers['Referer'] = SPOOFED_REFERER
        resp_spoof = requests.get(url, headers=spoofed_headers)

        if len(resp_clean.content) == len(resp_spoof.content):
            return False
        
        return True
        
    def scan(self, url):
        self.url = url

        try:
            for form in self.get_all_post_forms(self.url):
                if self._check_vuln_form(form):
                    if form is not None and url is not None:
                        self._vuln_forms.append({'URL': url, 'FORM': form['name']})
                else:
                    if not self._is_refer_checked(self.url):
                        if form is not None and url is not None:
                            self._vuln_forms.append({'URL': url, 'FORM': form['name']})

            return self._vuln_forms
        except requests.exceptions.ConnectionError:
            pass
        except Exception as e:
            return []


def get_args():
    """
    Get arguments for the CSRF Scanner Module.
    """
    parser = argparse.ArgumentParser(description="CSRF Scan Module")

    parser.add_argument('-d','--domain', help='The domain to scan', required=True)
    parser.add_argument('-c','--auth-cookie', help='Auth cookie', required=True)
    parser.add_argument('-a','--standalone', help='Standalone Mode', required=False, action='store_true')
    parser.add_argument('-u','--user-id', help='User ID', required=False)

    return vars(parser.parse_args())

def main():
    """
    Main function.
    """
    args = get_args()
    scanner = CsrfScanner(auth_cookie=args['auth_cookie'])
    results = scanner.scan(args['domain'])
    save_results_to_json(CSRF_RESULTS_PATH, results, args['user_id'])
    
if __name__ == '__main__':
    main()