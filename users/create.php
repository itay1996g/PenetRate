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
    <title>PenetRate - Create User</title>
    <!-- Bootstrap Core CSS -->
    <link href="../css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../css/lib/toastr/toastr.min.css" rel="stylesheet">
    <link href="../css/lib/sweetalert/sweetalert.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/helper.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <script src="../js/lib/jquery/jquery.min.js"></script>


    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <script src="http://malsup.github.com/jquery.form.js"></script>

    <script src="../js/jquery.validate.min.js"></script>
    <script src="../js/jquery.validate-init.js"></script>
    <script src="../js/toastr.min.js"></script>
    <script src="../js/toastr.init.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/sweetalert.init.js"></script>
    <script src="../js/form-submit.js"></script>



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
            <h3 class="text-primary">PenetRate</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Create User</li>
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
                        <h4 class="card-title">Create User</h4>
                        <h6 class="card-subtitle">Create new user</h6>
                        <form class="form p-t-20 form-valide" method="post" id="myform">
                            <input type="text" style="display:none;" id="formType" name="formType" value="Users">
                            <input type="text" style="display:none;" id="formID" name="formID" value="Create">

                            <div class="form-group">
                                <label for="fullname">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name"><!-- required -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                                    <input type="number" class="form-control" id="phone" name="phone" placeholder="Phone Number"><!-- required -->
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="position">Position <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-briefcase"></i></div>
                                    <select class="form-control custom-select" id="position" name="position" size="1">
                                        <!-- required -->
                                        <option value=''>--Select User Position--</option>
                                        <option value='Freelancer'>Freelancer</option>
                                        <option value='Company Employee'>Company Employee</option>
                                        <option value='Other'>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="role">Role <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-users"></i></div>
                                    <select class="form-control custom-select" id="role" name="role" size="1">
                                        <!-- required -->
                                        <option value=''>--Select User Role--</option>
                                        <option value='User'>User</option>
                                        <option value='Admin'>Admin</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Email address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-envelope"></i></div>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email"><!-- required -->
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password"><!-- required -->
                                    <div id="val-confirm-password-error" class="invalid-feedback animated fadeInDown">Please enter the same password as above</div>
                                </div>
                            </div>

                            <button type="submit" id="create" class="btn btn-success waves-effect waves-light m-r-10">Create</button>
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