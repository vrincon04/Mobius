<!DOCTYPE html>
<html lang="<?php echo $this->session->userdata('lang'); ?>">

<?php $this->load->view('pos_layout/header'); ?>

<body class="theme-red">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p><?php echo lang('please_wait'); ?>...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->

    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->

    <!-- Top Bar -->
    <nav class="navbar">
        <?php $this->load->view('pos_layout/navbar'); ?>
    <!-- #END# Top Bar -->
    </nav>
    <!-- Section Sidebar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <?php $this->load->view('pos_layout/sidebar'); ?>
        </aside>
        <!-- #END# Left Sidebar -->
    </section>
    <!-- #END# Section Sidebar -->

    <!-- Section Content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Mesages -->
            <?php if ( isset($_SESSION['alerts']) ): ?>
                <?php foreach ( $_SESSION['alerts'] as $alert ): ?>
                    <div class="alert alert-<?php echo $alert['type'] ?> alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <?php echo $alert['message'] ?>
                    </div>
                <?php endforeach; ?>
                <?php unset($_SESSION['alerts']); ?>
            <?php endif; ?>
            <!-- #END# Mesages -->

            <!-- Validation Errors Mesages -->
            <?php if ( validation_errors() ): ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <?= validation_errors() ?>
                </div>
            <?php endif; ?>
            <!-- #END# Validation Errors Mesages -->

            <!-- Content area -->
            <?php if( isset($content) && $content != '' ) { $this->load->view($content); } ?>
            <!-- #END# Content area -->
            <!-- Modal Dialogs ====================================================================================================================== -->
            <!-- Check User Modal -->
            <div class="modal fade" id="userModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <form name="check-user-form" id="check-user-form" role="form" method="post" class="form-validate">
                            <div class="modal-header">
                                <h4 class="modal-title" id="defaultModalLabel">
                                    <i class="material-icons">face</i>
                                    <?php echo lang('auth_user'); ?>
                                </h4>
                            </div>
                            <div class="modal-body">                                
                                <label for="username"><?php echo lang('username'); ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">account_circle</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="text" id="username" name="username" class="form-control" placeholder="<?php echo lang('enter_username'); ?>" required minlength="4" />
                                    </div>
                                </div>
                                
                                <label for="password"><?php echo lang('supervisor_password'); ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">vpn_key</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="<?php echo lang('enter_password'); ?>" required minlength="4" />
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect" id="user-button" data-target=""><?php echo strtoupper(lang('send')); ?></button>
                                <button type="button" class="btn btn-link waves-effect btn-close-modal" data-dismiss="modal"><?php echo strtoupper(lang('close')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Input Cash Drawer Modal -->
            <div class="modal fade" id="inputModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form name="input-form" id="input-form" role="form" method="post" class="form-validate">
                            <div class="modal-header">
                                <h4 class="modal-title" id="defaultModalLabel"><?php echo lang('register_entrance_to_cash_register'); ?></h4>
                            </div>
                            <div class="modal-body">
                                <label for="amount"><?php echo lang('amount'); ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="font-size: 24px;">
                                        $
                                    </span>
                                    <div class="form-line">
                                        <input type="text" id="amount" name="amount" class="form-control currency" value="<?php echo set_value('amount'); ?>" placeholder="<?php echo lang('enter_amount'); ?>" required  />
                                    </div>
                                </div>

                                <label for="description"><?php echo lang('reason_for_entry'); ?> </label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <textarea rows="1" name="description" id="description" class="form-control no-resize auto-growth" placeholder="<?php echo lang('enter_description')?>"><?php echo set_value('description'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect" id="input-button" data-type="entry"><?php echo strtoupper(lang('register_entry')); ?></button>
                                <button type="button" class="btn btn-link waves-effect btn-close-modal" data-dismiss="modal"><?php echo strtoupper(lang('close')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Output Cash Drawer Modal -->
            <div class="modal fade" id="outputModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form name="output-form" id="output-form" role="form" method="post" class="form-validate">
                            <div class="modal-header">
                                <h4 class="modal-title" id="defaultModalLabel"><?php echo lang('recorder_output_to_cash_register'); ?></h4>
                            </div>
                            <div class="modal-body">
                                <label for="amount"><?php echo lang('amount'); ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="font-size: 24px;">
                                        $
                                    </span>
                                    <div class="form-line">
                                        <input type="text" id="amount" name="amount" class="form-control currency" value="<?php echo set_value('amount'); ?>" placeholder="<?php echo lang('enter_amount'); ?>" required  />
                                    </div>
                                </div>

                                <label for="description"><?php echo lang('reason_for_output'); ?> </label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <textarea rows="1" name="description" id="description" class="form-control no-resize auto-growth" placeholder="<?php echo lang('enter_description')?>"><?php echo set_value('description'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect" id="output-button" data-type="output"><?php echo strtoupper(lang('register_output')); ?></button>
                                <button type="button" class="btn btn-link waves-effect btn-close-modal" data-dismiss="modal"><?php echo strtoupper(lang('close')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Close Cash Drawer -->
            <div class="modal fade" id="closeCashDrawerModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form name="close-form" id="close-form" role="form" method="post" class="form-validate">
                            <div class="modal-header">
                                <h4 class="modal-title" id="defaultModalLabel"><?php echo lang('closing_cash_register'); ?></h4>
                            </div>
                            <div class="modal-body">
                                <label for="theoretical"><?php echo lang('theoretical_cash_in_cash_register'); ?></label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="font-size: 24px;">
                                        $
                                    </span>
                                    <div class="form-line">
                                        <input type="text" id="theoretical" name="theoretical" class="form-control currency" value="<?php echo set_value('theoretical'); ?>" placeholder="<?php echo lang('enter_cash'); ?>" disabled  />
                                    </div>
                                </div>
                                <label for="block_cash"><?php echo lang('cash_in_cash_register'); ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="font-size: 24px;">
                                        $
                                    </span>
                                    <div class="form-line">
                                        <input type="text" id="block_cash" name="block_cash" class="form-control currency" value="<?php echo set_value('amount'); ?>" placeholder="<?php echo lang('enter_cash'); ?>" required  />
                                        <input type="hidden" id="status" name="status" value="close" />
                                        <input type="hidden" id="closed_by" name="closed_by" value="" />
                                    </div>
                                </div>

                                <label for="note"><?php echo lang('note'); ?> </label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <textarea rows="1" name="note" id="note" class="form-control no-resize auto-growth" placeholder="<?php echo lang('enter_description')?>"><?php echo set_value('note'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect" id="close-button" data-type="output"><?php echo strtoupper(lang('close_cash_drawer')); ?></button>
                                <button type="button" class="btn btn-link waves-effect btn-close-modal" data-dismiss="modal"><?php echo strtoupper(lang('close')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Payment Modal -->
            <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form name="payment-form" id="payment-form" role="form" method="post" class="form-validate">
                            <div class="modal-header">
                                <h4 class="modal-title" id="defaultModalLabel"><?php echo lang('total_payable'); ?> (<span id="total_payable">$0.00</span>)</h4>
                            </div>
                            <div class="modal-body">

                                <?php if( $this->session->userdata('is_tax') ): ?>
                                <label for="tax_type_id"><?php echo lang('type_voucher'); ?> <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select name="tax_type_id" id="tax_type_id" class="form-control show-tick" data-live-search="true" require>
                                            <?php foreach ($tax_types as $type): ?>
                                                <option value="<?php echo $type->type_id; ?>"><?php echo lang($type->lang); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <label for="payment_method_id"><?php echo lang('payment_method'); ?> <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <?php echo form_dropdown('payment_method_id', $payment_methods, set_value('payment_method_id', 1), 'id="payment_method_id" data-live-search="true" class="form-control show-tick" required') ?>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                    <label for="currency_id"><?php echo lang('currency'); ?> <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <?php echo form_dropdown('currency_id', $currencies, set_value('currency_id', $this->session->userdata('currency_id')), 'id="currency_id" data-live-search="true" class="form-control show-tick" required') ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                                <label for="amount"><?php echo lang('amount'); ?> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="font-size: 24px;">
                                        $
                                    </span>
                                    <div class="form-line">
                                        <input type="text" id="amount" name="amount" class="form-control currency" value="<?php echo set_value('amount'); ?>" placeholder="<?php echo lang('amount'); ?>" required autofocus />
                                    </div>
                                </div>
                                </div>
                                <div class="clearfix"></div>
                                
                                <label for="note"><?php echo lang('note'); ?> </label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <textarea rows="1" name="note" id="note" class="form-control no-resize auto-growth" placeholder="<?php echo lang('enter_description')?>"><?php echo set_value('note'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-info waves-effect" id="payment-button" data-type="payment"><?php echo strtoupper(lang('make_payment')); ?></button>
                                <button type="button" class="btn btn-link waves-effect btn-close-modal" data-dismiss="modal"><?php echo strtoupper(lang('close')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- #END# Modal Dialogs ====================================================================================================================== -->
        </div>
    </section>
    <!-- #END# Section Content -->

    <!-- Jquery Core Js -->
    <script src="<?php echo base_url('public/plugins/jquery/jquery.min.js'); ?>"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('public/plugins/bootstrap/js/bootstrap.js'); ?>"></script>

    <!-- Select Plugin Js -->
    <script src="<?php echo base_url('public/plugins/bootstrap-select/js/bootstrap-select.js'); ?>"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url('public/plugins/jquery-slimscroll/jquery.slimscroll.js'); ?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('public/plugins/node-waves/waves.js'); ?>"></script>

    <!-- Bootstrap Notify Js -->
    <script src="<?= base_url('public/plugins/bootstrap-notify/bootstrap-notify.js'); ?>"></script>

    <!-- SweetAlert Js -->
    <script src="<?= base_url('public/plugins/sweetalert/sweetalert.min.js'); ?>"></script>

    <!-- Momen With Locales Js -->
    <script src="<?= base_url('public/plugins/momentjs/moment-with-locales.min.js'); ?>"></script>
    
    <!-- Accounting Js -->
    <script src="<?= base_url('public/plugins/accounting/accounting.min.js'); ?>"></script>
    <script src="<?= base_url('public/plugins/accounting/accounting.region.js'); ?>"></script>

    <?php if ( isset($scripts) ): ?>
		<?php foreach ($scripts as $script): ?>
			<script src="<?= base_url(resource_link($script)) ?>"></script>
		<?php endforeach; ?>
	<?php endif; ?>

    <!-- Custom Js -->
    <script src="<?php echo base_url('public/js/admin.js'); ?>"></script>
    <script src="<?php echo base_url('public/js/src/app.js'); ?>"></script>
</body>
</html>