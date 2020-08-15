import argparse

from scanner import *

sys.path.append(os.path.abspath(os.path.join(__file__, os.pardir)) + '/..')
from Utils.helpers import *

# Local Consts
PAYLOAD = r'InJecTeD'

class XssScanner(VulnScanner):
    def __init__(self, auth_cookie=None, standalone=False):
        super().__init__(auth_cookie)
        
        if standalone:
            make_results_dir(RESULTS_DIR_PATH)
            make_results_dir(XSS_RESULTS_PATH)

    def _xss_payloads(self):
        """ Cross-Site Scripting"""
        payload =  [r"<script>alert('"+PAYLOAD+"')</script>"]
        payload += [r"<script>alert('"+PAYLOAD+r"');</script>"]
        payload += [r"\'\';!--\"<"+PAYLOAD+r">=&{()}"]
        payload += [r"<script>a=/"+PAYLOAD+r"/"]
        payload += [r"<body onload=alert('"+PAYLOAD+r"')>"]
        payload += [r"<iframe src=javascript:alert('"+PAYLOAD+r"')>"]
        payload += [r"<x onxxx=alert('"+PAYLOAD+r"') 1='"]
        payload += [r"</script><svg onload=alert("+PAYLOAD+r")>"]
        payload += [r"<svg onload=alert('"+PAYLOAD+r"')>"]
        payload += [r"><script>"+PAYLOAD+""]
        payload += [r"\"><script>alert('"+PAYLOAD+"');</script>"]
        payload += [r"<  script > "+PAYLOAD+" < / script>"]
        return payload

    def scan(self, url):
        """
        Given a `url`, it prints all XSS vulnerable forms and 
        returns True if any is vulnerable, False otherwise
        """
        self.url = url

        try:
            forms = self.get_all_forms(self.url)
            for form in forms:
                form_details = self.get_form_details(form)
                if form_details != {}:
                    for payload in self._xss_payloads():
                        content, input_name = self.submit_form(form_details, self.url, payload)
                        if content.content.decode() is not None and input_name is not None:
                            if payload in content.content.decode():
                                self.results.append({'URL': form_details['action'],
                                                     'input_name': input_name,
                                                     'value': payload })
                                break

        except requests.exceptions.ConnectionError:
            pass
        except Exception as e:
            raise e

        return self.results
        

def get_args():
    """
    Get arguments for the XSS Scanner Module.
    """
    parser = argparse.ArgumentParser(description="XSS Scan Module")

    parser.add_argument('-s','--site', help='The site to scan', required=True)
    parser.add_argument('-a','--standalone', help='Standalone Mode', required=False, action='store_true')
    parser.add_argument('-u','--user-id', help='User ID', required=False)

    return vars(parser.parse_args())

def main():
    """
    Main function.
    """
    args = get_args()
    scanner = XssScanner(standalone=args['standalone'])
    results = scanner.scan(args['site'])
    save_results_to_json(XSS_RESULTS_PATH, results, args['user_id'])
    
if __name__ == '__main__':
    main()
