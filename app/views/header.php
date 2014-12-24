<input id="success-msg" type="hidden" value="<?php echo Session::get('success') ? Session::get('success') : ''; ?>"/>
<input id="country" type="hidden" value="<?php echo Session::get('location') ? Session::get('location')['country'] : ''; ?>"/>

<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">

        <div class="navbar-header">
            <a href="../" class="navbar-brand"><img alt="melivery" src="/assets/logo_clock.png"/></a>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="navbar-collapse collapse" id="navbar-main">

            <?php if (Session::has('user.id')) { ?>
                <?php if (Session::get('user.id_user_type') == 2) {//2 = commerce user ?>
                    <ul class="nav navbar-nav">

                        <li>
                            <a href="/order"><?php echo Lang::get('segment.order.title.main_menu'); ?></a>
                        </li>

                        <li>
                            <a href="/product"><?php echo Lang::get('segment.product.title.main_menu'); ?></a>
                        </li>

                        <li>
                            <a href="/profile"><?php echo Lang::get('segment.profile.title.main_menu'); ?></a>
                        </li>
                        
                        <li>
                            <a href="/branch"><?php echo Lang::get('segment.branch.title.main_menu'); ?></a>
                        </li>

                    </ul>
                <?php } ?>
            <?php } ?>

            <div class="navbar-right">
                <ul class="nav navbar-nav">

                    <?php if (Auth::check()) { ?>
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?php echo Session::get('user')['name'] ?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    <img alt="User Image" class="img-circle" src="/assets/avatar3.png">
                                    <p>
                                        Jane Doe - Web Developer
                                        <small>Member since Nov. 2012</small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a class="btn btn-default btn-flat" href="#">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a class="btn btn-default btn-flat" href="<?php echo URL::route('logout') ?>">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li>
                            <a href="<?php echo URL::route('login') ?>">Iniciar Sesi&oacute;n</a>
                        </li>
                        <li>
                            <a href="<?php echo URL::route('signup') ?>">Registrarme</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>

        </div>
    </div>
</div>