<!DOCTYPE html>
<html lang="<?php echo App::getLocale(); ?>">
    <?php echo View::make('head'); ?>
    <body class="skin-red" data-spy="scroll" data-target="#scrollControl" data-offset="150">

        <?php echo View::make('header'); ?>

        <section class="content-header content-header-fixed">
            <div class="container">
                <h3>
                    <span class="section-title"><?php echo Lang::get('segment.order.name.plural') . ' realizados'; ?></span>

                    <!-- <a id="addProduct" href="#productForm" class="pull-right btn btn-success btn-flat" data-loading-text="Cargando..." data-close-text="Cerrar" data-cancel-text="Cancelar"><!-- TODO. Lang 
                    <?php echo Lang::get('common.action.add') . ' ' . Lang::get('segment.product.name.single'); ?>
                    </a>-->
                </h3>
            </div>
        </section>

        <!-- Main content -->
        <section class="container container-with-padding-top">

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
                <div class="col-xs-4" >
                    <div class="page-header">Lista de cosas</div><!-- TODO. Lang -->
                    <div id="scrollControl" data-spy="affix" data-offset-top="90">
                        <!-- general form elements -->
                        <div class="box box-solid">

                            <ul class="list-group nav">

                                <li class="list-group-item">
                                    <a href="#" class="scrollTo">
                                        Algo
                                    </a>
                                </li>

                            </ul>
                        </div><!-- /.box -->

                    </div><!--/.col (left) -->

                </div>

                <!-- right column -->
                <div class="col-xs-8">

                    <?php if (!empty($orders)) { ?>

                        <?php include 'progress.php'; ?>

                    <?php } else { ?>
                        <div class="well well-sm text-center">A&uacute;n no tienes pedidos realizados.</div><!-- TODO Lang -->
                    <?php } ?>

                </div><!--/.col (right) -->

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <?php echo View::make('footer'); ?>
    </body>
</html>