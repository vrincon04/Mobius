<div class="block-header">
    <h2><i class="material-icons" aria-hidden='true'>remove_red_eye</i> <?php echo lang('view'); ?> - <?php echo lang('purchase') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    <a href="<?php echo base_url('purchase'); ?>">
                        <i class="material-icons">keyboard_arrow_left</i>
                        <?php echo lang('purchases'); ?>
                    </a>
                </h2>
                <ul class="header-dropdown m-r--5">
                    <?php if( $purchase->status == 'unpaid' || $purchase->status == 'draft'): ?>
                    <li>
                        <a href="<?php echo base_url("purchase/edit/{$purchase->id}");?>" class="col-black font-bold m-l-10 m-r-10">
                            <?php echo strtoupper(lang('edit')); ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if( $purchase->status == 'unpaid' || $purchase->status == 'partial'): ?>
                    <li>
                        <a href="<?php echo base_url("purchase/edit/{$purchase->id}");?>" class="col-black font-bold m-l-10 m-r-10">
                            <?php echo strtoupper(lang('pay')); ?>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="javascript:void(0);" class="col-black font-bold m-l-10 m-r-10">
                            <?php echo strtoupper(lang('send')); ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="id" class="font-32 col-black m-b-0"><?php echo "C" . str_pad($purchase->id, 6, '0', STR_PAD_LEFT);  ?></label>
                        <br />
                        <span class="<?php echo $purchase->status; ?>">
                            <?php echo ucwords(lang($purchase->status)); ?> 
                            <small>
                            <?php if ($purchase->status === 'paid'): ?>
                            (<?php echo ucwords(strftime("%A %d %B %Y, %I:%M %p", strtotime($purchase->updated_at))) ?>)
                            <?php endif;?>
                            </small>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <label for="provider"><?php echo lang('provider')?></label>
                        <br>
                        <span><?php echo "{$purchase->provider->person->first_name} {$purchase->provider->person->last_name}" ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="type_of_credit"><?php echo lang('type_of_credit')?></label>
                        <br>
                        <span><?php echo lang("{$purchase->expiration_type->lang}") ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="created_by"><?php echo lang('created_by')?></label>
                        <br>
                        <span><?php echo "{$purchase->user->person->first_name} {$purchase->user->person->last_name}" ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <label for="created_at"><?php echo lang('created_at')?></label>
                        <br>
                        <span><?php echo ucwords(strftime("%A %d %B %Y, %I:%M %p", strtotime($purchase->created_at))) ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="date"><?php echo lang('date')?></label>
                        <br>
                        <span><?php echo ucwords(strftime("%d %B %Y", strtotime($purchase->date))) ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="expires_in"><?php echo lang('expires_in')?></label>
                        <br>
                        <span><?php echo ucwords(strftime("%d %B %Y", strtotime($purchase->expired_at))) ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <label for="annotations"><?php echo lang('annotations'); ?></label>
                        <br />
                        <span><?php echo $purchase->annotations; ?></span>
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
                        <?php foreach ($purchase->details as $detail): $detail->with(['product']) ?>
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
                                    <?php echo $purchase->currency->symbol . number_format($detail->cost, 2); ?>
                                </td>
                                <td class="text-right">
                                    <?php echo $purchase->currency->symbol . number_format($detail->quantity * $detail->cost, 2); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td class="font-bold"><?php echo lang('total'); ?></td>
                            <td class="font-bold text-right"><?php echo $purchase->currency->symbol . number_format($purchase->total, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- #END# Vertical Layout -->