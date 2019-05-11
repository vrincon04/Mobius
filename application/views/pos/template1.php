<div class="block-header">
    <h2><i class="material-icons">add_shopping_cart</i> <?php echo lang('new'); ?> - <?php echo lang('sale') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form id="order-form" method="post" enctype="multipart/form-data" data-validator="true" class="form-validate">
            <input type="hidden" name="status" id="status" value="pending" />
            <div class="card">
                <div class="header">
                    <h2>
                        <?php echo ucwords(lang('sale_information')); ?>
                        <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                    </h2>
                </div>
                <div class="body">
                    <label for="date"><?php echo lang('date'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons" style="font-size: 30px;">date_range</i>
                        </span>
                        <div class="form-line" id="bs_datepicker_container">
                            <input type="text" id="date" name="date" class="form-control input-datepicker" value="<?php echo strftime("%d %B %Y"); ?>" placeholder="<?php echo lang('enter_date'); ?>" required readonly />
                        </div>
                    </div>
                    
                    <label for="customer_id"><?php echo lang('customer'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <select name="customer_id" id="customer_id" class="form-control show-tick" data-live-search="true" require>
                                <?php foreach ($customers as $customer): $customer->with(['person']) ?>
                                    <option value="<?php echo $customer->id; ?>"><?php echo "{$customer->person->first_name} {$customer->person->last_name}"; ?></option>
                                <?php endforeach; ?>
                                <option value="-1"><?php echo "******" . lang('new_customer') . "******"; ?></option>
                            </select>
                        </div>
                    </div>

                    <label for="customer_id"><?php echo lang('dispatcher'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <select name="customer_id" id="customer_id" class="form-control show-tick" data-live-search="true" require>
                                <?php foreach ($employees as $employee): $employee->with(['person']) ?>
                                    <option value="<?php echo $employee->id; ?>"><?php echo "{$employee->person->first_name} {$employee->person->last_name}"; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="body table-responsive">
                    <table id="productsForm" class="table">
                        <thead>
                            <tr>
                                <th style="width: 15%;" class="text-right">
                                    <?php echo lang('quantity') ?> <span class="text-danger">*</span>
                                </th>
                                <th style="width: 50%;">
                                    <i class="fa fa-barcode"></i> <?php echo lang('product'); ?> <span class="text-danger">*</span>
                                </th>
                                <th style="width: 15%;" class="text-right">
                                    <?php echo lang('price') ?>
                                </th>
                                <th style="width: 15%;" class="text-right"><?php echo lang('total') ?></th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Form template-->
                            <tr id="productsForm_template">
                                <td class="text-right">
                                    <div class="form-group m-b-0">
                                        <div class="form-line">
                                            <input type="number" name="products[#index#][quantity]" id="productsForm_#index#_quantity" class="form-control text-right" value="1" placeholder="0" min="1" require />
                                            <input type="hidden" name="products[#index#][sale]" id="productsForm_#index#_sale" class="form-control number text-right" value="0" placeholder="0" />
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input id="productsForm_#index#_id" type="hidden" name="products[#index#][id]" value="new"/>
                                    <div class="form-group m-b-0">
                                        <div class="form-line">
                                            <select name="products[#index#][product_id]" id="productsForm_#index#_product_id" class="form-control ms select2_product" require autofocus></select>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <span id="item-price">0</span>
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

                    <a href="javascript:void(0);" class="btn btn-success waves-effetc m-r-10">
                        <i class="material-icons">payment</i> <?php echo strtoupper(lang('pay')); ?>
                    </a>
                    <a href="#" class="btn btn-info waves-effetc m-r-10" id="send-order-button">
                        <i class="material-icons">receipt</i> <?php echo strtoupper(lang('send_order')); ?>
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