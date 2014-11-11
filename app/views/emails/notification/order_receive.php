<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Han realizado un pedido desde Melivery.com</h2>

        <div>
            Hola <?php echo 'rohillion@hotmail.com'; ?>.<br>
            Ha llegado un pedido de <strong>Fulano De Tal</strong>. Por favor haga click <a href="<?php echo URL::route('commerce.commerce.order') ?>">aqu&iacute;</a> para verlo.<br/><br/>
            Saludos cordiales.<br><br>
            Atte.<br>
            Melivery
        </div>
    </body>
</html>
