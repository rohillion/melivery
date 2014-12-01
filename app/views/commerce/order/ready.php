<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

    <?php foreach ($orders['ready']['dealer'] as $dealer => $readyOrders) { ?>

        <div class="panel panel-default">

            <div class="panel-heading clearfix" role="tab" id="<?php echo $dealer; ?>">

                <h2 style="margin-top: 0; margin-bottom: 0;" class="pull-left">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $dealer; ?>" aria-expanded="true" aria-controls="collapse<?php echo $dealer; ?>">
                        <?php echo $dealer; ?>
                    </a>
                </h2>

                <?php if ($dealers[$dealer]->dispatched) { ?>

                    <button form="orders<?php echo $dealers[$dealer]->id; ?>" class="btn btn-primary btn-flat pull-right"><i class="fa fa-check-square-o"></i> Rendir</button>

                <?php } else { ?>

                    <form method="post" action="<?php echo URL::action('OrderController@dispatch', $dealers[$dealer]->id) ?>">
                        <button class="btn btn-success btn-flat pull-right"><i class="fa fa-truck fa-flip-horizontal"></i> Despachar</button>
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    </form>

                <?php } ?>

            </div>

            <div id="collapse<?php echo $dealer; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $dealer; ?>">
                <form id="orders<?php echo $dealers[$dealer]->id; ?>"  method="post" action="<?php echo URL::action('OrderController@report', $dealers[$dealer]->id) ?>" > 
                    <div style="background: #f1f1f1;" class="panel-body">

                        <?php foreach ($readyOrders as $order) { ?>

                            <div class="box box-solid">

                                <div class="box-header">
                                    <h3 class="col-xs-12 box-title">

                                        <i class="fa fa-user"></i> <?php echo $order['user']['name']; ?>

                                        <span class="order-progress-time pull-right" title="Hora de entrada - tiempo estimado de entrega">
                                            <i class="fa fa-clock-o"></i> <?php echo date("H:i", strtotime($order['updated_at'])); ?> - <span class="remaining-time"></span>
                                            <input type="hidden" value="<?php echo date("Y-m-d H:i:s", strtotime($order['updated_at']) + (60 * $order['estimated'])); ?>"/>
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

                                <?php if ($dealers[$dealer]->dispatched) { ?>

                                    <div class="box-footer">

                                        <span class="clearfix"></span>

                                        <div class="pull-right radio-toolbar">
                                            <input type="radio" id="done<?php echo $order['id'] ?>" name="orders[<?php echo $order['id'] ?>]" value="1">
                                            <label class="btn btn-default btn-flat btn-block" for="done<?php echo $order['id'] ?>"><i class="fa fa-check"></i> Entregado</label>
                                        </div>

                                        <div class="pull-right radio-toolbar">
                                            <input type="radio" id="notdone<?php echo $order['id'] ?>" name="orders[<?php echo $order['id'] ?>]" value="0">
                                            <label class="btn btn-default btn-flat btn-block" for="notdone<?php echo $order['id'] ?>"><i class="fa fa-close"></i> No entregado</label>
                                        </div>

                                        <span class="clearfix"></span>

                                    </div>

                                <?php } ?>

                            </div><!-- /.box -->

                        <?php } ?>

                    </div><!-- /.panel-body -->
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                </form>
            </div><!-- /.panel-collapse -->

        </div><!-- /.panel-default -->

    <?php } ?>

</div><!-- /.accordion -->