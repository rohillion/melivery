<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-landing">

        <?php echo View::make('header'); ?>

        <?php if (!is_null($commerce)) { ?>

            <div class="parallax-group">
                <?php
                $coverPath = Config::get('cons.image.commerceCover.path') . '/' . $commerce->id . '/' . Config::get('cons.image.commerceCover.name');
                $cover = File::exists($coverPath) ? $coverPath . '?cache=' . rand(1111, 9999) : Config::get('cons.image.commerceCover.tmp');
                ?>
                <div class="cover-container" style="background-image: url(<?php echo $cover ?>)"></div>
            </div>

            <!-- Main content -->
            <section class="page">

                <div id="content-header" class="content-header">
                    <div class="container">
                        <div id="filter-container" class="row">

                            <div class="col-xs-6 col-sm-5 col-md-4 col-lg-3 commerce-name">
                                <?php echo $commerce->commerce_name; ?>
                            </div>

                            <div class="filter-options col-xs-6 col-sm-7 col-md-8 col-lg-9">

                                <?php if (!is_null($commerce->branch)) { ?>
                                    <div id="branch-select" class="pull-left hidden-xs">
                                        <div href="#" id="branch-mask" class="filter-mask <?php echo $branches->count() > 1 ? 'popover-trigger' : '' ?>">

                                            <div class="mask pull-left">
                                                <?php echo $commerce->branch->street; ?>
                                            </div>

                                            <?php if ($branches->count() > 1) { ?>

                                                <div class="caret-wrapp pull-left">
                                                    <span class="caret"></span>
                                                </div>

                                                <div id="branch-list" class="popover-wrapper">
                                                    <ul class="list-group organized-list">

                                                        <span role="presentation" class="dropdown-header">Otras sucursales</span>

                                                        <?php foreach ($branches as $branch) { ?>
                                                            <?php if ($commerce->branch->id != $branch->id) { ?>

                                                                <li class="list-group-item">
                                                                    <a class="branch-item" href="?branch=<?php echo $branch->id; ?>"><?php echo $branch->street; ?></a>
                                                                </li>

                                                            <?php } ?>
                                                        <?php } ?>

                                                    </ul>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if (isset($productByCategory)) { ?>
                                    <div id="category-select" class="select-mask pull-right">
                                        <div href="#" id="category-mask" class="filter-mask popover-trigger margin-less">

                                            <div class="mask pull-left">
                                                <?php echo array_values($productByCategory)[0]['data']->category_name ?>
                                            </div>

                                            <div class="caret-wrapp pull-left">
                                                <span class="caret"></span>
                                            </div>

                                            <div id="category-list" class="popover-wrapper">
                                                <ul class="list-group organized-list">

                                                    <span role="presentation" class="dropdown-header">Categor&iacute;as</span>

                                                    <?php foreach ($productByCategory as $category) { ?>
                                                        <?php //if ($commerce->branch->id != $branch->id) { ?>

                                                        <li class="list-group-item">
                                                            <a class="category-item popover-item scrollTo" href="#<?php echo str_replace(' ', '_', $category['data']->category_name); ?>" data-label="<?php echo $category['data']->category_name; ?>"><?php echo $category['data']->category_name; ?></a>
                                                        </li>

                                                        <?php //} ?>
                                                    <?php } ?>

                                                </ul>
                                            </div>

                                        </div>
                                    </div>
                                <?php } ?>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="container">

                    <!-- Main row -->
                    <div class="row">

                        <!-- left column -->
                        <div class="col-xs-5 col-md-4 col-lg-3">
                            <div id="order-paper">
                                <div class="sidebar-nav box box-solid" role="navigation">
                                    <?php include 'basket.php'; ?>
                                </div>
                            </div>
                        </div>

                        <!-- right column -->
                        <div id="product-container" class="col-xs-12 col-sm-7 col-md-8 col-lg-9">

                            <?php if (!is_null($commerce->branch)) { ?>

                                <?php if (!$commerce->branch->products->isEmpty()) { ?>

                                    <?php foreach ($productByCategory as $category) { ?>


                                        <?php $i = 0; ?>
                                        <?php $count = count($category['products']); ?>
                                        <?php $even = is_int($count / 2); ?>

                                        <?php
                                        if ($even) {
                                            $lefties = $count % 3;
                                        } else {
                                            if (!is_int($count / 3)) {
                                                $lefties = $count % 3;
                                            }
                                        }
                                        ?>

                                        <!-- Category wrapper -->
                                        <div id="<?php echo str_replace(' ', '_', $category['data']->category_name); ?>">

                                            <div class="page-header"><?php echo $category['data']->category_name ?></div>

                                            <!-- 3 Products row -->
                                            <div class="row">

                                                <?php
                                                foreach ($category['products'] as $branchProduct) {

                                                    $cols = 'col-xs-12 col-md-6 col-lg-4';

                                                    if ($count == 1) {
                                                        $cols = 'col-xs-12 col-md-6';
                                                    } elseif ($count == 2) {
                                                        $cols = 'col-xs-12 col-md-6';
                                                    } elseif ($count == 3) {
                                                        if ($i == 3)
                                                            $cols = 'col-xs-12 col-lg-4';
                                                    } else {//mayor a 3
                                                        if ($even) {

                                                            if ($count - $i <= $lefties) {
                                                                if ($lefties > 1) {
                                                                    $cols = 'col-xs-12 col-md-6 col-lg-6';
                                                                } else {
                                                                    $cols = 'col-xs-12 col-md-6 col-lg-6';
                                                                }
                                                            }
                                                        } elseif (isset($lefties)) {

                                                            if ($count - $i <= $lefties) {
                                                                if ($lefties > 1) {
                                                                    $cols = 'col-xs-12 col-md-6 col-lg-6';
                                                                    if ($count - $i === 1)
                                                                        $cols = 'col-xs-12 col-md-12 col-lg-6';
                                                                } else {
                                                                    $cols = 'col-xs-12 col-md-12 col-lg-6';
                                                                }
                                                            }
                                                        } else {
                                                            if ($count - $i === 1)
                                                                $cols = 'col-xs-12 col-md-12 col-lg-4';
                                                        }
                                                    }
                                                    ?>

                                                    <!-- left column -->
                                                    <div class="<?php echo $cols; ?>">

                                                        <!-- general form elements -->
                                                        <div id="p<?php echo $branchProduct->id ?>" class="box box-solid">

                                                            <?php if ($branchProduct->product->image) { ?>
                                                                <img alt="" src="<?php echo asset('upload/product_image/' . $branchProduct->product->image); ?>" style="width: 100%; display: block;">
                                                            <?php } else { ?>
                                                                <img data-src="holder.js/100%" alt="100%" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNDIiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjQyIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEyMSIgeT0iMTAwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjE1cHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjQyeDIwMDwvdGV4dD48L3N2Zz4=" style="width: 100%; display: block;">
                                                            <?php } ?>

                                                            <div class="box-body">

                                                                <div class="caption">

                                                                    <div style="overflow: hidden;">
                                                                        <h3 class="product-title pull-left" title="<?php echo $branchProduct->product->tags->tag_name ?>"><?php echo $branchProduct->product->tags->tag_name ?></h3>
                                                                        <h3 class="pull-right">
                                                                            <?php
                                                                            //echo $branchProduct->prices->count() > 1 ? 'Desde' : '';
                                                                            echo ' $' . $branchProduct->prices[0]->price;
                                                                            ?>
                                                                        </h3>
                                                                    </div>

                                                                </div>

                                                            </div>

                                                            <div class="box-footer">
                                                                <form method="post" action="<?php echo URL::route('preorder.add') ?>">

                                                                    <?php if ($branchProduct->prices->count() > 1) { ?>
                                                                        <div class="dropup" style="position: relative;">
                                                                            <button id="prices-<?php echo $branchProduct->id ?>" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true" type="button">Agregar <span class="caret"></span></button>
                                                                            <ul class="dropdown-menu dropup" role="menu" aria-labelledby="prices-<?php echo $branchProduct->id ?>">
                                                                                <?php foreach ($branchProduct->prices as $price) { ?>
                                                                                    <li role="presentation">
                                                                                        <a class="addBasket" data-productid="<?php echo $branchProduct->id ?>" data-priceid="<?php echo $price->id ?>" role="menuitem" tabindex="-1" href="#"><?php echo $price->size->size_name . ' $' . $price->price ?></a>
                                                                                    </li>
                                                                                <?php } ?>
                                                                            </ul>
                                                                        </div><!-- /input-group -->
                                                                    <?php } else { ?>
                                                                        <button data-productid="<?php echo $branchProduct->id ?>" data-priceid="<?php echo $branchProduct->prices[0]->id ?>" class="btn btn-primary btn-sm addBasket" type="button">Agregar</button>
                                                                    <?php } ?>

                                                                </form>
                                                            </div>

                                                        </div><!-- /.box -->

                                                    </div><!--/.col (left) -->

                                                    <?php $i++; ?>

                                                <?php } ?>

                                            </div><!-- /.row (3 products row) -->

                                        </div><!-- /(category wrapper) -->

                                    <?php } ?>


                                <?php } else { ?>

                                    <div class="well well-sm text-center">No hay productos disponibles en esta sucursal.</div>
                                <?php } ?>

                            <?php } else { ?>

                                <div class="well well-sm text-center">Este comercio aun no ha configurado su men&uacute;.</div>
                            <?php } ?>



                        </div><!--/.col (left) -->

                    </div><!-- /.row (main row) -->
                </div>

            </section><!-- /.content -->


        <?php } else { ?>

            <?php echo Request::segment(1) ?> no existe, registralo!
        <?php } ?>

        <?php echo View::make('footer'); ?>

    </body>
</html>