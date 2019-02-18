<div class="block-header">
    <h2><i class="material-icons" aria-hidden='true'>remove_red_eye</i> <?php echo lang('view'); ?> - <?php echo lang('cash_drawer') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    <a href="<?php echo base_url('cash_drawer'); ?>">
                        <i class="material-icons">keyboard_arrow_left</i>
                        <?php echo lang('cash_drawers'); ?>
                    </a>
                </h2>
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="id" class="font-32 col-black m-b-0"><?php echo "CR" . str_pad($cash_drawer->id, 6, '0', STR_PAD_LEFT); ?></label>
                        <br />
                        <span class="<?php echo $cash_drawer->status; ?>">
                            <?php echo lang($cash_drawer->status); ?> 
                            <small class="col-black">
                            (<?php echo ucwords(strftime("%A %d %B %Y, %I:%M %p", strtotime( ($cash_drawer->status == 'open') ? $cash_drawer->opened_at : $cash_drawer->closed_at ))) ?>)
                            </small>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <label for="created_at"><?php echo lang('created_at')?></label>
                        <br>
                        <span><?php echo ucwords(strftime("%A %d %B %Y, %I:%M %p", strtotime($cash_drawer->created_at))) ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="opened_at"><?php echo lang('opened_at')?></label>
                        <br>
                        <span><?php echo ucwords(strftime("%A %d %B %Y, %I:%M %p", strtotime($cash_drawer->opened_at))) ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="closed_at"><?php echo lang('closed_at')?></label>
                        <br>
                        <span><?php echo ($cash_drawer->status == 'close') ? ucwords(strftime("%A %d %B %Y, %I:%M %p", strtotime($cash_drawer->closed_at))) : lang('pending'); ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <label for="user"><?php echo lang('user')?></label>
                        <br>
                        <span><?php echo "{$cash_drawer->user->person->first_name} {$cash_drawer->user->person->last_name}" ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="opened_by"><?php echo lang('opened_by')?></label>
                        <br>
                        <span><?php echo "{$cash_drawer->open->person->first_name} {$cash_drawer->open->person->last_name}" ?></span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label for="closed_by"><?php echo lang('closed_by')?></label>
                        <br>
                        <span><?php echo ($cash_drawer->status == 'close') ? "{$cash_drawer->close->person->first_name} {$cash_drawer->close->person->last_name}" : lang('pending') ?></span>
                    </div>
                </div>
            </div>

            <div class="header">
                <h2>
                    <?php echo lang('cash_drawer_detail_information'); ?>
                </h2>
            </div>
            <div class="body table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo lang('description');?></th>
                            <th width="15%"><?php echo lang('payment_method'); ?></th>
                            <th width="10%"><?php echo lang('type'); ?></th>
                            <th width="28%"><?php echo lang('date'); ?></th>
                            <th width="18%" class="text-right"><?php echo lang('amount'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; foreach ($cash_drawer->details as $detail): $detail->with(['payment_method']); $total += $detail->amount ?>
                            <tr>
                                <td>
                                    <?php echo $detail->description; ?>
                                </td>
                                <td>
                                    <?php echo lang($detail->payment_method->lang); ?>
                                </td>
                                <td class="<?php echo $detail->type; ?>">
                                    <?php echo lang($detail->type); ?>
                                </td>
                                <td>
                                    <?php echo ucwords(strftime("%A %d %B %Y, %I:%M %p", strtotime($detail->created_at))); ?>
                                </td>
                                <td class="text-right <?php echo $detail->type; ?>">
                                    <?php echo $cash_drawer->currency->symbol . number_format($detail->amount, 2); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td class="font-bold"><?php echo lang('total'); ?></td>
                            <td class="font-bold text-right"><?php echo $cash_drawer->currency->symbol . number_format($total, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- #END# Vertical Layout -->