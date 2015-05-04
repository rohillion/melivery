<!DOCTYPE html>
<html>
    <?php echo View::make('head'); ?>
    <body class="skin-red">

        <?php echo View::make('header'); ?>

        <section class="content-header content-header-fixed">

            <div class="container">

                <div id="filter-container" class="row">

                    <div id="filter-type" class="col-md-3">

                        <div id="type-select" class="pull-left">

                            <div href="#" id="type-mask" class="filter-mask popover-trigger">

                                <div class="mask pull-left"><?php echo Session::get('delivery') ? 'Para delivery' : 'Para retirar'; ?></div>

                                <div class="caret-wrapp pull-left">
                                    <span class="caret"></span>
                                </div>

                                <div id="type-list" class="popover-wrapper">

                                    <ul class="list-group organized-list">

                                        <span role="presentation" class="dropdown-header">Tipo de entrega</span><!-- TODO. Lang support -->

                                        <li class="list-group-item">
                                            <a class="sort-item" href="<?php echo URL::route('menu.change',1);?>">Para delivery</a>
                                        </li>

                                        <li class="list-group-item">
                                            <a class="sort-item" href="<?php echo URL::route('menu.change',0);?>">Para retirar</a>
                                        </li>

                                    </ul>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div id="filter-options" class="col-md-9">

                        <div id="food-select" class="pull-left">

                            <div href="#" id="category-mask" class="filter-mask popover-trigger">
                                <div class="mask pull-left"><?php echo urldecode(Request::segment(1)); ?></div>
                                <div class="caret-wrapp pull-left">
                                    <span class="caret"></span>
                                </div>

                                <div id="category-list" class="popover-wrapper">
                                    <ul class="list-group organized-list">

                                        <span role="presentation" class="dropdown-header">Categor&iacute;as</span>

                                        <?php $sortUri = Input::get('by') ? 'by=' . Input::get('by') . '&order=' . Input::get('order') : ''; ?>
                                        <?php $last = ''; ?>

                                        <?php foreach ($categories as $category) { ?>

                                            <?php $current = strtolower($category->category_name[0]); ?>

                                            <?php if ($last != $current) { ?>

                                                <li role="presentation" class="list-group-header"><?php echo $current; ?></li>

                                                <?php $last = $current; ?>

                                            <?php } ?>

                                            <li class="list-group-item">
                                                <a class="category-item" href="<?php echo $category->category_name . (($sortUri != '') ? '?' . $sortUri : ''); ?>">
                                                    <?php echo $category->category_name; ?>
                                                </a>
                                            </li>

                                        <?php } ?>

                                    </ul>
                                </div>

                            </div>

                            <div id="subcategory-mask" class="filter-mask popover-trigger">

                                <div class="mask pull-left">
                                    <?php $currentSubcategory = 'Todos'; ?>
                                    <?php foreach ($subcategories as $subcategory) { ?>

                                        <?php
                                        if ($subcategory->id == Input::get('subcategory')) {
                                            $currentSubcategory = $subcategory->subcategory_name;
                                            break;
                                        }
                                        ?>

                                    <?php } ?>

                                    <?php echo $currentSubcategory; ?>
                                </div>

                                <div class="caret-wrapp pull-left">
                                    <span class="caret"></span>
                                </div>

                                <div id="subcategory-list" class="popover-wrapper">
                                    <ul class="list-group organized-list">

                                        <span role="presentation" class="dropdown-header">Subcategor&iacute;as</span>

                                        <li class="list-group-item">
                                            <a class="category-item" href="<?php echo Request::segment(1) . (($sortUri != '') ? '?' . $sortUri : ''); ?>">
                                                Todos
                                            </a>
                                        </li>

                                        <?php foreach ($subcategories as $subcategory) { ?>

                                            <li class="list-group-item">
                                                <a class="category-item" href="<?php echo Request::segment(1); ?>?subcategory=<?php echo $subcategory->id  . (($sortUri != '') ? '&' . $sortUri : ''); ?>">
                                                    <?php echo $subcategory->subcategory_name; ?>
                                                </a>
                                            </li>

                                        <?php } ?>

                                    </ul>
                                </div>

                            </div>

                        </div>

                        <div id="sort-select" class="pull-right">

                            <?php
                            $sortUri = 'by=price';
                            if (Input::get('subcategory')) {
                                $sortUri = 'subcategory=' . Input::get('subcategory') . '&' . $sortUri;
                            }

                            $sortMask = 'Precio';
                            if (Input::get('order'))
                                $sortMask = Input::get('order') == 'asc' ? 'Mas barato' : 'Mas caro';
                            ?>

                            <div href="#" id="sort-mask" class="filter-mask popover-trigger">

                                <div class="mask pull-left"><?php echo $sortMask; ?></div>

                                <div class="caret-wrapp pull-left">
                                    <span class="caret"></span>
                                </div>

                                <div id="sort-list" class="popover-wrapper">
                                    <ul class="list-group organized-list">

                                        <span role="presentation" class="dropdown-header">Ordenar</span>

                                        <li class="list-group-item">
                                            <a class="sort-item" href="?<?php echo $sortUri . '&order=asc'; ?>">Mas barato</a>
                                        </li>

                                        <li class="list-group-item">
                                            <a class="sort-item" href="?<?php echo $sortUri . '&order=desc'; ?>">Mas caro</a>
                                        </li>

                                    </ul>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="container container-with-padding-top">

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
                <div class="col-xs-7 col-md-8 col-lg-9">

                    <!-- Main row -->
                    <div class="row">

                        <?php if (!$products->isEmpty()) { ?>

                            <?php foreach ($products as $branchProduct) {?>

                                <!-- left column -->
                                <div class="col-xs-12 col-md-6 col-lg-4">

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
                                                    <h3 class="product-title truncate pull-left" title="<?php echo $branchProduct->product->tags->tag_name ?>"><?php echo $branchProduct->product->tags->tag_name ?></h3>
                                                    <h3 class="pull-right">
                                                        <?php
                                                        echo $branchProduct->prices->count() > 1 ? 'Desde' : '';
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

                            <?php } ?>
                        <?php } else { ?>

                            <section class="container">
                                <div class="well well-sm text-center">A&uacute;n no tienes productos cargados en el men&uacute;.</div><!-- TODO. Lang Support-->
                            </section>

                        <?php } ?>

                    </div><!-- /.row (main row) -->

                </div><!--/.col (left) -->

            </div><!-- /.row (main row) -->

        </section><!-- /.content -->

        <?php echo!$products->isEmpty() ? $products->links() : ''; ?>

        <!-- Dependecies -->
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=geocoding"></script>

        <?php echo View::make('footer'); ?>

    </body>
</html>