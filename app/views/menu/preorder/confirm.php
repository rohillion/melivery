<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-red">

        <?php echo View::make('header'); ?>

        <section class="content-header content-header-fixed">
            <div class="container">
                <h3>Confirmaci&oacute;n del pedido</h3>
            </div>
        </section>

        <!-- Main content -->
        <section class="container container-with-padding-top order-confirmation-page">

            <!-- Main row -->
            <div class="row">

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

                <!-- left column -->
                <div class="col-xs-8 col-xs-offset-2 col-sm-12 col-sm-offset-0 col-md-8 col-md-offset-2">

                    <div id="order-paper" class="shown">
                        <div class="sidebar-nav box box-solid">
                            <?php include 'basket.php'; ?>
                        </div>
                    </div>

                </div>

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <!-- Modal -->
        <div id="address-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                        <h4 class="modal-title" id="addressModalLabel">Agregar direcci&oacute;n de entrega</h4>
                    </div>

                    <div class="modal-body">

                        <div class="form-group form-medium clearfix row">
                        
                            <div class="col-xs-12 col-sm-3">
                                <input type="text" class="form-control" id="userResidence" placeholder="Piso y Dpto">
                            </div>

                            <div class="col-xs-12 col-sm-4">
                                <input type="text" class="form-control" id="userAddress" placeholder="Calle y altura">
                            </div>

                            <div class="col-xs-12 col-sm-3 scrollable-dropdown-menu">
                                <input type="text" class="form-control typeahead" id="userCity" placeholder="Ciudad">
                            </div>
                            
                            <div class="hidden-xs col-sm-2">
                                <button id="searchLocation" class="btn btn-primary btn-block" title="Buscar"><i class="fa fa-search"></i></button>
                            </div>

                        </div>
                        
                        <div class="hidden-sm hidden-md hidden-lg form-group form-medium clearfix row">
                            <div class="col-xs-12">
                                <button id="searchLocation_mobile" class="btn btn-primary btn-block" title="Buscar"><i class="fa fa-search"></i></button>
                            </div>
                        </div>

                        <p id="not-found" class="bg-warning">No hemos podido encontrar la ubicaci&oacute;n exacta de la direcci&oacute;n que has ingresado. <br> Por favor, arrastra el indicador hasta donde el punto de entrega.</p>
                        <p id="found" class="bg-success">Hemos encontrado la ubicaci&oacute;n de entrega. En caso de no ser la correcta por favor, arrastra el indicador hasta la posici&oacute;n adecuada.</p>

                        <div id="map-canvas" style="min-height: 315px;background-color: rgb(229, 227, 223);"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="custom.saveAddress(this)" data-target="<?php echo URL::route('');?>">Guardar</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Google Maps -->
        <script src="https://maps.googleapis.com/maps/api/js"></script>

        <?php echo View::make('footer'); ?>

    </body>
</html>