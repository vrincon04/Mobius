<div class="block-header">
    <h2><i class="material-icons">add</i> <?php echo lang('new'); ?> - <?php echo lang('invoice') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form id="invoice-form" method="post" enctype="multipart/form-data" data-validator="true" class="form-validate">
            <input type="hidden" name="status" id="status" value="pending" />
            <div class="card">
                <div class="header">
                    <h2>
                        <?php echo ucwords(lang('personal_information')); ?>
                        <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                    </h2>
                </div>
                <div class="body">
                    <label for="customer_id"><?php echo lang('customer'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <select name="customer_id" id="customer_id" class="form-control ms select2" require></select>
                        </div>
                    </div>

                    <label for="currency_id"><?php echo lang('currency'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <?php echo form_dropdown('currency_id', $currencies, set_value('currency_id', $this->session->userdata('currency_id')), 'id="currency_id" data-live-search="true" class="form-control show-tick" required') ?>
                        </div>
                    </div>

                    <label for="expiration_type_id"><?php echo lang('type_of_credit'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <select name="expiration_type_id" id="expiration_type_id" class="form-control show-tick" data-live-search="true" required>
                            <?php foreach($expirations_types as $type): ?>
                                <option value="<?php echo $type->id; ?>" data-value="<?php echo $type->value; ?>"><?php echo lang($type->lang); ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <label for="date"><?php echo lang('date'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons" style="font-size: 30px;">date_range</i>
                        </span>
                        <div class="form-line" id="bs_datepicker_container">
                            <input type="text" id="date" name="date" class="form-control input-datepicker" value="<?php echo strftime("%d %B %Y"); ?>" placeholder="<?php echo lang('enter_date'); ?>" required />
                        </div>
                    </div>

                    <label for="expired_at"><?php echo lang('expires_in'); ?> <span class="text-danger">*</span> </label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons" style="font-size: 30px;">date_range</i>
                        </span>
                        <div class="form-line">
                            <input type="text" id="expired_at" name="expired_at" class="form-control input-datepicker" value="<?php echo strftime("%d %B %Y"); ?>" placeholder="<?php echo lang('enter_expired_at'); ?>" required datepickerGreaterThan='{"dateStart" : "#date", "dateEnd" : "#expired_at"}' />
                        </div>
                    </div>

                    <label for="annotations"><?php echo lang('annotations'); ?> </label>
                    <div class="form-group">
                        <div class="form-line">
                            <textarea rows="1" name="annotations" id="annotations" class="form-control no-resize auto-growth" placeholder="<?php echo lang('enter_annotations')?>"><?php echo set_value('annotations'); ?></textarea>
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
                                <th style="width: 55%;">
                                    <?php echo lang('product'); ?> <span class="text-danger">*</span>
                                </th>
                                <th style="width: 11%;" class="text-right">
                                    <?php echo lang('quantity') ?> <span class="text-danger">*</span>
                                </th>
                                <th style="width: 14%;" class="text-right">
                                    <?php echo lang('cost') ?> <span class="text-danger">*</span>
                                </th>
                                <th style="width: 15%;" class="text-right"><?php echo lang('total') ?></th>
                                <th style="width: 5%;">&nbsp;</th>
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
                                <td colspan="5" ></td>
                            </tr>
                            <!-- /No forms template-->
                            <!-- Controls -->
                            <tr id="productsForm_controls">
                                <td colspan="2">
                                    <span id="productsForm_add"><a href="javascript:void(0);"><i class="material-icons col-blue" style="vertical-align: middle;">add</i> <?php echo lang('add_other_product') ?></a></span>
                                </td>
                                <td class="font-bold font-15"><?php echo lang('total');?></td>
                                <td class="text-right font-bold font-15"><span id="main-total">0.00</span></td>
                                <td>&nbsp;</td>
                            </tr>
                            <!-- /Controls -->
                        </tbody>
                    </table>

                    <a href="<?php echo base_url('invoice'); ?>" class="btn btn-danger waves-effetc m-r-10">
                        <i class="material-icons">cancel</i> <?php echo strtoupper(lang('cancel')); ?>
                    </a>
                    <button class="btn btn-info waves-effect m-r-10 draft" type="submit">
                        <i class="material-icons">bookmark</i> <?php echo strtoupper(lang('save_draft')); ?>
                    </button>
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