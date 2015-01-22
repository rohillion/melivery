<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-dark">

        <?php echo View::make('header'); ?>

        <section class="content-header content-header-fixed">
            <div class="container">
                <h3>
                    <?php echo Lang::get('segment.branch.title.create'); ?>
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

                        <div class="box-body">

                            <form id="branchData" class="form-horizontal form-medium" role="form" method="post" action="<?php echo URL::action('BranchController@store'); ?>">

                                <?php include('create_form.php'); ?>

                            </form>

                        </div>

                        <div style="text-align: right;" class="box-footer">
                            <button class="btn btn-default btn-lg" form="branchData" type="submit">Guardar</button>
                        </div>

                    </div><!-- /.box -->

                </div><!--/.col (left) -->

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <!-- Modal -->
        <div id="address-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="addressModalLabel">Direcci&oacute;n de la sucursal</h4>
                    </div>

                    <div class="modal-body">

                        <div class="form-group form-large clearfix row">

                            <div class="col-xs-5">
                                <input type="text" class="form-control" id="branchAddress" placeholder="Calle y altura">
                            </div>

                            <div class="col-xs-5 scrollable-dropdown-menu">
                                <input type="text" class="form-control typeahead" id="branchCity" placeholder="Ciudad">
                            </div>
                            
                            <div class="col-xs-2">
                                <button id="searchBranchLocation" class="btn btn-primary btn-block" title="Buscar"><i class="fa fa-search"></i></button>
                            </div>

                        </div>
                        
                        <p id="not-found" class="bg-warning">No hemos podido encontrar la ubicaci&oacute;n exacta de la direcci&oacute;n que has ingresado. <br> Por favor, arrastra el indicador hasta donde se encuntra la sucursal.</p>
                        <p id="found" class="bg-success">Hemos encontrado la ubicaci&oacute;n de la sucursal. En caso de no ser la correcta por favor, arrastra el indicador hasta la posici&oacute;n adecuada.</p>

                        <div id="map-canvas" style="min-height: 315px;background-color: rgb(229, 227, 223);"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="custom.completeAddress()">Aplicar</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Google Maps -->
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>

        <?php echo View::make('footer'); ?>
        
    </body>
</html>