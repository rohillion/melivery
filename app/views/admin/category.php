<!-- left column -->
<div class="col-md-12">

    <?php if ($errors->has()) { ?>
        <?php foreach ($errors->all() as $error) { ?>
            <div class="alert alert-danger alert-dismissable">
                <i class="fa fa-ban"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php echo $error ?>
            </div>
        <?php } ?>
    <?php } else if (Session::get('success')) { ?>
        <div class="alert alert-success alert-dismissable">
            <i class="fa fa-check"></i>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo Session::get('success') ?>
        </div>
    <?php } ?>

    <!-- general form elements -->
    <div class="box box-primary">

        <div class="box-header">
            <h3 class="box-title"><?php echo Lang::get('segment.category.title.add_category') ?></h3>
        </div><!-- /.box-header -->
        <!-- form start -->

        <div class="box-body">
            <form role="form" method="post" action="category">
                <?php
                if ($errors->has('category_name')) {
                    $category_name_error = 'has-error';
                }
                ?>

                <div class="form-group <?php echo (isset($category_name_error)) ? $category_name_error : ''; ?>">
                    <label for="category_name"><?php echo Lang::get('segment.category.form.category_name') ?></label>
                    <input type="text" class="form-control" id="category_name" name="category_name" placeholder="<?php echo Lang::get('common.placeholder.name') ?>" value="<?php echo Input::old('category_name'); ?>">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><?php echo Lang::get('common.label.save') ?></button>
                </div>

            </form>

            <?php if (!$categories->isEmpty()) { ?>

                <table class="table table-condensed">
                    <tbody>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Nombre</th>
                            <th style="width: 40px">Estado</th>
                            <th style="width: 40px">&nbsp;</th>
                            <th style="width: 40px">&nbsp;</th>
                        </tr>

                        <?php foreach ($categories as $category) { ?>
                            <tr>
                                <td><?php echo $category->id; ?>.</td>
                                <td>
                                    <form method="post" action="category/<?php echo $category->id; ?>">
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <input type="text" name="category_name" class="form-control input-sm" value="<?php echo $category->category_name; ?>"/>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-sm btn-default btn-flat" type="submit">Editar</button>
                                                </span>
                                            </div><!-- /input-group -->
                                        </div>

                                        <input type="hidden" name="_method" value="PUT"/>
                                    </form>
                                </td>

                                <?php if ($category->active) { ?>
                                    <td><span class="badge bg-green">Activo</span></td>
                                    <td>
                                        <form method="post" action="category/status">
                                            <input type="hidden" name="category_id" value="<?php echo $category->id; ?>"/>
                                            <input type="hidden" name="status" value="0"/>
                                            <button type="submit" class="btn btn-sm btn-default btn-flat">Desactivar</button>
                                        </form>
                                    </td>
                                <?php } else { ?>
                                    <td><span class="badge bg-red">Inactivo</span></td>
                                    <td>
                                        <form method="post" action="category/status">
                                            <input type="hidden" name="category_id" value="<?php echo $category->id; ?>"/>
                                            <input type="hidden" name="status" value="1"/>
                                            <button type="submit" class="btn btn-sm btn-default btn-flat">Activar</button>
                                        </form>
                                    </td>
                                <?php } ?>

                                <td>
                                    <form method="post" action="<?php echo URL::to('category', $category->id) ?>">
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        <button type="submit" class="btn btn-sm btn-default btn-flat">Remover</button>
                                    </form>
                                </td>

                            </tr>
                        <?php } ?>

                    </tbody>
                </table>

            <?php } else { ?>
                <div class="well well-sm text-center">No hay categor&iacute;as cargadas</div>
            <?php } ?>

        </div><!-- /.box-body -->

    </div><!-- /.box -->

</div><!--/.col (left) -->