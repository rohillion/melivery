<?php foreach ($orders['done'] as $order) { ?>

    <!-- general form elements -->
    <div class="box box-solid">

        <div class="box-header">
            <h3 class="col-xs-12 box-title">

                <i class="fa fa-user"></i> <?php echo $order['user']['name']; ?>

                <span class="pull-right" title="Hora de entrada y tiempo transcurrido">
                    <i class="fa fa-clock-o"></i> <?php echo date("H:i", strtotime($order['created_at'])); ?> - <span class="elapsed-time"></span>
                </span>
            </h3>
        </div>

        <div class="box-body">

            <div class="commerce-order">

                <div class="commerce-order-products">

                    <ul class="order-body list-group">

                        <?php foreach ($order['order_products'] as $orderProduct) { ?>

                            <li class="order-item list-group-item">

                                <div class="order-item-head">
                                    <p class="pull-left"><?php echo $orderProduct['product']['tags']['tag_name'] ?></p>
                                    <span class="badge pull-right">x <?php echo $orderProduct['product_qty'] ?></span>
                                </div>

                                <?php if (!empty($orderProduct['attributes_order_product'])) { ?>

                                    <div class="order-item-attributes">
                                        <?php foreach ($orderProduct['attributes_order_product'] as $attributeOrderProduct) { ?>

                                            <span style="font-size: 11px; margin-right: 2px;" class="label label-info">
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

        <div class="box-footer">

            <div class="pull-right">
                <a href="#" class="btn btn-primary">Historial de este pedido</a>
            </div>

            <span class="clearfix"></span>

        </div>

        <!-- Modal -->
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
                                    <option value="5">Sin suministros (luz,agua,gaz).</option>
                                    <option value="6">Otros.</option>
                                </select>
                            </div>
                            <input type="hidden" name="status" value="5">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Si es posible realizar este pedido</button>
                        <button type="submit" class="btn btn-primary" form="reject-form-<?php echo $order['id'] ?>">Cancelar el pedido y notificar el comensal</button>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.box -->

<?php } ?>