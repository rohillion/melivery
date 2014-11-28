<div id="hello-wrapper" class="<?php echo!Cookie::get('position') ? 'show' : ''; ?>">
    <!-- Main content -->
    <section class="container container-with-padding-top">

        <!-- Main row -->
        <div class="row">

            <div>
                <img width="150" class="center-block" src="/assets/clock.png" alt="Melivery Clock"/>
            </div>

            <div>
                <img width="300" class="center-block" src="/assets/melivery.png" alt="Melivery"/>
            </div>

            <div style="margin-top:20px;" class="form-large">

                <div class="input-group col-lg-offset-2 col-lg-8">

                    <div style="background-color: white;" class="row">

                        <div class="col-xs-7">
                            <input style="border:none;" name="address" type="text" class="form-control address" placeholder="(Ej. C&oacute;rdoba 1055)">
                            <input class="position" name="position" type="hidden">
                        </div>

                        <div class="col-xs-5">


                            <div id="city-select" class="pull-right">

                                <div href="#" id="city-mask" class="filter-mask popover-trigger">

                                    <div class="mask pull-left">Ciudad</div>

                                    <div class="caret-wrapp pull-left">
                                        <span class="caret"></span>
                                    </div>

                                    <div id="city-list" class="popover-wrapper">
                                        <ul class="list-group organized-list">

                                            <span role="presentation" class="dropdown-header">Seleccione ciudad</span>

                                            <?php foreach ($cities as $city) { ?>
                                                <li class="list-group-item">
                                                    <a style="font-size:15px;" id="<?php echo $city->geonameid ?>" data-value="<?php echo $city->asciiname ?>" class="city-item" href="#"><?php echo $city->name . ' - '. $city->state->state_name ?></a>
                                                </li>
                                            <?php } ?>

                                        </ul>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <span class="input-group-btn">
                        <button id="search_food" style="border:none;" class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                    </span>
                </div>

            </div>

            <div style="margin-top:20px;">

                <div class="text-center">
                    <a id="skip_step" href="#" class="btn btn-link" type="button">Saltear este paso</a>
                </div>

            </div>

        </div><!-- /.row (main row) -->

    </section><!-- /.content -->

    <footer class="bottombar">

        <div class="col-lg-6 text-left">
            <span>
                <a href="#" class="btn btn-link">T&eacute;rminos y condiciones</a>
            </span>
        </div>

        <div class="col-lg-6 text-right">
            <span>
                <a href="#" class="btn btn-link">Melivery para comercios</a>
            </span>
        </div>

    </footer>

</div>