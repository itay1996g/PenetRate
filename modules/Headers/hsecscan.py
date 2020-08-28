# -*- coding: utf-8 -*-

"""hsecscan.hsecscan: provides entry point main()."""

__version__ = '0.0.1'

import os.path
import argparse
import sqlite3
from urlparse import urlparse
import urllib2
import urllib
import json
import ssl,requests

API_URL = r'http://193.106.55.103:8080/penetrate/helpers/ScansForm.php'
API_DATA = {'table_name': 'headers_scan',
            'ScanID': '',
            'Status': 'Finished',
            'GUID': 'ETAI_ITAY123AA6548'}


class RedirectHandler(urllib2.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):
        newreq = urllib2.HTTPRedirectHandler.redirect_request(
            self, req, fp, code, msg, headers, newurl)
        print '>> REDIRECT INFO <<'
        print_response(req.get_full_url(), code, headers)
        print '>> REDIRECT HEADERS DETAILS <<'
        print '['
        for header in headers.items():
            check_header(header)
        print ']'
        print '>> REDIRECT MISSING HEADERS <<'
        missing_headers(headers.items(), urlparse(newurl).scheme)
        return newreq


def print_database(headers):
    conn = sqlite3.connect(dbfile)
    cur = conn.cursor()
    cur.execute('SELECT * FROM headers')
    col_names = [cn[0] for cn in cur.description]
    for row in cur:
        col_index = 0
        if (headers == False) | (row[6] == 'Y'):
            for cel in row:
                print col_names[col_index] + ':', cel
                col_index += 1
            print '\n'
    cur.close()
    conn.close()


def print_header(header):
    conn = sqlite3.connect(dbfile)
    cur = conn.cursor()
    cur.execute(
        'SELECT "Header Field Name", "Reference", "Security Description", "Security Reference", "Recommendations", "CWE", "CWE URL" FROM headers WHERE "Header Field Name" = ? COLLATE NOCASE', [header])
    col_names = [cn[0] for cn in cur.description]
    for row in cur:
        col_index = 0
        for cel in row:
            print col_names[col_index] + ':', cel
            col_index += 1
    cur.close()
    conn.close()


def print_response(url, code, headers):
    # print 'URL:', url
    # print 'Code:', code
    # print 'Headers:'
    headers_list = []
    headers_dict = {}
    for line in str(headers).splitlines():
        headers_dict = {}
        if('Date' not in line):
            headers_dict["Name"] = line.split(':')[0]
            headers_dict["Value"] = line[(len(line.split(':')[0])+1):]
            headers_list.append(headers_dict)
    # print headers_list
    return headers_list


def check_header(response_headers):
    conn = sqlite3.connect(dbfile)
    cur = conn.cursor()
    headers_list = []
    for header in response_headers:
        t = (header[0],)
        if allheaders:
            cur.execute('SELECT "Header Field Name", "Reference", "Security Description", "Security Reference", "Recommendations", "CWE", "CWE URL" FROM headers WHERE "Header Field Name" = ? COLLATE NOCASE', t)
        else:
            cur.execute('SELECT "Header Field Name", "Reference", "Security Description", "Security Reference", "Recommendations", "CWE", "CWE URL" FROM headers WHERE "Enable" = "Y" AND "Header Field Name" = ? COLLATE NOCASE', t)
        col_names = [cn[0] for cn in cur.description]
        headers_dict = {}
        for row in cur:
            col_index = 0
            for cel in row:
                if col_names[col_index] == 'Header Field Name':
                    headers_dict[col_names[col_index]] = cel
                    headers_dict["Value"] = header[1]
                elif(col_names[col_index] == 'Security Reference' or col_names[col_index] == 'CWE URL'):
                    headers_dict[col_names[col_index]
                                 ] = "<a href='"+cel+"'><button class='btn btn-success waves-effect waves-light m-r-10' style='width: 100% !important;'>Click Here</button></a>"
                else:
                    headers_dict[col_names[col_index]] = cel
                col_index += 1
        if(len(headers_dict) != 0):
            headers_list.append(headers_dict)
    cur.close()
    conn.close()
    return headers_list


def missing_headers(headers, scheme):
    conn = sqlite3.connect(dbfile)
    cur = conn.cursor()
    cur.execute('SELECT "Header Field Name", "Reference", "Security Description", "Security Reference", "Recommendations", "CWE", "CWE URL", "HTTPS" FROM headers WHERE "Required" = "Y"')
    col_names = [cn[0] for cn in cur.description]
    header_names = [name[0] for name in headers]
    headers_list = []
    for row in cur:
        if (row[0].lower() not in (name.lower() for name in header_names)) & ((scheme == 'https') | (row[7] != 'Y')):
            col_index = 0
            headers_dict = {}
            for cel in row:
                if(col_names[col_index] == 'Security Reference' or col_names[col_index] == 'CWE URL'):
                    headers_dict[col_names[col_index]
                                 ] = "<a href='"+cel+"'><button class='btn btn-success waves-effect waves-light m-r-10' style='width: 100% !important;'>Click Here</button></a>"
                else:
                    headers_dict[col_names[col_index]] = cel

                col_index += 1
            headers_list.append(headers_dict)
    cur.close()
    conn.close()

    return headers_list


def scan(url, redirect, insecure, useragent, postdata, proxy, UID):
    request = urllib2.Request(url.geturl())
    request.add_header('User-Agent', useragent)
    request.add_header('Origin', 'http://hsecscan.com')
    request.add_header('Accept', '*/*')
    if postdata:
        request.add_data(urllib.urlencode(postdata))
    build = [urllib2.HTTPHandler()]
    if redirect:
        build.append(RedirectHandler())
    if proxy:
        build.append(urllib2.ProxyHandler({'http': proxy, 'https': proxy}))
    if insecure:
        context = ssl._create_unverified_context()
        build.append(urllib2.HTTPSHandler(context=context))
    urllib2.install_opener(urllib2.build_opener(*build))
    response = urllib2.urlopen(request)
    results = {}

    results['RESPONSE_Headers_Info'] = print_response(
        response.geturl(), response.getcode(), response.info())
    results['RESPONSE_Headers_Details'] = check_header(response.info().items())
    results['Missing_Headers_Details'] = missing_headers(
        response.info().items(), url.scheme)
    HEADERS_RESULTS_PATH = r"C:\\xampp\\htdocs\\PenetRate\\Results\\Headers\\"
    with open(HEADERS_RESULTS_PATH + r'{}.json'.format(UID), 'a') as f:
        json.dump(results, f, ensure_ascii=False, indent=4)

    API_DATA['ScanID'] = UID 
    resp = requests.post(API_URL, API_DATA)


def check_url(url):
    url_checked = urlparse(url)
    if ((url_checked.scheme != 'http') & (url_checked.scheme != 'https')) | (url_checked.netloc == ''):
        raise argparse.ArgumentTypeError(
            'Invalid %s URL (example: https://www.hsecscan.com/path).' % url)
    return url_checked


def is_valid_file(parser, dbfile):
    if not os.path.exists(dbfile):
        raise argparse.ArgumentTypeError(
            'The file %s does not exist.' % dbfile)
    fdb = open(dbfile, 'r')
    if fdb.read(11) != 'SQLite form':
        raise argparse.ArgumentTypeError(
            'The file %s is not a SQLite DB.' % dbfile)
    return dbfile


def main():
    parser = argparse.ArgumentParser(
        prog='hsecscan', description='A security scanner for HTTP response headers.')
    parser.add_argument('-P', '--database', action='store_true',
                        help='Print the entire response headers database.')
    parser.add_argument('-p', '--headers', action='store_true',
                        help='Print only the enabled response headers from database.')
    parser.add_argument('-H', '--header', metavar='Header',
                        help='Print details for a specific Header (example: Strict-Transport-Security).')
    parser.add_argument('-R', '--redirect', action='store_true',
                        help='Print redirect headers.')
    parser.add_argument('-i', '--insecure', action='store_true',
                        help='Disable certificate verification.')
    parser.add_argument('-U', '--useragent', metavar='User-Agent', default='hsecscan',
                        help='Set the User-Agent request header (default: hsecscan).')
    parser.add_argument('-D', '--dbfile', dest="dbfile", default=os.path.dirname(os.path.abspath(__file__)) +
                        '/hsecscan.db', type=lambda x: is_valid_file(parser, x), help='Set the database file (default: hsecscan.db).')
    parser.add_argument('-d', '--postdata', metavar='\'POST data\'', type=json.loads,
                        help='Set the POST data (between single quotes) otherwise will be a GET (example: \'{ "q":"query string", "foo":"bar" }\').')
    parser.add_argument(
        '-x', '--proxy', help='Set the proxy server (example: 192.168.1.1:8080).')
    parser.add_argument('-a', '--all', action='store_true',
                        help='Print details for all response headers. Good for check the related RFC.')
    parser.add_argument('-u', '--URL', type=check_url,
                        help='The URL to be scanned.')
    parser.add_argument('-UID', '--UID', help='User ID', required=True)

    args = parser.parse_args()
    global dbfile
    dbfile = args.dbfile
    if args.database == True:
        print_database(False)
    elif args.headers == True:
        print_database(True)
    elif args.header:
        print_header(args.header.lower())
    elif args.URL:
        global allheaders
        allheaders = args.all
        scan(args.URL, args.redirect, args.insecure,
             args.useragent, args.postdata, args.proxy, args.UID)
    else:
        parser.print_help()
