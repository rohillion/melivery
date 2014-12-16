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
        <section class="container container-with-padding-top order-panel">

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
                <section  id="order-panel" class="col-sm-8">

                    <?php if (!is_null($orders)) { ?>

                        <section id="order-progress">

                            <?php if (!is_null($orders['progress'])) { ?>

                                <?php include 'progress.php'; ?>

                            <?php } else { ?>

                                <div class="well well-sm text-center">No hay pedidos en marcha.</div>

                            <?php } ?>

                        </section>

                        <div id="order-pending-fixed">
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

                </section><!--/.col (left) -->

                <!-- right column -->
                <section id="dealer-panel" class="col-sm-4 hidden-xs">
                    <?php if (!$dealers->isEmpty()) { ?>

                        <?php foreach ($dealers as $dealer) { ?>

                            <?php $dealerTotal = 0 ?>

                            <div class="box box-solid" data-dealer-id="<?php echo $dealer->id ?>">
                                <div class="box-header">
                                    <h3 class="box-title"><?php echo $dealer->dealer_name ?> <i class="pull-right fa fa-lightbulb-o"></i></h3>

                                    <?php $hiddenDispatch = $dealer->orders->isEmpty() ? 'hidden' : ($dealer->dispatched ? 'hidden' : ''); ?>
                                    <a data-dealer="<?php echo $dealer->id ?>" class="<?php echo $hiddenDispatch; ?> dispatch btn btn-link pull-right text-primary">Enviar</a>

                                    <?php $hiddenReport = $dealer->dispatched ? '' : 'hidden'; ?>
                                    <a data-dealer="<?php echo $dealer->id ?>" class="<?php echo $hiddenReport; ?> report btn btn-link pull-right text-primary">Entregado</a>
                                </div>

                                <div class="box-body">

                                    <?php if (!$dealer->orders->isEmpty()) { ?>

                                        <?php foreach ($dealer->orders as $order) { ?>

                                            <div class="order-helper" data-id="<?php echo $order->id ?>" data-paycash="<?php echo $order->paycash ?>">
                                                <i class="fa fa-paperclip"></i>
                                                <div class="box box-solid client-helper-name">
                                                    <?php echo $order->user->name ?> 
                                                    <strong>$<?php echo $order->paycash ?></strong>
                                                </div>
                                            </div>

                                            <?php $dealerTotal = $dealerTotal + $order->paycash ?>

                                        <?php } ?>

                                    <?php } ?>
                                </div>


                                <div class="box-footer">
                                    <div class="dealer-total">
                                        <p class="clearfix" style="margin:0;">Total <span class="badge no-background pull-right">$<?php echo $dealerTotal; ?></span></p>
                                    </div>
                                </div>

                            </div>

                        <?php } ?>

                    <?php } else { ?>

                        <div class="well well-sm text-center">No hay repartidores cargados.</div>

                    <?php } ?>
                </section><!--/.col (right) -->

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <?php echo View::make('footer'); ?>

    </body>
</html>