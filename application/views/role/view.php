<div class="block-header">
    <h2><i class="material-icons" aria-hidden='true'>pageview</i> <?php echo lang('view'); ?> - <?php echo lang('roles') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                        <?php echo $role->name; ?>
                    </div>
                </div>

                <label for="description"><?php echo lang('description'); ?> </label>
                <div class="form-group">
                    <div class="form-line">
                        <?php echo $role->description; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Vertical Layout -->