<div class="form-group">
    <label for="address" class="col-xs-4 control-label">Direcci&oacute;n</label>

    <div class="col-xs-8">
        <input data-toggle="modal" data-target="#address-modal" name="address" type="text" class="form-control" id="address" placeholder="Direcci&oacute;n de la sucursal" value="<?php echo Input::old('address') ? Input::old('address') : ''; ?>">
    </div>

    <input name="position" id="position" type="hidden" value="<?php echo Input::old('position') ? Input::old('position') : ''; ?>">
    <input name="street" id="street" type="hidden" value="<?php echo Input::old('street') ? Input::old('street') : ''; ?>">
    <input name="city" id="city" type="hidden" value="<?php echo Input::old('city') ? Input::old('city') : ''; ?>">

</div>

<div class="form-group">
    <label for="branchEmail" class="col-xs-4 control-label">Email</label>

    <div class="col-xs-8">
        <input name="email" type="text" class="form-control" id="branchEmail" placeholder="Email de la sucursal" value="<?php echo Input::old('email') ? Input::old('email') : ''; ?>">
    </div>
</div>

<div class="form-group">
    <label for="branchPhone" class="col-xs-4 control-label">Tel&eacute;fono</label>

    <div class="row col-xs-8">
        <div class="col-xs-12">
            <input name="phone[primary]" type="text" class="form-control" id="branchPhone" placeholder="Tel&eacute;fono de la sucursal" value="<?php echo Input::old('phone.primary') ? Input::old('phone.primary') : ''; ?>">
        </div>

        <div class="col-xs-12">
            <input name="phone[optional]" type="text" class="form-control" id="branchPhoneOptional" placeholder="Tel&eacute;fono (opcional)" value="<?php echo Input::old('phone.optional') ? Input::old('phone.optional') : ''; ?>">
        </div>
    </div>
</div>

<div class="form-group">
    <label for="branchPickup" class="col-xs-4 control-label">Retira por sucursal</label>

    <div style="padding: 20px;line-height: 0;" class="col-xs-8">
        <input class="enable hidden-checkbox" type="checkbox" id="pickup" name="pickup" value="1">
        <label lang="es" for="pickup" class="switch-checkbox pull-right btn btn-flat btn-lg"></label>
    </div>
</div>

<div class="form-group">
    <label for="branchDelivery" class="col-xs-4 control-label">Entrega a domicilio</label>

    <div style="padding: 20px;line-height: 0;" class="col-xs-8">

        <input class="enable hidden-checkbox" type="checkbox" id="delivery" name="delivery">
        <label lang="es" for="delivery" class="switch-checkbox pull-right btn btn-flat btn-lg"></label>

        <span class="clearfix"></span>

        <div id="deliveryRadius" class="switchable-wrapper">

            <div class="box-header">
                <h3 class="box-title">Radio de entrega en manzanas</h3>
            </div>

            <div class="well well-md">

                <img id="mapBranchDelivery" style="width: 100%; display: block;" data-src="holder.js/100%" alt="100%" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNDIiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEyMSIgeT0iMTAwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjE1cHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjQyeDIwMDwvdGV4dD48L3N2Zz4=">

                <div class="slider irs-with-grid">
                    <input id="radio" name="radio" type="range" min="1" max="10" step="1" value="1"/>
                    <input name="delivery_area" type="hidden" id="delivery_area" />
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

                <?php if (Input::old('dealer')) { ?>

                    <?php for ($i = 0; $i < Input::old('dealer'); $i++) { ?>

                        <div class="row col-xs-12">
                            <input name="dealer[<?php echo $i; ?>]" type="text" class="form-control" placeholder="Repartidor (Ej. Juan)" value="<?php echo Input::old('dealer.' . $i) ? Input::old('dealer.' . $i) : ''; ?>">
                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <div class="row col-xs-12">
                        <input name="dealer[]" type="text" class="form-control" placeholder="Repartidor (Ej. Juan)">
                    </div>

                <?php } ?>

            </div>

        </div>
    </div>
</div>

<div class="form-group branchOpen">
    <label for="branchOpen" class="col-xs-4 control-label">Atenci&oacute;n al p&uacute;blico</label>
    <div class="col-xs-8 business-hours" style="padding:20px;">
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

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">