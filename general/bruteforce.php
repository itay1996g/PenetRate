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
    <title>PenetRate - Brute-Force</title>
    <!-- Bootstrap Core CSS -->
    <link href="../css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/helper.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <script src="../js/lib/jquery/jquery.min.js"></script>

    <script src="../js/lib/form-validation/jquery.validate.min.js"></script>
    <script src="../js/lib/form-validation/jquery.validate-init.js"></script>


    <style>
        [class^="ti-"],
        [class*=" ti-"],
        [class*=" fa-"] {
            padding-right: 5px !important;
        }
    </style>
    <script>
        $(document).ready(function() {


            $("form").submit(function(e) {
                e.preventDefault();
                var url = $("#url").val();
                var username = $("#username").val();
                var httpmethod = $("#httpmethod").val();
                var addparms = $("#addparms").val();
                var username_parameter = $("#username_parameter").val();
                var password_parameter = $("#password_parameter").val();

                var command = "python3 pwbruteforce.py -v ";
                if (httpmethod == "GET") {
                    command = command + "" + url + "?" + username_parameter + "=" + username + "&" + password_parameter + "={}" + addparms + " -m get -p password_list.txt";
                } else if (httpmethod == "POST") {
                    if (addparms != "") {
                        addparms = "," + addparms;
                    }
                    command = command + "" + url + ' -d "{\\"' + username_parameter + '"\\ :\\"' + username + '\\", \\"' + password_parameter + '\\": \\"{}}\\"}' + addparms + " -m post -p password_list.txt";
                }
                $("#payload").val(command);

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
                <li class="breadcrumb-item">General</li>
                <li class="breadcrumb-item active">Brute-Force Generator</li>
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
                        <h4 class="card-title">Brute-Force Attack Generator</h4>
                        <h6 class="card-subtitle">Generate your own Brute-Force</h6>
                        <form class="form p-t-20 form-valide">
                            <div class="form-group">
                                <label for="url">Webstie Login URL <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-globe"></i></div>
                                    <input type="text" class="form-control" id="url" name="url" placeholder="Webstie URL" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="username">Username <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-users"></i></div>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="username_parameter"><b>Username Parameter Name</b><span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                    <input type="text" class="form-control" id="username_parameter" name="username_parameter" placeholder="Username Parameter Name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password_parameter"><b>Password Parameter Name</b><span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-key"></i></div>
                                    <input type="text" class="form-control" id="password_parameter" name="password_parameter" placeholder="Password Parameter Name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="httpmethod">HTTP Method <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-html5"></i></div>
                                    <select class="form-control custom-select" id="httpmethod" name="httpmethod" required>
                                        <option value=''>--Select HTTP Method--</option>
                                        <option value='GET'>GET</option>
                                        <option value='POST'>POST</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="addparms">Additional Parameters</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-question-circle"></i></div>
                                    <textarea class="form-control ignore" rows="10" id="addparms" style="height: 60px;" placeholder='FORMAT FOR POST: \"Parameter Name"\ : \"Parameter Value\"&#10;FORMAT FOR GET: &Parameter_Name=Parameter_Value'></textarea>

                                </div>
                            </div>

                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10" style="width: 100% !important;" id="generate" name="generate">Generate Payload</button>
                            </br>
                            </br>
                            </br>

                            <div class="form-group">
                                <label for="payload">Brute-Force Python command and file</label>

                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-code"></i></div>
                                    <textarea class="form-control" rows="10" id="payload" style="height: 120px;"></textarea>
                                </div>
                                </br>
                                <a style="color:blue;padding-left:20px;" href="../modules/Bruteforce/pwbruteforce.py" download>Press to download pwbruteforce.py</a>

                            </div>

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