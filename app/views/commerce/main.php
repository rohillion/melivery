<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-dark">

        <?php echo View::make('header'); ?>

        


        <!-- Dependecies -->
        <script src="/assets/application.js" type="text/javascript"></script>
        <!-- admin -->
        <script src="/assets/commerce.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                main.init();
                commerce.init();
            });
        </script>

    </body>
</html>