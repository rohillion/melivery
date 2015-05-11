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

            <form action="<?php echo URL::route('account.reset') ?>" method="post">
                <div class="body">
                    <div class="form-group">
                        <input type="text" name="code" class="form-control" placeholder="C&oacute;digo" value="<?php echo Input::old('code'); ?>"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Nueva Clave"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirme Clave"/>
                    </div>        
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-red btn-block">Guardar clave</button>
                </div>
                <input type="hidden" name="mobile" value="+353874689450<?php //echo Input::old('mobile'); ?>">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            </form>
        </div>

        <?php echo View::make('footer'); ?>

    </body>
</html>