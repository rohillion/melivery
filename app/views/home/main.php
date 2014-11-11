<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-red">

        <?php echo View::make('header'); ?>

        <!-- Main content -->
        <section class="container">

            <!-- Main row -->
            <div class="row">

                <!-- left column -->
                <div class="col-md-12">

                    <h4>
                        Categor&iacute;as
                    </h4>

                    <!-- general form elements -->
                    <div class="box box-solid">

                        <div class="list-group">

                            <?php if (!$categories->isEmpty()) { ?>
                                <?php foreach ($categories as $categoryList) { ?>

                                    <a href="<?php echo URL::route('menu.products', $categoryList->category_name) ?>" class="list-group-item">
                                        <?php echo $categoryList->category_name; ?>
                                    </a>

                                <?php } ?>
                            <?php } ?>

                        </div>
                    </div><!-- /.box -->

                </div><!--/.col (left) -->

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <?php include('hello.php') ?>

        <!-- Dependecies -->
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
        <script src="/assets/application.js" type="text/javascript"></script>

        <!-- Custom -->
        <script src="/assets/home.js" type="text/javascript"></script>
        <script src="/assets/hello.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                main.init();
                home.init();
                hello.init();
            });
        </script>

    </body>
</html>