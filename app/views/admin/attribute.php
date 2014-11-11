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
            <h3 class="box-title">Tipos de atributo<?php //echo Lang::get('segment.attribute.title.add_attribute')                                                                                 ?></h3>
        </div><!-- /.box-header -->

        <div class="box-body">

            <?php
            if ($errors->has('d_attribute_type')) {
                $attributetype_name_error = 'has-error';
            }
            ?>
            <!-- form start -->
            <form role="form" method="post" action="attributetype">

                <div class="form-group">
                    <label for="attributetype_name"><?php echo Lang::get('segment.attribute_type.form.name') ?></label>
                    <input type="text" class="form-control" id="attributetype_name" name="attributetype_name" placeholder="<?php echo Lang::get('common.placeholder.name') ?>" value="<?php echo Input::old('attributetype_name'); ?>">
                </div>

                <div class="form-group">
                    <select name="rules[]" multiple class="form-control">
                        <option value="0">Sin reglas</option>
                        <?php foreach ($rules as $rule) { ?>
                            <option value="<?php echo $rule->id; ?>"><?php echo $rule->rule_type->rule_type_name . ' of ' . $rule->rule_value; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary btn-flat" type="submit"><?php echo Lang::get('common.label.save') ?></button>
                </div>
            </form>

            <?php if (!$attributetypes->isEmpty()) { ?>
                <table class="table table-condensed">
                    <tbody>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Nombre</th>
                            <th style="width: 40px">Estado</th>
                            <th style="width: 40px">&nbsp;</th>
                        </tr>
                        <?php foreach ($attributetypes as $attributetype) { ?>
                            <tr>
                                <td><?php echo $attributetype->id; ?>.</td>
                                <td>
                                    <form method="post" action="attributetype/<?php echo $attributetype->id; ?>">
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <input type="text" name="attributetype_name" class="form-control input-sm" value="<?php echo $attributetype->d_attribute_type; ?>"/>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-sm btn-default btn-flat" type="submit">Editar</button>
                                                </span>
                                            </div><!-- /input-group -->
                                        </div>

                                        <input type="hidden" name="_method" value="PUT"/>
                                    </form>
                                </td>

                                <?php if ($attributetype->active) { ?>
                                    <td><span class="badge bg-green">Activo</span></td>
                                    <td>
                                        <form method="post" action="attributetype/status">
                                            <input type="hidden" name="attributetype_id" value="<?php echo $attributetype->id; ?>"/>
                                            <input type="hidden" name="status" value="0"/>
                                            <button type="submit" class="btn btn-sm btn-default btn-flat pull-right">Desactivar</button>
                                        </form>
                                    </td>
                                <?php } else { ?>
                                    <td><span class="badge bg-red">Inactivo</span></td>
                                    <td>
                                        <form method="post" action="attributetype/status">
                                            <input type="hidden" name="attributetype_id" value="<?php echo $attributetype->id; ?>"/>
                                            <input type="hidden" name="status" value="1"/>
                                            <button type="submit" class="btn btn-sm btn-default btn-flat pull-right">Activar</button>
                                        </form>
                                    </td>
                                <?php } ?>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="well well-sm text-center">No hay tipos de atributo creados.</div>
            <?php } ?>



        </div><!-- /.box-body -->

    </div><!-- /.box -->

    <!-- general form elements -->
    <div class="box box-primary">

        <div class="box-header">
            <h3 class="box-title"><?php echo Lang::get('segment.attribute.title.add_attribute') ?></h3>
        </div><!-- /.box-header -->
        <!-- form start -->

        <div class="box-body">

            <?php if (!$attributetypes->isEmpty()) { ?>

                <form role="form" method="post" action="attribute">
                    <div class="form-group">
                        <label for="">Tipo de atributo:</label>
                        <select name="attribute_type_id" class="form-control">
                            <?php foreach ($attributetypes as $attributetype) { ?>

                                <option value="<?php echo $attributetype->id ?>"><?php echo $attributetype->d_attribute_type ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <?php
                    if ($errors->has('attribute_name')) {
                        $attribute_name_error = 'has-error';
                    }
                    ?>

                    <div class="form-group <?php echo (isset($attribute_name_error)) ? $attribute_name_error : ''; ?>">
                        <label for="attribute_name"><?php echo Lang::get('segment.attribute.form.attribute_name') ?></label>
                        <input type="text" class="form-control" id="attribute_name" name="attribute_name" placeholder="<?php echo Lang::get('common.placeholder.name') ?>" value="<?php echo Input::old('attribute_name'); ?>">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><?php echo Lang::get('common.label.save') ?></button>
                    </div>
                </form>

                <?php if (!$attributes->isEmpty()) { ?>
                    <table class="table table-condensed">
                        <tbody>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th style="">Nombre/Tipo</th>
                                <th>Estado</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
                            <?php foreach ($attributes as $attribute) { ?>
                                <tr>
                                    <td><?php echo $attribute->id; ?>.</td>
                                    <td>
                                        <form method="post" action="attribute/<?php echo $attribute->id; ?>">

                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="input-group">
                                                    <input type="text" name="attribute_name" class="form-control input-sm" value="<?php echo $attribute->attribute_name; ?>"/>
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-sm btn-default btn-flat" type="submit">Editar</button>
                                                    </span>
                                                </div><!-- /input-group -->
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <select name="attribute_type_id" class="form-control">
                                                    <?php foreach ($attributetypes as $attributetype) { ?>

                                                        <option <?php echo ($attribute->attribute_types->id == $attributetype->id) ? 'selected' : ''; ?> value="<?php echo $attributetype->id ?>"><?php echo $attributetype->d_attribute_type ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <input type="hidden" name="_method" value="PUT"/>
                                        </form>
                                    </td>

                                    <?php if ($attribute->active) { ?>
                                        <td><span class="badge bg-green">Activo</span></td>
                                        <td>
                                            <form method="post" action="attribute/status">
                                                <input type="hidden" name="attribute_id" value="<?php echo $attribute->id; ?>"/>
                                                <input type="hidden" name="status" value="0"/>
                                                <button type="submit" class="btn btn-sm btn-default btn-flat">Desactivar</button>
                                            </form>
                                        </td>
                                    <?php } else { ?>
                                        <td><span class="badge bg-red">Inactivo</span></td>
                                        <td>
                                            <form method="post" action="attribute/status">
                                                <input type="hidden" name="attribute_id" value="<?php echo $attribute->id; ?>"/>
                                                <input type="hidden" name="status" value="1"/>
                                                <button type="submit" class="btn btn-sm btn-default btn-flat">Activar</button>
                                            </form>
                                        </td>
                                    <?php } ?>

                                    <td>
                                        <form method="post" action="<?php echo URL::to('attribute', $attribute->id) ?>">
                                            <input type="hidden" name="_method" value="DELETE"/>
                                            <button type="submit" class="btn btn-sm btn-default btn-flat">Remover</button>
                                        </form>
                                    </td>

                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>

                    <div class="well well-sm text-center">No hay atributos creados.</div>

                <?php } ?>


            <?php } else { ?>

                <div class="well well-sm text-center">Para agregar atributos primero debe crear un tipo de atributo.</div>

            <?php } ?>

        </div><!-- /.box-body -->

    </div><!-- /.box -->


</div><!--/.col (left) -->

<!-- right column -->
