<?php if (!is_null($orders)) { ?>

    <div class="order-body">

        <?php foreach ($orders as $branchId => $commerce) { ?>

            <div class="commerce-order clearfix">

                <div class="commerce-info">
                    <div class="pull-left image">
                        <?php $logoPath = Config::get('cons.image.commerceLogo.path') . '/' . $commerce['commerce']->id . '/' . Config::get('cons.image.commerceLogo.name'); ?>
                        <?php if (File::exists($logoPath)) { ?>
                            <img src="<?php echo Config::get('app.url') . '/' . $logoPath . '?cache=' . rand(1111, 9999) ?>" class="img-circle" alt="User Image">
                        <?php } ?>
                    </div>
                    <div class="pull-left info">
                        <p><?php echo $commerce['commerce']->commerce_name; ?></p>
                    </div>
                </div>

                <div class="form-group" style="overflow: hidden;">
                    <label for="address" class="hidden-xs col-xs-4 control-label">Productos:</label>

                    <div class="col-xs-12 control-label">
                        <table class="table table-condensed">
                            <tbody>

                                <?php $i = 1; ?>
                                <?php $total = NULL ?>

                                <?php foreach ($commerce['products'] as $productIndex => $branchProduct) { ?>

                                    <?php $productTotal = $branchProduct->price->price * $branchProduct->qty ?>

                                    <tr>
                                        <td><a class="removeBasket" href="<?php echo URL::route('menu.preorder.remove') ?>?branch=<?php echo $branchId ?>&item=<?php echo $productIndex ?>&confirm=1"><i class="fa fa-trash-o" title="Quitar"></i></a></td>
                                        <td><?php echo $branchProduct->product->tags->tag_name ?></td>
                                        <td>
                                            <select class="qty" data-action="<?php echo URL::route('menu.preorder.qty') ?>?branch=<?php echo $branchId ?>&item=<?php echo $productIndex ?>&confirm=1">
                                                <?php for ($i = 1; $i <= 150; $i++) { ?>
                                                    <option <?php echo $i == $branchProduct->qty ? 'selected="selected"' : '' ?> value="<?php echo $i ?>"><?php echo $i ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td class="text-right"><span class="badge bg-red">$<?php echo $productTotal ?></span></td>
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

                                                    <form id="form-<?php echo $productIndex . $branchProduct->id ?>" method="post" action="<?php echo URL::route('menu.preorder.attr') ?>?confirm=1">

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

                                                            <div <?php echo $rules . '"'; ?> class="box box-solid config-panel <?php echo $attribute_type['attribute_type_name']; ?>">

                                                                <div class="box-header">
                                                                    <h4>
                                                                        <?php echo Lang::get('segment.attribute_type.item.' . $attribute_type['attribute_type_name']) ?>
                                                                        <small class="rules-viewport"><?php echo $rules_viewport; ?></small>
                                                                    </h4>
                                                                </div>

                                                                <div class="box-body">
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
                                                                </div>
                                                            </div>

                                                        <?php } ?>

                                                        <input name="branch" type="hidden" value="<?php echo $branchId ?>">
                                                        <input name="item" type="hidden" value="<?php echo $productIndex ?>">
                                                        <button class="aplyAttr btn btn-primary btn-sm" form="form-<?php echo $productIndex . $branchProduct->id ?>" type="button">Aplicar</button>
                                                    </form>
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

                <form id="pay" role="form" method="post" action="<?php echo URL::route('menu.preorder.store'); ?>">
                    <?php if (Session::get('delivery')) { ?>

                        <div class="form-group" style="overflow:hidden;">

                            <?php
                            $pay = array($total);

                            $payWith = CommonEvents::payWith($total);

                            if ($payWith) {
                                $pay = array_merge($pay, $payWith);
                            }
                            ?>

                            <label class="col-xs-12 col-sm-4 control-label">Paga con:</label>

                            <div class="col-xs-12 col-sm-8">
                                <?php foreach ($pay as $p) { ?>

                                    <div class="radio-inline">
                                        <label>
                                            <input style="position: inherit;" type="radio" name="pay[<?php echo $branchId ?>]" value="<?php echo $p ?>">
                                            $<?php echo $p ?>
                                        </label>
                                    </div>

                                <?php } ?>

                                <div class="radio-inline">
                                    <label>
                                        <input style="position: inherit;" class="custom-pay" type="radio" name="pay[<?php echo $branchId ?>]" value="custom">
                                        otro
                                    </label>
                                    <input style="width:inherit;display: inline-block;" class="custom-amount form-control" style="display:inline-block;" name="amount[<?php echo $branchId ?>]" type="text" placeholder="Monto">
                                </div>
                            </div>

                        </div>

                        <span class="clearfix"></span>

                        <div class="form-group" style="overflow:hidden;">
                            <label for="address" class="col-xs-12 col-sm-4">Direcci&oacute;n:</label>
                            <div class="col-xs-10 col-sm-7">
                                <select class="form-control">

                                </select>
                            </div>
                            <div class="col-xs-2 col-sm-1" style="padding-left:0;">
                                <button type="button" data-toggle="modal" data-target="#address-modal" class="btn btn-flat btn-success" title="Agregar direcci&oacute;n"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>

                    <?php } ?>
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                </form>
            </div>

        <?php } ?>

    </div>

    <div class="box-footer clearfix">
        <button class="btn btn-warning btn-lg col-xs-12 col-md-4 col-md-offset-4" form="pay">
            <strong>Ordenar</strong>
        </button>
    </div>

<?php } ?>