<div class="block-header">
    <h2><i class="material-icons">add</i> <?php echo lang('new'); ?> - <?php echo lang('employee') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form id="employee-form" method="post" enctype="multipart/form-data" class="form-validate">
            <div class="card">
                <div class="header">
                    <h2>
                        <?php echo ucwords(lang('personal_information')); ?>
                        <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                    </h2>
                </div>
                <div class="body">
                    <label for="department_id"><?php echo lang('department'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <?php echo form_dropdown('department_id', $departments, set_value('department_id'), 'id="department_id" class="form-control show-tick" required') ?>
                        </div>
                    </div>

                    <label for="area_id"><?php echo lang('area'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <select class="form-control show-tick" name="area_id" id="area_id" required data-live-search="true">
                                <option value=""><?php echo lang('choose_an_option'); ?></option>
                            </select>
                        </div>
                    </div>

                    <label for="document_type_id"><?php echo lang('document_type'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <?php echo form_dropdown('document_type_id', $documents_types, set_value('document_type_id', 1), 'id="document_type_id" class="form-control show-tick" required') ?>
                        </div>
                    </div>

                    <label for="document_number"><?php echo lang('document_number'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="document_number" name="document_number" class="form-control identification-card" value="<?php echo set_value('document_number'); ?>" placeholder="<?php echo lang('enter_document_number'); ?>" required minlength="2" maxlength="100" />
                        </div>
                    </div>

                    <label for="first_name"><?php echo lang('first_name'); ?> <span class="text-danger">*</span> </label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo set_value('first_name'); ?>" placeholder="<?php echo lang('enter_first_name'); ?>" required minlength="2" maxlength="60" />
                        </div>
                    </div>

                    <label for="middle_name"><?php echo lang('middle_name'); ?></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="middle_name" name="middle_name" class="form-control" value="<?php echo set_value('middle_name'); ?>" placeholder="<?php echo lang('enter_middle_name'); ?>" minlength="2" maxlength="60" />
                        </div>
                    </div>

                    <label for="last_name"><?php echo lang('last_name'); ?> <span class="text-danger">*</span> </label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo set_value('last_name'); ?>" placeholder="<?php echo lang('enter_last_name'); ?>" required minlength="2" maxlength="70" />
                        </div>
                    </div>

                    <label for="last_name2"><?php echo lang('mother_name'); ?></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="last_name2" name="last_name2" class="form-control" value="<?php echo set_value('last_name2'); ?>" placeholder="<?php echo lang('enter_mother_name'); ?>" minlength="2" maxlength="70" />
                        </div>
                    </div>

                    <label for="gender_id"><?php echo lang('gender'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <?php echo form_dropdown('gender_id', $genders, set_value('gender_id'), 'id="gender_id" class="form-control show-tick" required') ?>
                        </div>
                    </div>

                    <label for="dob"><?php echo lang('dob'); ?></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="dob" name="dob" class="datepicker form-control" value="<?php echo set_value('dob'); ?>" placeholder="<?php echo lang('enter_dob'); ?>"  />
                        </div>
                    </div>

                    <label for="country_id"><?php echo lang('country'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <?php echo form_dropdown('country_id', $countries, set_value('country_id'), 'id="country_id" class="form-control show-tick" required') ?>
                        </div>
                    </div>

                    <label for="state_id"><?php echo lang('state'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <select class="form-control show-tick" name="state_id" id="state_id" required data-live-search="true">
                                <option value=""><?php echo lang('choose_an_option'); ?></option>
                            </select>
                        </div>
                    </div>

                    <label for="city_id"><?php echo lang('city'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <select class="form-control show-tick" name="city_id" id="city_id" required data-live-search="true">
                                <option value=""><?php echo lang('choose_an_option'); ?></option>
                            </select>
                        </div>
                    </div>

                    <label for="address"><?php echo lang('address'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="address" name="address" class="form-control" value="<?php echo set_value('address'); ?>" placeholder="<?php echo lang('enter_address'); ?>" required minlength="2" maxlength="250" />
                        </div>
                    </div>

                    <label for="phone"><?php echo lang('phone'); ?> </label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="phone" name="phone" class="form-control mobile-phone-number" value="<?php echo set_value('phone'); ?>" placeholder="<?php echo lang('enter_phone'); ?>" />
                        </div>
                    </div>

                    <label for="mobile"><?php echo lang('mobile'); ?> </label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="mobile" name="mobile" class="form-control mobile-phone-number" value="<?php echo set_value('mobile'); ?>" placeholder="<?php echo lang('enter_mobile'); ?>" />
                        </div>
                    </div>

                    <button class="btn btn-primary waves-effect" type="submit">
                        <i class="material-icons">save</i> <?php echo strtoupper(lang('save')); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- #END# Vertical Layout -->