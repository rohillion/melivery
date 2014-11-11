<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-red">

        <?php echo View::make('header'); ?>
        
        <section class="content-header content-header-fixed">
            <div class="container">


            </div>
        </section>

        <!-- Main content -->
        <section class="container container-with-padding-top">

            <!-- Main row -->
            <div class="row">

                <!-- left column -->
                <div class="col-xs-12 col-md-offset-3 col-md-6">

                    <div class="sidebar-nav affix-top" role="navigation" data-spy="affix" data-offset-top="20">

                        <div id="order-paper" class="box box-solid">

                            <?php if (!is_null($orders)) { ?>

                                <div class="box-header">
                                    <h3 class="box-title">Confirmar pedido</h3>
                                </div>

                                <div class="order-body">

                                    <?php foreach ($orders as $commerceId => $commerce) { ?>

                                        <div class="commerce-order">

                                            <div class="commerce-panel">
                                                <div class="pull-left image">
                                                    <img src="assets/avatar3.png" class="img-circle" alt="User Image">
                                                </div>
                                                <div class="pull-left info">
                                                    <p><?php echo $commerce['commerce_name']; ?></p>
                                                </div>
                                            </div>

                                            <div class="commerce-order-products">

                                                <table class="table table-condensed">
                                                    <tbody>
                                                        <tr>
                                                            <th style="width: 10px">&nbsp;</th>
                                                            <th>Producto</th>
                                                            <th>Cant.</th>
                                                            <th style="width: 40px">Precio</th>
                                                            <th style="width: 10px"></th>
                                                        </tr>

                                                        <?php $i = 1; ?>
                                                        <?php $total = NULL ?>

                                                        <?php foreach ($commerce['products'] as $productIndex => $product) { ?>

                                                            <?php $productTotal = $product->price * $product->qty ?>

                                                            <tr>
                                                                <td><a href="<?php echo URL::route('menu.preorder.remove') ?>?order=<?php echo $commerceId ?>&item=<?php echo $productIndex ?>"><i class="fa fa-trash-o" title="Quitar"></i></a></td>
                                                                <td><?php echo $product->tags->tag_name ?></td>
                                                                <td> x <?php echo $product->qty; ?></td>
                                                                <td><span class="badge bg-red">$<?php echo $productTotal ?></span></td>
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
                                                            <td><span class="badge bg-red">$<?php echo $total ?></span></td>
                                                            <td></td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </div>

                                        </div>

                                    <?php } ?>

                                </div>

                                <div class="box-footer">

                                    <!--<form method="post" action="<?php //echo URL::route('menu.preorder.store');   ?>">
                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                        <button type="submit" class="btn btn-warning btn-block">
                                            <strong>Ordenar</strong>
                                        </button>
                                    </form>-->
                                    <a class="btn btn-warning btn-block" href="<?php echo URL::route('menu.preorder.store'); ?>"><strong>Ordenar</strong></a>

                                </div>

                            <?php } ?>
                        </div>
                    </div>
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