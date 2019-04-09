<div class="card">
    <div class="header">
        <h2><i class="material-icons">list</i> <?php echo lang('departments') ?></h2>
        <ul class="header-dropdown">
            <li>
                <a href="<?php echo base_url('department/create') ?>" class="btn btn-info btn-xs <?php echo grant_show('department', 'create') ?>" data-toggle="tooltip" data-original-title="<?php echo lang('create') ?>">
                    <i class="material-icons col-white">add</i>
                </a>
            </li>
        </ul>
    </div>

    <div class="body">
        <table id="departments-table" class="table table-bordered table-striped table-hover dataTable js-exportable" style="width: 100%;">
            <thead>
            <tr>
                <th>
                    <input type="checkbox" id="check-all" class="filled-in chk-col-orange" name="check[]" value="'+data.id+'">
                    <label class="m-b-0" for="check-all"></label>
                </th>
                <th class="all">ID</th>
                <th class="all"><?php echo lang('name') ?></th>
                <th class="all"><?php echo lang('description') ?></th>
                <th class="all"><?php echo lang('active') ?></th>
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
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
