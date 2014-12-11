<?php foreach ($orders['progress'] as $order) { ?>

    <div class="progress-order box box-solid" data-id="<?php echo $order['id']; ?>" data-client="<?php echo $order['user']['name']; ?>">

        <div class="box-header">
            <h3 class="col-xs-12 box-title">

                <?php
                if ($order['delivery']) {
                    $title = 'Viaja';
                    $icon = 'plane';
                } else {
                    $title = 'Barra';
                    $icon = 'home';
                }
                ?>

                <i title="<?php echo $title ?>" class="fa fa-<?php echo $icon ?>"></i> <?php echo $order['user']['name']; ?>
                
                <div class="grab-order text-center fa fa-ellipsis-h"></div>

                <span class="pull-right" title="Hora de entrada - Tiempo transcurrido">
                    <i class="fa fa-clock-o"></i> <?php echo date("H:i", strtotime($order['updated_at'])); ?> - <?php echo CommonEvents::humanTiming(strtotime($order['updated_at'])); ?>
                </span>
            </h3>
        </div>

        <div class="box-body">

            <div class="commerce-order">

                <div class="commerce-order-products">

                    <ul class="order-body list-group">

                        <?php $orderTotal = NULL ?>

                        <?php foreach ($order['order_products'] as $orderProduct) { ?>

                            <?php $productTotal = $orderProduct['product']['price'] * $orderProduct['product_qty'] ?>

                            <li class="order-item list-group-item">

                                <div class="order-item-head">
                                    <p class="pull-left"><?php echo $orderProduct['product']['tags']['tag_name'] ?> </p>
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

                            <?php $orderTotal += $productTotal; ?>

                        <?php } ?>

                    </ul>

                </div>

            </div>

        </div>

        <?php if ($order['delivery']) { ?>

            <div class="box-body">

                <div>
                    <p class="clearfix" style="margin:0;">Total <span class="badge no-background pull-right">$<?php echo $orderTotal; ?></span></p>
                </div>

            </div>

            <div class="box-body">

                <div>
                    <p class="clearfix" style="margin:0;">Paga con <span class="badge bg-red pull-right">$<?php echo $order['paycash']; ?></span></p>
                </div>

            </div>

        <?php } else { ?>

            <div class="box-body">

                <div>
                    <p class="clearfix" style="margin:0;">Total <span class="badge bg-red pull-right">$<?php echo $orderTotal; ?></span></p>
                </div>

            </div>

        <?php } ?>

    </div><!-- /.box -->

    <!-- Reject Modal -->
    <div class="modal fade" id="reject-motive-<?php echo $order['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="reject-motiveLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <form method="post" id="reject-form-<?php echo $order['id'] ?>" class="form-large" action="<?php echo URL::action('OrderController@changeStatus', $order['id']) ?>">
                        <div class="form-group">
                            <label style="padding-bottom:0;" for="branchPickup" class="col-xs-4 control-label">Motivo:</label>
                            <select name="motive" class="form-control">
                                <option value="1">Ya cerramos.</option>
                                <option value="2">No es posible cocinar el/los producto/s.</option>
                                <option value="3">El delivery no llega al domicilio.</option>
                                <option value="4">No se puede entregar el pedido (Falta de personal).</option>
                                <option value="5">Sin suministros (luz,agua,gas).</option>
                                <option value="6">Otros.</option>
                            </select>
                        </div>
                        <input type="hidden" name="status" value="5">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" form="reject-form-<?php echo $order['id'] ?>">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

<?php } ?>