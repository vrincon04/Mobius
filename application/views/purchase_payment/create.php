<div class="block-header">
    <h2><i class="material-icons">add</i> <?php echo lang('new'); ?> - <?php echo lang('purchase_payment') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form id="purchase_payment-form" method="post" enctype="multipart/form-data" data-validator="true" class="form-validate">
            <input type="hidden" name="type" id="type" value="expenses" />
            <input type="hidden" name="amount" id="amount" value="0" />
            <div class="card">
                <div class="header">
                    <h2>
                        <?php echo ucwords(lang('payment_information')); ?>
                        <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                    </h2>
                </div>
                <div class="body">
                    <label for="entity_id"><?php echo lang('provider'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <select name="entity_id" id="entity_id" class="form-control ms select2" require></select>
                        </div>
                    </div>

                    <label for="payment_method_id"><?php echo lang('payment_method'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <?php echo form_dropdown('payment_method_id', $payment_methods, set_value('payment_method_id'), 'id="payment_method_id" data-live-search="true" class="form-control show-tick" required') ?>
                        </div>
                    </div>
                    
                    <label for="currency_id"><?php echo lang('currency'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <?php echo form_dropdown('currency_id', $currencies, set_value('currency_id', $this->session->userdata('currency_id')), 'id="currency_id" data-live-search="true" class="form-control show-tick" required') ?>
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
                        <?php echo ucwords(lang('purcha_information')); ?>
                        <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                    </h2>
                </div>
                <div class="body table-responsive">
                    <table id="productsForm" class="table">
                        <thead>
                            <tr>
                                <th width="5%">
                                    <input type="checkbox" id="check-all" class="filled-in chk-col-orange" name="check[]" value="'+data.id+'">
                                    <label class="m-b-0" for="check-all"></label>
                                </th>
                                <th width="16%"><?php echo lang('purchase');?> #</th>
                                <th><?php echo lang('date'); ?></th>
                                <th><?php echo lang('expires_in'); ?></th>
                                <th width="18%"class="text-right"><?php echo lang('amount_payable'); ?></th>
                                <th width="18%" class="text-right"><?php echo lang('total_to_pay'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="purchases">

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th colspan="5" class="text-right"><span id="total">(--)</span></th>
                            </tr>
                        </tfoot>
                    </table>

                    <a href="<?php echo base_url('purchase_payment'); ?>" class="btn btn-danger waves-effetc m-r-10">
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