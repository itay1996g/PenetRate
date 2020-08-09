<?php
include_once 'helpers/session.php';
checkLoggedInMenu();
IsAdminMenu();

?>
<!-- Preloader - style you can find in spinners.css -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
</div>
<!-- Main wrapper  -->
<div id="main-wrapper">
    <!-- header header  -->
    <div class="header">
        <nav class="navbar top-navbar navbar-expand-md navbar-light">
            <!-- Logo -->
            <div class="navbar-header">
                <a class="navbar-brand" href="index.html">
                    <!-- Logo icon -->
                    <b><img src="../images/logo2.png" alt="homepage" class="dark-logo" /></b>
                    <!--End Logo icon -->
                    <!-- Logo text -->
                    <span><img src="../images/logo-text.png" alt="homepage" class="dark-logo" /></span>
                </a>
            </div>
            <!-- End Logo -->
            <div class="navbar-collapse">
                <!-- toggle and nav items -->
                <ul class="navbar-nav mr-auto mt-md-0">
                    <!-- This is  -->
                    <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                    <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                    <!-- Messages -->
                    <li class="nav-item dropdown mega-dropdown"> <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-th-large"></i></a>
                        <div class="dropdown-menu animated zoomIn">
                            <ul class="mega-dropdown-menu row">




                                <li class="col-lg-3 col-xlg-3 m-b-30">
                                    <h4 class="m-b-20">Scans</h4>
                                    <!-- List style -->
                                    <ul class="list-style-none">
                                        <li> <a href="../scans/scan.php" aria-expanded="false"><i class="fa fa-globe"></i><span class="hide-menu">Scan A
                                                    Website</span></a>

                                        </li>
                                        <li> <a href="../scans/history.php" aria-expanded="false"><i class="fa fa-qrcode"></i><span class="hide-menu">My Scans</span></a>

                                        </li>
                                    </ul>
                                </li>
                                <li class="col-lg-3 col-xlg-3 m-b-30">
                                    <h4 class="m-b-20">General</h4>
                                    <!-- List style -->
                                    <ul class="list-style-none">
                                        <li> <a href="../general/bank.php" aria-expanded="false"><i class="fa fa-bar-chart"></i><span class="hide-menu">Finding
                                                    Database</span></a>

                                        </li>
                                        <li> <a href="../general/payloads.php" aria-expanded="false"><i class="fa fa-code"></i><span class="hide-menu">Payloads
                                                    Generator</span></a>

                                        </li>
                                        <li> <a href="../general/bruteforce.php" aria-expanded="false"><i class="fa fa-user-secret"></i><span class="hide-menu">Brute-Force
                                                    Generator</span></a>

                                        </li>


                                    </ul>
                                </li>
                                <li class="col-lg-3 col-xlg-3 m-b-30">
                                    <h4 class="m-b-20">Users</h4>
                                    <!-- List style -->
                                    <ul class="list-style-none">
                                        <li> <a href="../users/edit.php" aria-expanded="false"><i class="fa fa-edit"></i><span class="hide-menu">Edit My
                                                    Profile</span></a>

                                        </li>
                                        <li> <a href="../users/manage.php" aria-expanded="false"><i class="fa fa-users"></i><span class="hide-menu">Manage
                                                    Users</span></a>

                                        </li>
                                        <li> <a href="../users/create.php" aria-expanded="false"><i class="fa fa-plus-square"></i><span class="hide-menu">Create A
                                                    User</span></a>

                                        </li>
                                        <li> <a href="../users/history.php" aria-expanded="false"><i class="fa fa-eye"></i><span class="hide-menu">Users Scans
                                                    History</span></a>

                                        </li>

                                    </ul>
                                </li>
                                <li class="col-lg-3 col-xlg-3 m-b-30">
                                    <h4 class="m-b-20">Extra</h4>
                                    <!-- List style -->
                                    <ul class="list-style-none">
                                        <li> <a href="../extra/about.php" aria-expanded="false"><i class="fa fa-info-circle"></i><span class="hide-menu">About
                                                    PenetRate</span></a>

                                        </li>
                                        <li> <a href="../extra/contactus.php" aria-expanded="false"><i class="fa fa-rocket"></i><span class="hide-menu">Contact
                                                    Us</span></a>

                                        </li>
                                        <li> <a href="../extra/contactus.php" aria-expanded="false"><i class="fa fa-inbox"></i><span class="hide-menu">Contact
                                                    Us Records</span></a>

                                        </li>

                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- End Messages -->
                </ul>
                <!-- User profile and search -->
                <ul class="navbar-nav my-lg-0">

                    <!-- Search -->
                    <li class="nav-item hidden-sm-down search-box"> <a class="nav-link hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-search"></i></a>
                        <form class="app-search">
                            <input type="text" class="form-control" placeholder="Search here"> <a class="srh-btn"><i class="ti-close"></i></a> </form>
                    </li>

                    <!-- Profile -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../images/users/5.jpg" alt="user" class="profile-pic" /></a>
                        <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                            <ul class="dropdown-user">
                                <li><a href="../users/edit.php"><i class="fa fa-user-circle"></i> Edit Profile</a></li>
                                <li><a href="../scans/history.php"><i class="fa fa-qrcode"></i> My Scans</a></li>
                                <li><a href="../users/logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <!-- End header header -->
    <!-- Left Sidebar  -->
    <div class="left-sidebar">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
            <!-- Sidebar navigation-->
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li class="nav-devider"></li>
                    <li class="nav-label">Home <span class="label label-rouded label-warning pull-right">1</span></li>
                    <li> <a href="../extra/dashboard.php" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">Dashboard</span></a>
                    </li>
                    <li class="nav-label">Scans <span class="label label-rouded label-danger pull-right">2</span></li>
                    <li> <a href="../scans/scan.php" aria-expanded="false"><i class="fa fa-globe"></i><span class="hide-menu">Scan A
                                Website</span></a>

                    </li>
                    <li> <a href="../scans/history.php" aria-expanded="false"><i class="fa fa-qrcode"></i><span class="hide-menu">My Scans</span></a>

                    </li>
                    <li class="nav-label">General <span class="label label-rouded label-primary pull-right">3</span>
                    </li>

                    <li> <a href="../general/bank.php" aria-expanded="false"><i class="fa fa-bar-chart"></i><span class="hide-menu">Finding Database</span></a>

                    </li>
                    <li> <a href="../general/payloads.php" aria-expanded="false"><i class="fa fa-code"></i><span class="hide-menu">Payloads
                                Generator</span></a>

                    </li>
                    <li> <a href="../general/bruteforce.php" aria-expanded="false"><i class="fa fa-user-secret"></i><span class="hide-menu">Brute-Force Generator</span></a>

                    </li>



                    <li class="nav-label">Users <span class="label label-rouded btn-primary pull-right">4</span></li>
                    <li> <a href="../users/edit.php" aria-expanded="false"><i class="fa fa-edit"></i><span class="hide-menu">Edit My
                                Profile</span></a>

                    </li>
                    <li> <a href="../users/manage.php" aria-expanded="false"><i class="fa fa-users"></i><span class="hide-menu">Manage
                                Users</span></a>

                    </li>
                    <li> <a href="../users/create.php" aria-expanded="false"><i class="fa fa-plus-square"></i><span class="hide-menu">Create A User</span></a>

                    </li>
                    <li> <a href="../users/history.php" aria-expanded="false"><i class="fa fa-eye"></i><span class="hide-menu">Users Scans
                                History</span></a>

                    </li>

                    <li class="nav-label">EXTRA <span class="label label-rouded label-success pull-right">3</span></li>

                    <li> <a href="../extra/about.php" aria-expanded="false"><i class="fa fa-info-circle"></i><span class="hide-menu">About PenetRate</span></a>

                    </li>
                    <li> <a href="../extra/contactus.php" aria-expanded="false"><i class="fa fa-rocket"></i><span class="hide-menu">Contact
                                Us</span></a>

                    </li>
                    <li> <a href="../extra/contactusTable.php" aria-expanded="false"><i class="fa fa-inbox"></i><span class="hide-menu">Contact
                                Us Records</span></a>

                    </li>

                </ul>
            </nav>
            <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
    </div>
    <!-- End Left Sidebar  -->
    <!-- Page wrapper  -->
    <div class="page-wrapper">