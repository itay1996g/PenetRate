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
    <title>PenetRate - Scan Website</title>
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
    ?>
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">PenetRate</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Scans</li>
                <li class="breadcrumb-item active">Scan A Website</li>
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
                        <h4 class="card-title">Scan Website</h4>
                        <h6 class="card-subtitle">Please enter website to scan and select features to deploy</h6>
                        <form class="form p-t-20 form-valide" action="#" method="post" id="myform" name="myform">
                            <input type="text" style="display:none;" id="formType" name="formType" value="Scans">
                            <input type="text" style="display:none;" id="formID" name="formID" value="Create">

                            <div class="form-group">
                                <label for="url">Webstie URL <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-globe"></i></div>
                                    <input type="text" class="form-control" id="url" name="url" placeholder="Webstie URL" value="https://isi-slonim.co.il/" required>
                                </div>
                            </div>
 
 
                            <div class="form-group">
                                <label for="scan_Cookie">Cookie</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                                    <input type="text" class="form-control ignore" id="scan_Cookie" name="scan_Cookie" placeholder="Cookie">
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="checkbox checkbox-success">
                                    <input id="openports" name="openports" type="checkbox">

                                    <label for="openports"> Open Ports </label>
                                    <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Open Ports"></i>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="checkbox checkbox-success">
                                    <input id="servicesscan" name="servicesscan" type="checkbox">

                                    <label for="servicesscan"> Services Scan </label>
                                    <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Services Scan"></i>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="checkbox checkbox-success">
                                    <input id="sslscan" name="sslscan" type="checkbox">

                                    <label for="sslscan"> SSL Scan </label>
                                    <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="SSL Scan"></i>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="checkbox checkbox-success">
                                    <input id="subdomainscan" name="subdomainscan" type="checkbox">

                                    <label for="subdomainscan"> Subdomain Scan </label>
                                    <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Subdomain Scan"></i>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="checkbox checkbox-success">
                                    <input id="mapdirectories" name="mapdirectories" type="checkbox">

                                    <label for="mapdirectories"> Map Directories </label>
                                    <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Map Directories"></i>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="checkbox checkbox-success">
                                    <input id="Crawler" name="Crawler" type="checkbox">

                                    <label for="Crawler"> Crawler </label>
                                    <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Crawler"></i>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="checkbox checkbox-success">
                                    <input id="mapheaders" name="mapheaders" type="checkbox">

                                    <label for="mapheaders"> Map Headers </label>
                                    <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Map Headers"></i>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="checkbox checkbox-success">
                                    <input id="clientsidevulnerability" name="clientsidevulnerability" type="checkbox">

                                    <label for="clientsidevulnerability">Sensitive Information</label>
                                    <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Client Side Vulnerabilities"></i>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="checkbox checkbox-success">
                                    <input id="Generalvulnerability" name="Generalvulnerability" type="checkbox">

                                    <label for="Generalvulnerability"> General Vulnerability </label>
                                    <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="General Vulnerability Scanner"></i>

                                </div>
                            </div>

                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10" style="width: 100% !important;">Start Scan</button>
                        </form>
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