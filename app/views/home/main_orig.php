<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-red">

        <?php echo View::make('header'); ?>

        <!-- Main content -->
        <section class="container">

            <!-- Main row -->
            <div class="row">

                <!-- left column -->
                <div class="col-md-12">

                    <h4>
                        Categor&iacute;as
                    </h4>

                    <!-- general form elements -->
                    <div class="box box-solid">

                        <div class="list-group">

                            <?php if (!$categories->isEmpty()) { ?>
                                <?php foreach ($categories as $categoryList) { ?>

                                    <a href="<?php echo URL::route('menu.products', $categoryList->category_name) ?>" class="list-group-item">
                                        <?php echo $categoryList->category_name; ?>
                                    </a>

                                <?php } ?>
                            <?php } ?>

                        </div>
                    </div><!-- /.box -->

                </div><!--/.col (left) -->

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <?php include('hello.php') ?>
        
        <!-- Modal -->
        <div id="address-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="addressModalLabel">Direcci&oacute;n de la sucursal</h4>
                    </div>

                    <div class="modal-body">
                        
                        <p id="not-found" class="bg-warning">No hemos podido encontrar la ubicaci&oacute;n exacta de la direcci&oacute;n que has ingresado. <br> Por favor, arrastra el indicador hasta donde te encuentras.</p>

                        <div id="map-canvas" style="min-height: 315px;background-color: rgb(229, 227, 223);"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary">Aplicar</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Dependecies -->
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>

        <?php echo View::make('footer'); ?>

    </body>
</html>