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
    <title>PenetRate - AuthBypass</title>



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

            $.ajax({
                type: 'GET',
                "url": "../Results/AuthBypass/<?php echo $UID; ?>.json",
                async: false,
                success: function(response) {
                    $('#AuthBypass').DataTable({
                        "ajax": {
                            "url": "../Results/AuthBypass/<?php echo $UID; ?>.json",
                            "dataSrc": function(json) {
                                var return_data = new Array();
                                if (json.AuthBypass == null) {
                                    return return_data;
                                } else {
                                    for (var i = 0; i < json.AuthBypass.length; i++) {
                                        return_data.push({
                                            'URL': "" + json.AuthBypass[i].URL
                                        })
                                    }
                                    return return_data;
                                }

                            }
                        },
                        columns: [{
                            data: 'URL',
                            title: 'URL'
                        }]
                    });

                },
                error: function(response) {
                    $('#AuthBypass').DataTable();
                }
            });




        });
    </script>

    <style>
        table,
        th,
        td,
        tr {
            text-align: left !important;
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
            <h3 class="text-primary">PenetRate</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Scans</li>
                <li class="breadcrumb-item active">AuthBypass</li>
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
                        <h4>AuthBypass</h4>
                    </div>
                    <div class="card-body">


                        <h4>AuthBypass</h4>
                        <bb style="color:blue;">
                           <a target="_blank" style="color:blue;" href="http://193.106.55.103:8080/penetrate/general/finding.php?id=234"> Press to read more about AuthBypass</a>
                        </bb>
                        <div class="table-responsive">
                            <table id="AuthBypass" class="table table-hover table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>URL</th>

                                    </tr>
                                </thead>
                                <tbody id="AuthBypass_tbody">



                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>URL</th>

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