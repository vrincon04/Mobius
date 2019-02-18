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