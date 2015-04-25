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

    $dir = Request::segment(1) ? '/' . Request::segment(1) : '/' . $assetDir;
}
?>


<!-- General -->
<script src="/assets/application.js" type="text/javascript"></script>

<!-- commerce -->
<script src="/assets/<?php echo $name ?><?php echo $dir; ?>/bundle.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
            }
        });

        main.init();
        custom.init();
    });
</script>