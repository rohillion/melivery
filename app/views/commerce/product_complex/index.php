<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-dark">

        <?php echo View::make('header'); ?>

        <section class="content-header content-header-fixed">
            <div class="container">
                <h3>
                    <?php echo Lang::get('segment.product.name.plural') . ' publicados'; ?>
                    <a href="product/create" class="pull-right btn btn-success btn-flat">
                        <i class="fa fa-edit"></i> 
                        <?php echo Lang::get('common.action.edit') . ' ' . Lang::get('segment.product.title.menu'); ?>
                    </a>
                </h3>
            </div>
        </section>

        <!-- Main content -->
        <section class="container container-with-padding-top">

            <!-- Main row -->
            <div class="row">

                <?php if (!$products->isEmpty()) { ?>

                    <?php $i = 0; ?>

                    <?php foreach ($products as $product) { ?>

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

                                        <div class="clearfix">
                                            <?php $attributes = array(); ?>

                                            <?php if (!$product->attributes->isEmpty()) { ?>

                                                <?php
                                                foreach ($product->attributes as $attributeProduct) {
                                                    $attributes[$attributeProduct->attribute_types->d_attribute_type]['attributes'][$attributeProduct->id]['id'] = $attributeProduct->id;
                                                    $attributes[$attributeProduct->attribute_types->d_attribute_type]['attributes'][$attributeProduct->id]['attribute_name'] = $attributeProduct->attribute_name;
                                                }
                                                ?>

                                                <?php foreach ($attributes as $attributeTypeName => $attributeType) { ?>

                                                    <h5><?php echo $attributeTypeName; ?></h5>

                                                    <div style="height: 60px;overflow: auto;" class="well well-sm">
                                                        <?php foreach ($attributeType['attributes'] as $attribute) { ?>

                                                            <span class="label label-default" ><?php echo $attribute['attribute_name']; ?></span>

                                                        <?php } ?>
                                                    </div>
                                                <?php } ?>



                                            <?php } ?>

                                        </div>

                                    </div>

                                </div>

                                <div style="overflow: hidden;" class="box-footer">
                                    <a href="#" class="btn btn-warning btn-sm" role="button" title="Pausar publicaci&oacute;n"><i class="fa fa-pause"></i> Pausar publicaci&oacute;n</a>
                                    <a href="<?php echo URL::route('commerce.product.create', $product->id_category) ?>#<?php echo $product->subcategory_id ?>" class="btn btn-info btn-sm" role="button" title="Editar publicaci&oacute;n"><i class="fa fa-edit"></i> Editar publicaci&oacute;n</a>
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

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <?php echo View::make('footer'); ?>

    </body>
</html>