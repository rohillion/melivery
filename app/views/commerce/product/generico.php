<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-dark">

        <?php echo View::make('header'); ?>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">

                <?php $active = 'active'; ?>

                <?php foreach ($category->subcategories as $subcategory) { ?>

                    <?php if ($subcategory->active) { ?>

                        <li class="<?php echo $active; ?>"><a href="#<?php echo $subcategory->id ?>" data-toggle="tab"><?php echo $subcategory->subcategory_name ?></a></li>

                        <?php $active = ''; ?>

                    <?php } ?>

                <?php } ?>

                <li class="pull-left header"><i class="fa fa-list"></i> Subcategor&iacute;a:</li>

            </ul>

            <div style="overflow:hidden;" class="tab-content">
                <?php $active = 'active'; ?>
                <?php foreach ($category->subcategories as $subcategory) { ?>

                    <?php if ($subcategory->active) { ?>

                        <div class="tab-pane <?php echo $active; ?>" id="<?php echo $subcategory->id ?>">

                            <p class="bg-info text-right">
                                <small class="text-left"></small>
                                <a class="btn btn-flat" data-toggle="modal" data-target="#custom-tag-modal-<?php echo $subcategory->id ?>" href="#"><i class="fa fa-plus"></i> No ves tu producto?</a>
                            </p>


                            <?php $active = ''; ?>
                            <form enctype="multipart/form-data" class="" method="post" action="<?php echo URL::action('ProductController@store') ?>">
                                <div class="list-group">

                                    <?php foreach ($subcategory->tags as $tag) { ?>

                                        <?php
                                        $checked = NULL;
                                        $price = NULL;
                                        $attributesProduct = null;
                                        $rulesProduct = null;

                                        if (!$category->products->isEmpty()) {

                                            foreach ($category->products as $product) {

                                                if ($product->tags->id == $tag->id) {

                                                    $checked = 'checked';
                                                    $price = $product->price;

                                                    $attributesProduct = $product->attributes->toArray();
                                                    $rulesProduct = $product->rules->toArray();

                                                    break;
                                                }
                                            }
                                        }
                                        ?>

                                        <div href="#" class="list-group-item">
                                            <input <?php echo $checked ?> class="add hidden-checkbox" type="checkbox" id="product_<?php echo $tag->id; ?>" name="product[<?php echo $tag->id; ?>][enable]"/>
                                            <label lang="es" for="product_<?php echo $tag->id; ?>" class="switch-checkbox pull-right btn btn-flat btn-xs"></label>

                                            <h4 class="list-group-item-heading product-title"><?php echo $tag->tag_name; ?></h4>

                                            <div class="switchable-wrapper">
                                                <div class="list-group-item-text">
                                                    <div class="form-group">
                                                        <label for="">Precio</label>
                                                        <input type="text" class="form-control" name="product[<?php echo $tag->id; ?>][price]" value="<?php echo $price; ?>"/>
                                                    </div>
                                                </div>

                                                <div class="list-group-item-text">
                                                    <div class="form-group">
                                                        <label for="">Foto</label>
                                                        <input type="file" class="form-control" name="product[<?php echo $tag->id; ?>][file]" value="<?php echo $price; ?>"/>
                                                    </div>
                                                </div>

                                                <div class="list-group-item-text">
                                                    <div class="form-group">

                                                        <?php if (!$subcategory->attributes->isEmpty()) { ?>

                                                            <?php
                                                            foreach ($subcategory->attributes as $attribute) {

                                                                foreach ($attribute->attribute_types->rules as $rule) {

                                                                    if (!$rule->pivot->required) {

                                                                        $subcategoryAttributes[$attribute->attribute_types->d_attribute_type]['rule_type'][$rule->rule_type->rule_type_name]['id'] = $rule->rule_type->id;
                                                                        $subcategoryAttributes[$attribute->attribute_types->d_attribute_type]['rule_type'][$rule->rule_type->rule_type_name]['rules'][$rule->id]['id'] = $rule->id;
                                                                        $subcategoryAttributes[$attribute->attribute_types->d_attribute_type]['rule_type'][$rule->rule_type->rule_type_name]['rules'][$rule->id]['rule_value'] = $rule->rule_value;
                                                                        $subcategoryAttributes[$attribute->attribute_types->d_attribute_type]['rule_type'][$rule->rule_type->rule_type_name]['rules'][$rule->id]['required'] = $rule->pivot->required;
                                                                    }
                                                                }

                                                                $subcategoryAttributes[$attribute->attribute_types->d_attribute_type]['id'] = $attribute->attribute_types->id;
                                                                $subcategoryAttributes[$attribute->attribute_types->d_attribute_type]['attributes'][$attribute->id]['id'] = $attribute->id;
                                                                $subcategoryAttributes[$attribute->attribute_types->d_attribute_type]['attributes'][$attribute->id]['attribute_name'] = $attribute->attribute_name;
                                                                $subcategoryAttributes[$attribute->attribute_types->d_attribute_type]['attributes'][$attribute->id]['multiple'] = $attribute->attribute_types->multiple;
                                                                $subcategoryAttributes[$attribute->attribute_types->d_attribute_type]['attributes'][$attribute->id]['valuable'] = $attribute->attribute_types->valuable;
                                                            }
                                                            ?>

                                                            <?php foreach ($subcategoryAttributes as $d_attribute_type => $attributeType) { ?>

                                                                <input type="hidden" name="product[<?php echo $tag->id; ?>][attribute_type][id]" value="<?php echo $attributeType['id']; ?>"/>

                                                                <label for=""><?php echo $d_attribute_type ?></label>
                                                                <br>

                                                                <?php if (isset($attributeType['rule_type'])) { ?>

                                                                    <div class="form-inline">
                                                                        <?php foreach ($attributeType['rule_type'] as $rule_type_name => $rule_type) { ?>

                                                                            <label for=""><?php echo $rule_type_name ?></label>

                                                                            <select class="form-control" id="" name="product[<?php echo $tag->id; ?>][attribute_type][rules][<?php echo $rule_type['id']; ?>]">

                                                                                <?php foreach ($rule_type['rules'] as $rule) { ?>

                                                                                    <?php $selected = ''; ?>

                                                                                    <?php
                                                                                    if (!is_null($rulesProduct)) {

                                                                                        foreach ($rulesProduct as $ruleProduct) {

                                                                                            if ($ruleProduct['id'] == $rule['id']) {

                                                                                                $selected = 'selected';
                                                                                                break;
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                    ?>

                                                                                    <option <?php echo $selected; ?> value="<?php echo $rule['id']; ?>"><?php echo $rule['rule_value']; ?></option>

                                                                                <?php } ?>

                                                                            </select>

                                                                        <?php } ?>
                                                                    </div>

                                                                <?php } ?>

                                                                <div>
                                                                    <?php foreach ($attributeType['attributes'] as $attribute) { ?>

                                                                        <?php $checked = ''; ?>

                                                                        <?php
                                                                        if (!is_null($attributesProduct)) {

                                                                            foreach ($attributesProduct as $attributeProduct) {

                                                                                if ($attributeProduct['id'] == $attribute['id']) {

                                                                                    $checked = 'checked';
                                                                                    break;
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>

                                                                        <div class="checkbox">
                                                                            <label lang="es" for="attr_<?php echo $tag->id . $attribute['id']; ?>">
                                                                                <input <?php echo $checked ?> id="attr_<?php echo $tag->id . $attribute['id']; ?>" type="checkbox" name="product[<?php echo $tag->id; ?>][attribute_type][attr][]" value="<?php echo $attribute['id']; ?>"/>
                                                                                <?php echo $attribute['attribute_name']; ?>
                                                                            </label>
                                                                        </div>

                                                                    <?php } ?>
                                                                </div>
                                                            <?php } ?>

                                                        <?php } ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php } ?>

                                </div>

                                <input type="hidden" name="category" value="<?php echo $category->id; ?>">
                                <input type="hidden" name="subcategory" value="<?php echo $subcategory->id; ?>">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                <button type="submit" class="pull-right btn btn-primary">Guardar cambios</button>

                            </form>
                        </div><!-- /.tab-pane -->



                    <?php } ?>

                    <div id="custom-tag-modal-<?php echo $subcategory->id ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md">
                            <form method="post" action="<?php echo URL::route('commerce.customtag.create') ?>" class="custom-tag-form">
                                <div class="modal-content">

                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label for="">Nombre del producto</label>
                                            <input placeholder="Ej. Sandwich de milanesa" class="form-control" type="text" name="tag"/>
                                        </div>

                                        <div class="form-group">

                                            <label for="">Categor&iacute;a</label>

                                            <select disabled class="form-control">
                                                <option selected><?php echo $category->category_name; ?></option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="">Subcategor&iacute;a</label>
                                            <select class="form-control" id="" name="subcategory">

                                                <?php foreach ($category->subcategories as $customTagSubcategory) { ?>

                                                    <option <?php echo $customTagSubcategory->id == $subcategory->id ? 'selected' : ''; ?> value="<?php echo $customTagSubcategory->id; ?>"><?php echo $customTagSubcategory->subcategory_name; ?></option>
                                                <?php } ?>

                                            </select>
                                        </div>

                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    </div>

                                    <div class="modal-footer">
                                        <input class="btn btn-primary btn-flat" type="submit" value="Guardar"/>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>

                <?php } ?>
            </div><!-- /.tab-content -->

        </div>

        <?php echo View::make('footer'); ?>

    </body>
</html>