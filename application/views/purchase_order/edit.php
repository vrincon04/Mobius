<div class="block-header">
    <h2><i class="material-icons">edit</i> <?php echo lang('edit'); ?> - <?php echo lang('purchase_order') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form id="purchase_order-form" method="post" enctype="multipart/form-data" data-validator="true" class="form-validate">
            <input type="hidden" name="status" id="status" value="pending" />
            <div class="card">
                <div class="header">
                    <h2>
                        <?php echo ucwords(lang('personal_information')); ?>
                        <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                    </h2>
                </div>
                <div class="body">
                    <label for="provider_id"><?php echo lang('provider'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <select name="provider_id" id="provider_id" class="form-control ms select2" require></select>
                        </div>
                    </div>

                    <label for="warehouse_id"><?php echo lang('warehouse'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <?php echo form_dropdown('warehouse_id', $warehouses, set_value('warehouse_id', $purchase_order->warehouse_id), 'id="warehouse_id" class="form-control show-tick" required') ?>
                        </div>
                    </div>

                    <label for="currency_id"><?php echo lang('currency'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <?php echo form_dropdown('currency_id', $currencies, set_value('currency_id', $purchase_order->currency_id), 'id="currency_id" data-live-search="true" class="form-control show-tick" required') ?>
                        </div>
                    </div>

                    <label for="date"><?php echo lang('date_of_the_purchase_order'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons" style="font-size: 30px;">date_range</i>
                        </span>
                        <div class="form-line" id="bs_datepicker_container">
                            <input type="text" id="date" name="date" class="form-control input-datepicker" value="<?php echo strftime("%d %B %Y", strtotime(set_value('date', $purchase_order->date))); ?>" placeholder="<?php echo lang('enter_date'); ?>" required />
                        </div>
                    </div>

                    <label for="expected_at"><?php echo lang('expected_at'); ?> <span class="text-danger">*</span> </label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons" style="font-size: 30px;">date_range</i>
                        </span>
                        <div class="form-line">
                            <input type="text" id="expected_at" name="expected_at" class="form-control input-datepicker" value="<?php echo strftime("%d %B %Y", strtotime(set_value('date', $purchase_order->expected_at))); ?>" placeholder="<?php echo lang('enter_expected_at'); ?>" required datepickerGreaterThan='{"dateStart" : "#date", "dateEnd" : "#expected_at"}' />
                        </div>
                    </div>

                    <label for="annotations"><?php echo lang('annotations'); ?> </label>
                    <div class="form-group">
                        <div class="form-line">
                            <textarea rows="1" name="annotations" id="annotations" class="form-control no-resize auto-growth" placeholder="<?php echo lang('enter_annotations'); ?>"><?php echo set_value('annotations', $purchase_order->annotations); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="header">
                    <h2>
                        <?php echo ucwords(lang('product_information')); ?>
                        <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                    </h2>
                </div>
                <div class="body table-responsive">
                    <table id="productsForm" class="table">
                        <thead>
                            <tr>
                                <th style="width: 40%;">
                                    <?php echo lang('product'); ?> <span class="text-danger">*</span>
                                </th>
                                <th class="text-right"><?php echo lang('in_stock') ?></th>
                                <th class="text-right"><?php echo lang('starters') ?></th>
                                <th style="width: 11%;" class="text-right">
                                    <?php echo lang('quantity') ?> <span class="text-danger">*</span>
                                </th>
                                <th style="width: 14%;" class="text-right">
                                    <?php echo lang('cost') ?> <span class="text-danger">*</span>
                                </th>
                                <th style="width: 15%;" class="text-right"><?php echo lang('total') ?></th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Form template-->
                            <tr id="productsForm_template">
                                <td>
                                    <input id="productsForm_#index#_id" type="hidden" name="products[#index#][id]" value="new"/>
                                    <div class="form-group m-b-0">
                                        <div class="form-line">
                                            <select name="products[#index#][product_id]" id="productsForm_#index#_product_id" class="form-control ms select2_product" require></select>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <span id="item-in-stock">0</span>
                                </td>
                                <td class="text-right">
                                    <span id="item-starters">0</span>
                                </td>
                                <td class="text-right">
                                    <div class="form-group m-b-0">
                                        <div class="form-line">
                                            <input type="text" name="products[#index#][quantity]" id="productsForm_#index#_quantity" class="form-control number text-right" value="0" placeholder="0" require />
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="form-group m-b-0">
                                        <div class="form-line">
                                            <input type="text" name="products[#index#][cost]" id="productsForm_#index#_cost" class="form-control currency text-right" value="0.00" placeholder="0.00" require />
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                <span id="item-total">0</span>
                                </td>
                                <td class="text-right">
                                    <a href="javascript:void(0);" id="productsForm_remove_current">
                                        <i class="material-icons col-red">delete</i>
                                    </a>
                                </td>
                            </tr>
                            <!-- /Form template-->
                            <!-- No forms template -->
                            <tr id="productsForm_noforms_template">
                                <td colspan="7" ></td>
                            </tr>
                            <!-- /No forms template-->
                            <!-- Controls -->
                            <tr id="productsForm_controls">
                                <td colspan="4">
                                    <span id="productsForm_add"><a href="javascript:void(0);"><i class="material-icons col-blue" style="vertical-align: middle;">add</i> <?php echo lang('add_other_product') ?></a></span>
                                </td>
                                <td class="font-bold font-15"><?php echo lang('total');?></td>
                                <td class="text-right font-bold font-15"><span id="main-total">0.00</span></td>
                                <td>&nbsp;</td>
                            </tr>
                            <!-- /Controls -->
                        </tbody>
                    </table>

                    <a href="<?php echo base_url('purchase_order'); ?>" class="btn btn-danger waves-effetc m-r-10">
                        <i class="material-icons">cancel</i> <?php echo strtoupper(lang('cancel')); ?>
                    </a>
                    <button class="btn btn-primary waves-effect save" type="submit">
                        <i class="material-icons">save</i> <?php echo strtoupper(lang('save')); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- #END# Vertical Layout -->
<span id="productsPre" data-products='<?php echo json_encode($products) ?>' ></span>