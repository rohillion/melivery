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

                            <form id="branchData" class="form-horizontal form-large" role="form" method="post" action="<?php echo URL::action('BranchController@store'); ?>">

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
        <div id="map-modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Indique su posici&oacute;n exacta</h4>
                    </div>
                    <div class="modal-body">
                        <div id="map-canvas" style="min-height: 315px;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary">Aplicar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Google Maps -->
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>

        <!-- geocoding -->
        <script src="/assets/geocoding.js" type="text/javascript"></script>

        <script type="text/javascript">
            /*var input = document.getElementById('branchAddress');
             
             var autocomplete = new google.maps.places.Autocomplete(input);
             
             function geoCode() {
             google.maps.event.addListener(autocomplete, 'place_changed', function() {
             
             var place = autocomplete.getPlace();
             
             if (!place.geometry) {
             return;
             }
             
             document.getElementById('branchCoord').value = place.geometry.location.toUrlValue();
             commerce.getDeliveryArea($('#branchCoord').val(), $('#radio').val());
             
             });
             }
             google.maps.event.addDomListener(window, 'load', geoCode());*/

            var cities = <?php echo $cities ?>;
        </script>


        <?php echo View::make('footer'); ?>

    </body>
</html>