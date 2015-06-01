<?php if (!is_null($orders)) { ?>


    <div class="box-header">
        <h3 class="box-title">Mi pedido</h3>
    </div>

    <div class="order-body">

        <?php foreach ($orders as $branchId => $commerceOrder) { ?>

            <div class="commerce-order">

                <div class="commerce-info">
                    <div class="pull-left image">
                        <?php $logoPath = Config::get('cons.image.commerceLogo.path') . '/' . $commerceOrder['commerce']->id . '/' . Config::get('cons.image.commerceLogo.name'); ?>
                        <?php if (File::exists($logoPath)) { ?>
                            <img src="<?php echo Config::get('app.url') . '/' . $logoPath . '?cache=' . rand(1111, 9999) ?>" class="img-circle" alt="User Image">
                        <?php } ?>
                    </div>
                    <div class="pull-left info">
                        <p><?php echo $commerceOrder['commerce']->commerce_name; ?></p>
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

                            <?php foreach ($commerceOrder['products'] as $productIndex => $branchProduct) { ?>

                                <?php $productTotal = $branchProduct->price->price * $branchProduct->qty ?>

                                <tr>
                                    <td><a class="removeBasket" href="<?php echo URL::route('menu.preorder.remove') ?>?branch=<?php echo $branchId ?>&item=<?php echo $productIndex ?>"><i class="fa fa-trash-o" title="Quitar"></i></a></td>
                                    <td><?php echo $branchProduct->product->tags->tag_name ?></td>
                                    <td>
                                        <select class="qty" data-action="<?php echo URL::route('menu.preorder.qty') ?>?branch=<?php echo $branchId ?>&item=<?php echo $productIndex ?>">
                                            <?php for ($i = 1; $i <= 150; $i++) { ?>
                                                <option <?php echo $i == $branchProduct->qty ? 'selected="selected"' : '' ?> value="<?php echo $i ?>"><?php echo $i ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td><span class="badge bg-red">$<?php echo $productTotal ?></span></td>
                                    <td>
                                        <?php if (!$branchProduct->product->attributes->isEmpty()) { ?> 

                                            <span class="config-product popover-trigger">
                                                <i class="fa fa-cog"></i>
                                            </span>

                                            <div class="config-panel-layout">

                                                <?php
                                                $productAttributes = NULL;

                                                foreach ($branchProduct->product->attributes as $attribute) {

                                                    $productAttributes[$attribute->attribute_types->id]['attribute_type_name'] = $attribute->attribute_types->d_attribute_type;
                                                    $productAttributes[$attribute->attribute_types->id]['attributes'][$attribute->id]['id'] = $attribute->id;
                                                    $productAttributes[$attribute->attribute_types->id]['attributes'][$attribute->id]['attribute_name'] = $attribute->attribute_name;
                                                    $productAttributes[$attribute->attribute_types->id]['attributes'][$attribute->id]['aditional_price'] = $attribute->pivot->aditional_price;
                                                    $productAttributes[$attribute->attribute_types->id]['attributes'][$attribute->id]['multiple'] = $attribute->attribute_types->multiple;
                                                }

                                                if (!$branchProduct->product->rules->isEmpty()) {

                                                    foreach ($branchProduct->product->rules as $rule) {

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
                                                            <form id="form-<?php echo $productIndex . $branchProduct->id ?>" method="post" action="<?php echo URL::route('menu.preorder.attr') ?>">

                                                                <input name="branch" type="hidden" value="<?php echo $branchId ?>">
                                                                <input name="item" type="hidden" value="<?php echo $productIndex ?>">

                                                                <?php foreach ($attribute_type['attributes'] as $attribute) { ?>

                                                                    <label class="checkbox-inline">
                                                                        <?php
                                                                        $checked = '';
                                                                        if (!is_null($branchProduct->attr) && in_array($attribute['id'], $branchProduct->attr)) {
                                                                            $checked = "checked";
                                                                            $total = $total + $attribute['aditional_price'];
                                                                        }
                                                                        ?>
                                                                        <input <?php echo $checked; ?> name="attr[]" data-attr="attr-<?php echo $productIndex ?><?php echo $branchProduct->id ?><?php echo $attribute['id'] ?>" type="checkbox" value="<?php echo $attribute['id'] ?>" autocomplete="off" class="product-attr"/> <?php echo $attribute['attribute_name'] . (($attribute['aditional_price'] != NULL) ? ' +$' . $attribute['aditional_price'] : '') ?>
                                                                    </label>

                                                                <?php } ?>
                                                            </form>
                                                        </div>

                                                        <div class="panel-footer">
                                                            <button class="aplyAttr btn btn-primary btn-sm" form="form-<?php echo $productIndex . $branchProduct->id ?>" type="button">Aplicar</button>
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
        <a class="btn btn-warning btn-block" href="<?php echo URL::route('menu.preorder.confirm'); ?>">
            <strong>Ordenar</strong>
        </a>
    </div>

<?php } else { ?>

    <div class="box-header">
        <h3 class="box-title">Mi pedido</h3>
    </div>

    <div class="order-body">
        <img class="bubble" src="/assets/bubble.png"/>
    </div>

    <div class="order-body empty-basket">
        <div class="box-footer">
            <a class="btn btn-warning btn-block disabled" href="#">
                <strong>Ordenar</strong>
            </a>
        </div>
    </div>

<?php } ?>