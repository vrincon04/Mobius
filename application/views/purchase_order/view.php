<div class="block-header">
    <h2><i class="material-icons" aria-hidden='true'>remove_red_eye</i> <?php echo lang('view'); ?> - <?php echo lang('purchase_order') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    <a href="<?php echo base_url('purchase_order'); ?>">
                        <i class="material-icons">keyboard_arrow_left</i>
                        <?php echo lang('purchase_orders'); ?>
                    </a>
                </h2>
                <ul class="header-dropdown m-r--5">
                    <?php if($purchase_order->status != 'close'): ?>
                    <li>
                        <a href="<?php echo base_url("purchase_order/receive/{$purchase_order->id}");?>" class="col-black font-bold m-l-10 m-r-10">
                            <?php echo strtoupper(lang('to_receive')); ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if( $purchase_order->status == 'pending' || $purchase_order->status == 'draft'): ?>
                    <li>
                        <a href="<?php echo base_url("purchase_order/edit/{$purchase_order->id}");?>" class="col-black font-bold m-l-10 m-r-10">
                            <?php echo strtoupper(lang('edit')); ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="javascript:void(0);" class="col-black font-bold m-l-10 m-r-10">
                            <?php echo strtoupper(lang('send')); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url("purchase_order/duplicate/{$purchase_order->id}");?>" class="col-black font-bold m-l-10 m-r-10">
                            <?php echo strtoupper(lang('duplicate')); ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="id" class="font-32 col-black m-b-0"><?php echo "OC" . str_pad($purchase_order->id, 6, '0', STR_PAD_LEFT);  ?></label>
                        <br />
                        <span>
                            <?php echo lang($purchase_order->status); ?> 
                            <small>
                            <?php if ($purchase_order->status === 'close'): ?>
                            (<?php echo ucwords(strftime("%A %d %B %Y, %I:%M %p", strtotime($purchase_order->updated_at))) ?>)
                            <?php endif;?>
                            </small>
                        </span>
                    </div>
                    <div class="col-xs-6">
                        <div class="progress m-b-0" style="height: 10px;">
                            <div class="progress-bar bg-grey" role="progressbar" aria-valuenow="<?php echo number_format($purchase_order->percentage, 2); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo number_format($purchase_order->percentage, 2); ?>%;"></div>
                        </div>
                        <span class="col-grey font-15"><?php echo lang('received') . " " . number_format($purchase_order->starters, 0) . " " . lang('of') . " " . number_format($purchase_order->quantity, 0) . " " . lang('completed_by') . " " . number_format($purchase_order->percentage, 2) . "%"; ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <label for="created_at"><?php echo lang('created_at')?></label>
                        <br>
                        <span><?php echo ucwords(strftime("%A %d %B %Y, %I:%M %p", strtotime($purchase_order->created_at))) ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="date"><?php echo lang('date_of_the_purchase_order')?></label>
                        <br>
                        <span><?php echo ucwords(strftime("%d %B %Y", strtotime($purchase_order->date))) ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="expected_at"><?php echo lang('expected_at')?></label>
                        <br>
                        <span><?php echo ucwords(strftime("%d %B %Y", strtotime($purchase_order->expected_at))) ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <label for="provider"><?php echo lang('provider')?></label>
                        <br>
                        <span><?php echo "{$purchase_order->provider->person->first_name} {$purchase_order->provider->person->last_name}" ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="warehouse"><?php echo lang('warehouse')?></label>
                        <br>
                        <span><?php echo "{$purchase_order->warehouse->name}" ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="ordered_for"><?php echo lang('ordered_for')?></label>
                        <br>
                        <span><?php echo "{$purchase_order->user->person->first_name} {$purchase_order->user->person->last_name}" ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <label for="annotations"><?php echo lang('annotations'); ?></label>
                        <br />
                        <span><?php echo $purchase_order->annotations; ?></span>
                    </div>
                </div>
            </div>

            <div class="header">
                <h2>
                    <?php echo lang('products'); ?>
                </h2>
            </div>
            <div class="body table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 60%;">
                                <?php echo lang('product'); ?>
                            </th>
                            <th style="width: 10%;" class="text-right">
                                <?php echo lang('quantity') ?>
                            </th>
                            <th style="width: 15%;" class="text-right">
                                <?php echo lang('cost') ?>
                            </th>
                            <th style="width: 15%;" class="text-right"><?php echo lang('total') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($purchase_order->details as $detail): $detail->with(['product']) ?>
                            <tr>
                                <td>
                                    <span class="font-15"><?php echo $detail->product->name; ?></span>
                                    <br />
                                    <i class="fa fa-barcode font-12"></i> <span class="font-12"><?php echo $detail->product->code; ?></span>
                                </td>
                                <td class="text-right">
                                    <?php echo number_format($detail->quantity, 0); ?>
                                </td>
                                <td class="text-right">
                                    <?php echo $purchase_order->currency->symbol . number_format($detail->cost, 2); ?>
                                </td>
                                <td class="text-right">
                                    <?php echo $purchase_order->currency->symbol . number_format($detail->quantity * $detail->cost, 2); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td class="font-bold"><?php echo lang('total'); ?></td>
                            <td class="font-bold text-right"><?php echo $purchase_order->currency->symbol . number_format($purchase_order->total, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- #END# Vertical Layout -->