<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="presentation-bg">

        <div><a href="//melivery<?php echo CommonEvents::get_tld()[1];?>" title="Melivery"><img width="150" class="center-block" src="/assets/clock.png" alt="Melivery Clock"/></a></div>
        <div><a href="//melivery<?php echo CommonEvents::get_tld()[1];?>" title="Melivery"><img width="300" class="center-block" src="/assets/melivery.png" alt="Melivery"/></a></div>

        <div class="form-box" id="login-box">

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

            <form action="<?php echo URL::route('account.request') ?>" method="post">
                <div class="body">
                    <div class="form-group">
                        <input name="mobile_region" type="text" class="form-control mobileFormat" placeholder="<?php echo Config::get('cons.mobile.'.Session::get('location.country')); ?>" value="<?php echo Input::old('mobile_region'); ?>" data-code="<?php echo Session::get('location.country'); ?>"/>
                        <input name="mobile" type="hidden"/>
                    </div>    
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-red btn-block">Env&iacute;enme el c&oacute;digo</button>
                    <p><a href="<?php echo URL::route('account.login')?>" class="text-center">Iniciar sesi&oacute;n</a></p>
                </div>
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            </form>
        </div>

        <?php echo View::make('footer'); ?>

    </body>
</html>