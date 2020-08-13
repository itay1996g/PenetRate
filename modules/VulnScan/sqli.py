import re
import os
import sys
import json
import argparse
from urllib.parse import urlparse
from urllib.request import Request, urlopen, urljoin, URLError, HTTPError

sys.path.append(os.path.abspath(os.path.join(__file__, os.pardir)) + '/..')
from Utils.helpers import *

SQL_ERRORS = {
                "MySQL": (r"SQL syntax.*MySQL", r"Warning.*mysql_.*", r"MySQL Query fail.*", r"SQL syntax.*MariaDB server"),
                "PostgreSQL": (r"PostgreSQL.*ERROR", r"Warning.*\Wpg_.*", r"Warning.*PostgreSQL"),
                "Microsoft SQL Server": (r"OLE DB.* SQL Server", r"(\W|\A)SQL Server.*Driver", r"Warning.*odbc_.*", r"Warning.*mssql_", r"Msg \d+, Level \d+, State \d+", r"Unclosed quotation mark after the character string", r"Microsoft OLE DB Provider for ODBC Drivers"),
                "Microsoft Access": (r"Microsoft Access Driver", r"Access Database Engine", r"Microsoft JET Database Engine", r".*Syntax error.*query expression"),
                "Oracle": (r"\bORA-[0-9][0-9][0-9][0-9]", r"Oracle error", r"Warning.*oci_.*", "Microsoft OLE DB Provider for Oracle"),
                "IBM DB2": (r"CLI Driver.*DB2", r"DB2 SQL error"),
                "SQLite": (r"SQLite/JDBCDriver", r"System.Data.SQLite.SQLiteException"),
                "Informix": (r"Warning.*ibase_.*", r"com.informix.jdbc"),
                "Sybase": (r"Warning.*sybase.*", r"Sybase message")
            }


class SQLIScanner(object):
    def __init__(self, base_url, user_id, auth_cookie=None, auth_crawler_results=False):
        self.base_url = base_url
        self.headers = {}
        self.user_id = user_id
        self.results = []

        if auth_cookie:
            self.auth_cookie = auth_cookie
            self.headers['Cookie'] = auth_cookie

        self.headers['User-Agent'] = DEFAULT_USER_AGENT

        if auth_crawler_results:
            with open(os.path.join(CRAWLER_RESULTS_PATH, r'auth_{}.json'.format(user_id)), 'r') as f:
                self.auth_crawler_results = json.load(f)

    def _check_sql_errors(self, html):
        for db, errors in SQL_ERRORS.items():
            for error in errors:
                if re.compile(error).search(html):
                    return db
        return None

    def _sqli_payloads(self):
        return ["'", "')", "';", '"', '")', 
                '";', '`', '`)', '`;', '\\', 
                "%27", "%%2727", "%25%27", "%60", "%5C"]

    def _get_html(self, url, headers=None):
        if not (url.startswith("http://") or url.startswith("https://")):
            return None

        request = Request(url, None, self.headers)
        html = None

        try:
            reply = urlopen(request, timeout=10)
        except HTTPError as e:
            if e.getcode() == 500:
                html = str(e)
            pass
        except URLError as e:
            pass
        except:
            pass

        else:
            return reply.read()

    def scan(self):

        if 'Info' in self.auth_crawler_results.keys():
            for url in self.auth_crawler_results['Info']:
                domain = urljoin(self.base_url, url.split("?")[0])
                queries = urlparse(url).query.split("&")

                if not any(queries):
                    continue

                for payload in self._sqli_payloads():
                    website = domain + "?" + ("&".join([param + payload for param in queries]))
                    source = self._get_html(website).decode()
                    if source:
                        vuln_db_name = self._check_sql_errors(source)
                        if vuln_db_name != None:
                            self.results.append({'DB_TYPE': vuln_db_name, 'URL': url})

        unique_result = list({result['URL']:result for result in self.results}.values())
        results = {'SQL Injection': unique_result}

        save_results_to_json(SQLI_RESULTS_PATH, results, self.user_id)
        
def get_args():
    """
    Get arguments for the SQLI Scanner Module.
    """
    parser = argparse.ArgumentParser(description="SQLI Scan Module")
    parser.add_argument('-s','--site', help='The domain to scan', required=True)
    parser.add_argument('-u','--user-id', help='User ID', required=False)
    parser.add_argument('-a','--auth-cookie', help='Auth Cookie', required=False)

    return vars(parser.parse_args())

def main():
    """
    Main function.
    """
    args = get_args()
    scanner = SQLIScanner(user_id=args['user_id'], base_url=args['site'], auth_cookie=args['auth_cookie'], auth_crawler_results=True)
    scanner.scan()
    
if __name__ == '__main__':
    main()
