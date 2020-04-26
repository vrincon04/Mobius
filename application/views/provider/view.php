<div class="block-header">
    <h2><i class="material-icons" aria-hidden='true'>pageview</i> <?php echo lang('view'); ?> - <?php echo lang('provider') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-xs-12 col-sm-3">
        <div class="card profile-card">
            <div class="profile-header">&nbsp;</div>
            <div class="profile-body">
                <div class="image-area">
                    <img src="<?php echo empty($provider->avatar) ? "http://mobius.test/public/images/tenants/default.jpg" : base_url($provider->avatar_path); ?>" alt="AdminBSB - Profile Image" />
                </div>
                <div class="content-area">
                    <h3><?php echo "{$provider->{$provider->entity_type}->first_name} {$provider->{$provider->entity_type}->last_name}"; ?></h3>
                </div>
            </div>
            <div class="body">
                <ul>
                    <li>
                        <div class="title">
                            <i class="material-icons">event</i>
                            <?php echo lang('dob'); ?>
                        </div>
                        <div class="content">
                            <?php echo strftime('%d %B %Y', strtotime($provider->{$provider->entity_type}->dob)); ?>
                        </div>
                    </li>
                    <li>
                        <div class="title">
                            <i class="material-icons">contact_phone</i>
                            <?php echo lang('contact_phone'); ?>
                        </div>
                        <div class="content">
                            <?php echo $provider->{$provider->entity_type}->phone; ?> <br />
                            <?php echo $provider->{$provider->entity_type}->mobile; ?>
                        </div>
                    </li>
                    <li>
                        <div class="title">
                            <i class="material-icons">location_on</i>
                            <?php echo lang('location'); ?>
                        </div>
                        <div class="content">
                            <?php echo "{$provider->{$provider->entity_type}->city->name}, {$provider->{$provider->entity_type}->address}"; ?>
                        </div>
                    </li>
                    <li>
                        <div class="title">
                            <i class="material-icons">assignment_ind</i>
                            <?php echo lang($provider->{$provider->entity_type}->document_type->lang); ?>
                        </div>
                        <div class="content">
                            <?php echo $provider->{$provider->entity_type}->document_number; ?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-9">
        <div class="card">
            <div class="body">
                <h3>TODO</h3>
            </div>
        </div>
    </div>
</div>
<!-- #END# Vertical Layout -->