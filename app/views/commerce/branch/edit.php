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
                                    <label for="address" class="col-xs-4 control-label">Direcci&oacute;n</label>

                                    <div class="col-xs-8">
                                        <input data-toggle="modal" data-target="#address-modal" name="address" type="text" class="form-control" id="address" placeholder="Direcci&oacute;n de la sucursal" value="<?php echo Input::old('address') ? Input::old('address') : ( isset($branch->street) && isset($branch->city_id) ? $branch->street . ', ' . $branch->city->name : '' ); ?>">
                                    </div>

                                    <input name="position" id="position" type="hidden" value="<?php echo Input::old('position') ? Input::old('position') : ( isset($branch->position) ? $branch->position : '' ); ?>">
                                    <input name="street" id="street" type="hidden" value="<?php echo Input::old('street') ? Input::old('street') : ( isset($branch->street) ? $branch->street : '' ); ?>">
                                    <input name="city" id="city" type="hidden" value="<?php echo Input::old('city') ? Input::old('city') : ( isset($branch->city_id) ? $branch->city_id : '' ); ?>">

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
                                        <input <?php echo Input::old('pickup') || $branch->pickup ? 'checked' : ''; ?> class="enable hidden-checkbox" type="checkbox" id="pickup" name="pickup" value="1">
                                        <label lang="es" for="pickup" class="switch-checkbox pull-right btn btn-flat btn-lg"></label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="branchDelivery" class="col-lg-4 control-label">Entrega a domicilio</label>

                                    <div style="padding: 20px;line-height: 0;" class="col-lg-8">

                                        <input <?php echo Input::old('delivery') || $branch->delivery ? 'checked' : ''; ?> class="enable hidden-checkbox" type="checkbox" id="delivery" name="delivery">
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
                                                <input name="radio" type="range" min="1" max="10" step="1" value="<?php echo Input::old('radio') ? Input::old('radio') : $branch->delivery ; ?>"/>
                                                <input name="delivery_area" type="hidden" id="delivery_area" value="<?php echo Input::old('delivery_area') ? Input::old('delivery_area') : $branch->area; ?>"/>
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

                                        <div id="dealer-list">

                                            <!-- dealer field model -->
                                            <div id="dealer-model" class="row col-xs-12 invisible">
                                                <input type="text" class="form-control" placeholder="Nombre del repartidor">
                                                <a class="remove-dealer" href="#" title="Remover repartidor"><i class="fa fa-remove"></i></a>
                                            </div>

                                            <div class="box-header">
                                                <h3 class="box-title">Repartidores:</h3>
                                            </div>

                                            <?php $i = 1; ?>
                                            <?php foreach ($branch->dealers as $dealer) { ?>

                                                <div class="row col-xs-12 dealer">
                                                    <input name="dealer[edit][<?php echo $dealer->id ?>]" type="text" class="form-control" placeholder="Nombre repartidor <?php echo $i ?>" value="<?php echo Input::old('dealer.edit.' . $dealer->id) ? Input::old('dealer.edit.' . $dealer->id) : $dealer->dealer_name; ?>">
                                                    <a class="remove-dealer" href="#" title="Remover repartidor"><i class="fa fa-remove"></i></a>
                                                </div>

                                                <?php $i++; ?>
                                            <?php } ?>

                                        </div>

                                        <div style="margin-top: 20px;" class="row col-xs-12 form-medium">
                                            <button type="button" id="add-dealer" class="btn btn-flat btn-success"><i class="fa fa-plus"></i> Agregar repartidor</button>
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