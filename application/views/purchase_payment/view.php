<div class="block-header">
    <h2><i class="material-icons" aria-hidden='true'>remove_red_eye</i> <?php echo lang('view'); ?> - <?php echo lang('purchase_payment') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    <a href="<?php echo base_url('purchase_payment'); ?>">
                        <i class="material-icons">keyboard_arrow_left</i>
                        <?php echo lang('purchase_payments'); ?>
                    </a>
                </h2>
                <ul class="header-dropdown m-r--5">
                    <li>
                        <a href="<?php echo base_url("purchase_payment/edit/{$purchase_payment->id}");?>" class="col-black font-bold m-l-10 m-r-10">
                            <?php echo strtoupper(lang('edit')); ?>
                        </a>
                    </li>
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
                        <label for="id" class="font-32 col-black m-b-0"><?php echo "PC" . str_pad($purchase_payment->id, 6, '0', STR_PAD_LEFT); ?></label>
                        <br />
                        <span class="<?php echo $purchase_payment->status; ?>">
                            <?php echo lang($purchase_payment->status); ?> 
                            <small class="col-black">
                            (<?php echo ucwords(strftime("%A %d %B %Y, %I:%M %p", strtotime($purchase_payment->created_at))) ?>)
                            </small>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <label for="created_at"><?php echo lang('created_at')?></label>
                        <br>
                        <span><?php echo ucwords(strftime("%A %d %B %Y, %I:%M %p", strtotime($purchase_payment->created_at))) ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="date"><?php echo lang('date')?></label>
                        <br>
                        <span><?php echo ucwords(strftime("%d %B %Y", strtotime($purchase_payment->date))) ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="ordered_for"><?php echo lang('paid_by')?></label>
                        <br>
                        <span><?php echo "{$purchase_payment->user->person->first_name} {$purchase_payment->user->person->last_name}" ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <label for="provider"><?php echo lang('provider')?></label>
                        <br>
                        <span><?php echo "{$purchase_payment->provider->person->first_name} {$purchase_payment->provider->person->last_name}" ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="payment_method"><?php echo lang('payment_method')?></label>
                        <br>
                        <span><?php echo lang($purchase_payment->payment_method->lang) ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="payment_method"><?php echo lang('payment_method')?></label>
                        <br>
                        <span><?php echo "{$purchase_payment->currency->code} ({$purchase_payment->currency->symbol})" ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <label for="annotations"><?php echo lang('annotations'); ?></label>
                        <br />
                        <span><?php echo $purchase_payment->annotations; ?></span>
                    </div>
                </div>
            </div>

            <div class="header">
                <h2>
                    <?php echo lang('purchases'); ?>
                </h2>
            </div>
            <div class="body table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="16%"><?php echo lang('purchase');?> #</th>
                            <th><?php echo lang('date'); ?></th>
                            <th width="18%"class="text-right"><?php echo lang('amount_payable'); ?></th>
                            <th width="18%" class="text-right"><?php echo lang('total_paid'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($purchase_payment->details as $detail): $detail->with(['purchase']) ?>
                            <tr>
                                <td>
                                    <span class="font-15"><?php echo "PC" . str_pad($detail->document_id, 6, '0', STR_PAD_LEFT); ?></span>
                                </td>
                                <td>
                                    <?php echo ucwords(strftime("%d %B %Y", strtotime($detail->purchase->date))); ?>
                                </td>
                                <td class="text-right">
                                    <?php echo $purchase_payment->currency->symbol . number_format($detail->purchase->total, 0); ?>
                                </td>
                                <td class="text-right">
                                    <?php echo $purchase_payment->currency->symbol . number_format($detail->amount, 0); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td class="font-bold"><?php echo lang('total'); ?></td>
                            <td class="font-bold text-right"><?php echo $purchase_payment->currency->symbol . number_format($purchase_payment->amount, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- #END# Vertical Layout -->