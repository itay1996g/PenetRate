<?php

function execInBackground($cmd) {
    if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $cmd, "r")); 
    }
    else {
        exec($cmd . " > /dev/null &");  
    }
}
$url = 'https://ins-isi.com/';
$ScanID = '35';
execInBackground('python3 C:\\Users\\User.User-PC\\Dropbox\\UniSchool\\PenetRate\\modules\\Crawler\\crawler.py -d ' . $url . ' -u ' . $ScanID);


?>