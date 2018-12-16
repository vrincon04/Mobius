<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-xs-12 col-sm-3">
        <div class="card profile-card">
            <div class="profile-header">&nbsp;</div>
            <div class="profile-body">
                <div class="image-area">
                    <img src="<?php echo base_url($tenant->logo_path); ?>" alt="AdminBSB - Profile Image" />
                </div>
                <div class="content-area">
                    <h3>
                        <?php echo $tenant->name; ?> 
                        <br>
                        <small><b>RNC: </b><?php echo $tenant->tin;?></small>
                    </h3>
                </div>
            </div>
            <div class="body">
                <ul>
                    <li>
                        <div class="title">
                            <i class="material-icons">event</i>
                            <?php echo lang('opening_date'); ?>
                        </div>
                        <div class="content">
                            <?php echo date('d/m/Y', strtotime($tenant->opening_at)); ?>
                        </div>
                    </li>
                    <li>
                        <div class="title">
                            <i class="material-icons">contact_phone</i>
                            <?php echo lang('contact'); ?>
                        </div>
                        <div class="content">
                            <i class="material-icons special-icons">phone</i> <?php echo $tenant->phone; ?>
                            <br />
                            <i class="material-icons special-icons">email</i> <?php echo $tenant->email; ?>
                            <br />
                            <i class="material-icons special-icons">link</i> <?php echo $tenant->web; ?>
                        </div>
                    </li>
                    <li>
                        <div class="title">
                            <i class="material-icons">location_on</i>
                            <?php echo lang('location'); ?>
                        </div>
                        <div class="content">
                            <?php echo "{$tenant->city->name}, {$tenant->address}"; ?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-9">
        <div class="card">
            <div class="body">
                <div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#company_information" aria-controls="settings" role="tab" data-toggle="tab">
                                <span><?php echo ucwords(lang('company_information')); ?></span>
                                <br />
                                <small>Básica - Contacto - Ubicación</small>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#taxes" aria-controls="taxes" role="tab" data-toggle="tab">
                                <span><?php echo ucwords(lang('taxes')); ?></span>
                                <br />
                                <small>RNC - Números Fiscales</small>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="company_information">
                            <form id="form-setting" class="form-validate" method="post" enctype="multipart/form-data" action="<?php echo base_url('tenant/updateInfo'); ?>">
                                <div>
                                    <div class="header">
                                        <h2>
                                            <?php echo ucwords(lang('basic_information')); ?>
                                            <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                                        </h2>
                                    </div>
                                    <div class="body">
                                        <label for="name"><?php echo lang('name'); ?> <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="name" name="name" class="form-control" value="<?php echo set_value('name', $tenant->name); ?>" placeholder="<?php echo lang('enter_name'); ?>" required maxlength="250" />
                                            </div>
                                        </div>
                                        <label for="business_name"><?php echo lang('business_name'); ?> <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="business_name" name="business_name" class="form-control" value="<?php echo set_value('business_name', $tenant->business_name); ?>" placeholder="<?php echo lang('enter_business_name'); ?>" required maxlength="250" />
                                            </div>
                                        </div>
                                            <label for="opening_at"><?php echo lang('opening_date'); ?></label>
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="opening_at" name="opening_at" class="datepicker form-control" value="<?php echo set_value('opening_at', date('d/m/Y', strtotime($tenant->opening_at))); ?>" placeholder="<?php echo lang('enter_opening_at'); ?>"  />
                                                </div>
                                            </div>
                                    </div>

                                    <div class="header">
                                        <h2>
                                            <?php echo ucwords(lang('system_information')); ?>
                                            <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                                        </h2>
                                    </div>
                                    <div class="body">
                                        <label for="timezone_id"><?php echo lang('timezone'); ?> <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select name="timezone_id" id="timezone_id" class="form-control show-tick" data-live-search="true" data-show-subtext="true" required>
                                                <?php foreach($timezones as $timezone):?>
                                                    <option value="<?php echo $timezone->id?>" data-subtext="<?php echo $timezone->format; ?>" <?php echo ($timezone->id === $tenant->timezone_id) ? 'selected' : ''; ?>><?php echo $timezone->name; ?></option>
                                                <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>
                                        <label for="currency_id"><?php echo lang('currency'); ?> <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select name="currency_id" id="currency_id" class="form-control show-tick" data-live-search="true" data-show-subtext="true" required>
                                                <?php foreach($currencies as $currency):?>
                                                    <option value="<?php echo $currency->id?>" data-subtext="<?php echo $currency->name; ?>" <?php echo ($currency->id === $tenant->currency_id) ? 'selected' : ''; ?>><?php echo $currency->code; ?></option>
                                                <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>
                                        <label for="date_format_id"><?php echo lang('date_format'); ?> <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <?php echo form_dropdown('date_format_id', $date_formats, set_value('date_format_id', $tenant->date_format_id), 'id="date_format_id" class="form-control show-tick" required') ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="header">
                                        <h2>
                                            <?php echo ucwords(lang('contact_information')); ?>
                                            <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                                        </h2>
                                    </div>
                                    <div class="body">
                                        <label for="phone"><?php echo lang('phone'); ?> <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="phone" name="phone" class="form-control mobile-phone-number" value="<?php echo set_value('phone', $tenant->phone); ?>" placeholder="<?php echo lang('enter_phone'); ?>" required />
                                            </div>
                                        </div>
                                        <label for="email"><?php echo lang('email'); ?></label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="email" id="email" name="email" class="form-control" value="<?php echo set_value('email', $tenant->email); ?>" placeholder="<?php echo lang('enter_email'); ?>" data-rule-email="true" maxlength="260" />
                                            </div>
                                        </div>
                                        <label for="web"><?php echo lang('website'); ?></label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="url" id="web" name="web" class="form-control" value="<?php echo set_value('web', $tenant->web); ?>" placeholder="<?php echo lang('enter_website'); ?>" data-url-email="true" maxlength="260" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="header">
                                        <h2>
                                            <?php echo ucwords(lang('location_information')); ?>
                                            <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                                        </h2>
                                    </div>
                                    <div class="body">
                                        <label for="country_id"><?php echo lang('country'); ?> <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <?php echo form_dropdown('country_id', $countries, set_value('country_id', $tenant->city->state->country_id), 'id="country_id" class="form-control show-tick" required') ?>
                                            </div>
                                        </div>

                                        <label for="state_id"><?php echo lang('state'); ?> <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <?php echo form_dropdown('state_id', $states, set_value('state_id', $tenant->city->state_id), 'id="state_id" class="form-control show-tick" required data-live-search="true"') ?>
                                            </div>
                                        </div>

                                        <label for="city_id"><?php echo lang('city'); ?> <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <?php echo form_dropdown('city_id', $cities, set_value('city_id', $tenant->city_id), 'id="city_id" class="form-control show-tick" required data-live-search="true"') ?>
                                            </div>
                                        </div>

                                        <label for="address"><?php echo lang('address'); ?> <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="address" name="address" class="form-control" value="<?php echo set_value('address', $tenant->address); ?>" placeholder="<?php echo lang('enter_address'); ?>" required minlength="2" maxlength="250" />
                                            </div>
                                        </div>

                                        <button class="btn btn-primary waves-effect" type="submit">
                                            <i class="material-icons">save</i> <?php echo strtoupper(lang('save')); ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="taxes">
                            <form id="form-taxes" class="form-validate" method="post" enctype="multipart/form-data">
                                <div>
                                    <div class="header">
                                        <h2>
                                            <?php echo ucwords(lang('taxes_information')); ?>
                                            <small>Los campos marcodo con <span class="text-danger">*</span> son requerido.</small>
                                        </h2>
                                    </div>
                                    <div class="body">
                                        <label for="tin"><?php echo lang('tin'); ?> <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="tin" name="tin" class="form-control" value="<?php echo set_value('tin', $tenant->tin); ?>" placeholder="<?php echo lang('enter_tin'); ?>" required maxlength="250" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #END# Vertical Layout -->