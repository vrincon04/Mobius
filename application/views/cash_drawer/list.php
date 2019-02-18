<div class="card">
    <div class="header">
        <h2><i class="material-icons">list</i> <?php echo lang('cash_drawers') ?></h2>
        <ul class="header-dropdown">
            <li>
                <a href="<?php echo base_url('cash_drawer/create') ?>" class="btn btn-info btn-xs <?php echo grant_show('cash_drawer', 'create') ?>" data-toggle="tooltip" data-original-title="<?php echo lang('create') ?>">
                    <i class="material-icons col-white">add</i>
                </a>
            </li>
        </ul>
    </div>

    <div class="body">
        <div class="table-responsive">
            <table id="cash_drawers-table" class="table table-bordered table-striped table-hover dataTable js-exportable">
                <thead>
                <tr>
                    <th class="all">ID</th>
                    <th class="all"><?php echo lang('user'); ?></th>
                    <th class="all"><?php echo lang('opened_by'); ?></th>
                    <th class="all"><?php echo lang('closed_by'); ?></th>
                    <th class="all"><?php echo lang('status'); ?></th>
                    <th class="all"><?php echo lang('opened_at'); ?></th>
                    <th class="all"><?php echo lang('closed_at'); ?></th>
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
                </tr>
                </tbody>
            </table>
        </div>
        
    </div>
</div>
