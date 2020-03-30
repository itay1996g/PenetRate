import socket
import os
import argparse
import json
import requests
import re
import paramiko
import wmi
from scapy.all import *

# Port scanner module for PenetRate
# Getting port numbers by string, Target IP Address, User-ID

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
        if not self._check_host(target_ip):
            return
        
        self._target_ip = target_ip
        self._port_nums_to_scan = []
        self._timeout = timeout
        self._uid = uid
        
        resp = self._parse_port_range(port_nums)
        if resp is not None:
            if MYSQLPORT_NUM in self._port_nums_to_scan:
                raise ValueError("DB Name must be specified")
            self._port_nums_to_scan = resp

        self._open_ports = {}
        self._filtered_ports = []
        self._connected_ports = {}

    def _check_host(self, ip):
        try:
            conf.verb = 0
            ping = sr1(IP(dst = ip)/ICMP())
    
            if ping is not None:
                return True
            return False
        except:
            return False
        
    def _TCPScanPort(self, port):
        srcport = RandShort()
        conf.verb = 0
        pkt = sr1(IP(dst=self._target_ip)/TCP(sport=srcport,
                                              dport=port,
                                              flags="S"),
                                              timeout=self._timeout)
        if pkt is not None:
            if pkt.haslayer(TCP):
                if pkt[TCP].flags == self._SYNACK:
                    self._open_ports[port] = socket.getservbyport(port)
                else:
                    self._filtered_ports.append(port)
            elif pkt.haslayer(ICMP):
                self._filtered_ports.append(port)
        else:
            RSTpkt = IP(dst = self._target_ip)/TCP(sport = srcport, dport = port, flags = "R")
            send(RSTpkt)

    def _check_valid_ports(self, ports):
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
    
    def _parse_port_range(self, port_range):
        ports = []
        port_range = port_range.replace(' ', '')
        port_range = port_range.split(',')
        for port in port_range:
            if self._check_valid_ports(port):
                if '-' in port:
                    min_port = int(port.split('-')[0])
                    max_port = int(port.split('-')[1])
                    for i in range(min_port, max_port + 1):
                        ports.append(i)
                else:
                    ports.append(port)
                    
        return set(ports)

    def _check_connection_to_port(self, port):
        with open(DEFAULT_CREDS_PATH, 'r') as creds_file:
            for line in creds_file.readlines():
                user = re.findall(CREDS_REGEX, line)[0][0]
                pwd = re.findall(CREDS_REGEX, line)[0][1]

                if port == SSH_PORT_NUM:
                    if self._check_ssh_connection(user, pwd):
                        self._connected_ports['SSH'] = { 'Username' : user, 'Password' : pwd }
                elif port == RDP_PORT_NUM:
                    if self.check_rdp_connection(user, pwd):
                        self._connected_ports['RDP'] = { 'Username' : user, 'Password' : pwd }
                else:
                    continue

    def _check_ssh_connection(self, user, pwd):
        ssh_client = paramiko.SSHClient()
        ssh_client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

        try:
            ssh_client.connect(hostname=self._target_ip, username=user, password=pwd)
            return True
        except paramiko.AuthenticationException:
            return False

    def _check_rdp_connection(self, user, pwd):
        try:
            connection = wmi.WMI(self._target_ip, user=user, password=pwd)
            return True
        except wmi.x_access_denied:
            return False
        
    def startScan(self):
        for port in self._port_nums_to_scan:
            try:
                self._TCPScanPort(int(port))
            except OSError:
                continue

        for port in self._open_ports:
            self._check_connection_to_port(int(port))
            
        self._save_results_to_json()

    def getOpenPorts(self):
        if len(self._open_ports) > 0:
            return self._open_ports

    def getFilteredPorts(self):
        if len(self._filtered_ports) > 0:
            return self._filtered_ports

    def getConnectedPorts(self):
        if len(self._connected_ports) > 0:
            return self._connected_ports

    def _save_results_to_json(self):
        if not os.path.exists(RESULTS_FILE_PATH):
            os.mkdir(RESULTS_FILE_PATH)

        results = {}
        
        results['Open'] = self.getOpenPorts()
        results['Filtered'] = self.getFilteredPorts()
        results['Connects'] = self.getConnectedPorts()
        
        with open(RESULTS_FILE_PATH + r'/{}.json'.format(self._uid), 'a', encoding='utf-8') as f:
            json.dump(results, f, ensure_ascii=False, indent=4)

def get_args():
    parser = argparse.ArgumentParser(description="Port Scan Module",
                                     usage="portscan.py -i <IP> -p <PORTS> -u <USER_ID>")

    parser.add_argument('-i','--ip', help='The IP Address to scan', required=True)
    parser.add_argument('-p','--port', help='Port range', required=True)
    parser.add_argument('-u','--uid', help='User ID', required=True)

    return vars(parser.parse_args())

def main():
    args = get_args()
    scanner = PortScanner(args['uid'], args['ip'], args['port'])
    scanner.startScan()

    #resp = requests.get(r'localhost')
    
if __name__ == '__main__':
    main()
    
