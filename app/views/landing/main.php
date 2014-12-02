<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-red">

        <?php echo View::make('header'); ?>

        <section class="content-header content-header-fixed">
            <div class="container">

                <?php if (!is_null($commerce)) { ?>

                    Hola! <?php echo $commerce->commerce_name; ?>

                <?php } else { ?>
                    
                    <?php echo Request::segment(1)?> no existe, registralo!
                <?php } ?>

            </div>
        </section>

        <!-- Main content -->
        <section class="container container-with-padding-top">

            <!-- Main row -->
            <div class="row">

                <!-- left column -->
                <div class="col-xs-12">

                </div>

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->


        <?php echo View::make('footer'); ?>

    </body>
</html>