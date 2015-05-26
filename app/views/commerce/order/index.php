<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-dark">

        <?php echo View::make('header'); ?>

        <section class="content-header content-header-fixed">
            <div class="container">
                <h3>
                    <?php echo Lang::get('segment.order.name.plural'); ?>
                    <!--<button id="togglePendingPanel" class="btn btn-success btn-flat pull-right" type="button">Comandas pendientes</button>-->
                    <a id="toggleHistory" class="btn btn-link pull-right" data-toggle="tooltip" data-placement="auto" data-original-title="Historial" title="Historial">
                        <i class="fa fa-history"></i>
                    </a>
                </h3>
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
                                <div data-toggle="tooltip" data-placement="auto" data-original-title="Cliente, repartidor o producto" class="input-group" style="background-color: white">
                                    <span class="input-group-addon" style="padding-right: 0;border: none;background-color: inherit;">
                                        <i class="fa fa-search"></i>
                                    </span>
                                    <input id="historyLiveSearch" type="text" class="form-control" style="border:none;" placeholder="Filtrar"/>
                                </div>
                            </div>

                            <section id="order-history" class="row">

                                <?php
                                foreach ($orders['history'] as $order) {
                                    include 'history.php';
                                }
                                ?>

                            </section>

                        <?php } else { ?>

                            <div class="form-group hidden liveSearch">
                                <div data-toggle="tooltip" data-placement="auto" data-original-title="Cliente, repartidor o producto" class="input-group" style="background-color: white">
                                    <span class="input-group-addon" style="padding-right: 0;border: none;background-color: inherit;">
                                        <i class="fa fa-search"></i>
                                    </span>
                                    <input id="historyLiveSearch" type="text" class="form-control" style="border:none;" placeholder="Filtrar"/>
                                </div>
                            </div>

                            <section id="order-history" class="row">
                                <div class="text-center no-data"><?php echo Lang::get('segment.order.messages.empty.history'); ?></div>
                            </section>


                        <?php } ?>

                    </section>

                    <!-- left column -->
                    <section  id="order-panel" class="col-sm-8">

                        <section id="order-progress">

                            <?php if (!is_null($orders['progress'])) { ?>

                                <div class="well well-sm text-center no-data hidden">No hay pedidos en marcha.</div>

                                <?php foreach ($orders['progress'] as $order) { ?>

                                    <?php include 'progress.php'; ?>

                                <?php } ?>

                            <?php } else { ?>

                                <div class="well well-sm text-center no-data">No hay pedidos en marcha.</div>

                            <?php } ?>

                        </section>

                        <div id="order-pending-fixed">
                            <section class="col-xs-12 col-sm-offset-2 col-sm-8 col-lg-offset-3 col-lg-6 shown" id="order-pending">

                                <?php if (!is_null($orders['pending'])) { ?>

                                    <?php foreach ($orders['pending'] as $order) { ?>

                                        <?php include 'pending.php'; ?>

                                    <?php } ?>

                                <?php } else { ?>

                                    <!--<div class="text-center">No hay pedidos pendientes.</div>-->

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
                    <div id="dealers">

                        <?php if (!$dealers->isEmpty()) { ?>

                            <?php foreach ($dealers as $dealer) { ?>

                                <?php $dealerTotal = 0 ?>

                                <div class="box box-solid dealer" data-dealer-id="<?php echo $dealer->id ?>">
                                    <div class="box-header">
                                        <h3 class="box-title"><?php echo $dealer->dealer_name ?> <!--<i class="pull-right fa fa-lightbulb-o"></i>TODO. Lightbulb filter--></h3>

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

                            <div class="well well-sm text-center no-data">No hay repartidores cargados.</div>

                        <?php } ?>

                    </div>

                    <div class="hidden">
                        <div id="dealerPanelModel" class="box box-solid" data-dealer-id="">
                            <div class="box-header">
                                <h3 class="box-title">
                                    <span class="newDealerName" contenteditable="true"></span>
                                    <!--<i class="pull-right fa fa-lightbulb-o"></i>TODO. Lightbulb filter-->
                                </h3>

                                <a data-dealer="" class="dispatch btn btn-link pull-right text-primary hidden">Enviar</a>
                                <a data-dealer="" class="hidden report btn btn-link pull-right text-primary">Entregado</a>

                                <div class="pull-right">
                                    <button type="button" class="btn btn-link saveDealer">Guardar</button>
                                    <button type="button" class="btn btn-link cancelDealer">Cancelar</button>
                                </div>
                            </div>

                            <div class="box-body bg-warning ui-sortable"></div>

                            <div class="box-footer">
                                <div class="dealer-total">
                                    <p class="clearfix" style="margin:0;">Total <span class="badge no-background pull-right">$0</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button id="addDealer" type="button" class="btn btn-success btn-flat btn-block">Agregar repartidor.</button>

                </section><!--/.col (right) -->

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <!-- Reject Modal -->
        <div class="modal fade" id="reject-motive" tabindex="-1" role="dialog" aria-labelledby="reject-motiveLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <form method="post" id="reject-form" class="form-large">
                            <div class="form-group">
                                <label style="padding-bottom:0;" for="branchPickup" class="col-xs-4 control-label">Motivo:</label>
                                <select name="motive" class="form-control">
                                    <option value="1">Ya cerramos.</option>
                                    <option value="2">No es posible cocinar el/los producto/s.</option>
                                    <option value="3">El delivery no llega al domicilio.</option>
                                    <option value="4">No se puede entregar el pedido (Falta de personal).</option>
                                    <option value="6">Sin suministros (luz,agua,gas).</option>
                                    <option value="8">Nadie lo recibi&oacute;.</option>
                                    <option value="7">Otros.</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary cancel-order" data-status="5" type="button" form="reject-form">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="changeOrderTypeModal" tabindex="-1" role="dialog" aria-labelledby="changeOrderTypeModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo Lang::get('segment.order.modal.change_order_type.title') ?></h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <?php echo Lang::get('segment.order.modal.change_order_type.body') ?>
                        </div>

                        <form method="post" id="" action="">
                            <div class="form-group">
                                <label for="changeOrderType_paycash" class="control-label"><?php echo Lang::get('segment.order.modal.change_order_type.label.paycash') ?></label>
                                <input id="changeOrderType_paycash" class="form-control" type="text"/>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="changeOrderType_cancel" type="button" class="btn btn-default" data-dismiss="modal"><?php echo Lang::get('segment.order.modal.change_order_type.action.dismiss') ?></button>
                        <button id="changeOrderType_success" type="button" class="btn btn-primary" form=""><?php echo Lang::get('segment.order.modal.change_order_type.action.accept') ?></button>
                    </div>
                </div>
            </div>
        </div>

        <?php echo View::make('footer'); ?>

    </body>
</html>