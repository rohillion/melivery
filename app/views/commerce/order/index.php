<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-dark">

        <?php echo View::make('header'); ?>

        <section class="content-header content-header-fixed">
            <div class="container">
                <h3 class="pull-left">
                    <?php echo Lang::get('segment.order.name.plural'); ?>

                </h3>
                <div class="form-medium pull-right">
                    <!--<label for="commerceName" class="col-lg-4 control-label">Nombre</label>-->
                    <select class="form-control">
                        <?php foreach ($user->branches as $branch) { ?>
                            <option <?php echo $branch->id == Session::get('user.branch_id') ? 'selected' : '' ?>><?php echo $branch->street ?></option>
                        <?php } ?>
                    </select>
                    <form method="post"></form>
                </div>
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


                    <section class="order-panel">

<!--<button id="switch-view" class="btn btn-default btn-flat btn-sm"><i class="fa fa-toggle-on"></i></button>-->

                        <div class="tabs tabs-style-iconbox">

                            <nav class="box box-solid" style="margin-bottom: 20px;">
                                <ul>
                                    <!--<li class="active">
                                        <a href="#order-pending" class="fa fa-exclamation fa-2x" role="tab" data-toggle="tab">
                                            <h4>Pendientes</h4>
                                        </a>
                                    </li>-->
                                    <li class="active">
                                        <a href="#order-progress" class="fa fa-gears fa-2x" role="tab" data-toggle="tab">
                                            <h4>En Marcha</h4>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#order-ready" class="fa fa-plane fa-2x" role="tab" data-toggle="tab">
                                            <h4>En Viaje/Para retirar</h4>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#order-done" class="fa fa-check fa-2x" role="tab" data-toggle="tab">
                                            <h4>Entregado</h4>
                                        </a>
                                    </li>
                                </ul>
                            </nav>

                            <?php if (!is_null($orders)) { ?>

                                <div class="row tab-content">

                                    <section class="col-xs-12 col-lg-4 visible-lg-block tab-pane active" id="order-progress">

                                        <?php if (!is_null($orders['progress'])) { ?>

                                            <?php include 'progress.php'; ?>

                                        <?php } else { ?>

                                            <div class="well well-sm text-center">No hay pedidos en marcha.</div>

                                        <?php } ?>

                                    </section>

                                    <section class="col-xs-12 col-lg-4 visible-lg-block tab-pane" id="order-ready">

                                        <?php if (!is_null($orders['ready'])) { ?>

                                            <?php
                                            foreach ($orders['ready'] as $orderReady) {

                                                if (!empty($orderReady['branch_dealer'])) {

                                                    $orders['ready']['dealer'][$orderReady['branch_dealer'][0]['dealer_name']][] = $orderReady;
                                                } else {

                                                    $orders['ready']['dealer']['barra'][] = $orderReady;
                                                }
                                            }
                                            ?>

                                            <?php include 'ready.php'; ?>

                                        <?php } else { ?>

                                            <div class="well well-sm text-center">No hay pedidos en viaje o para retirar.</div>

                                        <?php } ?>

                                    </section>

                                    <section class="col-xs-12 col-lg-4 visible-lg-block tab-pane" id="order-done">

                                        <?php if (!is_null($orders['done'])) { ?>

                                            <?php include 'done.php'; ?>

                                        <?php } else { ?>

                                            <div class="well well-sm text-center">No hay pedidos entregados.</div>

                                        <?php } ?>

                                    </section>

                                </div>

                                <div id="order-pending-fixed" class="shown">
                                    <section class="col-xs-12 col-sm-offset-2 col-sm-8 col-lg-offset-3 col-lg-6 shown" id="order-pending">

                                        <?php if (!is_null($orders['pending'])) { ?>

                                            <?php include 'pending.php'; ?>

                                        <?php } else { ?>

                                            <div class="text-center">No hay pedidos pendientes.</div>

                                        <?php } ?>

                                    </section>
                                </div>

                            <?php } else { ?>

                                <section>
                                    <div class="well well-sm text-center">Aun no tienes pedidos para visualizar.</div>
                                </section>

                            <?php } ?>

                        </div><!-- /tabs -->
                    </section>

                </div><!--/.col (left) -->

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <?php echo View::make('footer'); ?>

    </body>
</html>