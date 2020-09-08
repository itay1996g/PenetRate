# -*- coding: utf-8 -*-

"""
    Retriev and Parse Security Headers from a given URL.
"""

import os
import requests
from collections import OrderedDict
from bs4 import BeautifulSoup

__SECURITY_HEADERS_URL__ = os.environ.get("SEUCRITY_HEADERS_URL", "https://securityheaders.io/?q={0}")



def analyze_url(url):
    """
        Analyze the security relevant headers
        of the given URL.

        :param str url: the URL to analyze.

        :returns: the security headers with rating and comments.
        :rtype: dict
    """
    data = {}
    api_url = __SECURITY_HEADERS_URL__.format(url)
    response = requests.get(api_url)

    soup = BeautifulSoup(response.text, "html.parser")
    # data["ip"] = soup.find_all("th", "tableLabel", text="IP Address:")[0].find_next_sibling("td").text.strip()
    # data["site"] = soup.find_all("th", "tableLabel", text="Site:")[0].find_next_sibling("td").text.strip()

    headers = OrderedDict()
    # Parse Raw Headers Report Table
    headers_list = []
    headers_dict = {}
    position = 0
    my_headers = {}
    for header, value in get_report_table("Raw Headers", soup):
        headers_dict = {}
        headers_dict["Name"] = header
        headers_dict["rating"] = "info"
        headers_dict["value"] = value
        headers_dict["description"] = ""
        my_headers[header] = position
        position = position + 1
        headers_list.append(headers_dict)

    # Parse ratings from badges
    raw_headers = soup.find_all("th", "tableLabel", text="Headers:")[0].find_next_sibling("td").find_all("li")
    for raw_header in raw_headers:
        headers_dict = {}
        rating = "good" if "pill-green" in raw_header["class"] else "bad"
        if raw_header.text not in my_headers:
            headers_dict["Name"] = raw_header.text
            headers_dict["rating"] = rating
            headers_dict["description"] = ""
            my_headers[raw_header.text] = position
            position = position + 1
            headers_list.append(headers_dict)
        else:
            temp_header = {}
            pos = my_headers[raw_header.text]
            temp_header = headers_list[pos]
            headers_list.pop(pos)
            temp_header["rating"] = rating
            #headers_list.append(temp_header)
            headers_list.insert(pos, temp_header)




        #headers[raw_header.text]["rating"] = rating

    # Parse Missing Headers Report Table
    for header, value in get_report_table("Missing Headers", soup):
        temp_header = {}
        pos = my_headers[header]
        temp_header = headers_list[pos]
        headers_list.pop(pos)
        temp_header["description"] = value
        headers_list.insert(pos, temp_header)
        #headers_list.append(temp_header)       
        #headers[header]["description"] = value

    # Parse Additional Information Report Table
    for header, value in get_report_table("Additional Information", soup):
        temp_header = {}
        pos = my_headers[header]
        temp_header = headers_list[pos]
        headers_list.pop(pos)
        temp_header["description"] = value
        headers_list.insert(pos, temp_header)
        #headers[header]["description"] = value
        
   
    data["headers"] = headers_list
    return data


def get_report_table(title, soup):
    """
        Returns the data of the report table
        with the given title.

        :param str title: the title of the report table

        :returns: key/value pairs
        :rtype: generator
    """
    try:
        report_body = soup.find_all("div", "reportTitle", text=title)[0].find_next_sibling("div")
    except IndexError:
        return []
    else:
        report_th = (x.text for x in report_body.select("table tbody tr th"))
        report_td = (x.text for x in report_body.select("table tbody tr td"))
        return zip(report_th, report_td)
