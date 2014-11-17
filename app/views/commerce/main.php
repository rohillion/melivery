<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-dark">

        <?php echo View::make('header'); ?>

        


        <!-- Dependecies -->
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
        <script src="/assets/application.js" type="text/javascript"></script>
        
        <!-- geocoding -->
        <script src="/assets/geocoding.js" type="text/javascript"></script>
        
        <!-- commerce -->
        <script src="/assets/commerce.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                main.init();
                commerce.init();
            });
        </script>

    </body>
</html>