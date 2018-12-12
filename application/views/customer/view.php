<div class="block-header">
    <h2><i class="material-icons" aria-hidden='true'>pageview</i> <?php echo lang('view'); ?> - <?php echo lang('roles') ?></h2>
</div>
<!-- Vertical Layout -->
<div class="row clearfix">
    <div class="col-xs-12 col-sm-3">
        <div class="card profile-card">
            <div class="profile-header">&nbsp;</div>
            <div class="profile-body">
                <div class="image-area">
                    <img src="<?php echo base_url($user->avatar_path); ?>" alt="AdminBSB - Profile Image" />
                </div>
                <div class="content-area">
                    <h3><?php echo "{$user->person->first_name} {$user->person->last_name}"; ?></h3>
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
                            <?php echo date('d/m/Y', strtotime($user->person->dob)); ?>
                        </div>
                    </li>
                    <li>
                        <div class="title">
                            <i class="material-icons">contact_phone</i>
                            <?php echo lang('contact_phone'); ?>
                        </div>
                        <div class="content">
                            <?php echo $user->person->phone; ?> <br />
                            <?php echo $user->person->mobile; ?>
                        </div>
                    </li>
                    <li>
                        <div class="title">
                            <i class="material-icons">location_on</i>
                            <?php echo lang('location'); ?>
                        </div>
                        <div class="content">
                            <?php echo "{$user->person->city->name}, {$user->person->address}"; ?>
                        </div>
                    </li>
                    <li>
                        <div class="title">
                            <i class="material-icons">assignment_ind</i>
                            <?php echo lang($user->person->document_type->lang); ?>
                        </div>
                        <div class="content">
                            <?php echo $user->person->document_number; ?>
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
                        <li role="presentation" class="active"><a href="#profile_settings" aria-controls="settings" role="tab" data-toggle="tab">Profile Settings</a></li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="profile_settings">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="NameSurname" class="col-sm-2 control-label"><?php echo lang('username'); ?></label>
                                    <div class="col-sm-10">
                                        <div class="form-line focused">
                                            <p style="margin-top: 20px;"><?php echo $user->username; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Email" class="col-sm-2 control-label"><?php echo lang('email'); ?></label>
                                    <div class="col-sm-10">
                                        <div class="form-line focused">
                                            <p style="margin-top: 20px;"><?php echo $user->email; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label for="InputExperience" class="col-sm-2 control-label"><?php echo lang('is_active'); ?></label>

                                    <div class="col-sm-10">
                                        <div class="form-line">
                                            <p><i class="material-icons <?php echo ( $user->status == 'active' ) ? "text-success" : "text-danger"; ?>"><?php echo ( $user->status == 'active' ) ? "check_box" : "cancel"; ?></i></p>
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