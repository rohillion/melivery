<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-red">

        <?php echo View::make('header'); ?>

        <section class="content-header content-header-fixed">
            <div class="container">
                <h3>Confirmaci&oacute;n del pedido</h3>
            </div>
        </section>

        <!-- Main content -->
        <section class="container container-with-padding-top order-confirmation-page">

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

                <!-- left column -->
                <div class="col-xs-12">

                    <?php if (!is_null($orders)) { ?>

                        <form id="pay" role="form" method="post" action="<?php echo URL::route('menu.preorder.store'); ?>">

                            <?php foreach ($orders as $commerceId => $commerce) { ?>

                                <div id="order-paper" class="box box-solid form-medium">

                                    <div class="box-body order-body">

                                        <div class="commerce-order clearfix">

                                            <div class="commerce-info">
                                                <div class="pull-left image">
                                                    <img src="/assets/avatar3.png" class="img-circle" alt="User Image">
                                                </div>
                                                <div class="pull-left info">
                                                    <p><?php echo $commerce['commerce_name']; ?></p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="address" class="col-xs-4 control-label">Productos:</label>

                                                <div class="col-xs-8 control-label">
                                                    <table class="table table-condensed">
                                                        <tbody>

                                                            <?php $i = 1; ?>
                                                            <?php $total = NULL ?>

                                                            <?php foreach ($commerce['products'] as $productIndex => $product) { ?>

                                                                <?php $productTotal = $product->price * $product->qty ?>

                                                                <tr>
                                                                    <td><a href="<?php echo URL::route('menu.preorder.remove') ?>?order=<?php echo $commerceId ?>&item=<?php echo $productIndex ?>"><i class="fa fa-trash-o" title="Quitar"></i></a></td>
                                                                    <td><?php echo $product->tags->tag_name ?></td>
                                                                    <td> x <?php echo $product->qty; ?></td>
                                                                    <td class="text-right"><span class="badge bg-red">$<?php echo $productTotal ?></span></td>
                                                                    <td>
                                                                        <?php if (!$product->attributes->isEmpty()) { ?> 

                                                                            <span class="config-product popover-trigger">
                                                                                <i class="fa fa-cog"></i>
                                                                            </span>

                                                                            <div class="config-panel-layout">

                                                                                <?php
                                                                                foreach ($product->attributes as $attribute) {

                                                                                    $productAttributes[$attribute->attribute_types->id]['attribute_type_name'] = $attribute->attribute_types->d_attribute_type;
                                                                                    $productAttributes[$attribute->attribute_types->id]['attributes'][$attribute->id]['id'] = $attribute->id;
                                                                                    $productAttributes[$attribute->attribute_types->id]['attributes'][$attribute->id]['attribute_name'] = $attribute->attribute_name;
                                                                                    $productAttributes[$attribute->attribute_types->id]['attributes'][$attribute->id]['multiple'] = $attribute->attribute_types->multiple;
                                                                                }

                                                                                if (!$product->rules->isEmpty()) {

                                                                                    foreach ($product->rules as $rule) {

                                                                                        if (isset($productAttributes[$rule->pivot->attribute_type_id])) {
                                                                                            $productAttributes[$rule->pivot->attribute_type_id]['rules'][$rule->rule_type->rule_type_name] = $rule->rule_value;
                                                                                        }
                                                                                    }
                                                                                }
                                                                                ?>

                                                                                <?php foreach ($productAttributes as $attribute_type) { ?>

                                                                                    <?php
                                                                                    $rules = 'data-rules="';
                                                                                    $rules_viewport = NULL;
                                                                                    if (isset($attribute_type['rules'])) {

                                                                                        foreach ($attribute_type['rules'] as $rule_type => $rule) {
                                                                                            $rules .= $rule_type . '(' . $rule . '),';
                                                                                            $rules_viewport .= '(Elija ' . $rule . '),';
                                                                                        }
                                                                                        $rules = substr($rules, 0, -1);
                                                                                        $rules_viewport = substr($rules_viewport, 0, -1);
                                                                                    }
                                                                                    ?>

                                                                                    <div <?php echo $rules . '"'; ?> class="panel panel-default config-panel <?php echo $attribute_type['attribute_type_name']; ?>">

                                                                                        <div class="panel-heading">
                                                                                            <?php echo Lang::get('segment.attribute_type.item.' . $attribute_type['attribute_type_name']) ?>
                                                                                            <small class="rules-viewport"><?php echo $rules_viewport; ?></small>
                                                                                        </div>

                                                                                        <div class="panel-body">
                                                                                            <form id="form-<?php echo $productIndex . $product->id ?>" method="post" action="<?php echo URL::route('menu.preorder.config') ?>">

                                                                                                <input name="product[order]" type="hidden" value="<?php echo $commerceId ?>">
                                                                                                <input name="product[index]" type="hidden" value="<?php echo $productIndex ?>">
                                                                                                <input name="product[qty]" type="hidden" class="input-sm form-control" min="1" value="<?php echo $product->qty ?>">
                                                                                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                                                                                                <?php foreach ($attribute_type['attributes'] as $attribute) { ?>

                                                                                                    <label class="checkbox-inline">
                                                                                                        <input <?php echo!is_null($product->attr) && in_array($attribute['id'], $product->attr) ? "checked" : '' ?> name="product[attr][]" data-attr="attr-<?php echo $productIndex ?><?php echo $product->id ?><?php echo $attribute['id'] ?>" type="checkbox" value="<?php echo $attribute['id'] ?>" autocomplete="off" class="product-attr"/> <?php echo $attribute['attribute_name'] ?>
                                                                                                    </label>

                                                                                                <?php } ?>
                                                                                            </form>
                                                                                        </div>

                                                                                        <div class="panel-footer">
                                                                                            <button class="btn btn-primary btn-sm" form="form-<?php echo $productIndex . $product->id ?>" type="submit">Aplicar</button>
                                                                                        </div>

                                                                                    </div>

                                                                                <?php } ?>
                                                                            </div>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>

                                                                <?php $i++; ?>
                                                                <?php $total += $productTotal; ?>
                                                            <?php } ?>

                                                            <tr>
                                                                <td></td>
                                                                <td><strong>Total</strong></td>
                                                                <td></td>
                                                                <td class="text-right"><span class="badge bg-red">$<?php echo $total ?></span></td>
                                                                <td></td>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>


                                            <?php if (Session::get('delivery')) { ?>

                                                <div class="form-group">

                                                    <?php
                                                    $pay = array($total);

                                                    $payWith = CommonEvents::payWith($total);

                                                    if ($payWith) {
                                                        $pay = array_merge($pay, $payWith);
                                                    }
                                                    ?>

                                                    <label for="address" class="col-xs-4 control-label">Paga con:</label>

                                                    <div class="col-xs-8">
                                                        <?php foreach ($pay as $p) { ?>

                                                            <div class="radio-inline">
                                                                <label>
                                                                    <input type="radio" name="pay[<?php echo $commerceId ?>]" value="<?php echo $p ?>">
                                                                    $<?php echo $p ?>
                                                                </label>
                                                            </div>

                                                        <?php } ?>

                                                        <div class="radio-inline">
                                                            <label>
                                                                <input class="custom-pay" type="radio" name="pay[<?php echo $commerceId ?>]" value="custom">
                                                                otro
                                                            </label>
                                                            <input class="custom-amount" style="display:inline-block;" name="amount[<?php echo $commerceId ?>]" type="text" placeholder="Monto">
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                                                </div>

                                            <?php } ?>

                                        </div>

                                    </div>

                                </div>

                            <?php } ?>

                            <button class="btn btn-warning btn-lg col-xs-12 col-md-4 col-md-offset-4" form="pay">
                                <strong>Ordenar</strong>
                            </button>

                        <?php } ?>
                    </form>
                </div>

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <script src="/assets/application.js" type="text/javascript"></script>

        <!-- Custom -->
        <script src="/assets/menu.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                main.init();
                menu.init();
            });
        </script>

    </body>
</html>