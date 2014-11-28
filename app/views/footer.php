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
?>


<!-- General -->
<script src="/assets/application.js" type="text/javascript"></script>

<!-- commerce -->
<script src="/assets/<?php echo $name ?><?php echo Request::segment(1) ? '/'.Request::segment(1) : $assetDir ?>/bundle.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function() {
        main.init();
        custom.init();
    });
</script>