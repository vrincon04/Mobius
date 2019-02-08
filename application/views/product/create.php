<div class="block-header">
    <h2><i class="material-icons">add</i> <?php echo lang('new'); ?> - <?php echo lang('product') ?></h2>
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
                </div>
                <div class="body">
                    <label for="code"><?php echo lang('code'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-barcode" style="font-size: 30px;"></i>
                        </span>
                        <div class="form-line">
                            <input type="text" id="code" name="code" class="form-control" value="<?php echo set_value('code'); ?>" placeholder="<?php echo lang('enter_code'); ?>" autofocus required minlength="3" maxlength="20" />
                        </div>
                    </div>

                    <label for="name"><?php echo lang('name'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo set_value('name'); ?>" placeholder="<?php echo lang('enter_name'); ?>" required minlength="2" maxlength="100" />
                        </div>
                    </div>

                    <label for="description"><?php echo lang('description'); ?> </label>
                    <div class="form-group">
                        <div class="form-line">
                            <textarea rows="1" name="description" id="description" class="form-control no-resize auto-growth" placeholder="<?php echo lang('enter_description')?>"><?php echo set_value('description'); ?></textarea>
                        </div>
                    </div>

                    <label for="sale"><?php echo lang('price'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon" style="font-size: 24px;">
                            $
                        </span>
                        <div class="form-line">
                            <input type="text" id="sale" name="sale" class="form-control currency" value="<?php echo set_value('sale'); ?>" placeholder="<?php echo lang('enter_sale'); ?>" required  />
                        </div>
                    </div>

                    <label for="wholesale_price"><?php echo lang('wholesale_price'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon" style="font-size: 24px;">
                            $
                        </span>
                        <div class="form-line">
                            <input type="text" id="wholesale_price" name="wholesale_price" class="form-control currency" value="<?php echo set_value('wholesale_price'); ?>" placeholder="<?php echo lang('enter_wholesale_price'); ?>" required />
                        </div>
                    </div>

                    <label for="quantity_wholesale"><?php echo lang('quantity_for_wholesale'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="quantity_wholesale" name="quantity_wholesale" class="form-control number" value="<?php echo set_value('quantity_wholesale'); ?>" placeholder="<?php echo lang('enter_quantity_wholesale'); ?>" required />
                        </div>
                    </div>

                    <label for="category_id"><?php echo lang('category'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <?php echo form_dropdown('category_id', $categories, set_value('category_id'), 'id="category_id" class="form-control show-tick" required') ?>
                        </div>
                    </div>

                    <label for="image_path"><?php echo lang('image'); ?></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="file" name="image_path" id="image_path" class="form-control" accept="image/*" />
                        </div>
                    </div>
                </div>
                <div class="header">
                    <h2>
                        <?php echo ucwords(lang('inventory_detail')); ?>
                        <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                    </h2>
                </div>
                <div class="body">
                    <div id="warehousesForm">
                        <!-- Form template-->
                        <div class="row" id="warehousesForm_template">
                            <input id="warehousesForm_#index#_id" type="hidden" name="warehouses[#index#][id]" value="new"/>
                            <div class="col-sm-3 m-b-0">
                                <div class="form-group form-group-sm form-float">
                                    <div class="form-line focused">
                                        <?php echo form_dropdown('warehouses[#index#][warehouse_id]', $warehouses_drop, set_value('warehouse_id'), 'id="warehousesForm_#index#_warehouse_id" class="form-control show-tick" required') ?>
                                        <label class="form-label"><?= lang('warehouse') ?> *</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2 m-b-0">
                                <div class="form-group form-group-sm form-float">
                                    <div class="form-line focused">
                                        <select name="warehouses[#index#][measurement_id]" id="warehousesForm_#index#_measurement_id" class="form-control show-tick">
                                            <?php foreach ($types_measures as $type): $type_measure = $type->with(['measurements']) ?>
                                                <optgroup label="<?php echo lang($type->lang); ?>">
                                                    <?php foreach ($type_measure->measurements as $measurement): ?>
                                                        <option value="<?php echo $measurement->id; ?>"><?php echo lang($measurement->lang); ?></option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php endforeach; ?>
                                        </select>
                                        <label class="form-label"><?php echo lang('unit_of_measurement') ?> *</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-2 m-b-0">
                                <div class="form-group form-group-sm form-float">
                                    <div class="input-group">
                                        <span class="input-group-addon" style="font-size: 24px;">
                                            $
                                        </span>
                                        <div class="form-line">
                                            <input type="text" name="warehouses[#index#][cost]" id="warehousesForm_#index#_cost" class="form-control currency" value="" placeholder="<?php echo lang('enter_cost'); ?>" required />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2 m-b-0">
                                <div class="form-group form-group-sm form-float">
                                    <div class="form-line">
                                        <input type="text" name="warehouses[#index#][count]" id="warehousesForm_#index#_count" class="form-control number" value="" placeholder="<?php echo lang('enter_count'); ?>" required />
                                        <label class="form-label"><?php echo lang('initial_amount') ?> *</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2 m-b-0">
                                <div class="form-group form-group-sm form-float">
                                    <div class="form-line">
                                        <input type="text" name="warehouses[#index#][min]" id="warehousesForm_#index#_min" class="form-control number" value="" placeholder="<?php echo lang('enter_min_stock'); ?>" required />
                                        <label class="form-label"><?php echo lang('minimum_in_stock') ?> *</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-1 m-b-0">
                                <a href="javascript:void(0);" id="warehousesForm_remove_current">
                                    <i class="material-icons col-red">close</i>
                                </a>
                            </div>
                        </div>
                        <!-- /Form template-->

                        <!-- No forms template -->
                        <div id="warehousesForm_noforms_template"></div>
                        <!-- /No forms template-->

                        <!-- Controls -->
                        <div id="warehousesForm_controls">
                            <span id="warehousesForm_add"><a href="javascript:void(0);"><i class="material-icons col-blue" style="vertical-align: middle;">add</i> <?php echo lang('add_other_warehouse') ?></a></span>
                        </div>
                        <!-- /Controls -->
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
<span id="warehousesPre" data-warehouses='<?php echo json_encode($warehouses) ?>' ></span>