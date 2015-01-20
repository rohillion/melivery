<?php

$route = explode('.', Route::currentRouteName());

$name = array_shift($route);

switch ($name) {

    case 'admin':
    case 'commerce':
    case 'customer':

        $assetDir = 'dashboard';

        break;

    default :

        $assetDir = NULL;

        break;
}

if (!CommonEvents::isSubdomain() && Request::segment(1)) {

    $dir = NULL;
} else {

    $dir = Request::segment(1) ? '/' . Request::segment(1) : $assetDir;
}

$channel = $name == 'commerce' ? "branch_".Session::get('user.branch_id') : "user_".Session::get('user.id');
?>
<head>
    <meta charset="UTF-8">
    <!--<title>Melivery</title>-->
    <title>Melivery - <?php echo (Request::segment(1)) ? Lang::get('segment.' . Request::segment(1) . '.name.plural') : 'Dashboard'; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="_token" content="<?php echo csrf_token() ?>" />
    <!-- bootstrap 3.0.2 -->
    <link href="/assets/application.css" rel="stylesheet" type="text/css" />
    <link href="/assets/<?php echo $name ?><?php echo $dir; ?>/bundle.css" rel="stylesheet" type="text/css" />

    <script src="//js.pusher.com/2.2/pusher.min.js" type="text/javascript"></script>
    
    <script type="text/javascript">
        var chnl = '<?php echo $channel?>';
    </script>
    
</head>