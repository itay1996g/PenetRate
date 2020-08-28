<?php
include '../helpers/session.php';
checkLoggedIn();
IsAdmin();
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
    <title>PenetRate - Manage Users</title>


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
    <script src="../js/toastr.min.js"></script>
    <script src="../js/toastr.init.js"></script>
    <script src="../js/form-submit.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/sweetalert.init.js"></script>
    <link href="../css/lib/sweetalert/sweetalert.css" rel="stylesheet">

    <script>
        $(document).ready(function() {
            $('#manage_users_table').DataTable();
            $.contextMenu({
                selector: '#manage_users_table tbody tr',
                callback: function(key, options) {
                    var row_id = $(this).attr('id');
                    ///////////////////// DELETE Context MENU ///////////////
                    if (key == "delete") {
                        DeleteUserAlert("Are you sure you want to delete the User?", "You will not be able to recover this User !!", row_id, 'Manage');
                    }
                    ///////////////////// Update Context MENU ///////////////
                    else {
                        $('<form action="../users/user-edit.php" method="post"><input type="hidden" id="id" name="id" value="' + row_id + '"></input></form>').appendTo('body').submit().remove();
                    }
                },
                items: {
                    "edit": {
                        name: "Edit",
                        icon: "edit"
                    },
                    "delete": {
                        name: "Delete",
                        icon: "delete"
                    }
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
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Manage Users</li>
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
                        <h4>Manage Users </h4>
                        </br>
                        <a href="create.php"> <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Create User</button></a>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="manage_users_table" class="table table-hover table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Role</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $db = new DBController();
                                    $conn = $db->connect();
                                    $query = "SELECT * from users";
                                    if ($result = $conn->query($query)) {
                                        while ($row = $result->fetch_assoc()) {
                                            if ($row["UserRole"] == 'Admin') {
                                                $badge = 'primary';
                                            } else {
                                                $badge = 'success';
                                            }
                                            echo '<tr id="' . $row["UserID"] . '">';
                                            echo '<td>' . $row["Fullname"] . '</td>';
                                            echo '<td>' . $row["Email"] . '</td>';
                                            echo '<td><span class="badge badge-' . $badge . '">' . $row["UserRole"] . '</span></td>';
                                            echo '</tr>';
                                        }
                                        $result->free();
                                    }
                                    ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
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