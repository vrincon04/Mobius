<div class="card">
    <div class="header">
        <h2><i class="material-icons">list</i> <?= lang('employees') ?></h2>
        <ul class="header-dropdown">
            <li>
                <a href="<?= base_url('employee/create') ?>" class="btn btn-info btn-xs <?php echo grant_show('employee', 'create') ?>" data-toggle="tooltip" data-original-title="<?php echo lang('method_create') ?>">
                    <i class="material-icons col-white">add</i>
                </a>
            </li>
        </ul>
    </div>

    <div class="body">
        <div class="table-responsive">
            <table id="employees-table" class="table table-bordered table-striped table-hover dataTable js-exportable">
                <thead>
                <tr>
                    <th class="all">ID</th>
                    <th class="all"><?= lang('name') ?></th>
                    <th class="all"><?= lang('area') ?></th>
                    <th class="all"><?= lang('username') ?></th>
                    <th><?php echo lang('options') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
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
