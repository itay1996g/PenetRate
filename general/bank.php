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
            $('#Finding_Database_table').DataTable({
                "lengthMenu": [
                    [5, 10, 25, 50, 100, -1],
                    [5, 10, 25, 50, 100, "All"]
                ],
                columnDefs: [{
                    targets: [0, 1, 2],
                    createdCell: function(cell, cellData) {
                        if (cellData.length > 150) {

                            var $cell = $(cell);
                            $(cell).contents().wrapAll("<div class='content'></div>");
                            var $content = $cell.find(".content");

                            $(cell).append($("<button>Read more</button>"));
                            $btn = $(cell).find("button");

                            $content.css({
                                "height": "150px",
                                "overflow": "hidden"
                            })
                            $cell.data("isLess", true);

                            $btn.click(function() {
                                var isLess = $cell.data("isLess");
                                $content.css("height", isLess ? "auto" : "150px")
                                $(this).text(isLess ? "Read less" : "Read more")
                                $cell.data("isLess", !isLess)
                            })
                        }
                    }
                }]
            });



            $.contextMenu({
                selector: '#Finding_Database_table tbody tr',
                callback: function(key, options) {
                    var row_id = $(this).attr('id');
                    ///////////////////// View Context MENU ///////////////
                    if (key == "view") {
                        $('<form action="../general/finding.php" method="GET"><input type="hidden" id="id" name="id" value="' + row_id + '"></input></form>').appendTo('body').submit().remove();
                    }

                },
                items: {
                    "view": {
                        name: "View Finding",
                        icon: "fa-eye"
                    }
                }
            });

        });
    </script>

    <style>
        table,
        th,
        td,
        tr {
            text-align: left !important;
        }

        .truncate {
            max-width: 50px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
                            <table id="Finding_Database_table" class="table table-hover table-bordered" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Finding Name</th>
                                        <th>Description</th>
                                        <th>Initial Recommendations</th>

                                    </tr>
                                </thead>


                                <tbody>
                                    <?php
                                    $db = new DBController();
                                    $conn = $db->connect();
                                    $query = "SELECT * from findings_bank";
                                    if ($result = $conn->query($query)) {
                                        while ($row = $result->fetch_assoc()) {

                                            echo '<tr id="' . $row["FindingID"] . '">';
                                            echo '<td><span class="badge badge-primary">' . $row["name"] . '</span></td>';
                                            echo '<td>' . $row["description"] . '</td>';
                                            echo '<td>' . $row["recommendation"] . '</td>';
                                            echo '</tr>';
                                        }
                                        $result->free();
                                    }
                                    ?>

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