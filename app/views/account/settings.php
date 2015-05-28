<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-dark">

        <?php echo View::make('header'); ?>

        <section class="content-header content-header-fixed">
            <div class="container">
                <h3>
                    <?php echo Lang::get('segment.settings.name.plural'); ?>
                </h3>
            </div>
        </section>

        <!-- Main content -->
        <section style="padding-top: 122px;" class="container container-with-padding-top">

            <!-- Main row -->
            <div class="row">

                <!-- left column -->
                <div class="col-xs-12">

                    <?php if ($errors->has()) { ?>
                        <?php foreach ($errors->all() as $error) { ?>
                            <div class="alert alert-danger alert-dismissable">
                                <i class="fa fa-ban"></i>
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <?php echo $error ?>
                            </div>
                        <?php } ?>
                    <?php } else if (Session::get('success')) { ?>
                        <div class="alert alert-success alert-dismissable">
                            <i class="fa fa-check"></i>
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo Session::get('success') ?>
                        </div>
                    <?php } ?>

                </div><!--/.col (left) -->

                <!-- left column -->
                <div class="col-xs-offset-1 col-xs-10">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">Profile Account</a></li>
                            <!--<li><a href="#tab_2" data-toggle="tab">Billing</a></li>-->
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <form method="post" action="<?php echo URL::route('account.settings.profile'); ?>">

                                            <div class="form-group">
                                                <label for="mobile">Numero de m&oacute;vil</label>
                                                <input autocomplete="off" name="mobile_raw" type="text" class="form-control mobileFormat" placeholder="<?php echo Config::get('cons.mobile.' . Session::get('location.country')); ?>" value="<?php echo Session::get('user.mobile')? Session::get('user.mobile') : Input::old('mobile_raw'); ?>" data-code="<?php echo Session::get('location.country'); ?>"/>
                                                <input name="mobile" type="hidden" value="<?php echo Input::old('mobile'); ?>"/>
                                            </div>

                                            <div class="form-group">
                                                <label for="name">Nombre</label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Tu nombre" value="<?php echo Session::get('user.name') ?>">
                                            </div>
                                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                            <button class="btn btn-success btn-flat btn-md">Actualizar</button>
                                        </form>
                                    </div>
                                    <div class="col-xs-6">
                                        <form method="post" action="<?php echo URL::route('account.settings.password'); ?>">
                                            <div class="form-group">
                                                <label for="password">Clave actual</label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Clave actual" value="<?php echo Input::old('password') ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="newpassword">Nueva clave</label>
                                                <input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="Nueva clave" value="<?php echo Input::old('new_password') ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="confirm">Confirmar clave</label>
                                                <input type="password" class="form-control" id="confirm" name="confirm" placeholder="Confirmar clave" value="<?php echo Input::old('confirm_password') ?>">
                                            </div>
                                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                            <button class="btn btn-success btn-flat btn-md">Cambiar clave</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!--<div class="tab-pane" id="tab_2">
                                The European languages are members of the same family. Their separate existence is a myth.
                                For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                                in their grammar, their pronunciation and their most common words. Everyone realizes why a
                                new common language would be desirable: one could refuse to pay expensive translators. To
                                achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                                words. If several languages coalesce, the grammar of the resulting language is more simple
                                and regular than that of the individual languages.
                            </div> -->
                        </div> 
                    </div>
                </div>

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->


        <?php echo View::make('footer'); ?>

    </body>
</html>