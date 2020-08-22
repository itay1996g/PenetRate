import argparse
from crawler import *

CLIENTSIDE_TABLE_NAME = r"clientside_scan"
CRAWLER_TABLE_NAME = r"crawler_scan"
VULNSCAN_TABLE_NAME = r"generalvulnerabilities_scan"

def get_args():
  """
  Get arguments for the crawler's script.
  """
  parser = argparse.ArgumentParser(description="Website Crawler Module")

  parser.add_argument('-d','--domain', help='The IP Address to scan', required=True)
  parser.add_argument('-u','--uid', help='User ID', required=True)
  parser.add_argument('-c','--auth-cookie', help='Authenticated Cookie', required=False, default=None)
  parser.add_argument('-a','--attack', help='Attack Mode', required=False, default=False, action='store_true')
  parser.add_argument('-b','--dirbust', help='Run with DirBust', required=False, default=False, action='store_true')
  parser.add_argument('-f','--wordlist', help='Wordlist File', required=False)

  args = vars(parser.parse_args())

  if args['attack']:
    if not args['auth_cookie']:
      parser.error('In attack mode user must supply authenticated cookie')
  
  if args['dirbust']:
    if not args['wordlist']:
        parser.error('Wordlist must be specified in order to run dirbust')

  return args

def route_crawl(args):
  """
  If attack mode is selected:
    1. Start unauth crawling (by disabling attack mode and auth_cookie)
    2. Start auth crawling (attack mode dependes on user's selection)
    3. Run AuthBypass Scanner
    4. Run SQLInjection Scanner
  """
  
  with Crawler(base_url=args['domain'], attack_mode=False, auth_cookie=None) as crawler:
    crawler.crawl()
    crawler.save_results(args['uid'])

  if args['attack']:
    with Crawler(base_url=args['domain'], attack_mode=args['attack'], auth_cookie=args['auth_cookie']) as crawler:
      crawler._config_attack()
      crawler.crawl()
      crawler.save_results(args['uid'], 'auth')
      save_results_to_json(XSS_RESULTS_PATH, {'XSS': crawler.vuln_results['XSS']}, args['uid'])
      save_results_to_json(CSRF_RESULTS_PATH, {'CSRF': crawler.vuln_results['CSRF']}, args['uid'])

    send_to_api(args['uid'], CRAWLER_TABLE_NAME)
    send_to_api(args['uid'], CLIENTSIDE_TABLE_NAME)

    authbypass_scanner = AuthBypassScan(args['uid'], 
                             CRAWLER_RESULTS_PATH + r'/{}_{}.json'.format('auth', args['uid']),
                             CRAWLER_RESULTS_PATH + r'/{}_{}.json'.format('unauth', args['uid']))

    sqli_scanner = SQLIScanner(base_url=args['domain'],
                               user_id=args['uid'],
                               auth_cookie=args['auth_cookie'],
                               auth_crawler_results=True)
    
    authbypass_scanner.scan()
    sqli_scanner.scan()
    
  if args['dirbust']:
      sys.path.append(os.path.abspath(os.path.join(__file__, os.pardir)) + '/../DirBust')
      dirbust_module = __import__("dirbust")
      scanner = dirbust_module.DirBuster(args['domain'], args['wordlist'], args['uid'])
      scanner.scan()
      send_to_api(args['uid'], dirbust_module.DIRBUST_TABLE_NAME)

def main():
  args = get_args()

  make_results_dir(RESULTS_DIR_PATH)
  make_results_dir(CRAWLER_RESULTS_PATH)
  make_results_dir(SENSITIVEINFO_RESULTS_PATH)
  
  route_crawl(args)
  
  send_to_api(args['uid'], VULNSCAN_TABLE_NAME)

if __name__ == '__main__':
  main()
