<?php
$route = explode('.', Route::currentRouteName());
$name = array_shift($route);
?>
<!-- Dependecies -->
<script src="/assets/application.js" type="text/javascript"></script>
<!-- admin -->
<script src="/assets/<?php echo $name;?>.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function() {
        main.init();
        <?php echo $name;?>.init();
    });
</script>