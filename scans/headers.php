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
    <title>PenetRate - Mapped Headers</title>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script>
    <!-- Bootstrap Core CSS -->
    <link href="../css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/helper.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">


    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">



    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>


    <?php
    if (isset($_GET["UID"])) {
        $db = new DBController();
        $conn = $db->connect();
        $UID = $_GET["UID"];
    }



    ?>

    <script>
        $(document).ready(function() {

            $('#RESPONSE_Headers_Details').DataTable({
                "ajax": {
                    "url": "../Results/Headers/<?php echo $UID; ?>.json",
                    "dataSrc": "RESPONSE_Headers_Details"
                },
                columns: [{
                        data: 'Header Field Name',
                        title: 'Field Name'
                    },
                    {
                        data: 'Value',
                        title: 'Value'
                    },
                    {
                        data: 'CWE',
                        title: 'CWE(Common Weakness Enumeration)'
                    },
                    {
                        data: 'CWE URL',
                        title: 'CWE URL'
                    },
                    {
                        data: 'Security Reference',
                        title: 'Reference'
                    }
                ]
            });

            $('#Missing_Headers_Details').DataTable({
                "ajax": {
                    "url": "../Results/Headers/<?php echo $UID; ?>.json",
                    "dataSrc": "Missing_Headers_Details"
                },
                columns: [{
                        data: 'Header Field Name',
                        title: 'Field Name'
                    },
                    {
                        data: 'CWE',
                        title: 'CWE(Common Weakness Enumeration)'
                    },
                    {
                        data: 'CWE URL',
                        title: 'CWE URL'
                    },
                    {
                        data: 'Security Reference',
                        title: 'Reference'
                    }
                ]
            });


            $('#RESPONSE_Headers_Info').DataTable({
                "ajax": {
                    "url": "../Results/Headers/<?php echo $UID; ?>.json",
                    "dataSrc": "RESPONSE_Headers_Info"
                },
                columns: [{
                        data: 'Name',
                        title: 'Name'
                    },
                    {
                        data: 'Value',
                        title: 'Value'
                    }
                ]
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
                <li class="breadcrumb-item active">Headers</li>
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
                        <h4>Mapped Headers</h4>
                    </div>
                    <div class="card-body">
                        <h3>RESPONSE Headers Details</h3>
                        <h4><b style="background: yellow;">Issues found in the response headers</b></h4>
                        <table id="RESPONSE_Headers_Details" class="table table-hover table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Field Name</th>
                                    <th>Value</th>
                                    <th>CWE(Common Weakness Enumeration)</th>
                                    <th>CWE URL</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Field Name</th>
                                    <th>Value</th>
                                    <th>CWE(Common Weakness Enumeration)</th>
                                    <th>CWE URL</th>
                                    <th>Reference</th>
                                </tr>
                            </tfoot>
                        </table>

                        </br>
                        </br>

                        <h3>Missing Headers Details</h3>
                        <h4><b style="background: yellow;">Headers that should be implemented but are not..</b></h4>
                        <table id="Missing_Headers_Details" class="table table-hover table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Field Name</th>
                                    <th>CWE(Common Weakness Enumeration)</th>
                                    <th>CWE URL</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Field Name</th>
                                    <th>CWE(Common Weakness Enumeration)</th>
                                    <th>CWE URL</th>
                                    <th>Reference</th>
                                </tr>
                            </tfoot>
                        </table>

                        </br>
                        </br>

                        <h3>RESPONSE Headers Info</h3>
                        <h4><b style="background: green;color:white;">List of headers that are returned from server with their value</b></h4>
                        <div class="table-responsive">
                            <table id="RESPONSE_Headers_Info" class="table table-hover table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Value</th>

                                    </tr>
                                </thead>
                                <tbody id="RESPONSE_Headers_Info_tbody">



                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Value</th>
                                    </tr>
                                </tfoot>
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