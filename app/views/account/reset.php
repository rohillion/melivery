<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>AdminLTE | Log in</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="/assets/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="/assets/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="/assets/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="bg-black">

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

            <div class="header">Recuperaci&oacute;n de clave</div>
            <form action="<?php echo URL::route('reset'); ?>" method="post">
                <div class="body bg-gray">
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Nueva Clave"/>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirme Clave"/>
                    </div>
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn bg-olive btn-block">Guardar clave</button>
                </div>
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            </form>

        </div>


        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="/assets/bootstrap.min.js" type="text/javascript"></script>        

    </body>
</html>