<?php foreach ($orders['progress'] as $order) { ?>

<div class="progress-order box box-solid" data-id="<?php echo $order['id']; ?>" data-client="<?php echo $order['user']['name']; ?>" data-id="<?php echo $order['id']; ?>" data-delivery="<?php echo $order['delivery']; ?>" data-paycash="<?php echo !is_null($order['cash']['paycash'])? $order['cash']['paycash'] : $order['total']; ?>" data-change="<?php echo !is_null($order['cash']['change'])? $order['cash']['change'] : 0; ?>">

        <div class="box-header">
            <h3 class="col-xs-12 box-title">

                <?php
                if ($order['delivery']) {
                    $title = 'Para delivery';
                    $icon = 'arrow-up';
                } else {
                    $title = 'Para retirar';
                    $icon = 'arrow-down';
                }
                ?>

                <i data-toggle="tooltip" data-placement="auto" title="<?php echo $title ?>" class="fa fa-<?php echo $icon ?>"></i> 
                <span class="client-name popover-trigger"><?php echo $order['user']['name']; ?></span>
                
                <div class="hidden">
                    <div><i class="fa fa-phone"></i> 4328-9807</div>
                    <div><i class="fa fa-home"></i> Paraguay 914 4D</div>
                </div>

                <div class="grab-order text-center fa fa-ellipsis-h"></div>

                <div class="time-order pull-right">
                    <span data-toggle="tooltip" data-placement="auto" title="Hora de entrada">
                        <i class="fa fa-clock-o"></i> <?php echo date("H:i", strtotime($order['updated_at'])); ?> - 
                    </span>
                    <span data-toggle="tooltip" data-placement="auto" title="Transcurrido">
                        <?php echo CommonEvents::humanTiming(strtotime($order['updated_at'])); ?>
                    </span>
                </div>
            </h3>
        </div>

        <div class="box-body">

            <div class="commerce-order">

                <div class="commerce-order-products">

                    <ul class="order-body list-group">

                        <?php foreach ($order['order_products'] as $orderProduct) { ?>

                            <?php $productTotal = $orderProduct['branch_product_price']['price'] * $orderProduct['product_qty'] ?>

                            <li class="order-item list-group-item">

                                <div class="order-item-head">
                                    <p class="pull-left"><?php echo $orderProduct['branch_product']['product']['tags']['tag_name'] ?> </p>
                                    <p style="margin-left:5px;" class="pull-left"><strong> x <?php echo $orderProduct['product_qty'] ?></strong></p></span>
                                    <p class="pull-right"><span class="badge no-background pull-right">$<?php echo $productTotal; ?></span></p>
                                </div>

                                <?php if (!empty($orderProduct['attributes_order_product'])) { ?>

                                    <div class="order-item-attributes">
                                        <?php foreach ($orderProduct['attributes_order_product'] as $attributeOrderProduct) { ?>

                                            <span class="label">
                                                <?php echo $attributeOrderProduct['attributes']['attribute_name']; ?>
                                            </span>

                                        <?php } ?>
                                    </div>

                                <?php } ?>

                            </li>

                        <?php } ?>

                    </ul>

                </div>

            </div>

        </div>

        <?php if ($order['delivery']) { ?>

            <div class="box-body clearfix">
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-8 col-md-3 col-md-offset-9">
                        <p class="clearfix" style="margin:0;">Total <span class="badge no-background pull-right">$<?php echo $order['total']; ?></span></p>
                    </div>
                </div>
            </div>

            <div class="box-body clearfix">
                <div class="row">
                    <div class="hidden-xs col-sm-2">
                        <span data-toggle="tooltip" data-placement="auto" title="Cancelado">
                            <a data-order="<?php echo $order['id']; ?>" href="#" class="cancel-order-id fa fa-circle text-danger dot-button" data-toggle="modal" data-target="#reject-motive"></a>
                        </span>
                        <span data-toggle="tooltip" data-placement="auto" title="Entregado">
                            <a data-order="<?php echo $order['id']; ?>" data-status="4" style="margin-left:10px;" href="#" class="done-order fa fa-circle text-success dot-button"></a>
                        </span>
                    </div>
                    <div class="col-sm-4 col-sm-offset-6 col-md-3 col-md-offset-7">
                        <p class="clearfix" style="margin:0;">Paga con <span class="badge bg-yellow pull-right">$<?php echo $order['cash']['paycash']; ?></span></p>
                    </div>
                </div>
            </div>

        <?php } else { ?>

            <div class="box-body clearfix">
                <div class="row">
                    <div class="hidden-xs col-sm-2">
                        <span data-toggle="tooltip" data-placement="auto" title="Cancelado" >
                            <a data-order="<?php echo $order['id']; ?>" href="#" class="cancel-order-id fa fa-circle text-danger dot-button" data-toggle="modal" data-target="#reject-motive"></a>
                        </span>
                        <span data-toggle="tooltip" data-placement="auto" title="Entregado" >
                            <a data-order="<?php echo $order['id']; ?>" data-status="4" style="margin-left:10px;" href="#" class="done-order fa fa-circle text-success dot-button"></a>
                        </span>
                    </div>
                    <div class="col-sm-4 col-sm-offset-6 col-md-3 col-md-offset-7">
                        <p class="clearfix" style="margin:0;">Total <span class="badge bg-yellow pull-right">$<?php echo $order['total']; ?></span></p>
                    </div>
                </div>
            </div>

        <?php } ?>

    </div><!-- /.box -->

<?php } ?>

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