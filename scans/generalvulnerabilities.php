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
    <title>PenetRate - View General Vulnerabilities</title>
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
    $XSS = '';
    $SQLI = '';
    $CSRF = '';
    $AuthBypass = '';

    if (isset($_GET['UID'])) {

        $db = new DBController();
        $conn = $db->connect();
        $user_check = $_SESSION['UserID'];
        $scan_check = $_GET["UID"];
        $user_check = mysqli_real_escape_string($conn, $user_check);
        $scan_check = mysqli_real_escape_string($conn, $scan_check);
        $stmt = $conn->prepare("SELECT * FROM scans WHERE UserID = ? AND ScanID = ?");
        $stmt->bind_param('ss', $user_check, $scan_check);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            #XSS
            $XSS = '';
            $XSS = "<a href='http://193.106.55.103:8080/penetrate/scans/XSS.php?UID=" . $scan_check . "'>View Results</a>";


            #SQLI
            $SQLI = '';
            #XSS
            $SQLI = '';
            $SQLI = "<a href='http://193.106.55.103:8080/penetrate/scans/SQLI.php?UID=" . $scan_check . "'>View Results</a>";
            #CSRF
            $CSRF = '';
            $CSRF = "<a href='http://193.106.55.103:8080/penetrate/scans/CSRF.php?UID=" . $scan_check . "'>View Results</a>";

            #AuthBypass
            $AuthBypass = '';
            $AuthBypass = "<a href='http://193.106.55.103:8080/penetrate/scans/AuthBypass.php?UID=" . $scan_check . "'>View Results</a>";
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
                        <h4>View General Vulnerabilities</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="history_users_table" class="table table-hover table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>XSS</th>
                                        <th>SQLI</th>
                                        <th>CSRF</th>
                                        <th>AuthBypass</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td><?php echo $XSS ?></td>
                                    <td><?php echo $SQLI ?></td>
                                    <td><?php echo $CSRF ?></td>
                                    <td><?php echo $AuthBypass ?></td>
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