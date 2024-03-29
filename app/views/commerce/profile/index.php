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
                            <label for="cover-hidden">

                                <div class="img-container">
                                    <?php $coverPath = Config::get('cons.image.commerceCover.path') . '/' . $commerce->id . '/' . Config::get('cons.image.commerceCover.name'); ?>

                                    <?php if (File::exists($coverPath)) { ?>
                                        <img title="COVER" alt="COVER" src="<?php echo $coverPath . '?cache=' . rand(1111, 9999) ?>"/>
                                    <?php } else { ?>
                                        <img title="COVER" alt="COVER" src="<?php echo Config::get('cons.image.commerceCover.tmp') ?>"/>
                                    <?php } ?>
                                </div>

                                <div class="edit text-center">Editar</div>
                            </label>

                            <form id="cover-form" enctype="multipart/form-data" method="post" action="<?php echo URL::route('commerce.profile.cover') ?>" autocomplete="off">
                                <input data-form="cover-form" data-type="cover" type="file" name="cover" id="cover-hidden" class="hidden"/>
                            </form>
                        </div>

                        <div id="logo" class="col-xs-5 col-sm-3">
                            <label for="logo-hidden">

                                <div class="img-container">
                                    <?php $logoPath = Config::get('cons.image.commerceLogo.path') . '/' . $commerce->id . '/' . Config::get('cons.image.commerceLogo.name'); ?>

                                    <?php if (File::exists($logoPath)) { ?>
                                        <img title="LOGO" alt="LOGO" src="<?php echo $logoPath . '?cache=' . rand(1111, 9999) ?>"/>
                                    <?php } else { ?>
                                        <img title="LOGO" alt="LOGO" src="<?php echo Config::get('cons.image.commerceLogo.tmp') ?>"/>
                                    <?php } ?>

                                </div>

                                <div class="edit text-center">Editar</div>
                            </label>

                            <form id="logo-form" enctype="multipart/form-data" method="post" action="<?php echo URL::route('commerce.profile.logo') ?>" autocomplete="off">
                                <input data-form="logo-form" data-type="logo" type="file" name="logo" id="logo-hidden" class="hidden" />
                            </form>
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
                                        <div id="brandUrlBox" class="input-group">
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
                            <button id="saveProfile" class="btn btn-default btn-lg" form="commerceData" type="submit">Guardar</button>
                        </div>

                    </div><!-- /.box -->

                </div><!--/.col (left) -->

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <!-- Welcome Modal -->
        <div class="modal fade" id="welcomeModal" tabindex="-1" role="dialog" aria-labelledby="welcomeModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <h4 class="modal-title">Bienvenido a Melivery!</h4><!-- TODO. lang support -->
                    </div>
                    
                    <div class="modal-body">
                        <p>Antes de publicar tus productos necesitamos que configures algunos datos de tu comercio.</p>
                    </div>
                    
                    <div class="modal-footer">
                        <button id="startTour" type="button" class="btn btn-success btn-flat" data-dismiss="modal">Empezar configuraci&oacute;n</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Profile Completed Modal -->
        <div class="modal fade" id="profileCompletedModal" tabindex="-1" role="dialog" aria-labelledby="profileCompletedModal" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <h4 class="modal-title">Perfil configurado!</h4><!-- TODO. lang support -->
                    </div>
                    
                    <div class="modal-body">
                        <p>Ahora vamos a configurar una sucursal. Hac&eacute; Click en "Puntos de venta" en el men&uacute; superior para continuar.</p>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Ententido</button>
                    </div>
                </div>
            </div>
        </div>

        <?php echo View::make('footer'); ?>
        <script type="text/javascript">
            var showProfileTour = <?php echo Session::has('user.steps.1') ? 0 : 1 ?>;
            var showBranchTour = <?php echo Session::has('user.steps.2') ? 0 : 1 ?>;
        </script>
    </body>
</html>