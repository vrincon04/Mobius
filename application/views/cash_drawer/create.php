<div class="block-header">
    <h2><i class="material-icons">add</i> <?php echo lang('new'); ?> - <?php echo lang('cash_drawer') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form id="cash_drawer-form" method="post" enctype="multipart/form-data" data-validator="true" class="form-validate">
            <input type="hidden" name="status" id="status" value="open" />
            <div class="card">
                <div class="header">
                    <h2>
                        <?php echo ucwords(lang('cash_drawer_information')); ?>
                        <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                    </h2>
                </div>
                <div class="body">

                    <label for="user_id"><?php echo lang('user'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <?php echo form_dropdown('user_id', $users, set_value('user_id'), 'id="user_id" data-live-search="true" class="form-control show-tick" required') ?>
                        </div>
                    </div>

                    <label for="initial_balance"><?php echo lang('initial_balance'); ?> <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon" style="font-size: 24px;">
                            $
                        </span>
                        <div class="form-line">
                            <input type="text" id="initial_balance" name="initial_balance" class="form-control currency" value="<?php echo set_value('initial_balance'); ?>" placeholder="<?php echo lang('enter_price'); ?>" required  />
                        </div>
                    </div>
                </div>
                <div class="body">
                    <button class="btn btn-primary waves-effect m-t-50" type="submit">
                        <i class="material-icons" aria-hidden='true'>save</i> <?php echo strtoupper(lang('save')); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>