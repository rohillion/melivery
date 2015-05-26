<!DOCTYPE html>
<html>
    <?php echo View::make('head'); //echo (Request::segment(1))? Lang::get('segment.'.Request::segment(1).'.name.plural') : 'Dashboard' ;?>
    <body class="skin-black">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="../" class="navbar-brand logo"><img alt="melivery" src="/assets/logo_clock.png"/></a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <i class="fa fa-user"></i>
                                <span><?php echo Session::get('user.name') ?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li role="presentation">
                                    <a href="<?php echo URL::route('account.settings') ?>"><i class="fa fa-gear"></i> Settings</a><!--TODO. Lang Support-->
                                    <a href="<?php echo URL::route('logout') ?>"><i class="fa fa-sign-out"></i> Sign out</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="active">
                            <a href="index.html">
                                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="category">
                                <i class="fa fa-th"></i> <span>ABM Categor&iacute;as</span>
                            </a>
                        </li>
                        <li>
                            <a href="subcategory">
                                <i class="fa fa-th"></i> <span>ABM Subcategor&iacute;as</span>
                            </a>
                        </li>
                        <li>
                            <a href="rule">
                                <i class="fa fa-th"></i> <span>Reglas</span>
                            </a>
                        </li>
                        <li>
                            <a href="attribute">
                                <i class="fa fa-th"></i> <span>Atributos</span>
                            </a>
                        </li>
                        <li>
                            <a href="attribute_subcategory">
                                <i class="fa fa-th"></i> <span>Atributos por Subcategor&iacute;a</span>
                            </a>
                        </li>
                        <li>
                            <a href="tag">
                                <i class="fa fa-th"></i> <span>ABM Etiquetas</span>
                            </a>
                        </li>

                        <!--<li class="treeview">
                            <a href="#">
                                <i class="fa fa-folder"></i> <span>Examples</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="pages/examples/invoice.html"><i class="fa fa-angle-double-right"></i> Invoice</a></li>
                                <li><a href="pages/examples/login.html"><i class="fa fa-angle-double-right"></i> Login</a></li>
                                <li><a href="pages/examples/register.html"><i class="fa fa-angle-double-right"></i> Register</a></li>
                                <li><a href="pages/examples/lockscreen.html"><i class="fa fa-angle-double-right"></i> Lockscreen</a></li>
                                <li><a href="pages/examples/404.html"><i class="fa fa-angle-double-right"></i> 404 Error</a></li>
                                <li><a href="pages/examples/500.html"><i class="fa fa-angle-double-right"></i> 500 Error</a></li>
                                <li><a href="pages/examples/blank.html"><i class="fa fa-angle-double-right"></i> Blank Page</a></li>
                            </ul>
                        </li>-->
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Dashboard
                        <small>Control panel</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Dashboard</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

                    <!-- Main row -->
                    <div class="row">

                        <?php !is_null(Request::segment(1))? include(Request::segment(1).'.php') : include('dashboard.php'); ?>

                    </div><!-- /.row (main row) -->

                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <?php echo View::make('footer'); ?>
    </body>
</html>