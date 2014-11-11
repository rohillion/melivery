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
                            <!-- general form elements -->
                            <div class="box box-solid">

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

                                <?php if ($dealers[$dealer]->dispatched) { ?>

                                    <div class="box-footer">

                                        <div class="col-xs-4 col-lg-12 radio-toolbar">
                                            <input type="radio" id="done<?php echo $order['id'] ?>" name="orders[<?php echo $order['id'] ?>]" value="1">
                                            <label class="btn btn-default btn-flat btn-block btn-lg" for="done<?php echo $order['id'] ?>"><i class="fa fa-check"></i> Entregado</label>
                                        </div>

                                        <div class="reject-button col-xs-4 col-lg-12 radio-toolbar">
                                            <input type="radio" id="notdone<?php echo $order['id'] ?>" name="orders[<?php echo $order['id'] ?>]" value="0">
                                            <label class="btn btn-default btn-flat btn-lg btn-block" for="notdone<?php echo $order['id'] ?>"><i class="fa fa-close"></i> No entregado</label>
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