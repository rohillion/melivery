<div class="progress-order box box-solid <?php echo isset($order->new) ? 'new-progress' : '' ?>" data-id="<?php echo $order->id; ?>" data-client="<?php echo $order->user->name; ?>" data-id="<?php echo $order->id; ?>" data-delivery="<?php echo $order->delivery; ?>" data-paycash="<?php echo!is_null($order->cash) ? $order->cash->paycash : $order->total; ?>" data-change="<?php echo!is_null($order->cash) ? $order->cash->change : 0; ?>">

    <div class="box-header">
        <h3 class="col-xs-12 box-title">

            <?php
            if ($order->delivery) {
                $title = 'Para delivery';
                $icon = 'arrow-up';
            } else {
                $title = 'Para retirar';
                $icon = 'arrow-down';
            }
            ?>

            <i data-toggle="tooltip" data-placement="auto" title="<?php echo $title ?>" class="fa fa-<?php echo $icon ?>"></i> 
            <span <?php echo $order->delivery ? 'class="client-name popover-trigger"' : '' ?>><?php echo $order->user->name; ?></span>

            <?php if ($order->delivery) { ?>
                <div class="hidden">
                    <div><i class="fa fa-phone"></i> <?php echo $order->user->mobile; ?></div>
                    <div><i class="fa fa-home"></i> <?php echo!is_null($order->user_address) ? $order->user_address->floor_apt . ' ' . $order->user_address->street : 'No proporcionado'; ?></div>
                </div>
            <?php } ?>

            <div class="grab-order text-center fa fa-ellipsis-h"></div>

            <div class="time-order pull-right">
                <span data-toggle="tooltip" data-placement="auto" title="Hora de entrada"><!--TODO Lang-->
                    <i class="fa fa-clock-o"></i> <?php echo date("H:i", strtotime($order->updated_at)); ?> - 
                </span>
                <span data-toggle="tooltip" data-placement="auto" title="Transcurrido"><!--TODO Lang-->
                    <?php echo CommonEvents::humanTiming(strtotime($order->updated_at)); ?>
                </span>
            </div>
        </h3>
    </div>

    <div class="box-body">

        <div class="commerce-order">

            <div class="commerce-order-products">

                <ul class="order-body list-group">

                    <?php foreach ($order->order_products as $orderProduct) { ?>

                        <?php $productTotal = $orderProduct->branch_product_price->price * $orderProduct->product_qty ?>

                        <li class="order-item list-group-item">

                            <div class="order-item-head">
                                <p class="pull-left"><?php echo $orderProduct->branch_product->product->tags->tag_name ?> </p>
                                <p style="margin-left:5px;" class="pull-left"><strong> x <?php echo $orderProduct->product_qty ?></strong></p></span>
                                <p class="pull-right"><span class="badge no-background pull-right">$<?php echo $productTotal; ?></span></p>
                            </div>

                            <?php if (!empty($orderProduct->attributes_order_product)) { ?>

                                <div class="order-item-attributes">
                                    <?php foreach ($orderProduct->attributes_order_product as $attributeOrderProduct) { ?>

                                        <span class="label">
                                            <?php echo $attributeOrderProduct->attributes->attribute_name; ?>
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

    <?php if ($order->delivery) { ?>

        <div class="box-body clearfix">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-8 col-md-3 col-md-offset-9">
                    <p class="clearfix" style="margin:0;">Total <span class="badge no-background pull-right">$<?php echo $order->total; ?></span></p><!-- TODO. Lang-->
                </div>
            </div>
        </div>

        <div class="box-body clearfix">
            <div class="row">
                <div class="hidden-xs col-sm-2">
                    <span data-toggle="tooltip" data-placement="auto" title="Cancelado">
                        <a data-order="<?php echo $order->id; ?>" href="#" class="cancel-order-id fa fa-circle text-danger dot-button" data-toggle="modal" data-target="#reject-motive"></a>
                    </span>
                    <span data-toggle="tooltip" data-placement="auto" title="Entregado">
                        <a data-order="<?php echo $order->id; ?>" data-status="<?php echo Config::get('cons.order_status.done') ?>" style="margin-left:10px;" href="#" class="done-order fa fa-circle text-success dot-button"></a>
                    </span>
                </div>
                <div class="col-sm-4 col-sm-offset-6 col-md-3 col-md-offset-7">
                    <p class="clearfix" style="margin:0;">Paga con <span class="badge bg-yellow pull-right">$<?php echo $order->cash->paycash; ?></span></p><!-- TODO. Lang-->
                </div>
            </div>
        </div>

    <?php } else { ?>

        <div class="box-body clearfix">
            <div class="row">
                <div class="hidden-xs col-sm-2">
                    <span data-toggle="tooltip" data-placement="auto" title="Cancelado" >
                        <a data-order="<?php echo $order->id; ?>" href="#" class="cancel-order-id fa fa-circle text-danger dot-button" data-toggle="modal" data-target="#reject-motive"></a>
                    </span>
                    <span data-toggle="tooltip" data-placement="auto" title="Entregado" >
                        <a data-order="<?php echo $order->id; ?>" data-status="<?php echo Config::get('cons.order_status.done') ?>" style="margin-left:10px;" href="#" class="done-order fa fa-circle text-success dot-button"></a>
                    </span>
                </div>
                <div class="col-sm-4 col-sm-offset-6 col-md-3 col-md-offset-7">
                    <p class="clearfix" style="margin:0;">Total <span class="badge bg-yellow pull-right">$<?php echo $order->total; ?></span></p><!-- TODO. Lang-->
                </div>
            </div>
        </div>

    <?php } ?>

</div><!-- /.box -->