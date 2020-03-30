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
                    <h3 class="text-primary">PenetRate</h3> </div>
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
                                <form class="form p-t-20">
                                    <div class="form-group">
                                        <label for="url">Webstie Login URL <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-globe"></i></div>
                                            <input type="text" class="form-control" id="url" placeholder="Webstie URL" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Username <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                            <input type="text" class="form-control" id="username" placeholder="Username" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="httpmethod">HTTP Method <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-html5"></i></div>
                                            <select class="form-control custom-select" id="httpmethod" required>
                                                <option>--Select HTTP Method--</option>
                                                <option>GET</option>
                                                <option>POST</option>
                                            </select>
                                            </div>
                                    </div>
                                  
                                
                                    <div class="form-group">
                                        <label for="addheaders">Additional Headers</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-question-circle"></i></div>
                                            <textarea class="form-control" rows="10" id="addheaders" style="height: 60px;"></textarea>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="addparms">Additional Parameters</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-question-circle"></i></div>
                                            <textarea class="form-control" rows="10" id="addparms" style="height: 60px;"></textarea>

                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success waves-effect waves-light m-r-10" style="width: 100% !important;">Generate Payload</button>
                                    </br>
                                    </br>
                                     </br>

                                    <div class="form-group">
                                        <label for="payload">Brute-Force Payload</label>
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