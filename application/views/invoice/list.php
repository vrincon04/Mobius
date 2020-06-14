<div class="card">
    <div class="header">
        <h2><i class="material-icons">list</i> <?= lang('invoices') ?></h2>
        <ul class="header-dropdown">
            <li>
                <a href="<?= base_url('invoice/create') ?>" class="btn btn-info btn-xs <?= grant_show('invoice', 'create') ?>" data-toggle="tooltip" data-original-title="<?= lang('method_create') ?>">
                    <i class="material-icons col-white">add</i>
                </a>
            </li>
        </ul>
    </div>

    <div class="body">
        <div class="table-responsive">
            <table id="invoices-table" class="table table-bordered table-striped table-hover dataTable js-exportable">
                <thead>
                <tr>
                    <th class="all"><?php echo lang('invoice');?> #</th>
                    <th class="all"><?php echo lang('date'); ?></th>
                    <th class="all"><?php echo lang('customer'); ?></th>
                    <th class="all"><?php echo lang('status'); ?></th>
                    <th class="all"><?php echo lang('expires_in'); ?></th>
                    <th class="all"><?php echo lang('total'); ?></th>
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
