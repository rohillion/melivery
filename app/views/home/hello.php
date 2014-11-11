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

                    <div style="background-color: white;overflow: hidden;" class="row">

                        <div class="col-xs-7">
                            <input style="border:none;" name="address" type="text" class="form-control address" placeholder="(Ej. C&oacute;rdoba 1055)">
                            <input class="position" name="position" type="hidden">
                        </div>

                        <div class="col-xs-5">
                            <select id="city" style="border:none;height: 90px;font-size: 30px;" class="form-control">
                                <?php foreach($cities as $city){?>
                                <option id="<?php echo $city->geonameid ?>" value="<?php echo $city->asciiname ?>"><?php echo $city->name ?></option>
                                <?php }?>
                            </select>
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