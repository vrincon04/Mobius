<div class="block-header">
    <h2><i class="material-icons">add</i> <?php echo lang('new'); ?> - <?php echo lang('roles') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form id="role-form" method="post" enctype="multipart/form-data" class="form-validate">
            <div class="card">
                <div class="header">
                    <h2>
                        <?php echo ucwords(lang('role_information')); ?>
                        <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                    </h2>
                </div>
                <div class="body">
                    <label for="name"><?php echo lang('name'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo set_value('name'); ?>" placeholder="<?php echo lang('enter_name'); ?>" autofocus required minlength="2" maxlength="100" />
                        </div>
                    </div>

                    <label for="description"><?php echo lang('description'); ?> </label>
                    <div class="form-group">
                        <div class="form-line">
                            <textarea rows="1" name="description" id="description" class="form-control no-resize auto-growth" placeholder="<?php echo lang('enter_description')?>"></textarea>
                        </div>
                    </div>
                    
                    <button class="btn btn-primary waves-effect" type="submit">
                        <i class="material-icons" aria-hidden='true'>save</i> <?php echo strtoupper(lang('save')); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- #END# Vertical Layout -->