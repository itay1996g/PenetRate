import argparse
from crawler import *

CRAWLER_TABLE_NAME = r"clientside_scan"

def get_args():
  """
  Get arguments for the crawler's script.
  """
  parser = argparse.ArgumentParser(description="Website Crawler Module")

  parser.add_argument('-d','--domain', help='The IP Address to scan', required=True)
  parser.add_argument('-u','--uid', help='User ID', required=True)
  parser.add_argument('-c','--auth-cookie', help='Authenticated Cookie', required=False, default=None)
  parser.add_argument('-a','--attack', help='Attack Mode', required=False, default=False, action='store_true')

  args = vars(parser.parse_args())

  if args['attack']:
    if not args['auth_cookie']:
      parser.error('In attack mode user must supply authenticated cookie')

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

    authbypass_scanner = AuthBypassScan(args['uid'], 
                             CRAWLER_RESULTS_PATH + r'/{}_{}.json'.format('auth', args['uid']),
                             CRAWLER_RESULTS_PATH + r'/{}_{}.json'.format('unauth', args['uid']))

    sqli_scanner = SQLIScanner(base_url=args['domain'],
                               user_id=args['uid'],
                               auth_cookie=args['auth_cookie'],
                               auth_crawler_results=True)
    
    authbypass_scanner.scan()
    sqli_scanner.scan()

def main():
  args = get_args()

  make_results_dir(RESULTS_DIR_PATH)
  make_results_dir(CRAWLER_RESULTS_PATH)
  make_results_dir(SENSITIVEINFO_RESULTS_PATH)
  
  route_crawl(args)

if __name__ == '__main__':
  main()