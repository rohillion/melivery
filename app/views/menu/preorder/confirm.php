<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-red">

        <?php echo View::make('header'); ?>

        <section class="content-header content-header-fixed">
            <div class="container">
                <h3>Confirmaci&oacute;n del pedido</h3>
            </div>
        </section>

        <!-- Main content -->
        <section class="container container-with-padding-top order-confirmation-page">

            <!-- Main row -->
            <div class="row">

                <?php if ($errors->has()) { ?>
                    <?php foreach ($errors->all() as $error) { ?>
                        <div class="alert alert-danger alert-dismissable">
                            <i class="fa fa-ban"></i>
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <?php echo $error ?>
                        </div>
                    <?php } ?>
                <?php } else if (Session::get('success')) { ?>
                    <div class="alert alert-success alert-dismissable">
                        <i class="fa fa-check"></i>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo Session::get('success') ?>
                    </div>
                <?php } ?>

                <!-- left column -->
                <div class="col-xs-12">

                    <div id="order-paper">
                        <div class="box box-solid">
                            <?php include 'basket.php'; ?>
                        </div>
                    </div>

                </div>

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <?php echo View::make('footer'); ?>

    </body>
</html>