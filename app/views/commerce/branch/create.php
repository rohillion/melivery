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

                    <ul id="progressbar">
                        <li class="active">Configuraci&oacute;n b&aacute;sica</li>
                        <li>Pickup</li>
                        <li>Delivery</li>
                        <li>Horarios de atenci&oacute;n</li>
                    </ul>

                    <!-- general form elements -->
                    <div class="box box-solid branch-data">

                        <div class="box-body">
                            <form id="branchBasic" class="form-horizontal form-medium" role="form" method="post" action="<?php echo URL::action('BranchController@store'); ?>">

                                <div class="clearfix">
                                    <label for="address" class="col-xs-12 col-sm-4 control-label">Direcci&oacute;n</label>

                                    <div class="col-xs-12 col-sm-8">
                                        <input data-toggle="modal" data-target="#address-modal" name="address" type="text" class="form-control" id="address" placeholder="Direcci&oacute;n de la sucursal" value="<?php echo Input::old('address') ? Input::old('address') : ''; ?>">
                                    </div>

                                    <input name="position" id="position" type="hidden" value="<?php echo Input::old('position') ? Input::old('position') : '-34.603304,-58.387943'; ?>">
                                    <input name="street" id="street" type="hidden" value="<?php echo Input::old('street') ? Input::old('street') : ''; ?>">
                                    <input name="city" id="city" type="hidden" value="<?php echo Input::old('city') ? Input::old('city') : ''; ?>">

                                </div>

                                <div class="clearfix">
                                    <label for="branchEmail" class="col-xs-12 col-sm-4 control-label">Email</label>

                                    <div class="col-xs-12 col-sm-8">
                                        <input name="email" type="text" class="form-control" id="branchEmail" placeholder="Email de la sucursal" value="<?php echo Input::old('email') ? Input::old('email') : ''; ?>">
                                    </div>
                                </div>

                                <div class="clearfix">
                                    <label for="branchPhone" class="col-xs-12 col-sm-4 control-label">Tel&eacute;fono</label>

                                    <div class="col-xs-12 col-sm-8">
                                        <div>
                                            <input name="phone[primary]" type="text" class="form-control" id="branchPhone" placeholder="Tel&eacute;fono de la sucursal" value="<?php echo Input::old('phone.primary') ? Input::old('phone.primary') : ''; ?>">
                                        </div>

                                        <div>
                                            <input name="phone[optional]" type="text" class="form-control" id="branchPhoneOptional" placeholder="Tel&eacute;fono (opcional)" value="<?php echo Input::old('phone.optional') ? Input::old('phone.optional') : ''; ?>">
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                        <div class="box-footer clearfix">
                            <div class="col-xs-12 text-right">
                                <button class="btn btn-default btn-lg next" form="branchBasic" type="button">Siguiente</button>
                            </div>
                        </div>

                    </div>

                    <!-- general form elements -->
                    <div class="box box-solid branch-data">
                        <div class="box-body">
                            <form id="branchPickUp" class="form-horizontal form-medium" role="form" method="post" action="<?php echo URL::action('BranchController@store'); ?>">
                                <div class="clearfix">
                                    <label for="branchPickup" class="col-xs-4 control-label">Retira por sucursal</label>

                                    <div style="padding: 20px;line-height: 0;" class="col-xs-8">
                                        <input <?php echo Input::old('pickup') ? 'checked' : '' ?> class="enable hidden-checkbox" type="checkbox" id="pickup" name="pickup" value="1">
                                        <label lang="es" for="pickup" class="switch-checkbox pull-right btn btn-flat btn-lg"></label>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="box-footer clearfix">
                            <div class="col-xs-12 text-right">
                                <button class="btn btn-default btn-lg prev" type="button">Atr&aacute;s</button>
                                <button class="btn btn-default btn-lg next" form="branchPickUp" type="button">Siguiente</button>
                            </div>
                        </div>
                    </div>

                    <!-- general form elements -->
                    <div class="box box-solid branch-data">
                        <div class="box-body">
                            <form id="branchDelivery" role="form" method="post" action="<?php echo URL::action('BranchController@store'); ?>">

                                <div class="clearfix">
                                    <div class="form-medium form-group clearfix">
                                        <label for="branchDelivery" class="col-xs-12 col-sm-4">Entrega a domicilio</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input <?php echo Input::old('delivery') ? 'checked' : '' ?> class="enable hidden-checkbox" type="checkbox" id="delivery" name="delivery">
                                            <label lang="es" for="delivery" class="switch-checkbox pull-right btn btn-flat btn-lg"></label>

                                            <span class="clearfix"></span>
                                        </div>
                                    </div>

                                    <div class="col-xs-4">

                                        <div class="hidden">
                                            <div class="panel-group areaGroupModel" role="tablist" aria-multiselectable="true">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading clearfix" role="tab">
                                                        <h4 class="panel-title pull-left">
                                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                                Area de entrega 1
                                                            </a>
                                                        </h4>
                                                        <div class="actionArea pull-right">
                                                            <button type="button" href="#" class="btn btn-link width-padding removeArea">Borrar</button>
                                                            <button type="button" href="#" class="btn btn-link width-padding editArea">Editar</button>
                                                        </div>
                                                    </div>

                                                    <div class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">

                                                        <div class="onAreaEdition">
                                                            <div class="clearfix">
                                                                <span class="col-xs-6">Nombre</span>
                                                                <span class="col-xs-6">
                                                                    <input class="custom-amount form-control custom-control border-bottom" name="areas[name][]" type="text" placeholder="Area de entrega 1">
                                                                </span>
                                                            </div>

                                                            <div class="clearfix">
                                                                <span class="col-xs-6">Costo de env&iacute;o</span>
                                                                <span class="col-xs-6">
                                                                    <input class="custom-amount form-control custom-control border-bottom" name="areas[cost][]" type="text" placeholder="0.00">
                                                                </span>
                                                            </div>

                                                            <div class="clearfix">
                                                                <span class="col-xs-6">Pedido m&iacute;nimo</span>
                                                                <span class="col-xs-6">
                                                                    <input class="custom-amount form-control custom-control border-less" name="areas[min][]" type="text" placeholder="0.00">
                                                                </span>
                                                            </div>

                                                            <div class="panel-footer">
                                                                <button type="button" class="btn btn-link cancelArea">Cancelar</button>
                                                                <button type="button" class="btn btn-link saveArea">Guardar</button>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="dataArea">
                                                            <div class="clearfix cost">
                                                                <span class="col-xs-6">Costo de env&iacute;o</span>
                                                                <span class="col-xs-6 amount"></span>
                                                            </div>

                                                            <div class="clearfix min">
                                                                <span class="col-xs-6">Pedido m&iacute;nimo</span>
                                                                <span class="col-xs-6 amount"></span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <input name="areas[area][]" type="hidden" class="deliveryArea" />
                                            </div>
                                        </div>

                                        <!-- Delivery Panels -->
                                        <div id="deliveryPanelAreas"></div>

                                        <button type="button" class="btn btn-success btn-flat btn-block addArea">Agregar zona de entrega</button>

                                    </div>

                                    <div class="col-xs-8">

                                        <div id="deliveryArea">

                                            <div id="mapBranchDelivery" style="height: 250px;"></div>

                                        </div>

                                    </div>
                                </div>

                            </form>
                        </div>

                        <div class="box-footer clearfix">
                            <div class="col-xs-12 text-right">
                                <button class="btn btn-default btn-lg prev" type="button">Atr&aacute;s</button>
                                <button class="btn btn-default btn-lg next" form="branchDelivery" type="button">Siguiente</button>
                            </div>
                        </div>
                    </div>

                    <!-- general form elements -->
                    <div class="box box-solid branch-data">

                        <div class="box-body">
                            <form id="branchOpening" class="" role="form" method="post" action="<?php echo URL::action('BranchController@store'); ?>">

                                <div class="box-body clearfix">
                                    <label for="branchOpen" class="col-xs-4 control-label form-medium">Atenci&oacute;n al p&uacute;blico</label>
                                    <div class="col-xs-8 business-hours form-large">
                                        <?php foreach (CommonEvents::dayArray() as $dayIndex => $day) { ?>
                                            <div class="business-hours-control clearfix">
                                                <div class="day-range">

                                                    <div class="col-xs-3">
                                                        <input type="text" class="form-control input-sm" disabled value="<?php echo Lang::get('common.day.long.' . $dayIndex) ?>"/>
                                                    </div>

                                                    <div class="col-xs-6 range-control hour-range from-label <?php echo Input::old('days.' . $dayIndex . '.open') ? '' : 'invisible'; ?>">
                                                        <div class="col-xs-6">
                                                            <div class="timepicker">
                                                                <div class="bootstrap-timepicker">
                                                                    <input name="days[<?php echo $dayIndex; ?>][from]" type="text" class="form-control input-sm from-range" value="<?php echo Input::old('days.' . $dayIndex . '.from') ? Input::old('days.' . $dayIndex . '.from') : '08:00'; ?>"/>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-6">
                                                            <div class="timepicker">
                                                                <div class="bootstrap-timepicker">
                                                                    <input name="days[<?php echo $dayIndex; ?>][to]" type="text" class="form-control input-sm to-range" value="<?php echo Input::old('days.' . $dayIndex . '.to') ? Input::old('days.' . $dayIndex . '.to') : '18:00'; ?>"/>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <a href="#" title="Copiar anterior" class="copy-last-time">
                                                            <i class="fa fa-clock-o"></i>
                                                        </a>
                                                    </div>

                                                    <div style="padding-top: 15px;" class="pull-right">
                                                        <input <?php echo Input::old('days.' . $dayIndex . '.open') ? 'checked' : ''; ?> class="enable hidden-checkbox day-status" type="checkbox" id="day-<?php echo $dayIndex; ?>" name="days[<?php echo $dayIndex; ?>][open]" value="1">
                                                        <label lang="<?php echo App::getLocale(); ?>" for="day-<?php echo $dayIndex; ?>" class="switch-checkbox pull-right btn btn-flat btn-lg"></label>
                                                    </div>

                                                </div>

                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                            </form>
                        </div>

                        <div class="box-footer clearfix">
                            <div class="col-xs-12 text-right">
                                <button class="btn btn-default btn-lg prev" type="button">Atr&aacute;s</button>
                                <button class="btn btn-default btn-lg next" form="branchOpening" type="button">Guardar</button>
                            </div>
                        </div>
                    </div>

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
        <script src="https://maps.googleapis.com/maps/api/js?libraries=drawing"></script>


        <?php echo View::make('footer'); ?>

        <script src="http://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>

    </body>
</html>