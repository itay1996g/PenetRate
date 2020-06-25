<?php

include_once 'db-config.php';
include_once 'Input.php';
include_once '../helpers/session.php';


if (isset($_POST['formID'])) {
    $formID = Input::str($_POST['formID']);
    $db = new DBController();
    $conn = $db->connect();

    //Create Scan :: Regular Operation
    if ($formID == 'Create') {
        if (checkLoggedIn()) {
            if (isset($_POST['url']) && isset($_POST['loginurl']) && isset($_POST['username']) && isset($_POST['scan_password'])) {
                $form = $_POST;
                try {
                    $url = Input::str($form['url']);
                    $loginurl = Input::str($form['loginurl']);
                    $username = Input::str($form['username']);
                    $scan_password = Input::str($form['scan_password']);
                    $openports = $sslscan = $subdomainscan = $mapdirectories = $mapheaders = $clientsidevulnerability = $Generalvulnerability = '0';

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
                    if (isset($_POST['mapheaders'])) {
                        $mapheaders = '1';
                    }
                    if (isset($_POST['clientsidevulnerability'])) {
                        $clientsidevulnerability = '1';
                    }
                    if (isset($_POST['Generalvulnerability'])) {
                        $Generalvulnerability = '1';
                    }

                    $stmt = $conn->prepare('SELECT * FROM Users WHERE UserID = ?');
                    $stmt->bind_param('s', $_SESSION['UserID']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $stmt = $conn->prepare("INSERT INTO scans (UserID, URL, Login_URL, username, password, Is_PortsScan, Is_SSLScan, Is_SubdomainsScan, Is_DirectoriesScan, Is_HeadersScan, Is_ClientSideScan, Is_GeneralVulnerabilitiesScan,Status) values (?,?,?,?,?,?,?,?,?,?,?,?,'Created')");
                        $stmt->bind_param('ssssssssssss', $_SESSION['UserID'], $url, $loginurl, $username, $scan_password, $openports, $sslscan, $subdomainscan, $mapdirectories, $mapheaders, $clientsidevulnerability, $Generalvulnerability);
                        if ($stmt->execute()) {
                            $ScanID = $stmt->insert_id;
                            $data = "Created";
                            if ($openports == '1') {
                                $stmt = $conn->prepare("INSERT INTO ports_scan (ScanID, Status) values (?,'Created')");
                                $stmt->bind_param('s',  $ScanID);
                                if ($stmt->execute()) {
                                    $data = "Created";
                                } else {
                                    $data = "Error";
                                }
                            }
                            if ($sslscan == '1') {
                                $stmt = $conn->prepare("INSERT INTO ssl_scan (ScanID, Status) values (?,'Created')");
                                $stmt->bind_param('s',  $ScanID);
                                if ($stmt->execute()) {
                                    $data = "Created";
                                } else {
                                    $data = "Error";
                                }
                            }
                            if ($subdomainscan == '1') {
                                $stmt = $conn->prepare("INSERT INTO subdomains_scan (ScanID, Status) values (?,'Created')");
                                $stmt->bind_param('s',  $ScanID);
                                if ($stmt->execute()) {
                                    $data = "Created";
                                } else {
                                    $data = "Error";
                                }
                            }
                            if ($mapdirectories == '1') {
                                $stmt = $conn->prepare("INSERT INTO directories_scan (ScanID, Status) values (?,'Created')");
                                $stmt->bind_param('s',  $ScanID);
                                if ($stmt->execute()) {
                                    $data = "Created";
                                } else {
                                    $data = "Error";
                                }
                            }
                            if ($mapheaders == '1') {
                                $stmt = $conn->prepare("INSERT INTO headers_scan (ScanID, Status) values (?,'Created')");
                                $stmt->bind_param('s',  $ScanID);
                                if ($stmt->execute()) {
                                    $data = "Created";
                                } else {
                                    $data = "Error";
                                }
                            }
                            if ($clientsidevulnerability == '1') {
                                $stmt = $conn->prepare("INSERT INTO clientside_scan (ScanID, Status) values (?,'Created')");
                                $stmt->bind_param('s',  $ScanID);
                                if ($stmt->execute()) {
                                    $data = "Created";
                                } else {
                                    $data = "Error";
                                }
                            }
                            if ($Generalvulnerability == '1') {
                                $stmt = $conn->prepare("INSERT INTO generalvulnerabilities_scan (ScanID, Status) values (?,'Created')");
                                $stmt->bind_param('s',  $ScanID);
                                if ($stmt->execute()) {
                                    $data = "Created";
                                } else {
                                    $data = "Error";
                                }
                            }
                        } else {
                            $data = "Error";
                        }
                    } else {
                        header("Location: ../users/login.html");
                    }

                    echo $data;
                } catch (Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
            } else {
                header("Location: ../users/login.html");
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
        $stmt = $conn->prepare("UPDATE " . $table_name . " set Status = ? WHERE ScanID = ?");
        $stmt->bind_param('ss', $Status, $ScanID);
        if ($stmt->execute()) {
            $data = "Updated";
        } else {
            $data = "ErrorUpdated";
        }
        echo $data;
    }
} else {
    header("Location: ../users/login.html");
}
