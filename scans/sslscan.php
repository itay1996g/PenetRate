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
    <title>PenetRate - SSLScan</title>



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

            $('#CipherSuites').DataTable({
                "ajax": {
                    "url": "../Results/SSLScan/<?php echo $UID; ?>.json",
                    "dataSrc": function(json) {
                        var return_data = new Array();
                        try {

                            for (var i = 0; i < json.endpoints[0].details.suites.list.length; i++) {
                                cipher_name = json.endpoints[0].details.suites.list[i].name;
                                if ((cipher_name.toLowerCase().indexOf("tls_rsa") >= 0) || (cipher_name.toLowerCase().indexOf("cbc") >= 0) || (cipher_name.toLowerCase().indexOf("3des") >= 0)) {
                                    cipher_name = "<bb style='background:yellow;'>Weak - " + cipher_name + "</bb>";
                                }

                                return_data.push({
                                    'name': "" + cipher_name,
                                    'cipherStrength': "" + json.endpoints[0].details.suites.list[i].cipherStrength
                                })
                            }
                        } catch (err) {
                            console.log(err);
                        }

                        return return_data;
                    }

                },
                columns: [{
                        data: 'name',
                        title: 'name'
                    },
                    {
                        data: 'cipherStrength',
                        title: 'cipherStrength'
                    }
                ]
            });


            $('#protocols').DataTable({
                "ajax": {
                    "url": "../Results/SSLScan/<?php echo $UID; ?>.json",
                    "dataSrc": function(json) {
                        var return_data = new Array();
                        try {
                            for (var i = 0; i < json.endpoints[0].details.protocols.length; i++) {
                                protocol_version = json.endpoints[0].details.protocols[i].version;
                                if ((protocol_version.toLowerCase().indexOf("1.2") >= 0) || (protocol_version.toLowerCase().indexOf("1.3") >= 0)) {
                                    protocol_version = "<bb style='background:green;'>Good - " + protocol_version + "</bb>";
                                } else {

                                    protocol_version = "<bb style='background:yellow;'>Weak - " + protocol_version + "</bb>";
                                }
                                return_data.push({
                                    'name': "" + json.endpoints[0].details.protocols[i].name,
                                    'version': "" + protocol_version
                                })
                            }
                        } catch (err) {
                            console.log(err);
                        }
                        return return_data;
                    }

                },
                columns: [{
                        data: 'name',
                        title: 'name'
                    },
                    {
                        data: 'version',
                        title: 'version'
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
                <li class="breadcrumb-item active">SSLScan</li>
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
                        <h4>SSLScan</h4>
                    </div>
                    <div class="card-body">
                        <h4>Cipher Suites</h4>
                        <bb style="color:blue;">
                            <a target="_blank" style="color:blue;" href="http://193.106.55.103:8080/penetrate/general/finding.php?id=339"> Press to read more about Weak Cipher Suites</a>
                        </bb>
                        <table id="CipherSuites" class="table table-hover table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>name</th>
                                    <th>cipherStrength</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>name</th>
                                    <th>cipherStrength</th>
                                </tr>
                            </tfoot>
                        </table>

                        </br>
                        </br>

                        <h4>protocols</h4>
                        <bb style="color:blue;">
                            <a target="_blank" style="color:blue;" href="http://193.106.55.103:8080/penetrate/general/finding.php?id=328"> Press to read more about Insufficient Transport Layer Protection TLSv1.0/TLSv1.1</a>
                        </bb>
                        <table id="protocols" class="table table-hover table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>name</th>
                                    <th>version</th>
                                </tr>
                            </thead>
                            <tbody id="protocols_tbody">



                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>name</th>
                                    <th>version</th>
                                </tr>
                            </tfoot>
                        </table>

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