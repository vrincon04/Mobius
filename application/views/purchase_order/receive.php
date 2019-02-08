<div class="block-header">
    <h2><i class="material-icons" aria-hidden='true'>inbox</i> <?php echo ucwords(lang('to_receive')); ?> - <?php echo lang('purchase_order') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    <?php echo lang('product'); ?>
                </h2>
                <ul class="header-dropdown m-r--5">
                    <li>
                        <a href="javascript:void(0);" class="col-black font-bold m-l-10 m-r-10">
                            <?php echo strtoupper(lang('mark_all_as_received')); ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="body table-responsive">
                <form id="purchase_order_receive-form" method="post" enctype="multipart/form-data" data-validator="true" class="form-validate">
                    <input type="hidden" name="purchase_order_id" value="<?php echo $purchase_order->id; ?>" />
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 60%;">
                                    <?php echo lang('product'); ?>
                                </th>
                                <th style="width: 10%;" class="text-right">
                                    <?php echo lang('ordered') ?>
                                </th>
                                <th style="width: 15%;" class="text-right">
                                    <?php echo lang('received') ?>
                                </th>
                                <th style="width: 15%;" class="text-right"><?php echo lang('to_receive') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($purchase_order->details as $detail): $detail->with(['product']) ?>
                                <?php if( (float) $detail->quantity != (float) $detail->starters ): ?>
                                <tr>
                                    <td>
                                    <input type="hidden" name="product[<?php echo $detail->id; ?>]" value="<?php echo $detail->product_id; ?>" />
                                        <span class="font-15"><?php echo $detail->product->name; ?></span>
                                        <br />
                                        <span class="font-12"><?php echo $detail->product->code; ?></span>
                                    </td>
                                    <td class="text-right">
                                        <?php echo number_format($detail->quantity, 0); ?>
                                        <input type="hidden" name="quantity[<?php echo $detail->id; ?>]" value="<?php echo $detail->quantity; ?>" />
                                    </td>
                                    <td class="text-right">
                                        <?php echo number_format($detail->starters, 0); ?>
                                        <input type="hidden" name="starters[<?php echo $detail->id; ?>]" value="<?php echo $detail->starters; ?>" />
                                    </td>
                                    <td class="text-right">
                                        <div class="form-group m-b-0">
                                            <div class="form-line">
                                                <input type="text" name="receive[<?php echo $detail->id; ?>]" id="detailsForm_<?php echo $detail->id?>_receive" class="form-control number text-right" value="0.00" placeholder="0.00" require max="<?php echo $detail->quantity - $detail->starters; ?>" />
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <a href="<?php echo base_url('purchase_order'); ?>" class="btn btn-danger waves-effetc m-r-10">
                        <i class="material-icons">cancel</i> <?php echo strtoupper(lang('cancel')); ?>
                    </a>
                    <button class="btn btn-primary waves-effect save" type="submit">
                        <i class="material-icons">inbox</i> <?php echo strtoupper(lang('to_receive')); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- #END# Vertical Layout -->