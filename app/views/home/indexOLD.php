<section class="content-header content-header-fixed">
    <div class="container">
        <h3>
            Categorias<?php /* Lang::get('segment.profile.title.main_menu'); */ ?>
        </h3>
    </div>
</section>

<!-- Main content -->
<section style="padding-top: 122px;" class="container container-with-padding-top">

    <!-- Main row -->
    <div class="row">

        <!-- left column -->
        <div class="col-md-12">

            <!-- Main row -->
            <div class="row">

                <ul>
                    <?php foreach ($categories as $category) { ?>
                        <li><?php echo $category->id . '- ' . $category->category_name . '<br>'; ?></li>
                    <?php } ?>
                </ul>

            </div><!-- /.row (main row) -->

        </div><!--/.col (left) -->

    </div><!-- /.row (main row) -->

</section><!-- /.content -->