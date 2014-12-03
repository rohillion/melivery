<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-landing">

        <?php echo View::make('header'); ?>

        <?php if (!is_null($commerce)) { ?>

                                <!--<section class="cover-container">

                                </section> -->

            <div class="parallax">

                <div class="parallax-group">
                    <div class="cover-container parallax-layer parallax-layer-back">
                        <img src="http://wallfon.com/walls/others/delicious-food.jpg"/>
                        <!-- <img src="http://p1.pichost.me/i/66/1910819.jpg"/>-->
                    </div>
                </div>

                <div class="parallax-layer parallax-layer-base page">

                    <!-- Main content -->
                    <section class="">

                        <div class="content-header">
                            <div class="container">

                                Hola! <?php echo $commerce->commerce_name; ?>

                            </div>
                        </div>

                        <div class="container">

                            <!-- Main row -->
                            <div class="row">

                                <!-- left column -->
                                <div class="col-md-3">
                                    <div class="sidebar-nav affix-top" role="navigation" data-spy="affix" data-offset-top="20">

                                        <div id="order-paper" class="box box-solid">

                                            <?php if (!is_null($orders)) { ?>

                                                <div class="box-header">
                                                    <h3 class="box-title">Mi pedido</h3>
                                                </div>

                                                <div class="order-body">

                                                    <?php foreach ($orders as $branchId => $commerce) { ?>

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
                                                                                <td><a href="<?php echo URL::route('menu.preorder.remove') ?>?branch=<?php echo $branchId ?>&item=<?php echo $productIndex ?>"><i class="fa fa-trash-o" title="Quitar"></i></a></td>
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
                                                                                            $productAttributes = NULL;

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

                                                                                                            <input name="product[branch]" type="hidden" value="<?php echo $branchId ?>">
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

                                                                                                                                    <!--<form method="post" action="<?php //echo URL::route('menu.preorder.store');                ?>">
                                                                                                                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                                                                                                                        <button type="submit" class="btn btn-warning btn-block">
                                                                                                                                            <strong>Ordenar</strong>
                                                                                                                                        </button>
                                                                                                                                    </form>-->
                                                    <a class="btn btn-warning btn-block" href="<?php echo URL::route('menu.preorder.confirm'); ?>"><strong>Ordenar</strong></a>

                                                </div>

                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>

                                <!-- right column -->
                                <div class="col-xs-9">

                                    <!-- Main row -->
                                    <div class="row">

                                        <?php if (!($commerce->branches->count() > 1)) { ?>

                                            <?php if (!$commerce->branches[0]->products->isEmpty()) { ?>

                                                <?php $i = 0; ?>

                                                <?php foreach ($commerce->branches[0]->products as $product) { ?>

                                                    <?php
                                                    if ($i % 3 == 0 && $i) {
                                                        echo '</div><div class="row">';
                                                    }
                                                    ?>

                                                    <!-- left column -->
                                                    <div class="col-md-4">

                                                        <!-- general form elements -->
                                                        <div id="p<?php echo $product->id ?>" class="box box-solid">

                                                            <?php if ($product->image) { ?>
                                                                <img alt="" src="<?php echo asset('upload/product_photo/' . $product->id . '/' . $product->image); ?>" style="width: 100%; display: block;">
                                                            <?php } else { ?>
                                                                <img data-src="holder.js/100%" alt="100%" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNDIiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEyMSIgeT0iMTAwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjE1cHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjQyeDIwMDwvdGV4dD48L3N2Zz4=" style="width: 100%; display: block;">
                                                            <?php } ?>

                                                            <div class="box-body">

                                                                <div class="caption">

                                                                    <div style="overflow: hidden;">
                                                                        <h3 class="product-title truncate pull-left" title="<?php echo $product->tags->tag_name ?>"><?php echo $product->tags->tag_name ?></h3>
                                                                        <h3 class="pull-right"><?php echo ' $' . $product->price; ?></h3>
                                                                    </div>

                                                                </div>

                                                            </div>

                                                            <div style="overflow: hidden;" class="box-footer">
                                                                <form method="post" action="<?php echo URL::route('menu.preorder.add') ?>">

                                                                    <input name="product[id]" type="hidden" value="<?php echo $product->id ?>" />
                                                                    <input name="product[branch]" type="hidden" value="<?php echo $product->branch_id ?>" />
                                                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                                                                    <div class="row pull-left col-lg-8">
                                                                        <div class="input-group">
                                                                            <span class="input-group-btn">
                                                                                <button class="btn btn-primary btn-sm" type="submit">Agregar</button>
                                                                            </span>
                                                                            <input name="product[qty]" type="number" class="input-sm form-control" min="1" value="1">
                                                                        </div><!-- /input-group -->
                                                                    </div><!-- /.col-lg-6 -->
                                                                </form>
                                                            </div>

                                                        </div><!-- /.box -->

                                                    </div><!--/.col (left) -->
                                                    <?php $i++; ?>
                                                <?php } ?>
                                            <?php } else { ?>

                                                <section class="container">
                                                    <div class="well well-sm text-center">A&uacute;n no tienes productos cargados en el men&uacute;.</div>
                                                </section>

                                            <?php } ?>

                                        <?php } ?>

                                    </div><!-- /.row (main row) -->

                                </div><!--/.col (left) -->

                            </div><!-- /.row (main row) -->
                        </div>

                    </section><!-- /.content -->
                </div>


            </div>


        <?php } else { ?>

            <?php echo Request::segment(1) ?> no existe, registralo!
        <?php } ?>

        <?php echo View::make('footer'); ?>

    </body>
</html>