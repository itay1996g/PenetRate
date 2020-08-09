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
    <title>PenetRate - Payload Generator</title>
    <!-- Bootstrap Core CSS -->
    <link href="../css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/helper.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <script src="../js/lib/jquery/jquery.min.js"></script>

    <style>
        [class^="ti-"],
        [class*=" ti-"],
        [class*=" fa-"] {
            padding-right: 5px !important;
        }
    </style>
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
    ?>
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Penetrate</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Extra</li>
                <li class="breadcrumb-item active">About PenetRate</li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><i class="fa fa-info-circle"></i>About PenetRate</h4>
                        <h6 class="card-subtitle">A little about our Foundation</h6>
                        </br>

                        <h4>
                            Black-box testing is a method of software testing that examines the functionality of an application without peering into its internal structures or workings.

                            This method of test can be applied virtually to every level of software testing: unit, integration, system and acceptance. It is sometimes referred to as specification-based testing.

                        </h4>

                        </br>

                        <a href="contactus.php">
                            For any question feel free to
                            <span style="color:#26dad2;font-weight: 700;text-decoration: underline;">Contact Us</span>
                        </a>


                    </div>
                </div>
            </div>
            <div class="col-lg-3"></div>


        </div>
        <!-- End PAge Content -->
    </div>
    <?php
    include('../footer.html');
    ?>