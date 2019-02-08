<div class="card">
    <div class="header">
        <h2><i class="material-icons">list</i> <?= lang('payments') ?></h2>
        <ul class="header-dropdown">
            <li>
                <a href="<?= base_url('purchase_payment/create') ?>" class="btn btn-info btn-xs <?= grant_show('purchase_payment', 'create') ?>" data-toggle="tooltip" data-original-title="<?= lang('method_create') ?>">
                    <i class="material-icons col-white">add</i>
                </a>
            </li>
        </ul>
    </div>

    <div class="body">
        <div class="table-responsive">
            <table id="purchase_payments-table" class="table table-bordered table-striped table-hover dataTable js-exportable">
                <thead>
                <tr>
                    <th class="all"><?php echo lang('payment');?> #</th>
                    <th class="all"><?php echo lang('date'); ?></th>
                    <th class="all"><?php echo lang('provider'); ?></th>
                    <th class="all"><?php echo lang('payment_method'); ?></th>
                    <th class="all"><?php echo lang('status'); ?></th>
                    <th class="all"><?php echo lang('amount'); ?></th>
                    <th><?php echo lang('options'); ?></th>
                    <th><?php echo lang('first_name'); ?></th>
                    <th><?php echo lang('last_name'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
