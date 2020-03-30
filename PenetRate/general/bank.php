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
    <title>PenetRate - Finding Database</title>

 
 
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
        $('#Finding_Database_table').DataTable();


        $.contextMenu({
            selector: '#Finding_Database_table tbody tr',
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
                    name: "View Scan",
                    icon: "fa-eye"
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
                        <li class="breadcrumb-item">General</li>
                        <li class="breadcrumb-item active">Finding Database</li>
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
                                <h4>Finding Database</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="Finding_Database_table" class="table table-hover table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Finding Name</th>
                                                <th>Description</th>
                                                <th>Initial Recommendations</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Information Disclosure</td>
                                                <td>It is a common practice to conceal internal information about servers and application components from the website users. This is important since users of a website have no need for information regarding the server’s type and version; while this information is relevant to system administrators and developers, it can be used by malicious attackers to uncover security vulnerabilities in the system.
                                                    During the security review it was noted that the system divulges information, such as the server’s type, via the HTTP responses headers and the use of Jetty as part of a malformed request.
                                                    This information can be used by malicious users in order to infiltrate the system.
                                                    Many products have known security flaws in them. Once discovered, the flaws become public and can be used by an attacker. It is also within the attacker’s capabilities to verify whether or not the products used within the website and the web server are the latest versions available. If not, an attacker may read about problematic issues that have been fixed in the latest versions of the products, and deduce that they still exist in the older version used by the application. This would allow the attacker to exploit the vulnerable server and application in a manner which may even allow them to execute code on the remote machine.
                                                        
                                                </td>
                                                <td>■	Configure the web server to conceal redundant information regarding its type and version.
                                                    ■	Design custom error pages and do not use the templates provided by the web application server, that allow to gather information about the type of the server and the technologies in use.
                                                    ■	Define and apply security configuration standards and maintenance procedures for any and all platform components. The standard procedures should ensure that server and platform components are configured according to security best practices and that software security updates are installed regularly.
                                                    </td>
                                            </tr>
                                            <tr>
                                                <td>Information Disclosure</td>
                                                <td>It is a common practice to conceal internal information about servers and application components from the website users. This is important since users of a website have no need for information regarding the server’s type and version; while this information is relevant to system administrators and developers, it can be used by malicious attackers to uncover security vulnerabilities in the system.
                                                    During the security review it was noted that the system divulges information, such as the server’s type, via the HTTP responses headers and the use of Jetty as part of a malformed request.
                                                    This information can be used by malicious users in order to infiltrate the system.
                                                    Many products have known security flaws in them. Once discovered, the flaws become public and can be used by an attacker. It is also within the attacker’s capabilities to verify whether or not the products used within the website and the web server are the latest versions available. If not, an attacker may read about problematic issues that have been fixed in the latest versions of the products, and deduce that they still exist in the older version used by the application. This would allow the attacker to exploit the vulnerable server and application in a manner which may even allow them to execute code on the remote machine.
                                                        
                                                </td>
                                                <td>■	Configure the web server to conceal redundant information regarding its type and version.
                                                    ■	Design custom error pages and do not use the templates provided by the web application server, that allow to gather information about the type of the server and the technologies in use.
                                                    ■	Define and apply security configuration standards and maintenance procedures for any and all platform components. The standard procedures should ensure that server and platform components are configured according to security best practices and that software security updates are installed regularly.
                                                    </td>
                                            </tr>
                                            
                                
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Finding Name</th>
                                                <th>Description</th>
                                                <th>Initial Recommendations</th>
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