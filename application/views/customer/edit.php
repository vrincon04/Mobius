<div class="block-header">
    <h2><i class="material-icons">edit</i> <?php echo lang('edit'); ?> - <?php echo lang('customer') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form id="customer-form" method="post" enctype="multipart/form-data" class="form-validate">
            <div class="card">
                <div class="header">
                    <h2>
                        <?php echo ucwords(lang('personal_information')); ?>
                        <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                    </h2>
                </div>
                <div class="body">
                    <label for="document_type_id"><?php echo lang('document_type'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <?php echo form_dropdown('document_type_id', $documents_types, set_value('document_type_id', $customer->person->document_type_id), 'id="document_type_id" class="form-control show-tick" required') ?>
                        </div>
                    </div>

                    <label for="document_number"><?php echo lang('document_number'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="document_number" name="document_number" class="form-control identification-card" value="<?php echo set_value('document_number', $customer->person->document_number); ?>" placeholder="<?php echo lang('enter_document_number'); ?>" required minlength="2" maxlength="100" />
                        </div>
                    </div>

                    <label for="first_name"><?php echo lang('first_name'); ?> <span class="text-danger">*</span> </label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo set_value('first_name', $customer->person->first_name); ?>" placeholder="<?php echo lang('enter_first_name'); ?>" required minlength="2" maxlength="60" />
                        </div>
                    </div>

                    <label for="middle_name"><?php echo lang('middle_name'); ?></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="middle_name" name="middle_name" class="form-control" value="<?php echo set_value('middle_name', $customer->person->middle_name); ?>" placeholder="<?php echo lang('enter_middle_name'); ?>" minlength="2" maxlength="60" />
                        </div>
                    </div>

                    <label for="last_name"><?php echo lang('last_name'); ?> <span class="text-danger">*</span> </label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo set_value('last_name', $customer->person->last_name); ?>" placeholder="<?php echo lang('enter_last_name'); ?>" required minlength="2" maxlength="70" />
                        </div>
                    </div>

                    <label for="last_name2"><?php echo lang('mother_name'); ?></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="last_name2" name="last_name2" class="form-control" value="<?php echo set_value('last_name2', $customer->person->last_name2); ?>" placeholder="<?php echo lang('enter_mother_name'); ?>" minlength="2" maxlength="70" />
                        </div>
                    </div>

                    <label for="gender_id"><?php echo lang('gender'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <?php echo form_dropdown('gender_id', $genders, set_value('gender_id', $customer->person->gender_id), 'id="gender_id" class="form-control show-tick" required') ?>
                        </div>
                    </div>

                    <label for="dob"><?php echo lang('dob'); ?></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="dob" name="dob" class="datepicker form-control" value="<?php echo set_value('dob', date('d/m/Y', strtotime($customer->person->dob))); ?>" placeholder="<?php echo lang('enter_dob'); ?>"  />
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
                            <select class="form-control show-tick" name="city_id" id="city_id" required data-city="<?php echo $customer->person->city_id; ?>" data-live-search="true">
                                <option value=""><?php echo lang('choose_an_option'); ?></option>
                            </select>
                        </div>
                    </div>

                    <label for="address"><?php echo lang('address'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="address" name="address" class="form-control" value="<?php echo set_value('address', $customer->person->address); ?>" placeholder="<?php echo lang('enter_address'); ?>" required minlength="2" maxlength="250" />
                        </div>
                    </div>

                    <label for="email"><?php echo lang('email'); ?> <span class="text-danger">*</span></label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo set_value('email', $customer->email); ?>" placeholder="<?php echo lang('enter_email'); ?>" required data-rule-email="true" maxlength="260" />
                        </div>
                    </div>

                    <label for="phone"><?php echo lang('phone'); ?> </label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="phone" name="phone" class="form-control mobile-phone-number" value="<?php echo set_value('phone', $customer->person->phone); ?>" placeholder="<?php echo lang('enter_phone'); ?>" />
                        </div>
                    </div>

                    <label for="mobile"><?php echo lang('mobile'); ?> </label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" id="mobile" name="mobile" class="form-control mobile-phone-number" value="<?php echo set_value('mobile', $customer->person->mobile); ?>" placeholder="<?php echo lang('enter_mobile'); ?>" />
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