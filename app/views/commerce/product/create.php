<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-dark">

        <?php echo View::make('header'); ?>

        <section class="content-header">
            <div class="container">
                <h3>
                    <?php echo Lang::get('common.action.edit') . ' ' . Lang::get('segment.product.title.menu'); ?>
                </h3>
            </div>
        </section>

        <!-- Main content -->
        <section class="container">

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

            <!-- Main row -->
            <div class="row">

                <?php if (!is_null($category)) { ?>

                    <!-- left column -->
                    <div class="col-md-4">

                        <h4>
                            Categor&iacute;as
                        </h4>

                        <!-- general form elements -->
                        <div class="box box-solid">

                            <div class="list-group">

                                <?php if (!$categories->isEmpty()) { ?>
                                    <?php foreach ($categories as $categoryList) { ?>

                                        <a href="<?php echo URL::route('commerce.product.create', $categoryList->id) ?>" class="list-group-item">
                                            <?php echo $categoryList->category_name; ?>
                                        </a>

                                    <?php } ?>
                                <?php } ?>

                            </div>
                        </div><!-- /.box -->

                    </div><!--/.col (left) -->

                    <!-- right column -->
                    <div class="col-md-8">

                        <h4>
                            <?php echo 'Productos'//Lang::get('common.action.new') . ' ' . Lang::get('segment.product.name.single'); ?>
                        </h4>

                        <!-- general form elements -->
                        <div class="box box-solid">


                            <?php include('generico.php'); ?>

                        </div><!-- /.box -->

                    </div><!--/.col (right) -->
                <?php } else { ?>

                    <!-- category column -->
                    <div class="col-md-12">

                        <h4>
                            Categor&iacute;as
                        </h4>

                        <!-- general form elements -->
                        <div class="box box-solid">

                            <div class="list-group">

                                <?php if (!$categories->isEmpty()) { ?>
                                    <?php foreach ($categories as $categoryList) { ?>

                                        <a href="<?php echo URL::route('commerce.product.create', $categoryList->id) ?>" class="list-group-item">
                                            <?php echo $categoryList->category_name; ?>
                                        </a>

                                    <?php } ?>
                                <?php } ?>

                            </div>
                        </div><!-- /.box -->

                    </div><!--/.col (left) -->

                <?php } ?>

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <?php echo View::make('footer'); ?>

    </body>
</html>