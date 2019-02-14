<div class="card">
    <div class="header">
        <h2><i class="material-icons">list</i> <?php echo lang('products') ?></h2>
        <ul class="header-dropdown">
            <li>
                <a href="<?php echo base_url('product/create') ?>" class="btn btn-info btn-xs <?php echo grant_show('product', 'create') ?>" data-toggle="tooltip" data-original-title="<?php echo lang('create') ?>">
                    <i class="material-icons col-white">add</i>
                </a>
            </li>
        </ul>
    </div>

    <div class="body">
        <div class="table-responsive">
            <table id="products-table" class="table table-bordered table-striped table-hover dataTable js-exportable">
                <thead>
                <tr>
                    <th class="all">ID</th>
                    <th class="all"><?php echo lang('code'); ?></th>
                    <th class="all"><?php echo lang('name'); ?></th>
                    <th class="all"><?php echo lang('category'); ?></th>
                    <th class="all"><?php echo lang('price'); ?></th>
                    <!--th class="all"><?php echo lang('wholesale_price'); ?></th>
                    <th class="all"><?php echo lang('quantity_for_wholesale'); ?></th-->
                    <th class="all"><?php echo lang('cost'); ?></th>
                    <th class="all"><?php echo lang('in_stock'); ?></th>
                    <th><?php echo lang('options') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <!--td></td>
                    <td></td-->
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
