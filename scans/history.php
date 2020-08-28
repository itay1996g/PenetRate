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
    <title>PenetRate - My Scans History</title>



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
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/sweetalert.init.js"></script>
    <link href="../css/lib/sweetalert/sweetalert.css" rel="stylesheet">





    <script>
        $(document).ready(function() {
            $('#history_users_table').DataTable();
            $.contextMenu({
                selector: '#history_users_table tbody tr',
                callback: function(key, options) {
                    var row_id = $(this).attr('id');
                    ///////////////////// DELETE Context MENU ///////////////
                    if (key == "delete") {
                        DeleteScanAlert("Are you sure you want to delete the Scan?", "You will not be able to recover the results !!", row_id, 'History');
                    }
                    ///////////////////// Update Context MENU ///////////////
                    else {
                        $('<form action="../scans/viewscan.php" method="post"><input type="hidden" id="ScanID" name="ScanID" value="' + row_id + '"></input></form>').appendTo('body').submit().remove();
                    }
                },
                items: {
                    "edit": {
                        name: "View",
                        icon: "fa-eye"
                    }
                    // ,
                    // "delete": {
                    //     name: "Delete",
                    //     icon: "delete"
                    // }
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
                <li class="breadcrumb-item active">My Scans</li>
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
                        <h4>My Scans History</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="history_users_table" class="table table-hover table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>URL</th>
                                        <th>Date</th>
                                        <th>Status</th>



                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $db = new DBController();
                                    $conn = $db->connect();
                                    $user_check = $_SESSION['UserID'];
                                    $user_check = mysqli_real_escape_string($conn, $user_check);
                                    $stmt = $conn->prepare("SELECT * FROM scans WHERE UserID = ?");
                                    $stmt->bind_param('s', $user_check);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            if ($row["Status"] == 'Finished') {
                                                $badge = 'green';
                                            } else {
                                                $badge = 'success';
                                            }
                                            echo '<tr id="' . $row["ScanID"] . '">';
                                            echo '<td>' . $row["URL"] . '</td>';
                                            echo '<td>' . $row["Date"] . '</td>';
                                            echo '<td style="color: white;"><span class="badge badge-' . $badge . '">' . $row["Status"] . '</span></td>';

                                            echo '</tr>';
                                        }
                                        $result->free();
                                    }
                                    ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>URL</th>
                                        <th>Date</th>
                                        <th>Status</th>

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