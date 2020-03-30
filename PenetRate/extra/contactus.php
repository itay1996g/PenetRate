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
    <title>PenetRate - Contact Us</title>
    <!-- Bootstrap Core CSS -->
    <link href="../css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../css/helper.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <script src="../js/lib/jquery/jquery.min.js"></script>

<style>
[class^="ti-"], [class*=" ti-"], [class*=" fa-"]
{
    padding-right: 5px !important;
}

</style>
</head>

<body class="fix-header fix-sidebar">
<?php
include('../menu.html');
?>
            <!-- Bread crumb -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Penetrate</h3> </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Extra</li>
                        <li class="breadcrumb-item active">Contact Us</li>
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
                                <h4 class="card-title"><i class="fa fa-comments"></i>Contact Us</h4>
                                <h6 class="card-subtitle">We are here for you.</h6>
                                <form class="form p-t-20">
                               
                                <div class="form-group">
                                        <label for="email">Email address <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-envelope"></i></div>
                                            <input type="email" class="form-control" id="email" placeholder="Enter email" required>
                                        </div>
                                    </div>
                                  
                              
                                    <div class="form-group">
                                        <label for="payload">Message</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-text-width"></i></div>
                                            <textarea class="form-control" rows="10" id="payload" style="height: 120px;"></textarea>

                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10" style="width: 100% !important;">Rocket Message <i class="fa fa-rocket"></i></button>
                                    </br>
                                    </br>
                                     </br>

              
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