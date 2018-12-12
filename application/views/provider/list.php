<div class="card">
    <div class="header">
        <h2><i class="material-icons">list</i> <?= lang('providers') ?></h2>
        <ul class="header-dropdown">
            <li>
                <a href="<?= base_url('provider/create') ?>" class="btn btn-info btn-xs <?= grant_show('provider', 'create') ?>" data-toggle="tooltip" data-original-title="<?= lang('method_create') ?>">
                    <i class="material-icons col-white">add</i>
                </a>
            </li>
        </ul>
    </div>

    <div class="body">
        <div class="table-responsive">
            <table id="providers-table" class="table table-bordered table-striped table-hover dataTable js-exportable">
                <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="check-all" class="filled-in chk-col-orange" name="check[]" value="'+data.id+'">
                        <label class="m-b-0" for="check-all"></label>
                    </th>
                    <th class="all">ID</th>
                    <th class="all"><?= lang('name') ?></th>
                    <th class="all"><?= lang('email') ?></th>
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
