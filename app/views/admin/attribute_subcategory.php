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

    <div class="box box-primary">

        <div class="box-header">
            <h3 class="box-title">Atributos por categor&iacute;a</h3>
        </div><!-- /.box-header -->

        <div class="box-body">

            <?php if (!$categories->isEmpty()) { ?>

                <div class="box-group" id="accordion">

                    <?php foreach ($categories as $category) { ?>

                        <div class="panel box box-solid box-default">
                            <div class="box-header">
                                <h4 class="box-title">
                                    <a style="text-transform: capitalize" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $category->category_name; ?>" class="">
                                        <?php echo $category->category_name; ?>
                                    </a>
                                </h4>
                            </div>

                            <div id="<?php echo $category->category_name; ?>" class="panel-collapse in" style="height: auto;">
                                <div class="box-body">

                                    <?php foreach ($category->subcategories as $subcategory) { ?>

                                        <div class="box-header">
                                            <h5 class="box-title"><?php echo $subcategory->subcategory_name; ?></h5>
                                            <div class="box-tools">
                                                <!-- Single button -->
                                                <div class="pull-right">
                                                    <button type="button" class="btn btn-default popover-trigger">
                                                        Action <span class="caret"></span>
                                                    </button>
                                                    <div class="hide">

                                                        <form method="post" action="attribute_subcategory">
                                                            <ul class="list-group">
                                                                <?php $attributeList = $attributes->toArray(); ?>

                                                                <?php foreach ($subcategory->attributes as $subcategoryAttribute) { ?>

                                                                    <?php foreach ($attributeList as $key => $attribute) { ?>

                                                                        <?php if ($subcategoryAttribute->id == $attribute['id']) { ?>

                                                                            <?php unset($attributeList[$key]) ?>
                                                                        <?php } ?>

                                                                    <?php } ?>

                                                                <?php } ?>

                                                                <?php foreach ($attributeList as $availableAttribute) { ?>

                                                                    <li class="list-group-item">

                                                                        <label style="display: block;cursor: pointer;" for="<?php echo $subcategory->id . '-' . $availableAttribute['id']; ?>">
                                                                            <input id="<?php echo $subcategory->id . '-' . $availableAttribute['id']; ?>" type="checkbox" name="attribute[<?php echo $subcategory->id; ?>][]" value="<?php echo $availableAttribute['id']; ?>" class="simple"/>
                                                                            <?php echo $availableAttribute['attribute_name']; ?>
                                                                        </label>
                                                                    </li>
                                                                <?php } ?>

                                                            </ul>
                                                            <button type="submit" class="btn btn-primary btn-block">Agregar</button>
                                                        </form>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <?php if (!$subcategory->attributes->isEmpty()) { ?>
                                            <table class="table table-condensed">
                                                <tbody>
                                                    <tr>
                                                        <th>Atributo</th>
                                                        <th>Tipo</th>
                                                        <th>&nbsp;</th>
                                                    </tr>

                                                    <?php foreach ($subcategory->attributes as $attribute) { ?>
                                                        <tr>
                                                            <td><?php echo $attribute->attribute_name; ?></td>
                                                            <td><?php echo $attribute->attribute_types->d_attribute_type; ?></td>
                                                            <td>
                                                                <form method="post" action="attribute_subcategory/<?php echo $attribute->pivot->id ?>">
                                                                    <input type="hidden" value="delete" name="_method"/>
                                                                    <button type="submit" class="btn btn-sm btn-default btn-flat pull-right">Remover</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        <?php } else { ?>
                                            <div class="well well-sm text-center">Esta subcategor&iacute;a no tiene atributos asignados.</div>
                                        <?php } ?>

                                    <?php } ?>

                                </div>
                            </div>

                        </div>

                    <?php } ?>

                </div>

            <?php } else { ?>

                <div class="well well-sm text-center">Para agregar atributos a una categor&iacute;a primero debe crear una categor&iacute;a.</div>
            <?php } ?>
        </div><!-- /.box-body -->

    </div><!-- /.box -->

</div><!--/.col (left) -->