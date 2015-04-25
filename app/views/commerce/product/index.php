<!DOCTYPE html>
<html lang="<?php echo App::getLocale(); ?>">
    <?php echo View::make('head'); ?>
    <body class="skin-dark" data-spy="scroll" data-target="#scrollControl" data-offset="150">

        <?php echo View::make('header'); ?>

        <section class="content-header content-header-fixed">
            <div class="container">
                <h3>
                    <span class="section-title" data-addproduct="<?php echo Lang::get('common.action.add') . ' ' . Lang::get('segment.product.name.single'); ?>" data-publishedproduct="<?php echo Lang::get('segment.product.name.plural') . ' publicados'; ?>" ><?php echo Lang::get('segment.product.name.plural') . ' publicados'; ?></span>

                    <a id="addProduct" href="#productForm" class="pull-right btn btn-success btn-flat" data-loading-text="Cargando..." data-close-text="Cerrar" data-cancel-text="Cancelar"><!-- TODO. Lang -->
                        <?php echo Lang::get('common.action.add') . ' ' . Lang::get('segment.product.name.single'); ?>
                    </a>
                </h3>
            </div>
        </section>

        <!-- Main content -->
        <section class="container container-with-padding-top">

            <!-- Main row -->
            <div class="row">

                <?php if (!empty($productsByCategory)) { ?>

                    <!-- left column -->
                    <div class="col-xs-4" >
                        <div class="page-header">Categor&iacute;as</div><!-- TODO. Lang -->
                        <div id="scrollControl" data-spy="affix" data-offset-top="90">
                            <!-- general form elements -->
                            <div class="box box-solid">

                                <ul class="list-group nav">

                                    <?php foreach ($productsByCategory as $category) { ?>

                                        <li class="list-group-item">
                                            <a href="#c<?php echo $category['data']->id; ?>" class="scrollTo">
                                                <?php echo $category['data']->category_name; ?>
                                            </a>
                                        </li>

                                    <?php } ?>

                                </ul>
                            </div><!-- /.box -->

                        </div><!--/.col (left) -->

                    </div>

                    <!-- left column -->
                    <div class="col-xs-8">

                        <?php foreach ($productsByCategory as $category) { ?>

                            <div id="c<?php echo $category['data']->id; ?>" class="page-header"><?php echo $category['data']->category_name; ?></div>

                            <div class="row">

                                <?php $i = 0; ?>

                                <?php foreach ($category['branchProducts'] as $branchproduct) { ?>

                                    <?php
                                    /*if ($i % 3 == 0 && $i) {
                                        echo '</div><div class="row">';
                                    }*/
                                    ?>

                                    <!-- left column -->
                                    <div class="col-xs-12">

                                        <!-- general form elements -->
                                        <div id="p<?php echo $branchproduct->product->id ?>" class="box box-solid">

                                            <div class="col-xs-4">
                                                <div class="row">
                                                    <?php if ($branchproduct->product->image) { ?>
                                                        <img alt="" src="<?php echo asset('upload/product_image/' . $branchproduct->product->image); ?>" style="width: 100%; display: block;">
                                                    <?php } else { ?>
                                                        <img data-src="holder.js/100%" alt="100%" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNDIiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEyMSIgeT0iMTAwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjE1cHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjQyeDIwMDwvdGV4dD48L3N2Zz4=" style="width: 100%; display: block;">
                                                    <?php } ?>
                                                </div>
                                            </div>

                                            <div class="col-xs-8">

                                                <div class="caption">

                                                    <div style="overflow: hidden;">
                                                        <h3 class="product-title truncate pull-left" title="<?php echo $branchproduct->product->tags->tag_name ?>"><?php echo $branchproduct->product->tags->tag_name ?></h3>
                                                        <h3 class="pull-right">
                                                            <?php
                                                            echo $branchproduct->prices->count() > 1 ? 'Desde' : '';
                                                            echo ' $' . $branchproduct->prices[0]->price;
                                                            ?>
                                                        </h3>
                                                    </div>

                                                    <div class="clearfix">
                                                        <?php $attributes = array(); ?>

                                                        <?php if (!$branchproduct->product->attributes->isEmpty()) { ?>

                                                            <?php
                                                            foreach ($branchproduct->product->attributes as $attributeProduct) {
                                                                $attributes[$attributeProduct->attribute_types->d_attribute_type]['attributes'][$attributeProduct->id]['id'] = $attributeProduct->id;
                                                                $attributes[$attributeProduct->attribute_types->d_attribute_type]['attributes'][$attributeProduct->id]['attribute_name'] = $attributeProduct->attribute_name;
                                                                $attributes[$attributeProduct->attribute_types->d_attribute_type]['attributes'][$attributeProduct->id]['aditional_price'] = $attributeProduct->pivot->aditional_price;
                                                            }
                                                            ?>

                                                            <?php foreach ($attributes as $attributeTypeName => $attributeType) { ?>

                                                                <h5><?php echo Lang::get('segment.attribute_type.item.' . $attributeTypeName); ?></h5>

                                                                <div style="height: 60px;overflow: auto;" class="well well-sm">
                                                                    <?php foreach ($attributeType['attributes'] as $attribute) { ?>

                                                                        <span class="label label-default" ><?php echo $attribute['attribute_name']; ?> <?php echo $attribute['aditional_price'] > 0 ? ' + $' . $attribute['aditional_price'] : ''; ?></span>

                                                                    <?php } ?>
                                                                </div>
                                                            <?php } ?>

                                                        <?php } ?>

                                                    </div>

                                                </div>
                                            </div>

                                            <span class="clearfix"></span>

                                            <div class="box-footer">

                                                <span data-toggle="tooltip" data-placement="auto" title="" data-original-title="Despublicar producto">
                                                    <a href="<?php echo URL::route('commerce.product.delete', $branchproduct->id) ?>" class="deleteProduct fa fa-circle text-danger dot-button"></a>
                                                </span>

                                                <?php
                                                $statusClass = 'text-warning';
                                                $statusLabel = 'Pausar publicaci&oacute;n';
                                                if (!$branchproduct->active) {
                                                    $statusClass = 'text-success';
                                                    $statusLabel = 'Reanudar publicaci&oacute;n';
                                                }
                                                ?>

                                                <span data-toggle="tooltip" data-placement="auto" title="" data-original-title="<?php echo $statusLabel?>">
                                                    <a style="margin-left:10px;" href="<?php echo URL::route('commerce.product.changestatus', $branchproduct->id) ?>" class="statusProduct fa fa-circle <?php echo $statusClass?> dot-button"></a>
                                                </span>

                                                <span data-toggle="tooltip" data-placement="auto" title="" data-original-title="Editar publicaci&oacute;n">
                                                    <a style="margin-left:10px;" href="<?php echo URL::route('commerce.product.view', $branchproduct->id) ?>" class="editProduct fa fa-circle text-info dot-button"></a>
                                                </span>

                                                <?php if (!$branchproduct->active) { ?>
                                                    <span data-toggle="tooltip" data-placement="left" title="" data-original-title="Publicaci&oacute;n en pausa" class="fa-stack pull-right">
                                                        <i class="fa fa-circle fa-stack-2x"></i>
                                                        <i class="fa fa-pause fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                <?php } ?>
                                            </div>

                                        </div><!-- /.box -->

                                    </div><!--/.col (left) -->
                                    <?php $i++; ?>
                                <?php } ?>
                            </div>
                        <?php } ?>

                    </div>

                <?php } else { ?>

                    <section class="container">
                        <div class="well well-sm text-center">A&uacute;n no tienes productos cargados en el men&uacute;.</div><!-- TODO Lang -->
                    </section>

                <?php } ?>

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <div id="product-form-fixed">

            <div class="container">

                <section class="col-xs-4" id="product-form-container">

                    <div id="attribute-panel-model" class="form-group attribute-panel hidden">

                        <label class="attribute-type-name" for=""></label>

                        <div class="well well-sm">

                            <div class="row">
                                <div class="col-xs-6">
                                    <label class="sublabel text-muted" for="">Cant. M&iacute;nima <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="Cantidad m&iacute;nima de opciones que puede elegir el comensal. Por ejemplo, para gustos de helado, si se establece un m&iacute;nimo de 2, el comensal deber&aacute; elegir al menos dos gustos."></i></label><!-- TODO Lang -->
                                </div>
                                <div class="col-xs-6">
                                    <label class="sublabel text-muted" for="">Cant. M&aacute;xima <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="Cantidad m&aacute;xima de opciones que puede elegir el comensal. Por ejemplo, para gustos de helado, si se establece un m&aacute;ximo de 6, el comensal podr&aacute; elegir hasta seis gustos."></i></label><!-- TODO Lang -->
                                </div>
                            </div>

                            <div class="row">
                                <div class="overflow-hidden form-group">
                                    <div class="col-xs-6">
                                        <select class="form-control min_limit">

                                            <option value="0">No hay m&iacute;nimo</option><!-- TODO. Lang. -->

                                            <?php foreach ($rules['min_limit'] as $rule) { ?>
                                                <option value="<?php echo $rule->id; ?>"><?php echo $rule->rule_value; ?></option><!-- TODO. Lang. -->
                                            <?php } ?>

                                        </select>
                                    </div>
                                    <div class="col-xs-6">
                                        <select class="form-control max_limit">
                                            <option value="0">No hay m&aacute;ximo</option><!-- TODO. Lang. -->
                                            <?php foreach ($rules['max_limit'] as $rule) { ?>
                                                <option value="<?php echo $rule->id; ?>"><?php echo $rule->rule_value; ?></option><!-- TODO. Lang. -->
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-6">
                                    <label class="sublabel text-muted" for="">Nombre</label><!-- TODO Lang -->
                                </div>
                                <div class="col-xs-5">
                                    <label class="sublabel text-muted" for="">Precio adicional <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="Precio adicional para una opci&oacute;n en particular. Por ejemplo, los barquillos para helado pueden costar $5 extra sobre el precio base del helado."></i></label><!-- TODO Lang -->
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group clearfix">
                                    <div class="col-xs-6" style="position:relative;">
                                        <input placeholder="Ej. Dulce de leche, Tomate" class="form-control attributeTypeahead" type="text"/><!-- TODO Lang -->
                                        <input class="selectedAttribute" type="hidden"/>
                                        <div class="defaultSuggestion attributeSuggestions">
                                            <div class="tt-dataset-defaults">
                                                <span class="tt-suggestions" style="display: block;"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-5">
                                        <input placeholder="Valor" class="form-control attributeAditionalPrice" type="text"/><!-- TODO Lang -->
                                    </div>
                                    <div class="pull-left text-right clearfix">
                                        <button title="Agregar" type="button" class="btn btn-link add-attribute"><i class="fa fa-plus-circle text-success"></i></button><!-- TODO Lang -->
                                    </div>
                                </div>
                            </div>

                            <div class="selected-attributes-panel"></div>
                        </div>

                    </div><!-- attribute panel model -->

                    <div id="price-size-row-model" class="overflow-hidden price-size-row hidden form-group">
                        <div class="col-xs-6">
                            <input placeholder="Ej. Mediano" class="form-control multipriceSize" type="text" name="multiprice[size][]"/><!-- TODO Lang -->
                        </div>
                        <div class="col-xs-5">
                            <input placeholder="Valor" class="form-control multipricePrice" type="text" name="multiprice[price][]"/><!-- TODO Lang -->
                        </div>
                        <div class="pull-left text-right">
                            <button type="button" class="btn btn-link remove-price-size"><i class="fa fa-close text-danger"></i></button>
                        </div>
                    </div>

                    <form id="productForm" class="form-group" method="post" action="<?php echo URL::action('ProductController@store') ?>" enctype="multipart/form-data">

                        <div id="categoryConfPanel" class="confPanel current">
                            <div class="form-group">
                                <label for="category">A que categor&iacute;a desea agregar el producto?</label><!-- TODO Lang -->
                                <ul id="category" class="list-group nav">
                                    <?php foreach ($categories as $category) { ?>
                                        <li class="list-group-item">
                                            <label class="reset selectable" for="c-opt<?php echo $category->id; ?>">
                                                <?php echo $category->category_name; ?>
                                                <input id="c-opt<?php echo $category->id; ?>" class="hidden" type="radio" name="category" value="<?php echo $category->id; ?>"/>
                                            </label>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>

                        <div id="subcategoryConfPanel" class="confPanel">
                            <div class="form-group">
                                <label for="subcategory">Subcategor&iacute;a</label><!-- TODO Lang -->
                                <ul id="subcategory" class="list-group nav"></ul>
                            </div>
                        </div>

                        <div id="productConfPanel" class="confPanel">
                            <div class="form-group" style="position:relative;">
                                <label for="tag">Nombre</label><!-- TODO Lang -->
                                <input id="tagName" placeholder="Ej. Empanada de carne, 1/2 Kilo de helado" class="form-control" type="text" name="tagName"/><!-- TODO Lang -->
                                <input id="tag" name="tag" type="hidden"/>

                                <div id="tagSuggestions" class="defaultSuggestion">
                                    <div class="tt-dataset-defaults">
                                        <span class="tt-suggestions" style="display: block;"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="position:relative;">
                                <label for="image">Foto</label><!-- TODO Lang -->
                                <input id="image" class="form-control" type="file" name="image" data-path="<?php echo URL::route('commerce.product.imagetmp'); ?>"/>
                                <input id="imageChanged" type="hidden" name="imagechanged" value="0">
                            </div>

                            <div class="form-group">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="multisize" id="multisize"> M&uacute;ltiples tama&ntilde;os<!-- TODO Lang -->
                                </label>
                            </div>

                            <div id="singleprice" class="form-group">
                                <label for="price">Precio</label><!-- TODO Lang -->
                                <input id="price" placeholder="Valor" class="form-control" type="text" name="price"/>
                            </div>

                            <div id="multiprice" class="form-group">

                                <div class="row">
                                    <div class="col-xs-6">
                                        <label for="">Tama&ntilde;o</label><!-- TODO Lang -->
                                    </div>
                                    <div class="col-xs-6">
                                        <label for="">Precio</label><!-- TODO Lang -->
                                    </div>
                                </div>

                                <div id="price-size" class="row"></div>

                                <button type="button" id="add-price-size" class="btn btn-link">Agregar tama&ntilde;o</button><!-- TODO Lang -->
                            </div>
                        </div>

                        <div id="attribute-panel-container" class="confPanel"></div>

                    </form>

                    <div class="bottom-action">
                        <div class="form-group">
                            <button id="nextConfPanel" type="button" class="btn btn-flat btn-success btn-block">Siguiente</button><!-- TODO Lang -->
                            <button id="saveProduct" type="button" class="btn btn-flat btn-success btn-block hidden" data-loading-text="Guardando...">Guardar</button>
                        </div>
                        <button id="prevConfPanel" type="button" class="btn btn-link" style="visibility:hidden;">Volver</button><!-- TODO Lang -->
                    </div>

                </section>

                <section class="col-xs-8" id="product-preview-container">

                    <div id="frontPreview" class="col-xs-6 col-xs-offset-3">

                        <!-- general form elements -->
                        <div class="box box-solid">

                            <img data-src="holder.js/100%" alt="100%" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNDIiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEyMSIgeT0iMTAwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjE1cHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjQyeDIwMDwvdGV4dD48L3N2Zz4=" style="width: 100%; display: block;">

                            <div class="box-body">

                                <div class="caption">

                                    <div style="overflow: hidden;">
                                        <h3 class="product-title truncate pull-left" title="Nuevo plato">Nuevo producto</h3>
                                        <h3 class="product-price pull-right"> $0</h3>
                                    </div>

                                </div>

                            </div>

                        </div><!-- /.box -->

                    </div>

                </section>

            </div>
        </div>

        <?php echo View::make('footer'); ?>

        <script type="text/javascript" src="<?php echo URL::action('JsLocalizationController@createJsMessages'); ?>"></script>

    </body>
</html>