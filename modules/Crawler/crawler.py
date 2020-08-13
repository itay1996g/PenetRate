import json
import argparse
import threading
from time import sleep
from concurrent.futures import as_completed
from concurrent.futures import ThreadPoolExecutor

from crawler_main import *

sys.path.append(os.path.abspath(os.path.join(__file__, os.pardir)) + '/..')
from Utils.helpers import *
from VulnScan.authbypass import AuthBypassScan

# Local Consts
CRAWLER_TABLE_NAME = r"clientside_scan"
THREAD_COUNT = 20
links_to_crawl = CheckableQueue()


def get_args():
  """
  Get arguments for the crawler's script.
  """
  parser = argparse.ArgumentParser(description="Website Crawler Module")

  parser.add_argument('-d','--domain', help='The IP Address to scan', required=True)
  parser.add_argument('-u','--uid', help='User ID', required=True)
  parser.add_argument('-t','--threads', help='Threads count', required=False, default=THREAD_COUNT)
  parser.add_argument('-c','--auth-cookie', help='Authenticated Cookie', required=False, default=None)
  parser.add_argument('-a','--attack', help='Attack Mode', required=False, default=False, action='store_true')

  args = vars(parser.parse_args())

  if args['attack']:
    if not args['auth_cookie']:
      parser.error('In attack mode user must supply authenticated cookie')

  return args


def save_results(results, personal_info, user_id, attack_mode='unauth'):
  try:            
    with open(CRAWLER_RESULTS_PATH + r'/{}_{}.json'.format(attack_mode, user_id), 'w') as output_file:
      json.dump({'Info': results}, output_file, ensure_ascii=False, indent=4)

    with open(CRAWLER_RESULTS_PATH + r'/{}_{}_extract_info.json'.format(attack_mode, user_id), 'w', encoding='utf8', errors="surrogateescape") as output_file:
      results_to_file = {'Info': []}
      for info in personal_info:
        results_to_file['Info'].append(info)
      json.dump(results_to_file, output_file, ensure_ascii=False, indent=4)
  except Exception as e:
      raise e


def crawl_on_link_thread(url, crawler_object, attack=False):
  try:
    result = crawler_object.crawl(threading.current_thread(), url, links_to_crawl, attack)
    links_to_crawl.task_done()

    return result
  except Exception as e:
    raise e  


def start_crawl(args):
  results = []
  personal_info = []

  crawler_instance = Crawler(base_url=args['domain'], cookies=args['auth_cookie'], attack_mode=args['attack'])

  if args['attack']:
    crawler_instance._config_attack()

  links_to_crawl.put(args['domain'])

  while not links_to_crawl.empty():
    with ThreadPoolExecutor(max_workers=args['threads']) as threads_executor:
      url = links_to_crawl.get()
      future_links = []
      
      if url is not None:
        future_link = threads_executor.submit(crawl_on_link_thread, url, crawler_instance, args['attack'])
        future_links.append(future_link)

      for future_link in as_completed(future_links):
        try:
          if future_link.result() != None:
            results.append(future_link.result()[0])
        except Exception as e:
          print (str(e))

  try:
    if crawler_instance.cookies is not None:
      save_results(results=list(set(results)), 
                   personal_info=crawler_instance._personal_info, 
                   user_id=args['uid'], 
                   attack_mode='auth')
    else:
      save_results(results=list(set(results)), 
                   personal_info=crawler_instance._personal_info, 
                   user_id=args['uid'])
  except Exception as e:
    print ("Error while writing crawler results to file")
    print (str(e))

  try:
    if args['attack']:
      save_results_to_json(XSS_RESULTS_PATH, crawler_instance.vuln_results['XSS'], args['uid'])
      save_results_to_json(CSRF_RESULTS_PATH, crawler_instance.vuln_results['CSRF'], args['uid'])
  except Exception as e:
    print ("Error while writing vuln scan results to file")
    print (str(e))

  del crawler_instance


def route_crawl(args):
  """
  If attack mode is selected:
    1. Start auth crawling (attack mode dependes on user's selection)
    2. Start unauth crawling (by disabling attack mode and auth_cookie)
    3. Run AuthBypass Scanner
  """
  attack_selected = False

  if args['attack']:
    attack_selected = True

  if args['auth_cookie']:
    start_crawl(args)

  args['auth_cookie'] = None
  args['attack'] = False
  links_to_crawl = CheckableQueue()

  start_crawl(args)

  if attack_selected:
    authbypass_scanner = AuthBypassScan(args['uid'], 
                             CRAWLER_RESULTS_PATH + r'/{}_{}.json'.format('auth', args['uid']),
                             CRAWLER_RESULTS_PATH + r'/{}_{}.json'.format('unauth', args['uid']))

    sqli_scanner = SQLIScanner(user_id=args['domain'],
                               base_url=args['site'], 
                               auth_cookie=args['auth_cookie'])
    
    authbypass_scanner.scan()
    sqli_scanner.scan()


def main():
  args = get_args()

  make_results_dir(RESULTS_DIR_PATH)
  make_results_dir(CRAWLER_RESULTS_PATH)
  
  route_crawl(args)

  #send_to_api(args['uid'], CRAWLER_TABLE_NAME)

if __name__ == '__main__':
  main()
