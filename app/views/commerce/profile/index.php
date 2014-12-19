<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-dark">

        <?php echo View::make('header'); ?>

        <section class="content-header content-header-fixed">
            <div class="container">
                <h3>
                    <?php echo Lang::get('segment.profile.title.main_menu'); ?>
                </h3>
            </div>
        </section>

        <!-- Main content -->
        <section style="padding-top: 122px;" class="container container-with-padding-top">

            <!-- Main row -->
            <div class="row">

                <!-- left column -->
                <div class="col-md-12">

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

                    <!-- general form elements -->
                    <div class="box box-solid">

                        <div id="cover">
                            <img title="COVER" alt="COVER" src="upload/commerce_image/<?php echo Session::get('user.id_commerce')?>/cover.jpg"/>
                        </div>
                        
                        <div id="logo" class="col-xs-3">
                            <img title="LOGO" alt="LOGO" src="upload/commerce_image/<?php echo Session::get('user.id_commerce')?>/logo.png"/>
                        </div>

                        <div class="box-body">

                            <form id="commerceData" class="form-horizontal form-large" role="form" method="post" action="<?php echo URL::route('commerce.profile'); ?>">

                                <div class="form-group">
                                    <label for="commerceName" class="col-lg-4 control-label">Nombre</label>

                                    <div class="col-lg-8">
                                        <input name="name" type="text" class="form-control" id="commerceName" placeholder="Nombre de tu negocio" value="<?php echo $commerce->commerce_name; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="brandUrl" class="col-lg-4 control-label">URL</label>

                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">melivery.com<?php echo Session::get('location.country') ? '.' . Session::get('location.country') : '' ?>/</span>
                                            <input name="url" type="text" class="form-control" id="brandUrl" placeholder="tunegocio" value="<?php echo $commerce->commerce_url; ?>">
                                        </div>
                                    </div>

                                </div>

                                <?php
                                /* if ($commerce->branches->isEmpty()) {
                                  echo View::make('commerce.branch.create_form');
                                  } */
                                ?>

                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            </form>

                        </div>

                        <div style="text-align: right;" class="box-footer">
                            <button class="btn btn-default btn-lg" form="commerceData" type="submit">Guardar</button>
                        </div>

                    </div><!-- /.box -->

                    <!-- general form elements -->
                    <div class="box box-solid">

                        <?php //if (!$commerce->branches->isEmpty()) { ?>
                        <?php if ($commerce->commerce_name != NULL) { ?>

                            <div class="box-body">

                                <form id="commerceData" class="form-horizontal form-large" role="form" method="post" action="<?php echo URL::route('commerce.profile'); ?>">

                                    <div class="form-group">
                                        <label for="commerceName" class="col-lg-4 control-label">Puntos de venta</label>

                                        <div style="padding-top: 20px;" class="col-lg-8">
                                            <div class="row">

                                                <?php foreach ($commerce->branches as $branch) { ?>

                                                    <a href="<?php echo URL::action('BranchController@edit', $branch->id); ?>">
                                                        <img class="col-lg-4" src="<?php echo asset('upload/branch_image/' . $branch->id . '/' . $branch->id . '.png'); ?>"/>
                                                    </a>

                                                <?php } ?>

                                                <div class="col-lg-4 add-branch">
                                                    <a href="<?php echo URL::route('commerce.branch.create'); ?>">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </form>

                            </div>

                        <?php } ?>

                    </div><!-- /.box -->

                </div><!--/.col (left) -->

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->


        <?php echo View::make('footer'); ?>

    </body>
</html>