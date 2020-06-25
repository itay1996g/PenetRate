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
    <title>PenetRate - Finding</title>
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

        textarea {
            text-align: left !important;
            direction: ltr !important;
        }

        label {
            font-weight: 700 !important;
            padding-top: 5px !important;
        }
    </style>

    <?php
    if (isset($_GET["id"])) {

        $db = new DBController();
        $conn = $db->connect();
        $finding_ID = $_GET["id"];
        $finding_ID = mysqli_real_escape_string($conn, $finding_ID);
        $stmt = $conn->prepare("SELECT * FROM findings_bank WHERE FindingID = ?");
        $stmt->bind_param('s', $finding_ID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $session_row = $result->fetch_assoc();
            $name = $session_row["name"];
            $description = $session_row["description"];
            $recommendation = $session_row["recommendation"];
        } else {
            header("Location: ../users/login.html");
            exit;
        }
    } else {
        header("Location: ../users/login.html");
        exit;
    }
    ?>
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
                <li class="breadcrumb-item active">Finding</li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $name; ?></h4>



                        <div class="form-group">
                            <i class="fa fa-question-circle"></i> <label for="recommendation">Description</label>
                            <div>
                                <?php echo $description; ?>
                            </div>

                        </div>


                        <div class="form-group">
                            <i class="fa fa-wrench"></i> <label for="recommendation">Recommendation</label>
                            <div>
                                <?php echo $recommendation; ?>
                            </div>

                        </div>
                        <a href="bank.php"><button class="btn btn-success waves-effect waves-light m-r-10" style="width: 100% !important;">Back Home</button></a>

                    </div>
                </div>
            </div>
            <div class="col-lg-1"></div>


        </div>
        <!-- End PAge Content -->
    </div>
    <?php
    include('../footer.html');
    ?>