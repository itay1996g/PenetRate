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


<style>
[class^="ti-"], [class*=" ti-"], [class*=" fa-"]
{
    padding-right: 5px !important;
}

</style>
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
                    <h3 class="text-primary">PenetRate</h3> </div>
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
                                <form class="form p-t-20 form-valide" action="#" method="post">
                                 
                    
                                    <div class="form-group">
                                        <label for="attack">Attack <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-star"></i></div>
                                            <select class="form-control custom-select" id="attack" name="attack" required>
                                                <option value=''>--Select Attack--</option>
                                                <option value='SQLI'>SQLI</option>
                                                <option value='XSS'>XSS</option>
                                                <option value='CSV Injection'>CSV Injection</option>
                                            </select>
                                            </div>
                                    </div>

                                  
                                
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-question-circle"></i></div>
                                            <textarea class="form-control" rows="10" id="description" style="height: 120px;"></textarea>

                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10" style="width: 100% !important;">Generate Payload</button>
                                </br>
                                </br>
                                 </br>
                                    <div class="form-group">
                                        <label for="payload">Payload</label>
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