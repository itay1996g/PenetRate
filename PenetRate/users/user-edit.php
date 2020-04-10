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
    <title>PenetRate - Edit User</title>
    <!-- Bootstrap Core CSS -->
    <link href="../css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/helper.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <script src="../js/lib/jquery/jquery.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>
    <link href="../css/lib/toastr/toastr.min.css" rel="stylesheet">
    <link href="../css/lib/sweetalert/sweetalert.css" rel="stylesheet">
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

    <?php
    if (isset($_POST["id"])) {

        $db = new DBController();
        $conn = $db->connect();
        $user_check = $_POST["id"];
        $user_check = mysqli_real_escape_string($conn, $user_check);
        $stmt = $conn->prepare("SELECT * FROM users WHERE UserID = ?");
        $stmt->bind_param('s', $user_check);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $session_row = $result->fetch_assoc();
            $Fullname = $session_row["Fullname"];
            $Phone = $session_row["Phone"];
            $Position = $session_row["Position"];
            $UserRole = $session_row["UserRole"];
            $Email = $session_row["Email"];
        } else {
            header("Location: ../users/login.html");
            exit;
        }
    }
    ?>


    <script>
        $(document).ready(function() {
            $("#delete").click(function() {
                var row_id = $("#UserChangeID").val();
                DeleteUserAlert("Are you sure you want to delete the User?", "You will not be able to recover this User !!", row_id, 'Edit');


            });
        });
    </script>

</head>

<body class="fix-header fix-sidebar">
    <?php
        if ($IsAdmin) {
        include('../menuadmin.php');
    } else {
        include('../menu.php');
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
                <li class="breadcrumb-item active">Edit User</li>
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
                        <button type="submit" id="delete" name="delete" class="btn btn-danger btn-flat m-b-30 m-t-30">Delete User</button>

                        <h4 class="card-title">Edit User</h4>
                        <h6 class="card-subtitle">Edit User details</h6>
                        <form class="form p-t-20 form-valide" method="post" id="myform">
                            <input type="text" style="display:none;" id="formType" name="formType" value="Users">
                            <input type="text" style="display:none;" id="formID" name="formID" value="UserEdit">
                            <input type="text" style="display:none;" id="UserChangeID" name="UserChangeID" value="<?php echo $user_check; ?>">

                            <div class="form-group">
                                <label for="email">Email address</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-envelope"></i></div>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $Email; ?>"><!-- required -->
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fullname">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name" required value="<?php echo $Fullname; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                                    <input type="number" class="form-control" id="phone" name="phone" placeholder="Phone Number" required value="<?php echo $Phone; ?>">
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="position">Position <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-briefcase"></i></div>
                                    <select class="form-control custom-select" id="position" name="position" size="1">
                                        <!-- required -->
                                        <option value=''>--Select User Position--</option>
                                        <option value='Freelancer' <?php if ($Position == 'Freelancer') {
                                                                        echo ("selected");
                                                                    } ?>>Freelancer</option>
                                        <option value='Company Employee' <?php if ($Position == 'Company Employee') {
                                                                                echo ("selected");
                                                                            } ?>>Company Employee</option>
                                        <option value='Other' <?php if ($Position == 'Other') {
                                                                    echo ("selected");
                                                                } ?>>Other</option>
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
                                        <option value='User' <?php if ($UserRole == 'User') {
                                                                    echo ("selected");
                                                                } ?>>User</option>
                                        <option value='Admin' <?php if ($UserRole == 'Admin') {
                                                                    echo ("selected");
                                                                } ?>>Admin</option>
                                    </select>
                                </div>
                            </div>


                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10">Edit</button>
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