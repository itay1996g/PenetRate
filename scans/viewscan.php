<?php
include '../helpers/session.php';
checkLoggedIn();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon.png">
    <title>PenetRate - View Scan Status</title>
    <!-- Bootstrap Core CSS -->
    <link href="../css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/helper.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <script src="../js/lib/jquery/jquery.min.js"></script>


    <script src="../js/jquery.validate.min.js"></script>
    <script src="../js/jquery.validate-init.js"></script>
    <script src="../js/form-submit.js"></script>
    <script src="../js/toastr.min.js"></script>
    <script src="../js/toastr.init.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/sweetalert.init.js"></script>


    <style>
        [class^="ti-"],
        [class*=" ti-"],
        [class*=" fa-"] {
            padding-right: 5px !important;
        }

        a {
            color: #007bff !important;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip({
                placement: 'top'
            });
        });
    </script>

</head>

<body class="fix-header fix-sidebar">
    <?php
    if ($_SESSION['UserRole'] == 'Admin') {
        include('../menuadmin.php');
    } else if ($_SESSION['UserRole'] == 'User') {
        include('../menu.php');
    } else {
        header("Location: ../users/login.html");
        exit;
    }
    if (isset($_POST['ScanID'])) {

        $db = new DBController();
        $conn = $db->connect();
        $user_check = $_SESSION['UserID'];
        $scan_check = $_POST['ScanID'];
        $user_check = mysqli_real_escape_string($conn, $user_check);
        $scan_check = mysqli_real_escape_string($conn, $scan_check);

        $stmt = $conn->prepare("SELECT * FROM scans WHERE UserID = ? AND ScanID = ?");
        $stmt->bind_param('ss', $user_check, $scan_check);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {



            #Ports Scan
            $Open_Ports = '';
            $stmt = $conn->prepare("SELECT * FROM ports_scan WHERE ScanID = ?");
            $stmt->bind_param('s', $scan_check);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['Status'] == 'Not Scanned') {
                    $Open_Ports = '<bb style="color:red;">Not Scanned<bb>';
                } else if ($row['Status'] == 'Created') {
                    $Open_Ports = '<bb style="color:green;">Scanning<bb>';
                } else if ($row['Status'] == 'Finished') {
                    $Open_Ports = "<a href='http://127.0.0.1:8080/penetrate/scans/ports.php?UID=" . $scan_check . "'>View Results</a>";
                }
            } else {
                echo 'Error with ports_scan';
            }

            #services
            $services = '';
            $stmt = $conn->prepare("SELECT * FROM services_scan WHERE ScanID = ?");
            $stmt->bind_param('s', $scan_check);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['Status'] == 'Not Scanned') {
                    $services = '<bb style="color:red;">Not Scanned<bb>';
                } else if ($row['Status'] == 'Created') {
                    $services = '<bb style="color:green;">Scanning<bb>';
                } else if ($row['Status'] == 'Finished') {
                    $services = "<a href='http://127.0.0.1:8080/penetrate/scans/services.php?UID=" . $scan_check . "'>View Results</a>";
                }
            } else {
                echo 'Error with services';
            }



            #sslscan
            $sslscan = '';
            $stmt = $conn->prepare("SELECT * FROM ssl_scan WHERE ScanID = ?");
            $stmt->bind_param('s', $scan_check);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {

                $row = $result->fetch_assoc();
                if ($row['Status'] == 'Not Scanned') {
                    $sslscan = '<bb style="color:red;">Not Scanned<bb>';
                } else if ($row['Status'] == 'Created') {
                    $sslscan = '<bb style="color:green;">Scanning<bb>';
                } else if ($row['Status'] == 'Finished') {
                    $sslscan = "<a href='http://127.0.0.1:8080/penetrate/scans/sslscan.php?UID=" . $scan_check . "'>View Results</a>";
                }
            } else {
                echo 'Error with sslscan';
            }

            #subdomains
            $subdomains = '';
            $stmt = $conn->prepare("SELECT * FROM subdomains_scan WHERE ScanID = ?");
            $stmt->bind_param('s', $scan_check);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['Status'] == 'Not Scanned') {
                    $subdomains = '<bb style="color:red;">Not Scanned<bb>';
                } else if ($row['Status'] == 'Created') {
                    $subdomains = '<bb style="color:green;">Scanning<bb>';
                } else if ($row['Status'] == 'Finished') {
                    $subdomains = "<a href='http://127.0.0.1:8080/penetrate/scans/subdomains.php?UID=" . $scan_check . "'>View Results</a>";
                }
            } else {
                echo 'Error with subdomains';
            }


            #directories
            $directories = '';
            $stmt = $conn->prepare("SELECT * FROM directories_scan WHERE ScanID = ?");
            $stmt->bind_param('s', $scan_check);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['Status'] == 'Not Scanned') {
                    $directories = '<bb style="color:red;">Not Scanned<bb>';
                } else if ($row['Status'] == 'Created') {
                    $directories = '<bb style="color:green;">Scanning<bb>';
                } else if ($row['Status'] == 'Finished') {
                    $directories = "<a href='http://127.0.0.1:8080/penetrate/scans/directories.php?UID=" . $scan_check . "'>View Results</a>";
                }
            } else {
                echo 'Error with directories';
            }


            #headers
            $headers = '';
            $stmt = $conn->prepare("SELECT * FROM headers_scan WHERE ScanID = ?");
            $stmt->bind_param('s', $scan_check);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['Status'] == 'Not Scanned') {
                    $headers = '<bb style="color:red;">Not Scanned<bb>';
                } else if ($row['Status'] == 'Created') {
                    $headers = '<bb style="color:green;">Scanning<bb>';
                } else if ($row['Status'] == 'Finished') {
                    $headers = "<a href='http://127.0.0.1:8080/penetrate/scans/headers.php?UID=" . $scan_check . "'>View Results</a>";
                }
            } else {
                echo 'Error with headers';
            }

            #sensitiveinfo
            $sensitiveinfo = '';
            $stmt = $conn->prepare("SELECT * FROM clientside_scan WHERE ScanID = ?");
            $stmt->bind_param('s', $scan_check);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['Status'] == 'Not Scanned') {
                    $sensitiveinfo = '<bb style="color:red;">Not Scanned<bb>';
                } else if ($row['Status'] == 'Created') {
                    $sensitiveinfo = '<bb style="color:green;">Scanning<bb>';
                } else if ($row['Status'] == 'Finished') {
                    $sensitiveinfo = "<a href='http://127.0.0.1:8080/penetrate/scans/sensitiveinfo.php?UID=" . $scan_check . "'>View Results</a>";
                }
            } else {
                echo 'Error with sensitiveinfo';
            }


            #sensitiveinfo
            $generalvulnerabilities_scan = '';
            $stmt = $conn->prepare("SELECT * FROM generalvulnerabilities_scan WHERE ScanID = ?");
            $stmt->bind_param('s', $scan_check);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['Status'] == 'Not Scanned') {
                    $generalvulnerabilities_scan = '<bb style="color:red;">Not Scanned<bb>';
                } else if ($row['Status'] == 'Created') {
                    $generalvulnerabilities_scan = '<bb style="color:green;">Scanning<bb>';
                } else if ($row['Status'] == 'Finished') {
                    $generalvulnerabilities_scan = "<a href='http://127.0.0.1:8080/penetrate/scans/generalvulnerabilities_scan.php?UID=" . $scan_check . "'>View Results</a>";
                }
            } else {
                echo 'Error with generalvulnerabilities_scan';
            }
        } else {
            echo '<script>window.location.href = "../users/login.html";</script>';
        }
    } else {
        echo '<script>window.location.href = "../users/login.html";</script>';
    }


    ?>
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">PenetRate</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Scans</li>
                <li class="breadcrumb-item active">Scan Status</li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->
        <div class="row">

            <!-- /# column -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-title">
                        <h4>View Scan Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="history_users_table" class="table table-hover table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Open Ports</th>
                                        <th>Services Scan</th>
                                        <th>SSL Scan</th>
                                        <th>Subdomain Scan</th>
                                        <th>Map Directories</th>
                                        <th>Map Headers</th>
                                        <th>Client Side Vulnerability</th>
                                        <th>General Vulnerability</th>



                                    </tr>
                                </thead>
                                <tbody>
                                    <td><?php echo $Open_Ports ?></td>
                                    <td><?php echo $services ?></td>
                                    <td><?php echo $sslscan ?></td>
                                    <td><?php echo $subdomains ?></td>
                                    <td><?php echo $directories ?></td>
                                    <td><?php echo $headers ?></td>
                                    <td><?php echo $sensitiveinfo ?></td>
                                    <td><?php echo $generalvulnerabilities_scan ?></td>




                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <!-- /# card -->
            </div>
            <!-- /# column -->
        </div>
        <!-- /# row -->
        <!-- End PAge Content -->
    </div>
    <?php
    include('../footer.html');
    ?>