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
            <h3 class="box-title"><?php echo Lang::get('segment.tag.title.add_tag') ?></h3>
        </div><!-- /.box-header -->
        <!-- form start -->

        <div class="box-body">

            <form role="form" method="post" action="tag">
                <div class="form-group">
                    <label for="">Subcategor&iacute;a:</label>

                    <select name="subcategory_id" class="form-control">
                        <?php foreach ($categories as $category) { ?>
                            <optgroup label="<?php echo $category->category_name ?>">
                                <?php foreach ($category->subcategories as $subcategory) { ?>

                                    <option value="<?php echo $subcategory->id ?>"><?php echo $subcategory->subcategory_name ?></option>
                                <?php } ?>
                            <?php } ?>
                        </optgroup>
                    </select>
                </div>

                <?php
                if ($errors->has('d_tag')) {
                    $tag_name_error = 'has-error';
                }
                ?>

                <div class="form-group <?php echo (isset($tag_name_error)) ? $tag_name_error : ''; ?>">
                    <label for="tag_name"><?php echo Lang::get('segment.tag.form.tag_name') ?></label>
                    <input type="text" class="form-control" id="tag_name" name="tag_name" placeholder="<?php echo Lang::get('common.placeholder.name') ?>" value="<?php echo Input::old('tag_name'); ?>">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><?php echo Lang::get('common.label.save') ?></button>
                </div>
            </form>

            <?php if (!$tags->isEmpty()) { ?>
                <table class="table table-condensed">
                    <tbody>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th style="">Nombre/Tipo</th>
                            <th>Estado</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        <?php foreach ($tags as $tag) { ?>
                            <tr>
                                <td><?php echo $tag->id; ?>.</td>
                                <td>
                                    <form method="post" action="tag/<?php echo $tag->id; ?>">

                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="input-group">
                                                <input type="text" name="tag_name" class="form-control input-sm" value="<?php echo $tag->tag_name; ?>"/>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-sm btn-default btn-flat" type="submit">Editar</button>
                                                </span>
                                            </div><!-- /input-group -->
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <select name="subcategory_id" class="form-control">
                                                <?php foreach ($subcategories as $subcategory) { ?>

                                                    <option <?php echo ($tag->subcategories->id == $subcategory->id) ? 'selected' : ''; ?> value="<?php echo $subcategory->id ?>"><?php echo $subcategory->subcategory_name ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <input type="hidden" name="_method" value="PUT"/>
                                    </form>
                                </td>

                                <?php if ($tag->active) { ?>
                                    <td><span class="badge bg-green">Activo</span></td>
                                    <td>
                                        <form method="post" action="tag/status">
                                            <input type="hidden" name="tag_id" value="<?php echo $tag->id; ?>"/>
                                            <input type="hidden" name="status" value="0"/>
                                            <button type="submit" class="btn btn-sm btn-default btn-flat">Desactivar</button>
                                        </form>
                                    </td>
                                <?php } else { ?>
                                    <td><span class="badge bg-red">Inactivo</span></td>
                                    <td>
                                        <form method="post" action="tag/status">
                                            <input type="hidden" name="tag_id" value="<?php echo $tag->id; ?>"/>
                                            <input type="hidden" name="status" value="1"/>
                                            <button type="submit" class="btn btn-sm btn-default btn-flat">Activar</button>
                                        </form>
                                    </td>
                                <?php } ?>

                                <td>
                                    <form method="post" action="<?php echo URL::to('tag', $tag->id) ?>">
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        <button type="submit" class="btn btn-sm btn-default btn-flat">Remover</button>
                                    </form>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>

                <div class="well well-sm text-center">No hay etiquetas creadas.</div>

            <?php } ?>

        </div><!-- /.box-body -->

    </div><!-- /.box -->


</div><!--/.col (left) -->

<!-- right column -->
