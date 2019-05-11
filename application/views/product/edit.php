<div class="block-header">
    <h2><i class="material-icons">edit</i> <?php echo lang('edit'); ?> - <?php echo lang('product') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form id="product-form" method="post" enctype="multipart/form-data" class="form-validate">
            <div class="card">
                <div class="header">
                    <h2>
                        <?php echo ucwords(lang('product_information')); ?>
                        <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li id="salable-switch">
                            <div class="switch">
                                <label for="is_salable"><?php echo lang('salable_product'); ?>
                                    <input type="checkbox" name="is_salable" id="is_salable" value="1" <?php echo set_checkbox('is_salable', '1', ($product->is_salable == '1')) ?> disabled />
                                    <span class="lever switch-col-cyan"></span>
                                </label>
                            </div>
                        </li>
                        <li id="composed-switch">
                            <div class="switch">
                                <label for="is_composed"><?php echo lang('compound_product'); ?>
                                    <input type="checkbox" name="is_composed" id="is_composed" value="1" <?php echo set_checkbox('is_composed', '1', ($product->is_composed == '1')) ?> disabled />
                                    <span class="lever switch-col-cyan"></span>
                                </label>
                            </div>
                        </li>
                        <li id="stock-switch">
                            <div class="switch">
                                <label for="is_stock"><?php echo lang('inventorial_product'); ?>
                                    <input type="checkbox" name="is_stock" id="is_stock" value="1" <?php echo set_checkbox('is_stock', '1', ($product->is_stock == '1')) ?> disabled />
                                    <span class="lever switch-col-cyan"></span>
                                </label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <label for="category_id"><?php echo lang('category'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <?php echo form_dropdown('category_id', $categories, set_value('category_id', $product->category_id), 'id="category_id" class="form-control show-tick" required') ?>
                        </div>
                    </div>

                    <label for="code"><?php echo lang('code'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-barcode" style="font-size: 30px;"></i>
                        </span>
                        <div class="form-line">
                            <input type="text" id="code" name="code" class="form-control" value="<?php echo set_value('code', $product->code); ?>" placeholder="<?php echo lang('enter_code'); ?>" autofocus required minlength="3" maxlength="20" />
                        </div>
                    </div>

                    <label for="name"><?php echo lang('name'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo set_value('name', $product->name); ?>" placeholder="<?php echo lang('enter_name'); ?>" required minlength="2" maxlength="100" />
                        </div>
                    </div>

                    <label for="description"><?php echo lang('description'); ?> </label>
                    <div class="form-group">
                        <div class="form-line">
                            <textarea rows="1" name="description" id="description" class="form-control no-resize auto-growth" placeholder="<?php echo lang('enter_description')?>"><?php echo set_value('description', $product->description); ?></textarea>
                        </div>
                    </div>

                    <label for="sale"><?php echo lang('price'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon" style="font-size: 24px;">
                            $
                        </span>
                        <div class="form-line">
                            <input type="text" id="sale" name="sale" class="form-control currency" value="<?php echo set_value('sale', $product->sale); ?>" placeholder="<?php echo lang('enter_sale'); ?>" required  />
                        </div>
                    </div>

                    <label for="cost"><?php echo lang('cost'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon" style="font-size: 24px;">
                            $
                        </span>
                        <div class="form-line">
                            <input type="text" id="cost" name="cost" class="form-control currency" value="<?php echo set_value('cost', $product->cost); ?>" placeholder="<?php echo lang('enter_cost'); ?>" required  />
                        </div>
                    </div>

                    <!--label for="wholesale_price"><?php echo lang('wholesale_price'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon" style="font-size: 24px;">
                            $
                        </span>
                        <div class="form-line">
                            <input type="text" id="wholesale_price" name="wholesale_price" class="form-control currency" value="<?php echo set_value('wholesale_price', $product->wholesale_price); ?>" placeholder="<?php echo lang('enter_wholesale_price'); ?>" required />
                        </div>
                    </div>

                    <label for="quantity_wholesale"><?php echo lang('quantity_for_wholesale'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="quantity_wholesale" name="quantity_wholesale" class="form-control number" value="<?php echo set_value('quantity_wholesale', $product->quantity_wholesale); ?>" placeholder="<?php echo lang('enter_quantity_wholesale'); ?>" required />
                        </div>
                    </div -->

                    <label for="image_path"><?php echo lang('image'); ?></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="file" name="image_path" id="image_path" class="form-control" accept="image/*" />
                        </div>
                    </div>

                    <button class="btn btn-primary waves-effect m-t-50" type="submit">
                        <i class="material-icons" aria-hidden='true'>save</i> <?php echo strtoupper(lang('save')); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- #END# Vertical Layout -->