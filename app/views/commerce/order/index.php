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
                <button id="toggleHistory" class="btn btn-success btn-flat" type="button">Ver Historial</button>
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

                <?php if (!is_null($orders)) { ?>

                    <section id="order-history-fixed" class="col-sm-2">

                        <?php if (isset($orders['history']) && !is_null($orders['history'])) { ?>

                            <div class="form-group">
                                <div class="input-group" style="background-color: white">
                                    <span class="input-group-addon" style="padding-right: 0;border: none;background-color: inherit;">
                                        <i class="fa fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" style="border:none;"/>
                                </div>
                            </div>

                            <section id="order-history" class="row">

                                <?php foreach ($orders['history'] as $order) { ?>

                                    <div class="order-helper" data-id="<?php echo $order['id'] ?>" data-client="<?php echo $order['user']['name'] ?>" data-paycash="<?php echo $order['cash']['paycash']; ?>">
                                        <i class="fa fa-paperclip"></i>
                                        <div class="box box-<?php echo $order['status_id'] == Config::get('cons.order_status.done') ? 'success' : 'danger'; ?> order-helper-client">
                                            <div class="client-name"><?php echo $order['user']['name'] ?></div>
                                            <div class="order-change">
                                                Total <strong>$<?php echo $order['total'] ?></strong>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>

                                <?php //include 'pending.php'; ?>

                            </section>

                        <?php } else { ?>

                            <div class="text-center">No hay pedidos en el historial.</div><!-- TODO. Lang support -->

                        <?php } ?>

                    </section>

                    <!-- left column -->
                    <section  id="order-panel" class="col-sm-8">

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

                    </section><!--/.col (left) -->

                <?php } else { ?>

                    <section class="col-sm-8">
                        <div class="well well-sm text-center">Aun no tienes pedidos para visualizar.</div>
                    </section><!--/.col (left) -->

                <?php } ?>
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

                                <div class="box-body bg-warning">

                                    <?php if (!$dealer->orders->isEmpty()) { ?>

                                        <?php foreach ($dealer->orders as $order) { ?>

                                            <div class="order-helper" data-id="<?php echo $order->id ?>" data-paycash="<?php echo $order->cash->paycash ?>">
                                                <i class="fa fa-paperclip"></i>
                                                <div class="box box-solid order-helper-client">

                                                    <div class="client-name">
                                                        <?php echo $order->user->name ?> 
                                                    </div>

                                                    <div class="order-change">
                                                        Cambio
                                                        <strong>$<?php echo $order->cash->change ?></strong>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php $dealerTotal = $dealerTotal + $order->cash->paycash ?>

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

        <!-- Modal -->
        <div class="modal fade" id="changeOrderTypeModal" tabindex="-1" role="dialog" aria-labelledby="changeOrderTypeModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cambiar el tipo de entrega de este pedido</h4><!-- /.TODO. Soporte Lang -->
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            Este pedido es <strong>Para Retirar</strong> por mostrador y se cambiara a <strong>Delivery</strong>. Por favor, si lo sabe, ingrese el monto con el que abonará el comensal, de lo contrario deje el campo en blanco.
                        </div>

                        <form method="post" id="" action="<?php echo URL::route('commerce.order.type', $order['id']) ?>">
                            <div class="form-group">
                                <label for="changeOrderType_paycash" class="control-label">Monto con el que se abonará:</label>
                                <input id="changeOrderType_paycash" class="form-control" type="text"/>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="changeOrderType_cancel" type="button" class="btn btn-default" data-dismiss="modal">Matener el tipo de entrega como esta</button>
                        <button id="changeOrderType_success" type="button" class="btn btn-primary" form="">Cambiar el tipo de entrega a Delivery</button>
                    </div>
                </div>
            </div>
        </div>

        <?php echo View::make('footer'); ?>

    </body>
</html>