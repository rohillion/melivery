<?php foreach ($orders['progress'] as $order) { ?>

    <!-- general form elements -->
    <div class="progress-order box box-solid">

        <div class="col-xs-12 box-header">
            <h1 style="display: inherit;float: none;" class="clearfix box-title">

                <div class="row">
                    <span class="col-lg-12">
                        <i class="fa fa-user"></i> <?php echo $order['user']['name']; ?>
                    </span>

                    <span class="col-lg-12">
                        <i class="fa fa-home"></i> <?php echo explode(',', $order['user']['customer']['address'])[0] ?> <?php echo explode(',', $order['user']['customer']['address'])[1] ?>
                    </span>

                    <span class="col-lg-12">
                        <i class="fa fa-phone"></i> <?php echo $order['user']['customer']['phone'] ?>
                    </span>

                    <span class="col-lg-12" title="Hora de entrada y tiempo restante">
                        <i class="fa fa-clock-o"></i> <?php echo date("H:i", strtotime($order['updated_at'])); ?> - <span class="remaining-time"></span>
                    </span>
                    <input type="hidden" value="<?php echo date("Y-m-d H:i:s", strtotime($order['updated_at']) + (60 * $order['estimated'])); ?>" class="order-progress-time"/>
                </div>

            </h1>
        </div>


        <div class="box-body">

            <div style="padding-top:10px;" class="col-xs-12">

                <div style="margin-bottom:0;" class="well well-sm">
                    <div style="margin-bottom:0;" class="list-group">

                        <?php foreach ($order['order_products'] as $orderProduct) { ?>

                            <a href="#" class="list-group-item">
                                <h4 class="list-group-item-heading"><?php echo $orderProduct['product']['tags']['tag_name']; ?> $<?php echo $orderProduct['product']['price']; ?></h4>
                                <p class="list-group-item-text">

                                    <?php if (!empty($orderProduct['attributes_order_product'])) { ?>

                                        <?php foreach ($orderProduct['attributes_order_product'] as $attributeOrderProduct) { ?>

                                            <span class="label label-info"><?php echo $attributeOrderProduct['attributes']['attribute_name']; ?></span>

                                        <?php } ?>

                                    <?php } ?>

                                </p>
                            </a>

                        <?php } ?>
                    </div>
                </div>

            </div>

            <span class="clearfix"></span>
        </div>

        <div class="box-footer">

            <div class="col-xs-4 col-lg-12">
                <button type="submit" class="btn btn-success btn-flat btn-block btn-lg" data-toggle="modal" data-target="#dealer-modal">Este pedido esta listo</button>
            </div>

            <div class="reject-button col-xs-4 col-lg-12">
                <button form="reject" type="button" class="btn btn-default btn-flat btn-lg btn-block" data-toggle="modal" data-target="#reject-motive-<?php echo $order['id'] ?>">No realizable</button>
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
                                    <option value="5">Sin suministros (luz,agua,gas).</option>
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

<!-- Modal -->
<div class="modal fade" id="dealer-modal" tabindex="-1" role="dialog" aria-labelledby="dealer-motiveLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form method="post" id="dealer-form" class="form-large" action="<?php echo URL::action('OrderController@changeStatus', $order['id']) ?>">
                    <div class="form-group">

                        <label style="padding-bottom:0;" for="branchPickup" class="control-label">Responsable de esta entrega:</label>

                        <div class="list-group radio-toolbar">

                            <?php if (!is_null($dealers)) { ?>

                                <?php foreach ($dealers as $dealer) { ?>

                                    <?php if (!$dealer->dispatched) { ?>

                                        <input type="radio" id="dealer<?php echo $dealer->id ?>" name="dealer" value="<?php echo $dealer->id ?>">
                                        <label class="list-group-item" for="dealer<?php echo $dealer->id ?>"><?php echo $dealer->dealer_name ?></label>

                                    <?php } else { ?>

                                        <label class="disabled list-group-item"><?php echo $dealer->dealer_name ?><span style="line-height: 150%;" class="label label-warning pull-right"><i class="fa fa-truck fa-flip-horizontal"></i> En reparto</span></label>

                                    <?php } ?>
                                <?php } ?>

                            <?php } ?>

                            <input type="radio" id="barra" name="dealer" value="0">
                            <label class="list-group-item" for="barra">Se retira por barra</label>

                        </div>

                    </div>

                    <input type="hidden" name="status" value="3">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar, el pedido no esta listo a&uacute;n</button>
                <button type="submit" class="btn btn-primary" form="dealer-form">Asignar responsable</button>
            </div>
        </div>
    </div>
</div>