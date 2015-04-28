<?php
$products='';
foreach ($order->order_products as $order_product) {
    $products .= $order_product->branch_product->product->tags->tag_name . ', ';
}
?>
<div class="history-order order-helper <?php echo isset($order->new) ? 'new-history' : '' ?>" data-id="<?php echo $order['id'] ?>" data-products="<?php echo $products ?>" data-client="<?php echo $order['user']['name'] ?>" data-dealer="<?php echo!$order->branch_dealer->isEmpty() ? $order->branch_dealer[0]->dealer_name : ''; ?>" data-paycash="<?php echo $order['cash']['paycash']; ?>">
    <i class="fa fa-paperclip"></i>
    <div class="box box-<?php echo $order['status_id'] == Config::get('cons.order_status.done') ? 'success' : 'danger'; ?> order-helper-client">
        <div class="client-name"><?php echo $order['user']['name'] ?></div>
        <div class="order-change">
            Total <strong>$<?php echo $order['total'] ?></strong>
        </div>
    </div>
</div>