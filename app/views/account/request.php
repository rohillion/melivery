<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="presentation-bg">

        <div><img width="150" class="center-block" src="/assets/clock.png" alt="Melivery Clock"/></div>
        <div><img width="300" class="center-block" src="/assets/melivery.png" alt="Melivery"/></div>

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
                        <input type="text" name="mobile" class="form-control" placeholder="Tu m&oacute;vil" value="<?php echo Input::old('mobile'); ?>"/>
                    </div>    
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-red btn-block">Env&iacute;enme el c&oacute;digo</button>
                    <p><a href="<?php echo URL::route('account.login')?>" class="text-center">Login</a></p>
                </div>
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            </form>
        </div>

        <?php echo View::make('footer'); ?>

    </body>
</html>