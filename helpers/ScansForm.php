<?php

include_once 'db-config.php';
include_once 'Input.php';
include_once '../helpers/session.php';


function execInBackground($cmd)
{
    if (substr(php_uname(), 0, 7) == "Windows") {
        pclose(popen("start /B " . $cmd, "r"));
    } else {
        exec($cmd . " > /dev/null &");
    }
}

if (isset($_POST['formID'])) {
    $formID = Input::str($_POST['formID']);
    $db = new DBController();
    $conn = $db->connect();

    //Create Scan :: Regular Operation
    if ($formID == 'Create') {
        if (checkLoggedIn()) {
            if (isset($_POST['url'])) {
                $form = $_POST;
                try {
                    $url = Input::str($form['url']);
                    $ip = str_replace("https://", "", $url);
                    $ip = str_replace("http://", "", $ip);
                    $ip = str_replace("/", "", $ip);
                    $ip = gethostbyname($ip);

                    $scan_Cookie = '';
                    if (isset($_POST['scan_Cookie'])) {
                        $scan_Cookie = Input::str($form['scan_Cookie']);
                    }
                    $openports = $sslscan = $subdomainscan = $mapdirectories = $Crawler = $mapheaders = $clientsidevulnerability = $Generalvulnerability = $servicesscan = '0';

                    if (isset($_POST['openports'])) {
                        $openports = '1';
                    }
                    if (isset($_POST['sslscan'])) {
                        $sslscan = '1';
                    }
                    if (isset($_POST['subdomainscan'])) {
                        $subdomainscan = '1';
                    }
                    if (isset($_POST['mapdirectories'])) {
                        $mapdirectories = '1';
                    }
                    if (isset($_POST['Crawler'])) {
                        $Crawler = '1';
                    }

                    if (isset($_POST['mapheaders'])) {
                        $mapheaders = '1';
                    }
                    if (isset($_POST['clientsidevulnerability'])) {
                        $clientsidevulnerability = '1';
                    }
                    if (isset($_POST['Generalvulnerability'])) {
                        $Generalvulnerability = '1';
                    }
                    if (isset($_POST['servicesscan'])) {
                        $servicesscan = '1';
                    }

                    $stmt = $conn->prepare('SELECT * FROM Users WHERE UserID = ?');
                    $stmt->bind_param('s', $_SESSION['UserID']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $stmt = $conn->prepare("INSERT INTO scans (UserID, URL, scan_Cookie, Is_PortsScan, Is_ServicesScan, Is_SSLScan, Is_SubdomainsScan, Is_DirectoriesScan,Is_CrawlerScan, Is_HeadersScan, Is_ClientSideScan, Is_GeneralVulnerabilitiesScan,Status) values (?,?,?,?,?,?,?,?,?,?,?,?,'Created')");
                        $stmt->bind_param('ssssssssssss', $_SESSION['UserID'], $url, $scan_Cookie, $openports, $servicesscan, $sslscan, $subdomainscan, $mapdirectories, $Crawler, $mapheaders, $clientsidevulnerability, $Generalvulnerability);
                        if ($stmt->execute()) {
                            $ScanID = $stmt->insert_id;
                            $data = "Created";
                            $Status = 'Not Scanned';
                            if ($openports == '1') {
                                $Status = 'Created';
                            }
                            $stmt = $conn->prepare("INSERT INTO ports_scan (ScanID, Status) values (?,?)");
                            $stmt->bind_param('ss',  $ScanID, $Status);
                            if ($stmt->execute()) {
                                $data = "Created";
                                if ($openports == '1') {
                                    execInBackground('python C:\\xampp\\htdocs\\PenetRate\\modules\\Portscan\\portscan.py -i ' . $ip . ' -u ' . $ScanID);
                                }
                            } else {
                                $data = "Error";
                            }


                            $Status = 'Not Scanned';
                            if ($servicesscan == '1') {
                                $Status = 'Created';
                            }
                            $stmt = $conn->prepare("INSERT INTO services_scan (ScanID, Status) values (?,?)");
                            $stmt->bind_param('ss',  $ScanID, $Status);
                            if ($stmt->execute()) {

                                $data = "Created";
                                if ($servicesscan == '1') {
                                    execInBackground('python3 C:\\xampp\\htdocs\\PenetRate\\modules\\ServiceScan\\servicescan.py -d ' . $url . ' -u ' . $ScanID);
                                }
                            } else {
                                $data = "Error";
                            }


                            $Status = 'Not Scanned';
                            if ($sslscan == '1') {
                                $Status = 'Created';
                            }
                            $stmt = $conn->prepare("INSERT INTO ssl_scan (ScanID, Status) values (?,?)");
                            $stmt->bind_param('ss',  $ScanID, $Status);
                            if ($stmt->execute()) {
                                $data = "Created";
                                if ($sslscan == '1') {
                                    execInBackground('python3 C:\\xampp\\htdocs\\PenetRate\\modules\\SSLScan\\sslscan.py -d ' . $url . ' -u ' . $ScanID);
                                }
                            } else {
                                $data = "Error";
                            }


                            $Status = 'Not Scanned';
                            if ($subdomainscan == '1') {
                                $Status = 'Created';
                            }
                            $stmt = $conn->prepare("INSERT INTO subdomains_scan (ScanID, Status) values (?,?)");
                            $stmt->bind_param('ss',  $ScanID, $Status);
                            if ($stmt->execute()) {
                                $data = "Created";
                                if ($subdomainscan == '1') {
                                    execInBackground('python3 C:\\xampp\\htdocs\\PenetRate\\modules\\Subdomains\\runsubdomains.py -d ' . $url . ' -u ' . $ScanID);
                                }
                            } else {
                                $data = "Error";
                            }

                            $Status = 'Not Scanned';
                            if ($mapdirectories == '1') {
                                $Status = 'Created';
                            }
                            $add = '';
                            $stmt = $conn->prepare("INSERT INTO directories_scan (ScanID, Status) values (?,?)");
                            $stmt->bind_param('ss',  $ScanID, $Status);
                            if ($stmt->execute()) {
                                $data = "Created";
                                if ($mapdirectories == '1') {
                                    $add = '-b -f small';
                                }
                            } else {
                                $data = "Error";
                            }

                            $Status = 'Not Scanned';
                            if ($Crawler == '1') {
                                $Status = 'Created';
                            }
                            $stmt = $conn->prepare("INSERT INTO crawler_scan (ScanID, Status) values (?,?)");
                            $stmt->bind_param('ss',  $ScanID, $Status);
                            if ($stmt->execute()) {
                                $data = "Created";
                            } else {
                                $data = "Error";
                            }

                            $Status = 'Not Scanned';
                            if ($mapheaders == '1') {
                                $Status = 'Created';
                            }
                            $stmt = $conn->prepare("INSERT INTO headers_scan (ScanID, Status) values (?,?)");
                            $stmt->bind_param('ss',  $ScanID, $Status);
                            if ($stmt->execute()) {
                                $data = "Created";
                                if ($mapheaders == '1') {
                                    execInBackground('hsecscan -i -UID ' . $ScanID . ' -u ' . $url);
                                }
                            } else {
                                $data = "Error";
                            }


                            $Status = 'Not Scanned';
                            if ($clientsidevulnerability == '1') {
                                $Status = 'Created';
                            }
                            $stmt = $conn->prepare("INSERT INTO clientside_scan (ScanID, Status) values (?,?)");
                            $stmt->bind_param('ss',  $ScanID, $Status);
                            if ($stmt->execute()) {
                                $data = "Created";
                            } else {
                                $data = "Error";
                            }

                            $Status = 'Not Scanned';
                            if ($Generalvulnerability == '1') {
                                $Status = 'Created';
                            }
                            $stmt = $conn->prepare("INSERT INTO generalvulnerabilities_scan (ScanID, Status) values (?,?)");
                            $stmt->bind_param('ss',  $ScanID, $Status);
                            if ($stmt->execute()) {
                                $data = "Created";
                                if ($Generalvulnerability == '1') {
                                    if ($scan_Cookie != '') {
                                        execInBackground('python3 C:\\xampp\\htdocs\\PenetRate\\modules\\Crawler\\crawler_main.py -d ' . $url . ' ' . $add . ' -a -c "' . $scan_Cookie . '" -u ' . $ScanID);
                                    }
                                } else if ($Crawler == '1') {
                                    if ($scan_Cookie != '') {
                                        execInBackground('python3 C:\\xampp\\htdocs\\PenetRate\\modules\\Crawler\\crawler_main.py -d ' . $url . ' ' . $add . '  -c "' . $scan_Cookie . '" -u ' . $ScanID);
                                    } else {
                                        execInBackground('python3 C:\\xampp\\htdocs\\PenetRate\\modules\\Crawler\\crawler_main.py -d ' . $url . ' ' . $add . '  -u ' . $ScanID);
                                    }
                                }
                            }
                            if ($Generalvulnerability == '0' && $Crawler == '0' && $mapdirectories == '1') {
                                execInBackground('python3 C:\\xampp\\htdocs\\PenetRate\\modules\\DirBust\\dirbust.py -d ' . $url . ' -f small -u ' . $ScanID);
                            }
                        } else {
                            $data = "Error";
                        }
                    } else {
                        $data = "Error";
                    }


                    echo $data;
                } catch (Exception $e) {
                    echo 'ErrorLogin';
                }
            } else {
                echo 'ErrorLogin';
            }
        }
    }
} else if (isset($_POST['ScanID']) && isset($_POST['Status']) && isset($_POST['table_name']) && isset($_POST['GUID'])) {
    $db = new DBController();
    $conn = $db->connect();
    $form = $_POST;
    $table_name = Input::str($form['table_name']);
    $ScanID = Input::str($form['ScanID']);
    $Status = Input::str($form['Status']);
    $GUID = Input::str($form['GUID']);


    if ($GUID == 'ETAI_ITAY123AA6548') {

        $can_i_go = true;
        $stmt = $conn->prepare("SELECT Status FROM " . $table_name . " WHERE ScanID = ?");
        $stmt->bind_param('s', $ScanID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['Status'] == 'Not Scanned') {
                $can_i_go = false;
            }
        }

        if ($can_i_go == true) {
            $stmt = $conn->prepare("UPDATE " . $table_name . " set Status = ? WHERE ScanID = ?");
            $stmt->bind_param('ss', $Status, $ScanID);
            if ($stmt->execute()) {
                $data = "Updated";
            } else {
                $data = "ErrorUpdated";
            }
            echo $data;
            $stmt = $conn->prepare("SELECT COUNT(*) as counting FROM(SELECT count(*) FROM ports_scan WHERE Status = 'Created' AND ScanID = ? HAVING COUNT(*)>0 UNION ALL SELECT count(*) FROM crawler_scan WHERE Status = 'Created' AND ScanID = ? HAVING COUNT(*)>0 UNION ALL SELECT count(*) FROM headers_scan WHERE Status = 'Created' AND ScanID = ? HAVING COUNT(*)>0 UNION ALL SELECT count(*) FROM clientside_scan WHERE Status = 'Created' AND ScanID = ? HAVING COUNT(*)>0 UNION ALL SELECT count(*) FROM directories_scan WHERE Status = 'Created' AND ScanID = ? HAVING COUNT(*)>0 UNION ALL SELECT count(*) FROM generalvulnerabilities_scan WHERE Status = 'Created' AND ScanID = ? HAVING COUNT(*)>0 UNION ALL SELECT count(*) FROM services_scan WHERE Status = 'Created' AND ScanID = ? HAVING COUNT(*)>0 UNION ALL SELECT count(*) FROM ssl_scan WHERE Status = 'Created' AND ScanID = ? HAVING COUNT(*)>0 UNION ALL SELECT count(*) FROM subdomains_scan WHERE Status = 'Created' AND ScanID = ? HAVING COUNT(*)>0)x");
            $stmt->bind_param('sssssssss', $ScanID, $ScanID, $ScanID, $ScanID, $ScanID, $ScanID, $ScanID, $ScanID, $ScanID);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['counting'] > 0) {
                    echo 'Not Finished';
                } else {
                    $Status = 'Finished';
                    $stmt = $conn->prepare("UPDATE scans set Status = ? WHERE ScanID = ?");
                    $stmt->bind_param('ss', $Status, $ScanID);
                    if ($stmt->execute()) {
                        $data = "Updated_scans";
                    } else {
                        $data = "ErrorUpdatedscans";
                    }
                }
            }
        }
    }
} else {
    echo '<script>window.location.href = "../users/login.html";</script>';
}
