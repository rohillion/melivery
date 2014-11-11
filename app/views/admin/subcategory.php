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
            <h3 class="box-title"><?php echo Lang::get('segment.subcategory.title.add_subcategory') ?></h3>
        </div><!-- /.box-header -->
        <!-- form start -->

        <div class="box-body">
            <?php if (!$categories->isEmpty()) { ?>
                <form role="form" method="post" action="subcategory">
                    <?php
                    if ($errors->has('subcategory_name')) {
                        $subcategory_name_error = 'has-error';
                    }
                    ?>

                    <div class="form-group">
                        <label for="">Categor&iacute;a relacionada:</label>
                        <select name="category_id" class="form-control">
                            <?php foreach ($categories as $category) { ?>

                                <option value="<?php echo $category->id ?>"><?php echo $category->category_name ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group <?php echo (isset($subcategory_name_error)) ? $subcategory_name_error : ''; ?>">
                        <label for="subcategory_name"><?php echo Lang::get('segment.subcategory.form.subcategory_name') ?></label>
                        <input type="text" class="form-control" id="subcategory_name" name="subcategory_name" placeholder="<?php echo Lang::get('common.placeholder.name') ?>" value="<?php echo Input::old('subcategory_name'); ?>">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><?php echo Lang::get('common.label.save') ?></button>
                    </div>

                </form>

                <?php if (!$subcategories->isEmpty()) { ?>

                    <table class="table table-condensed">
                        <tbody>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Nombre</th>
                                <th style="width: 40px">Estado</th>
                                <th style="width: 40px">&nbsp;</th>
                                <th style="width: 40px">&nbsp;</th>
                            </tr>

                            <?php foreach ($subcategories as $subcategory) { ?>
                                <tr>
                                    <td><?php echo $subcategory->id; ?>.</td>
                                    <td>
                                        <form method="post" action="subcategory/<?php echo $subcategory->id; ?>">
                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="input-group">
                                                    <input type="text" name="subcategory_name" class="form-control input-sm" value="<?php echo $subcategory->subcategory_name; ?>"/>
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-sm btn-default btn-flat" type="submit">Editar</button>
                                                    </span>
                                                </div><!-- /input-group -->
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <select name="category_id" class="form-control">
                                                    <?php foreach ($categories as $category) { ?>

                                                        <option <?php echo ($category->id == $subcategory->id_category) ? 'selected' : ''; ?> value="<?php echo $category->id ?>"><?php echo $category->category_name ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <input type="hidden" name="_method" value="PUT"/>
                                        </form>
                                    </td>

                                    <?php if ($subcategory->active) { ?>
                                        <td><span class="badge bg-green">Activo</span></td>
                                        <td>
                                            <form method="post" action="subcategory/status">
                                                <input type="hidden" name="subcategory_id" value="<?php echo $subcategory->id; ?>"/>
                                                <input type="hidden" name="status" value="0"/>
                                                <button type="submit" class="btn btn-sm btn-default btn-flat">Desactivar</button>
                                            </form>
                                        </td>
                                    <?php } else { ?>
                                        <td><span class="badge bg-red">Inactivo</span></td>
                                        <td>
                                            <form method="post" action="subcategory/status">
                                                <input type="hidden" name="subcategory_id" value="<?php echo $subcategory->id; ?>"/>
                                                <input type="hidden" name="status" value="1"/>
                                                <button type="submit" class="btn btn-sm btn-default btn-flat">Activar</button>
                                            </form>
                                        </td>
                                    <?php } ?>

                                    <td>
                                        <form method="post" action="<?php echo URL::to('subcategory', $subcategory->id) ?>">
                                            <input type="hidden" name="_method" value="DELETE"/>
                                            <button type="submit" class="btn btn-sm btn-default btn-flat">Remover</button>
                                        </form>
                                    </td>

                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>

                <?php } else { ?>
                    <div class="well well-sm text-center">No hay subcategor&iacute;as cargadas</div>
                <?php } ?>

            <?php } else { ?>

                <div class="well well-sm text-center">Para agregar subcategor&iacute;as primero debe crear categor&iacute;as.</div>
            <?php } ?>

        </div><!-- /.box-body -->

    </div><!-- /.box -->

</div><!--/.col (left) -->