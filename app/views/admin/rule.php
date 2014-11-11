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
            <h3 class="box-title">Tipos de regla<?php //echo Lang::get('segment.rule.title.add_rule')                                                                           ?></h3>
        </div><!-- /.box-header -->

        <div class="box-body">

            <?php
            if ($errors->has('rule_type_name')) {
                $ruletype_name_error = 'has-error';
            }
            ?>
            <!-- form start -->
            <form role="form" method="post" action="ruletype">
                <label for="ruletype_name"><?php echo Lang::get('segment.rule_type.form.name') ?></label>
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control input-sm" id="ruletype_name" name="ruletype_name" placeholder="<?php echo Lang::get('common.placeholder.name') ?>" value="<?php echo Input::old('ruletype_name'); ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-sm btn-primary btn-flat" type="submit"><?php echo Lang::get('common.label.save') ?></button>
                        </span>
                    </div><!-- /input-group -->
                </div>
            </form>

            <?php if (!$ruletypes->isEmpty()) { ?>
                <table class="table table-condensed">
                    <tbody>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Nombre</th>
                            <th style="width: 40px">Estado</th>
                            <th style="width: 40px">&nbsp;</th>
                            <th style="width: 40px">&nbsp;</th>
                        </tr>
                        <?php foreach ($ruletypes as $ruletype) { ?>
                            <tr>
                                <td><?php echo $ruletype->id; ?>.</td>
                                <td>
                                    <form method="post" action="ruletype/<?php echo $ruletype->id; ?>">
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <input type="text" name="ruletype_name" class="form-control input-sm" value="<?php echo $ruletype->rule_type_name; ?>"/>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-sm btn-default btn-flat" type="submit">Editar</button>
                                                </span>
                                            </div><!-- /input-group -->
                                        </div>

                                        <input type="hidden" name="_method" value="PUT"/>
                                    </form>
                                </td>

                                <?php if ($ruletype->active) { ?>
                                    <td><span class="badge bg-green">Activo</span></td>
                                    <td>
                                        <form method="post" action="ruletype/status">
                                            <input type="hidden" name="ruletype_id" value="<?php echo $ruletype->id; ?>"/>
                                            <input type="hidden" name="status" value="0"/>
                                            <button type="submit" class="btn btn-sm btn-default btn-flat pull-right">Desactivar</button>
                                        </form>
                                    </td>
                                <?php } else { ?>
                                    <td><span class="badge bg-red">Inactivo</span></td>
                                    <td>
                                        <form method="post" action="ruletype/status">
                                            <input type="hidden" name="ruletype_id" value="<?php echo $ruletype->id; ?>"/>
                                            <input type="hidden" name="status" value="1"/>
                                            <button type="submit" class="btn btn-sm btn-default btn-flat pull-right">Activar</button>
                                        </form>
                                    </td>
                                <?php } ?>

                                <td>
                                    <form method="post" action="<?php echo URL::to('ruletype',$ruletype->id); ?>">
                                        <input type="hidden" name="_method" value="DELETE"/>
                                        <button type="submit" class="btn btn-sm btn-default btn-flat pull-right">Remover</button>
                                    </form>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="well well-sm text-center">No hay tipos de regla creados.</div>
            <?php } ?>



        </div><!-- /.box-body -->

    </div><!-- /.box -->

    <!-- general form elements -->
    <div class="box box-primary">

        <div class="box-header">
            <h3 class="box-title"><?php echo Lang::get('segment.rule.title.add_rule') ?></h3>
        </div><!-- /.box-header -->
        <!-- form start -->

        <div class="box-body">

            <?php if (!$ruletypes->isEmpty()) { ?>

                <form role="form" method="post" action="rule">
                    <div class="form-group">
                        <label for="">Tipo de atributo:</label>
                        <select name="rule_type_id" class="form-control">
                            <?php foreach ($ruletypes as $ruletype) { ?>

                                <option value="<?php echo $ruletype->id ?>"><?php echo $ruletype->rule_type_name ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <?php
                    if ($errors->has('rule_value')) {
                        $rule_value_error = 'has-error';
                    }
                    ?>

                    <div class="form-group <?php echo (isset($rule_value_error)) ? $rule_value_error : ''; ?>">
                        <label for="rule_value"><?php echo Lang::get('segment.rule.form.rule_value') ?></label>
                        <input type="text" class="form-control" id="rule_value" name="rule_value" placeholder="<?php echo Lang::get('common.placeholder.value') ?>" value="<?php echo Input::old('rule_value'); ?>">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><?php echo Lang::get('common.label.save') ?></button>
                    </div>
                </form>

                <?php if (!$rules->isEmpty()) { ?>
                    <table class="table table-condensed">
                        <tbody>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th style="">Nombre/Tipo</th>
                                <th>Estado</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
                            <?php foreach ($rules as $rule) { ?>
                                <tr>
                                    <td><?php echo $rule->id; ?>.</td>
                                    <td>
                                        <form method="post" action="rule/<?php echo $rule->id; ?>">

                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <div class="input-group">
                                                    <input type="text" name="rule_value" class="form-control input-sm" value="<?php echo $rule->rule_value; ?>"/>
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-sm btn-default btn-flat" type="submit">Editar</button>
                                                    </span>
                                                </div><!-- /input-group -->
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                <select name="rule_type_id" class="form-control">
                                                    <?php foreach ($ruletypes as $ruletype) { ?>

                                                        <option <?php echo ($rule->rule_type->id == $ruletype->id) ? 'selected' : ''; ?> value="<?php echo $ruletype->id ?>"><?php echo $ruletype->rule_type_name ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <input type="hidden" name="_method" value="PUT"/>
                                        </form>
                                    </td>

                                    <?php if ($rule->active) { ?>
                                        <td><span class="badge bg-green">Activo</span></td>
                                        <td>
                                            <form method="post" action="rule/status">
                                                <input type="hidden" name="rule_id" value="<?php echo $rule->id; ?>"/>
                                                <input type="hidden" name="status" value="0"/>
                                                <button type="submit" class="btn btn-sm btn-default btn-flat">Desactivar</button>
                                            </form>
                                        </td>
                                    <?php } else { ?>
                                        <td><span class="badge bg-red">Inactivo</span></td>
                                        <td>
                                            <form method="post" action="rule/status">
                                                <input type="hidden" name="rule_id" value="<?php echo $rule->id; ?>"/>
                                                <input type="hidden" name="status" value="1"/>
                                                <button type="submit" class="btn btn-sm btn-default btn-flat">Activar</button>
                                            </form>
                                        </td>
                                    <?php } ?>

                                    <td>
                                        <form method="post" action="<?php echo URL::to('rule', $rule->id) ?>">
                                            <input type="hidden" name="_method" value="DELETE"/>
                                            <button type="submit" class="btn btn-sm btn-default btn-flat">Remover</button>
                                        </form>
                                    </td>

                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>

                    <div class="well well-sm text-center">No hay reglas creadas.</div>

                <?php } ?>


            <?php } else { ?>

                <div class="well well-sm text-center">Para agregar reglas primero debe crear un tipo de regla.</div>

            <?php } ?>

        </div><!-- /.box-body -->

    </div><!-- /.box -->


</div><!--/.col (left) -->

<!-- right column -->
