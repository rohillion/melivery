<div class="pending-order box box-solid <?php echo isset($order->new) ? 'new-pending' : '' ?>" data-id="<?php echo $order->id; ?>" data-client="<?php echo $order->user->name; ?>" data-id="<?php echo $order->id; ?>" data-delivery="<?php echo $order->delivery; ?>" data-paycash="<?php echo!is_null($order->cash) ? $order->cash->paycash : $order->total; ?>" data-change="<?php echo!is_null($order->cash) ? $order->cash->change : 0; ?>">

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
            <span class="client-name popover-trigger"><?php echo $order->user->name; ?></span>

            <div class="hidden">
                <div><i class="fa fa-phone"></i> <?php echo $order->user->customer->phone; ?></div>
                <div><i class="fa fa-home"></i> <?php echo $order->user->customer->address; ?></div>
            </div>

            <div class="time-order pull-right">
                <span data-toggle="tooltip" data-placement="auto" title="Hora de entrada">
                    <i class="fa fa-clock-o"></i> <?php echo CommonEvents::humanTiming(strtotime($order->created_at)); ?>
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
                    <p class="clearfix" style="margin:0;">Total <span class="badge no-background pull-right">$<?php echo $order->total; ?></span></p>
                </div>
            </div>
        </div>

        <div class="box-body clearfix">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-6 col-md-3 col-md-offset-9">
                    <p class="clearfix" style="margin:0;">Paga con <span class="badge bg-yellow pull-right">$<?php echo $order->cash->paycash; ?></span></p>
                </div>
            </div>
        </div>

    <?php } else { ?>

        <div class="box-body clearfix">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-6 col-md-3 col-md-offset-9">
                    <p class="clearfix" style="margin:0;">Total <span class="badge bg-yellow pull-right">$<?php echo $order->total; ?></span></p>
                </div>
            </div>
        </div>

    <?php } ?>

    <div class="box-footer">

        <div class="form-group">
            <div class="btn-group btn-group-justified">

                <div class="btn-group">
                    <button title="Less time" type="button" class="btn btn-flat decreaseEstimated"><i class="fa fa-minus-circle"></i></button>
                </div>

                <div class="btn-group">
                    <button  title="10 minutes" form="estimated-1" type="submit" class="btn btn-success btn-flat estimatedTime" value="10" data-action="<?php echo URL::action('OrderController@update', $order->id) ?>">10m</button>
                </div>

                <div class="btn-group">
                    <button title="20 minutes" form="estimated-2" type="submit" class="btn btn-success btn-flat estimatedTime" value="20" data-action="<?php echo URL::action('OrderController@update', $order->id) ?>">20m</button>
                </div>

                <div class="btn-group">
                    <button title="30 minutes" form="estimated-3" type="submit" class="btn btn-success btn-flat estimatedTime" value="30" data-action="<?php echo URL::action('OrderController@update', $order->id) ?>">30m</button>
                </div>

                <div class="btn-group">
                    <button title="More time" type="button" class="btn btn-flat increaseEstimated"><i class="fa fa-plus-circle"></i></button>
                </div>

            </div>
        </div>

        <div>
            <div class="dropup" style="position: relative;">
                <button id="motives-<?php echo $order->id ?>" class="btn btn-link btn-sm btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="true" type="button">No realizable <span class="caret"></span></button>
                <ul class="dropdown-menu dropup" role="menu" aria-labelledby="motives-<?php echo $order->id ?>">
                    <?php foreach ($motives as $motive) { ?>
                        <li role="presentation"><a class="rejectOrder" data-motiveid="<?php echo $motive->id ?>" data-orderid="<?php echo $order->id ?>" role="menuitem" tabindex="-1" href="#"><?php echo Lang::get('order.motives.'.$motive->motive_name.'.motive_description')?></a></li>
                    <?php } ?>
                </ul>
            </div><!-- /input-group -->
        </div>

        <span class="clearfix"></span>

    </div>

</div><!-- /.box -->