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

            <form action="<?php echo URL::route('account.signup') ?>" method="post">
                <div class="body">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="Tu nombre" value="<?php echo Input::old('name'); ?>"/>
                    </div>

                    <div class="form-group">
                        <input type="text" name="mobile" class="form-control" placeholder="Tu m&oacute;vil" value="<?php echo Input::old('mobile'); ?>"/>
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Tu nueva clave" value="<?php echo Input::old('password'); ?>"/>
                    </div>

                    <div class="form-group">
                        <select name="account_type" class="form-control">
                            <option <?php echo $nan; ?>>C&oacute;mo desea utilizar su cuenta?</option>
                            <option <?php echo $commercial; ?> value="commercial">Uso comercial: Quiero vender comida.</option>
                            <option <?php echo $individual; ?> value="individual">Uso particular: Quiero comprar comida.</option>
                        </select>
                    </div>         
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-red btn-block">Create account</button>
                    <a href="<?php echo URL::route('account.login'); ?>" class="text-center">Already have an account?</a>
                </div>
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            </form>
        </div>

        <?php echo View::make('footer'); ?>

    </body>
</html>