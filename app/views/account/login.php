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

            <form action="<?php echo URL::route('account.login') ?>" method="post">
                <div class="body">
                    <div class="form-group">
                        <input name="mobile_raw" type="text" class="form-control mobileFormat" placeholder="<?php echo Config::get('cons.mobile.'.Session::get('location.country')); ?>" value="<?php echo Input::old('mobile_raw'); ?>" data-code="<?php echo Session::get('location.country'); ?>"/>
                        <input name="mobile" type="hidden" value="<?php echo Input::old('mobile'); ?>"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password"/>
                    </div>          
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-red btn-block">Sign me in</button>

                    <p><a href="request">Forgot your password?</a></p>

                    <a href="signup" class="text-center">Sign Up</a>
                </div>
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            </form>
        </div>

        <?php echo View::make('footer'); ?>

    </body>
</html>