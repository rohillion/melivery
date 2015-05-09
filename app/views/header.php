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
                <?php if (Session::get('user.id_user_type') == Config::get('cons.user_type.commerce')) { ?>
                    <ul class="nav navbar-nav">

                        <li>
                            <a href="<?php echo URL::route('commerce.order') ?>"><?php echo Lang::get('segment.order.title.main_menu'); ?></a>
                        </li>

                        <li>
                            <a href="<?php echo URL::route('commerce.product') ?>"><?php echo Lang::get('segment.product.title.main_menu'); ?></a>
                        </li>

                        <li>
                            <a href="<?php echo URL::route('commerce.profile') ?>"><?php echo Lang::get('segment.profile.title.main_menu'); ?></a>
                        </li>

                        <li>
                            <a href="<?php echo URL::route('commerce.branch') ?>"><?php echo Lang::get('segment.branch.title.main_menu'); ?></a>
                        </li>

                    </ul>
                <?php } elseif (Session::get('user.id_user_type') == Config::get('cons.user_type.customer')) { ?>

                    <ul class="nav navbar-nav">

                        <li>
                            <a href="<?php echo URL::route('customer') ?>"><?php echo Lang::get('common.label.my_orders'); ?></a>
                        </li>

                    </ul>

                <?php } ?>
            <?php } ?>

            <div class="navbar-right">
                <ul class="nav navbar-nav">

                    <?php if (Session::has('user.id')) { ?>
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
                        <?php
                        $BranchUserEloquent = new App\Repository\BranchUser\EloquentBranchUser(new BranchUser);
                        $branchUsers = $BranchUserEloquent->all(['*'], ['branch'], ['user_id' => Session::get('user.id')]);
                        ?>
                        <?php if ($branchUsers->count() > 1) { ?>
                            <li class="dropdown">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <i class="fa fa-home"></i>
                                    <span><i class="caret"></i></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li role="presentation" class="dropdown-header">Cambiar sucursal:</li>
                                    <?php foreach ($branchUsers as $branchUser) { ?>
                                        <li role="presentation">
                                            <a href="<?php echo URL::route('commerce.branch.current',$branchUser->id) ?>"><?php echo $branchUser->branch->street; ?> <?php echo $branchUser->branch->id == Session::get('user.branch_id') ? '<i class="fa fa-check"></i>' : '' ?></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
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