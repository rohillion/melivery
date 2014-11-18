<?php
$route = explode('.', Route::currentRouteName());
$name = array_shift($route);
?>

<!-- General -->
<script src="/assets/application.js" type="text/javascript"></script>

<!-- commerce -->
<script src="/assets/<?php echo $name ?>/<?php echo Request::segment(1)? Request::segment(1) : 'dashboard' ?>/bundle.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function() {
        main.init();
        custom.init();
    });
</script>