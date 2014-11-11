<?php
$route = explode('.', Route::currentRouteName());
$name = array_shift($route);
$channel = $name == 'commerce' ? "branch_".Session::get('user.branch_id') : "user_".Session::get('user.id');
?>
<head>
    <meta charset="UTF-8">
    <!--<title>Melivery</title>-->
    <title>Melivery - <?php echo (Request::segment(1)) ? Lang::get('segment.' . Request::segment(1) . '.name.plural') : 'Dashboard'; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- bootstrap 3.0.2 -->
    <link href="/assets/application.css" rel="stylesheet" type="text/css" />
    <link href="/assets/<?php echo $name ?>.css" rel="stylesheet" type="text/css" />

    <script src="//js.pusher.com/2.2/pusher.min.js" type="text/javascript"></script>
    
    <script type="text/javascript">
        var chnl = '<?php echo $channel?>';
    </script>
    
</head>