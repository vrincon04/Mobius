<div class="block-header">
    <h2><i class="material-icons" aria-hidden='true'>pageview</i> <?php echo lang('view'); ?> - <?php echo lang('area') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    <?php echo ucwords(lang('area_information')); ?>
                    <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                </h2>
                <ul class="header-dropdown m-r--5">
                        <li id="pos-switch">
                            <div class="switch">
                                <label for="is_pos"><?php echo lang('is_pos_area'); ?>
                                    <b><?php echo ($area->is_pos == "1") ? 'Sí' : 'No'; ?></b>
                                </label>
                            </div>
                        </li>
                    </ul>
            </div>
            <div class="body">
                <label for="name"><?php echo lang('name'); ?> <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="form-line">
                        <?php echo $area->name; ?>
                    </div>
                </div>

                <label for="description"><?php echo lang('description'); ?> </label>
                <div class="form-group">
                    <div class="form-line">
                        <?php echo $area->description; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Vertical Layout -->