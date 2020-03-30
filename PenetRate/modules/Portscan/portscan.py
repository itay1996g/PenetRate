import socket
import os
import argparse
import json
import requests
import re
import paramiko
import wmi
from scapy.all import *

# Local Consts
RESULTS_FILE_PATH  = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__)))) + r"/Results"
DEFAULT_CREDS_PATH = os.path.dirname(os.path.abspath(__file__)) + r"/addons/default_creds.txt"
CREDS_REGEX = r"^(.*)\:(.*)$"
SSH_PORT_NUM = 22
TELNET_PORT_NUM = 23
MYSQLPORT_NUM = 3306
RDP_PORT_NUM = 3389

class PortScanner(object):

    _SYNACK = 0x12
    _RSTACK = 0x14
        
    def __init__(self, uid, target_ip, port_nums=None, timeout=0.4):
        """
        Before creating the scanner object, 
        If the IP is reachable, create a scanner object.
        """
        if not self.check_host(target_ip):
            return
        
        self.target_ip = target_ip
        self.ports_to_scan = []
        self.timeout = timeout
        self.uid = uid
        
        resp = self.parse_port_range(port_nums)
        
        if resp is not None:
            if MYSQLPORT_NUM in self.ports_to_scan:
                raise ValueError("DB Name must be specified")
            self.ports_to_scan = resp

        self.open_ports = {}
        self.filtered_ports = []
        self.connected_ports = {}

    def check_host(self, ip):
        """
        Check whether the IP is responding to ICMP requests.
        If so - the IP is reachable.
        """
        try:
            conf.verb = 0
            ping = sr1(IP(dst = ip)/ICMP())
    
            if ping is not None:
                return True
            return False
        except:
            return False
        
    def tcp_port_scan(self, port):
        """
        TCP Scan for a port.
        Using scapy, send a proper packet.
        If a SYN ACK recived, then the port behind the IP will be marked as open.
        If not, the port will be marked as filtered.
        In both cases, the method will send a RST packet for the service.
        """
        srcport = RandShort()
        conf.verb = 0
        pkt = sr1(IP(dst=self.target_ip)/TCP(sport=srcport,
                                              dport=port,
                                              flags="S"),
                                              timeout=self.timeout)
        if pkt is not None:
            if pkt.haslayer(TCP):
                if pkt[TCP].flags == self._SYNACK:
                    self.open_ports[port] = socket.getservbyport(port)
                else:
                    self.filtered_ports.append(port)
            elif pkt.haslayer(ICMP):
                self.filtered_ports.append(port)
        else:
            RSTpkt = IP(dst = self.target_ip)/TCP(sport = srcport, dport = port, flags = "R")
            send(RSTpkt)

    def check_valid_ports(self, ports):
        """
        Check if a port number is valid.
        Suits for port range and a single port.
        """
        if '-' not in ports:
            if int(ports) <= 65535:
                return True
            return False
        else:
            min_port = int(ports.split('-')[0])
            max_port = int(ports.split('-')[1])
            if min_port > 65535 or max_port > 65535:
                return False
            return True
    
    def parse_port_range(self, port_range):
        """
        Parsing a port range recived as a string to an iterable list.
        """
        ports = []
        port_range = port_range.replace(' ', '')
        port_range = port_range.split(',')
        
        for port in port_range:
            if self.check_valid_ports(port):
                if '-' in port:
                    min_port = int(port.split('-')[0])
                    max_port = int(port.split('-')[1])
                    for i in range(min_port, max_port + 1):
                        ports.append(i)
                else:
                    ports.append(port)
                    
        return set(ports)

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
                        self.connected_ports['SSH'] = { 'Username' : user, 'Password' : pwd }
                elif port == RDP_PORT_NUM:
                    if self.check_rdp_connection(user, pwd):
                        self.connected_ports['RDP'] = { 'Username' : user, 'Password' : pwd }
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
        except paramiko.AuthenticationException:
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
        For open SSH, Telnet and RDP ports (22, 23, 3389) the method will
        try to connect to the service behind the port with common default credentials. 
        """
        for port in self.ports_to_scan:
            try:
                self.tcp_port_scan(int(port))
            except OSError:
                continue

        for port in self.open_ports:
            self.check_connection_to_port(int(port))
            
        self.save_results_to_json()

    def get_open_ports(self):
        if len(self.open_ports) > 0:
            return self.open_ports

    def get_open_ports(self):
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
        if not os.path.exists(RESULTS_FILE_PATH):
            os.mkdir(RESULTS_FILE_PATH)

        results = {}
        
        results['Open'] = self.get_open_ports()
        results['Filtered'] = self.get_open_ports()
        results['Connects'] = self.get_connected_ports()
        
        with open(RESULTS_FILE_PATH + r'/{}.json'.format(self.uid), 'a', encoding='utf-8') as f:
            json.dump(results, f, ensure_ascii=False, indent=4)

def get_args():
    """
    Get arguments for the port scanner script.
    """
    parser = argparse.ArgumentParser(description="Port Scan Module",
                                     usage="portscan.py -i <IP> -p <PORTS> -u <USER_ID>")

    parser.add_argument('-i','--ip', help='The IP Address to scan', required=True)
    parser.add_argument('-p','--port', help='Port range', required=True)
    parser.add_argument('-u','--uid', help='User ID', required=True)

    return vars(parser.parse_args())

def main():
    """
    Main function.
    """
    args = get_args()
    scanner = PortScanner(args['uid'], args['ip'], args['port'])
    scanner.scan()

    #resp = requests.get(r'localhost')
    
if __name__ == '__main__':
    main()
    
