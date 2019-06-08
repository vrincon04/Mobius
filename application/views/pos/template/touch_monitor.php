<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-xs-12 col-sm-5">
        <div class="card">
            <div class="body" style="height:90vh; padding: 20px 0px!important;" id="info-table">
                <input type="hidden" name="id" id="id" value="0" />
                <div class="form-group">
                    <div class="form-line">
                        <select name="customer_id" id="customer_id" class="form-control show-tick" data-live-search="true" require>
                            <?php foreach ($customers as $customer): $customer->with(['person']) ?>
                                <option value="<?php echo $customer->id; ?>"><?php echo "{$customer->person->first_name} {$customer->person->last_name}"; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-line">
                    <select name="search_product" id="search_product" class="form-control ms select2_product" autofocus data-placeholder="<?php echo lang('search_product'); ?>"></select>
                    </div>
                </div>

                <div class="table-responsive font-12">
                    <table id="productsForm" class="table" style="display: block;">
                        <thead style="display: inline-block; width: 100%;">
                            <tr>
                                <th style="width: 37%;">
                                    <?php echo lang('product'); ?>
                                </th>
                                <th style="width: 22%;" class="text-right">
                                    <?php echo lang('price') ?>
                                </th>
                                <th style="width: 11%;" class="text-right">
                                    <?php echo lang('qty') ?>
                                </th>
                                <th style="width: 22%;" class="text-right"><?php echo lang('subtotal') ?></th>
                                <th><i class="material-icons col-red font-12">delete</i></th>
                            </tr>
                        </thead>
                        <tbody class="scrollbar style-3" style="height: 200px; display: inline-block; width: 100%; overflow: auto;" id="product-table"></tbody>
                        <tfoot style="display: inline-block; width: 100%;">
                            <tr>
                                <th style="width:70%;"><?php echo lang('subtotal'); ?></th>
                                <th style="width:26%;" class="text-right" id="main-subtotal">$0.00</th>
                                <th>&nbsp;</th>
                            </tr>
                            <?php if( $this->session->userdata('is_tax') ): ?>
                            <tr>
                                <th class="no-border-top"><?php echo lang('tax'); ?>(18%)</th>
                                <th class="no-border-top text-right" id="main-tax">$0.00</th>
                                <th class="no-border-top">&nbsp;</th>
                            </tr>
                            <?php endif;?>
                            <tr>
                                <th class="no-border-top"><?php echo lang('total'); ?></th>
                                <th class="no-border-top text-right" id="main-total">$0.00</th>
                                <th class="no-border-top">&nbsp;</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div id="footer-button">
                    <a href="javascript:void(0);" class="btn btn-primary waves-effetc" id="save-order-button">
                        <i class="material-icons">save</i> <?php echo strtoupper(lang('save')); ?> <b>[F1]</b>
                    </a>
                    
                    <a href="javascript:void(0);" class="btn btn-info waves-effetc" id="print-order-button">
                        <i class="material-icons">receipt</i> <?php echo strtoupper(lang('print_order')); ?> <b>[F2]</b>
                    </a>

                    <a href="javascript:void(0);" class="btn btn-success waves-effetc" id="pay-order-button" data-toggle="modal" data-target="#paymentModal" data-open="#paymentModal">
                        <i class="material-icons">payment</i> <?php echo strtoupper(lang('pay')); ?> <b>[F3]</b>
                    </a>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-7">
        <div class="card">
            <div class="body scrollbar style-3 flex-container" id="product-card" style="height: 90vh;"></div>
        </div>
    </div>
</div>
<!-- #END# Vertical Layout -->