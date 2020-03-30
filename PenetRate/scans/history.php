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


<script>
    $(document).ready(function() {
        $('#history_users_table').DataTable();


        $.contextMenu({
            selector: '#history_users_table tbody tr',
            callback: function(key, options) {
                //var row_id = $(this).attr('id');
                //var DBclientUniqueID = row_id.substr(row_id.indexOf("_") + 1);
                ///////////////////// DELETE Context MENU ///////////////

                if (key == "view") {
                    alert('delete');
                    // $(this).addClass('recordToDelete');
                    // var clientName = $(this).find("td").eq(1).text();
                    // var clientID = $(this).find("td").eq(3).text();
                    // var clientHBdate = $(this).find("td").eq(0).text();
                    // $('#confirmHBClientDelete #clientHbname').text(clientName);
                    // $('#confirmHBClientDelete #clientHbid').text(clientID);
                    // $('#confirmHBClientDelete #clientHbdate').text(clientHBdate);
                    // $('#confirmHBClientDelete').removeClass('display-none');
                } else {
                    alert('edit');

                    ///////////////////// UPDATE Context MENU ///////////////
                    // $.ajax({
                    //     url: 'includes/php/GetClientByID.php',
                    //     type: 'POST',
                    //     data: {
                    //         id: DBclientUniqueID
                    //     },
                    //     success: function(response) {
                    //         var json_object = JSON.parse(response)[0];
                    //         $('#addHbModal').removeClass('display-none');
                    //         $('.input-material-label-txt').each(function() {
                    //             $(this).addClass('active-material-label-txt');
                    //         });
                    //         reset_Modal();

                    //         var active_stage = (JSON.parse(response)[0].active_stage);
                    //         var str_stage = '#TabStage' + active_stage + 'Link li';
                    //         $('.active-tab').removeClass('active-tab');
                    //         $(str_stage).addClass('active-tab');
                    //         $('.tab').removeClass('active-tab');
                    //         str_stage = '#TabStage' + active_stage;
                    //         $(str_stage).addClass('active-tab');




                    //         $.each(json_object, function(key, value) {
                    //             if (key == 'verificationDetails') {
                    //                 if (value == 'true') {
                    //                     $('#verificationDetails').prop('checked', true);

                    //                 } else {
                    //                     $('#verificationDetails').prop('checked', false);

                    //                 }
                    //             } else if (key == 'toIssueBanking') {
                    //                 if (value == 'true') {
                    //                     $('#toIssueBanking').prop('checked', true);

                    //                 } else {
                    //                     $('#toIssueBanking').prop('checked', false);

                    //                 }
                    //             } else if (key == 'country_3years') {
                    //                 if (value == 'true') {
                    //                     $('#country_3years').prop('checked', true);

                    //                 } else {
                    //                     $('#country_3years').prop('checked', false);

                    //                 }
                    //             } else if (key == 'passport_3years') {
                    //                 if (value == 'true') {
                    //                     $('#passport_3years').prop('checked', true);

                    //                 } else {
                    //                     $('#passport_3years').prop('checked', false);

                    //                 }
                    //             } else if (key == 'employeeName') {
                    //                 $('#employeeName').val(value);
                    //                 $('#employeeName').trigger('change');

                    //             } else if (key == 'issueHB_employeeName') {
                    //                 $('#issueHB_employeeName').val(value);
                    //                 $('#issueHB_employeeName').trigger('change');

                    //             } else if (key == 'processHB_employeeName') {
                    //                 $('#processHB_employeeName').val(value);
                    //                 $('#processHB_employeeName').trigger('change');

                    //             } else if (key == 'saleHB_employeeName') {
                    //                 $('#saleHB_employeeName').val(value);
                    //                 $('#saleHB_employeeName').trigger('change');

                    //             } else if (key == 'processHB_handler') {
                    //                 $('#processHB_handler').val(value);
                    //                 $('#processHB_handler').trigger('change');

                    //             } else if (key == 'product') {
                    //                 $('#product').val(value);
                    //                 $('#product').trigger('change');

                    //             } else if (key == 'active_status') {
                    //                 $('#FirstStage_status').val(value);
                    //                 $('#FirstStage_status').trigger('change');
                    //                 $('#SecondStage_status').val(value);
                    //                 $('#SecondStage_status').trigger('change');
                    //                 $('#processHB_status').val(value);
                    //                 $('#processHB_status').trigger('change');
                    //                 $('#saleHB_status').val(value);
                    //                 $('#saleHB_status').trigger('change');
                    //             } else if (key == 'active_comment') {
                    //                 $('#comments').val(value);
                    //                 $('#issueHB_comments').val(value);
                    //                 $('#processHB_comments').val(value);
                    //                 $('#saleHB_comments').val(value);
                    //             } else if (key != 'comments' && key != 'issueHB_comments' && key != 'processHB_comments' && key != 'saleHB_comments' && key != 'saleHB_status' && key != 'processHB_status') {
                    //                 $("#" + key).val(value);
                    //             }
                    //         });

                    //         $("#column_id").val(JSON.parse(response)[0].id);




                    //     }
                    // });
                }
            },
            items: {
                "view": {
                    name: "View Scan Results",
                    icon: "fa-eye"
                },  
                "delete": {
                    name: "Delete Scan",
                    icon: "delete"
                }
            }
        });


    });
</script>

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
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>https://www.gooogle.com</td>
                                                <td>20/12/2020</td>
                                            </tr>
                                            <tr>
                                                <td>https://www.gooogle.com</td>
                                                <td>20/12/2020</td>
                                            </tr>       
                                            <tr>
                                                <td>https://www.gooogle.com</td>
                                                <td>20/12/2020</td>
                                            </tr>
                                
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>URL</th>
                                                <th>Date</th>
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