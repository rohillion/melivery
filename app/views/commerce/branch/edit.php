<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-dark">

        <?php echo View::make('header'); ?>

        <section class="content-header content-header-fixed">
            <div class="container">
                <h3>
                    <?php echo Lang::get('segment.branch.title.edit'); ?>
                    <button form="deleteBranch" type="submit" class="pull-right btn btn-danger btn-flat">
                        <i class="fa fa-trash-o"></i>
                        <?php echo 'Eliminar esta sucursal'; //echo Lang::get('common.action.edit') . ' ' . Lang::get('segment.product.title.menu'); ?>
                    </button>
                </h3>
                <form id="deleteBranch" method="post" action="<?php echo URL::action('BranchController@destroy', $branch->id) ?>">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="_method" value="DELETE">
                </form>
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

                            <form id="branchData" class="form-horizontal form-large" role="form" method="post" action="<?php echo URL::action('BranchController@update', $branch->id); ?>">


                                <div class="form-group">
                                    <label for="branchAddress" class="col-lg-4 control-label">Direcci&oacute;n</label>

                                    <div class="col-lg-8">
                                        <input name="address" type="text" class="form-control" id="branchAddress" placeholder="Direcci&oacute;n de la sucursal" value="<?php echo Input::old('address') ? Input::old('address') : ( isset($branch->address) ? $branch->address : '' ); ?>">
                                        <input name="position" id="branchCoord" type="hidden" value="<?php echo $branch->position; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="branchEmail" class="col-xs-4 control-label">Email</label>

                                    <div class="col-xs-8">
                                        <input name="email" type="text" class="form-control" id="branchEmail" placeholder="Email de la sucursal" value="<?php echo Input::old('email') ? Input::old('email') : ( isset($branch->email) ? $branch->email : '' ); ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="branchPhone" class="col-xs-4 control-label">Tel&eacute;fono</label>

                                    <div class="row col-xs-8">
                                        <div class="col-xs-12">
                                            <input name="phone[primary]" type="text" class="form-control" id="branchPhone" placeholder="Tel&eacute;fono de la sucursal" value="<?php echo Input::old('phone.primary') ? Input::old('phone.primary') : ( isset($branch->phones[0]) ? $branch->phones[0]->number : '' ); ?>">
                                        </div>

                                        <div class="col-xs-12">
                                            <input name="phone[optional]" type="text" class="form-control" id="branchPhoneOptional" placeholder="Tel&eacute;fono (opcional)" value="<?php echo Input::old('phone.optional') ? Input::old('phone.optional') : ( isset($branch->phones[1]) ? $branch->phones[1]->number : '' ); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="branchPickup" class="col-lg-4 control-label">Retira por sucursal</label>

                                    <div style="padding: 20px;line-height: 0;" class="col-lg-8">
                                        <input <?php echo $branch->pickup ? 'checked' : ''; ?> class="enable hidden-checkbox" type="checkbox" id="pickup" name="pickup" value="1">
                                        <label lang="es" for="pickup" class="switch-checkbox pull-right btn btn-flat btn-lg"></label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="branchDelivery" class="col-lg-4 control-label">Entrega a domicilio</label>

                                    <div style="padding: 20px;line-height: 0;" class="col-lg-8">

                                        <input <?php echo $branch->delivery ? 'checked' : ''; ?> class="enable hidden-checkbox" type="checkbox" id="delivery" name="delivery">
                                        <label lang="es" for="delivery" class="switch-checkbox pull-right btn btn-flat btn-lg"></label>

                                        <span class="clearfix"></span>

                                        <div id="deliveryRadius" class="switchable-wrapper">

                                            <div class="box-header">
                                                <h3 class="box-title">Radio de entrega en manzanas</h3>
                                            </div>

                                            <?php if ($branch->delivery) { ?>

                                                <img id="mapBranchDelivery" style="width: 100%; display: block;" src="<?php echo asset('upload/branch_image/' . $branch->id . '/' . $branch->id . '_area.png?cache=' . rand(111, 999)); ?>">

                                            <?php } else { ?>

                                                <img id="mapBranchDelivery" style="width: 100%; display: block;" data-src="holder.js/100%" alt="100%" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNDIiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEyMSIgeT0iMTAwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjE1cHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjQyeDIwMDwvdGV4dD48L3N2Zz4=">
                                            <?php } ?>

                                            <div class="slider irs-with-grid">
                                                <input name="radio" onchange="commerce.getDeliveryArea($('#branchCoord').val(), this.value)" type="range" min="1" max="10" step="1" value="<?php echo $branch->delivery; ?>"/>
                                                <input name="delivery_area" type="hidden" id="delivery_area" value="<?php echo $branch->area; ?>"/>
                                                <span class="irs-grid">

                                                    <span class="irs-grid-pol" style="left: 2.5%;"></span>
                                                    <span class="irs-grid-text" style="left: 2%;">1</span>

                                                    <span class="irs-grid-pol" style="left: 13%;"></span>
                                                    <span class="irs-grid-text" style="left: 12.5%;">2</span>

                                                    <span class="irs-grid-pol" style="left: 23.5%;"></span>
                                                    <span class="irs-grid-text" style="left: 23%;">3</span>

                                                    <span class="irs-grid-pol" style="left: 34%;"></span>
                                                    <span class="irs-grid-text" style="left: 33.5%;">4</span>

                                                    <span class="irs-grid-pol" style="left: 44.5%;"></span>
                                                    <span class="irs-grid-text" style="left: 44%;">5</span>

                                                    <span class="irs-grid-pol" style="left: 55%;"></span>
                                                    <span class="irs-grid-text" style="left: 54.5%;">6</span>

                                                    <span class="irs-grid-pol" style="left: 65.5%;"></span>
                                                    <span class="irs-grid-text" style="left: 65%;">7</span>

                                                    <span class="irs-grid-pol" style="left: 76.2%;"></span>
                                                    <span class="irs-grid-text" style="left: 75.7%;">8</span>

                                                    <span class="irs-grid-pol" style="left: 87%;"></span>
                                                    <span class="irs-grid-text" style="left: 86.5%;">9</span>

                                                    <span class="irs-grid-pol" style="left: 97.5%;"></span>
                                                    <span class="irs-grid-text" style="left: 97%;">10</span>

                                                </span>
                                            </div>

                                        </div>

                                        <div class="">

                                            <div class="box-header">
                                                <h3 class="box-title">Repartidores:</h3>
                                            </div>

                                            <?php $i = 1; ?>
                                            <?php foreach ($branch->dealers as $dealer) { ?>

                                                <div class="row col-xs-12">
                                                    <input name="dealer[<?php echo $dealer->id ?>]" type="text" class="form-control" placeholder="Nombre repartidor <?php echo $i ?>" value="<?php echo Input::old('dealer.' . $dealer->id) ? Input::old('dealer.' . $dealer->id) : $dealer->dealer_name; ?>">
                                                </div>

                                                <?php $i++; ?>
                                            <?php } ?>

                                        </div>

                                    </div>
                                </div>

                                <div class="form-group branchOpen">
                                    <label for="branchOpen" class="col-xs-4 control-label">Atenci&oacute;n al p&uacute;blico</label>
                                    <div class="row col-xs-8 business-hours">
                                        <?php foreach ($branch->openingHours as $day) { ?>
                                            <div class="business-hours-control clearfix">
                                                <div class="day-range">

                                                    <div class="col-xs-3">
                                                        <input type="text" class="form-control input-sm" disabled value="<?php echo Lang::get('common.day.long.' . $day->day_id) ?>"/>
                                                    </div>

                                                    <div class="col-xs-6 range-control hour-range from-label <?php echo!$day->open ? 'invisible' : ''; ?>">
                                                        <div class="col-xs-6">
                                                            <div class="timepicker">
                                                                <div class="bootstrap-timepicker">
                                                                    <input name="days[<?php echo $day->day_id; ?>][from]" type="text" class="form-control input-sm from-range" value="<?php echo $day->open_time; ?>"/>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-6">
                                                            <div class="timepicker">
                                                                <div class="bootstrap-timepicker">
                                                                    <input name="days[<?php echo $day->day_id; ?>][to]" type="text" class="form-control input-sm to-range" value="<?php echo $day->close_time; ?>"/>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <a href="#" title="Copiar anterior" class="copy-last-time">
                                                            <i class="fa fa-clock-o"></i>
                                                        </a>

                                                    </div>

                                                    <div style="padding-top: 15px;" class="pull-right">
                                                        <input <?php echo $day->open ? 'checked' : ''; ?> class="enable hidden-checkbox day-status" type="checkbox" id="day-<?php echo $day->day_id; ?>" name="days[<?php echo $day->day_id; ?>][open]" value="1">
                                                        <label lang="<?php echo App::getLocale(); ?>" for="day-<?php echo $day->day_id; ?>" class="switch-checkbox pull-right btn btn-flat btn-lg"></label>
                                                    </div>

                                                </div>

                                            </div>
                                        <?php } ?>
                                    </div>

                                </div>

                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                <input type="hidden" name="_method" value="PUT">

                                <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
                                <script type="text/javascript">
                                                    var input = document.getElementById('branchAddress');

                                                    var autocomplete = new google.maps.places.Autocomplete(input);

                                                    function geoCode() {
                                                        google.maps.event.addListener(autocomplete, 'place_changed', function() {

                                                            var place = autocomplete.getPlace();
                                                            /*console.log(place.geometry.location.lat());
                                                             console.log(place.geometry.location.lng());
                                                             console.log(place.geometry.location.toUrlValue());*/
                                                            if (!place.geometry) {
                                                                return;
                                                            }

                                                            document.getElementById('branchCoord').value = place.geometry.location.toUrlValue();
                                                            //commerce.getDeliveryMapPreview();

                                                        });
                                                    }
                                                    google.maps.event.addDomListener(window, 'load', geoCode());
                                </script>

                            </form>

                        </div>

                        <div style="text-align: right;" class="box-footer">
                            <button class="btn btn-default btn-lg" form="branchData" type="submit">Guardar</button>
                        </div>

                    </div><!-- /.box -->

                </div><!--/.col (left) -->

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <?php echo View::make('footer'); ?>

    </body>
</html>