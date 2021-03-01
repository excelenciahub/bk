<!DOCTYPE html>
<html>    
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title><?php echo isset($header_title)?$header_title.' | ':''; ?><?php echo SITE_NAME; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport" />
        <link type="text/css" rel="stylesheet" href="<?php echo ADMIN_PLUGINS; ?>ace/css/chosen.min.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo ADMIN_PLUGINS; ?>ace/css/ace.min.css" />
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" type="text/css" href="<?php echo ADMIN_CSS; ?>bootstrap.min.css" />
        <!-- Font Awesome -->
        <link rel="stylesheet" type="text/css" href="<?php echo ADMIN_CSS; ?>font-awesome.min.css" />
        <!-- Ionicons -->
        <link rel="stylesheet" type="text/css" href="<?php echo ADMIN_CSS; ?>ionicons.min.css" />
        <!-- Theme style -->
        <link rel="stylesheet" type="text/css" href="<?php echo ADMIN_CSS; ?>AdminLTE.css" />
        
        <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" type="text/css" href="<?php echo ADMIN_CSS; ?>skins/_all-skins.css" />
        
        <!-- jQuery 3 -->
        <script type="text/javascript" src="<?php echo ADMIN_JS; ?>jquery.min.js"></script>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition skin-blue-light fixed sidebar-mini">
        <!-- Site wrapper -->
        <div class="wrapper">
    
            <header class="main-header">
                <!-- Logo -->
                <a href="<?php echo ADMIN_URL; ?>" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>B</b>K</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><?php echo SITE_NAME; ?></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
    
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                  <img src="<?php echo ADMIN_IMAGES; ?>user.jpg" class="user-image" alt="User Image" />
                                  <span class="hidden-xs"><?php echo $_SESSION['admin_name']; ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="<?php echo ADMIN_IMAGES; ?>user.jpg" class="img-circle" alt="User Image" />
                                        <p>
                                            <?php echo $_SESSION['admin_name']; ?>
                                        </p>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="<?php echo ADMIN_URL; ?>change-password.php" class="btn btn-default btn-sm btn-flat text-olive"><i class="fa fa-cog"></i> Change Password</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="<?php echo ADMIN_URL; ?>sign-out.php" class="btn btn-default btn-sm btn-flat text-aqua"><i class="fa fa-sign-out"></i> Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- =============================================== -->
    
            <!-- Left side column. contains the sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="<?php echo ADMIN_IMAGES; ?>user.jpg" class="img-circle" alt="User Image" />
                        </div>
                        <div class="pull-left info">
                            <p><?php echo $_SESSION['admin_name']; ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu" data-widget="tree">
                        <li class="header">MAIN NAVIGATION</li>
                        <li class="<?php echo FILENAME=='dashboard.php'?'active':''; ?>"><a href="<?php echo ADMIN_URL; ?>dashboard.php"><i class="fa fa-dashboard text-olive"></i> <span>Dashboard</span></a></li>
                        <li class="<?php echo FILENAME=='state.php'?'active':''; ?>"><a href="<?php echo ADMIN_URL; ?>state.php"><i class="fa fa-building text-purple"></i> <span>State</span></a></li>
                        <li class="<?php echo FILENAME=='city.php'?'active':''; ?>"><a href="<?php echo ADMIN_URL; ?>city.php"><i class="fa fa-building text-purple"></i> <span>City</span></a></li>
                        <li class="<?php echo FILENAME=='users.php'?'active':''; ?>"><a href="<?php echo ADMIN_URL; ?>users.php"><i class="fa fa-users text-olive"></i> <span>Users</span></a></li>
                        <li class="<?php echo FILENAME=='category.php'?'active':''; ?>"><a href="<?php echo ADMIN_URL; ?>category.php"><i class="fa fa-envira text-maroon"></i> <span>Category</span></a></li>
                        <li class="<?php echo FILENAME=='products.php'?'active':''; ?>"><a href="<?php echo ADMIN_URL; ?>products.php"><i class="fa fa-book text-blue"></i> <span>Products</span></a></li>
                        <li class="<?php echo FILENAME=='orders.php'?'active':''; ?>"><a href="<?php echo ADMIN_URL; ?>orders.php?action=add"><i class="fa fa-cart-plus text-fuchsia"></i> <span>Orders</span></a></li>
                        <li class="<?php echo FILENAME=='setting.php'?'active':''; ?>"><a href="<?php echo ADMIN_URL; ?>setting.php"><i class="fa fa-cogs text-green"></i> <span>Setting</span></a></li>
                        <li class="<?php echo FILENAME=='export.php'?'active':''; ?>"><a href="<?php echo ADMIN_URL; ?>export.php"><i class="fa fa-database text-olive"></i> <span>Export</span></a></li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>
    
            <!-- =============================================== -->
            
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1><i class="fa <?php echo $header_icon; ?>"></i> <?php echo $header_title; ?></h1>
                </section>