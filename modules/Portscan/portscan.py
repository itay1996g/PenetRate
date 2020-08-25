import os
import re
import sys
import wmi
import nmap
import socket
import argparse
import paramiko

sys.path.append(os.path.abspath(os.path.join(__file__, os.pardir)) + '/..')
from Utils.helpers import *

# Local Consts
PORTSCAN_RESULTS_PATH = RESULTS_DIR_PATH + r"/Portscan"
DEFAULT_CREDS_PATH = os.path.dirname(os.path.abspath(__file__)) + r"/addons/default_creds.txt"
CREDS_REGEX = r"^(.*)\:(.*)$"
VALID_SITE_REGEX = r'https?:\/\/(.*)\/.'
SSH_PORT_NUM = 22
RDP_PORT_NUM = 3389
PORTSCAN_TABLE_NAME = r"ports_scan"
DEFAULT_PORTS_TO_SCAN = '21, 22, 23, 25, 53, 80, 110, 113, 115, 135, 138, 443, 445, 3306, 3389, 8080, 8000'

class PortScanner(object):

    def __init__(self, uid, ip):
        self.target_ip = ip
        self.ports_to_scan = DEFAULT_PORTS_TO_SCAN
        self.uid = uid
        
        try:
            socket.inet_aton(ip)
        except socket.error:
            self.target_ip = re.findall(r'https?:\/\/(.*)\/.?', ip)

        if self.target_ip == []:
            raise ValueError("Invalid Address")

        self.target_ip = self.target_ip[0]

        try:
            self.scanner = nmap.PortScanner()
        except:
            raise ValueError("NMAP can not be found!")

        self.open_ports = []
        self.filtered_ports = []
        self.connected_ports = []
        
    def check_syntax(self):
        """
        Check if the specified hosts and ports are legal.
        """
        try:
            if 'error' in self.scanner.scaninfo().keys():
                return False
            if self.scanner.all_hosts() == []:
                return False
            self.target_ip = self.scanner.all_hosts()[0]
            return True
        except:
            return False

    def check_connection_to_port(self, port):
        """
        This method iterates through every Username and Password in the default credentials file
        And calls the proper connection's check method.
        """
        with open(DEFAULT_CREDS_PATH, 'r') as creds_file:
            for line in creds_file.readlines():
                user = re.findall(CREDS_REGEX, line)[0][0]
                pwd = re.findall(CREDS_REGEX, line)[0][1]

                if port == SSH_PORT_NUM:
                    if self.check_ssh_connection(user, pwd):
                        self.connected_ports.append({'ServiceName': 'SSH',
                                                     'Username' : user,
                                                     'Password' : pwd })
                elif port == RDP_PORT_NUM:
                    if self.check_rdp_connection(user, pwd):
                        self.connected_ports.append({'ServiceName': 'RDP',
                                                     'Username' : user,
                                                     'Password' : pwd })
                else:
                    continue

    def check_ssh_connection(self, user, pwd):
        """
        Check for successful SSH connection with default credentials.
        """
        ssh_client = paramiko.SSHClient()
        ssh_client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

        try:
            ssh_client.connect(hostname=self.target_ip, username=user, password=pwd)
            return True
        except Exception as e:
            return False

    def check_rdp_connection(self, user, pwd):
        """
        Check for successful RDP connection with default credentials.
        """
        try:
            connection = wmi.WMI(self.target_ip, user=user, password=pwd)
            return True
        except wmi.x_access_denied:
            return False
        
    def scan(self):
        """
        Main scan method.
        This method iterates through all the ports in the specified port range.
        For open SSH, RDP ports (22, 3389) the method will
        try to connect to the service behind the port with common default credentials. 
        """
        try:
            output = self.scanner.scan(hosts=self.target_ip, ports=self.ports_to_scan)
        except AssertionError as e:
            print (str(e))

        if self.check_syntax():
            self.parse_results(output)
            for port in self.open_ports:
                self.check_connection_to_port(int(port['Port']))

        self.save_results_to_json()

    def parse_results(self, results):
        """
        Iterate through all ports in scan's output.
        Insert each open port to 'open_ports' list.
        Insert each filtered port to 'filtered_ports' list.
        """
        try:
            for port in results['scan'][self.target_ip]['tcp'].iteritems():
                try:
                    service_name = socket.getservbyport(port[0])
                except:
                    service_name = 'Unknown'
                if port[1]['state'] == 'open':
                    self.open_ports.append({'Port':port[0], 'ServiceName' : service_name})
                elif port[1]['state'] == 'filtered':
                    self.filtered_ports.append({'Port':port[0], 'ServiceName' : service_name})
                else:
                    continue
        except Exception as e:
            raise ValueError(str(e))
            
    def get_open_ports(self):
        if len(self.open_ports) > 0:
            return self.open_ports

    def get_filtered_ports(self):
        if len(self.filtered_ports) > 0:
            return self.filtered_ports

    def get_connected_ports(self):
        if len(self.connected_ports) > 0:
            return self.connected_ports

    def save_results_to_json(self):
        """
        This function makes 'Results' directory if not already exists.
        After that, writing the results into a json file.
        The file name will be the UID specified.
        """
        if not os.path.exists(RESULTS_DIR_PATH):
            os.mkdir(RESULTS_DIR_PATH)

        if not os.path.exists(PORTSCAN_RESULTS_PATH):
            os.mkdir(PORTSCAN_RESULTS_PATH)

        results = {}
        
        results['Open'] = self.get_open_ports()
        results['Filtered'] = self.get_filtered_ports()
        results['Connects'] = self.get_connected_ports()
        
        with open(PORTSCAN_RESULTS_PATH + r'/{}.json'.format(self.uid), 'w') as f:
            json.dump(results, f, ensure_ascii=False, indent=4)


def get_args():
    """
    Get arguments for the port scanner script.
    """
    parser = argparse.ArgumentParser(description="Port Scan Module",
                                     usage="portscan.py -i <IP> -u <USER_ID>")

    parser.add_argument('-i','--ip', help='The IP Address to scan', required=True)
    parser.add_argument('-u','--uid', help='User ID', required=True)

    return vars(parser.parse_args())

def main():
    """
    Main function.
    """
    args = get_args()
    try:
        scanner = PortScanner(args['uid'], args['ip'])
        scanner.scan()
    except:
        results = {}

        results['Open'] = []
        results['Filtered'] = []
        results['Connects'] = []

        with open(PORTSCAN_RESULTS_PATH + r'/{}.json'.format(args['uid']), 'w') as f:
            json.dump(results, f, ensure_ascii=False, indent=4)

        send_to_api(args['uid'], PORTSCAN_TABLE_NAME)
    
if __name__ == '__main__':
    main()
