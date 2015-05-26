<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-dark">

        <?php echo View::make('header'); ?>

        <section class="content-header content-header-fixed">
            <div class="container">
                <h3>
                    Puntos de venta
                    <?php //echo Lang::get('segment.profile.title.main_menu'); ?>
                </h3>
            </div>
        </section>

        <!-- Main content -->
        <section style="padding-top: 122px;" class="container container-with-padding-top">

            <!-- Main row -->
            <div class="row">

                <!-- left column -->
                <div class="col-md-12">

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

                    <!-- general form elements -->
                    <div class="box box-solid">

                        <div class="box-body">

                            <div class="form-large">
                                <!-- <div class="col-xs-4">
                                    
                                </div> -->

                                <div class="col-xs-12">
                                    <div class="row">

                                        <?php foreach ($branches as $branch) { ?>

                                            <a href="<?php echo URL::action('BranchController@edit', $branch->id); ?>">
                                                <img class="col-xs-3" src="<?php echo asset('upload/branch_image/' . $branch->id . '/' . $branch->id . '.png'); ?>"/>
                                            </a>

                                        <?php } ?>

                                        <div class="col-xs-3 add-branch">
                                            <a href="<?php echo URL::route('commerce.branch.create'); ?>">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            
                            <span class="clearfix"></span>

                        </div>

                    </div><!-- /.box -->

                </div><!--/.col (left) -->

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->


        <?php echo View::make('footer'); ?>

    </body>
</html>