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

    <script src="../js/lib/form-validation/jquery.validate.min.js"></script>
    <script src="../js/lib/form-validation/jquery.validate-init.js"></script>


    <link href="../css/lib/sweetalert/sweetalert.css" rel="stylesheet">
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
            $('#attack').change(function() {
                if (this.value != '0') {
                    $.ajax({
                        url: "../helpers/GeneralForm.php",
                        type: 'POST',
                        data: 'findingID=' + (this.value),
                        success: function(response) {
                            if (response == 'Error') {
                                UserAlertError("Attack Does Not Exists", "Please choose a different Attack");

                            } else {
                                var splited = response.split("SPLITHERE");
                                $("textarea#payload").val(splited[0]);
                                $("textarea#description").val(splited[1]);
                            }
                        }
                    });
                } else {
                    $('#myform')[0].reset();

                }

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
                <li class="breadcrumb-item active">Payload Generator</li>
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
                        <h4 class="card-title">Payload Generator</h4>
                        <h6 class="card-subtitle">Generate your own payload</h6>
                        <form class="form p-t-20 form-valide" id="myform" name="myform" action="#" method="post">


                            <div class="form-group">
                                <label for="attack">Attack <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-star"></i></div>
                                    <select class="form-control custom-select" id="attack" name="attack" required>
                                        <option value='0'>--Select Attack--</option>
                                        <?php
                                        $db = new DBController();
                                        $conn = $db->connect();
                                        $query = "SELECT name,findingID from payloadgenerator";
                                        if ($result = $conn->query($query)) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . $row["findingID"] . '">' . $row["name"] . '</option>';
                                            }
                                            $result->free();
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-question-circle"></i></div>
                                    <textarea class="form-control" rows="50" id="description" style="height: 220px;"></textarea>

                                </div>
                            </div>

                            <button type="submit" class="btn btn-success waves-effect waves-light m-r-10" style="width: 100% !important;">Generate Payload</button>
                            </br>
                            </br>
                            </br>
                            <div class="form-group">
                                <label for="payload">Payloads</label>
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-code"></i></div>


                                    <textarea class="form-control" rows="10" id="payload" style="height: 120px;"></textarea>

                                </div>
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