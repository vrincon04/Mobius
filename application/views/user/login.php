<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- Title-->
    <title>Iniciar Sesión | <?= $this->config->item('app_name'); ?></title>
    <!-- Favicon-->
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url('public/plugins/bootstrap/css/bootstrap.css'); ?>" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo base_url('public/plugins/node-waves/waves.css'); ?>" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo base_url('public/plugins/animate-css/animate.css'); ?>" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo base_url('public/css/style.css'); ?>" rel="stylesheet">
</head>
<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);"><?php echo $this->config->item('app_name'); ?><b> 2.0</b></a>
            <small>Tu Punto de Venta Agil</small>
        </div>

        <div class="card">
            <div class="body">
                <!-- Alert Mesages -->
                <?php if ( isset($_SESSION['alerts']) ): ?>
                    <?php foreach ( $_SESSION['alerts'] as $alert ): ?>
                        <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <?php echo $alert['message'] ?>
                        </div>
                    <?php endforeach; ?>
                    <?php unset($_SESSION['alerts']); ?>
                <?php endif; ?>
                <!-- / Validation Errors Mesages -->
                <?php if ( validation_errors() ): ?>
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <?= validation_errors() ?>
                    </div>
                <?php endif; ?>
                <form id="sign_in" method="post">
                    <div class="msg"><?php echo lang('sing_in_to_start'); ?></div>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="username" placeholder="<?php echo lang('username'); ?>" required autofocus>
                        </div>
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="<?php echo lang('password'); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme"><?php echo ucwords(lang('remember_me')); ?></label>
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" type="submit"><?php echo strtoupper(lang('sign_in')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    <!-- Jquery Core Js -->
    <script src="<?php echo base_url('public/plugins/jquery/jquery.min.js'); ?>"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url('public/plugins/bootstrap/js/bootstrap.js'); ?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url('public/plugins/node-waves/waves.js'); ?>"></script>

    <!-- Validation Plugin Js -->
    <script src="<?php echo base_url('public/plugins/jquery-validation/jquery.validate.js'); ?>"></script>
    <script src="<?php echo base_url('public/plugins/jquery-validation/localization/messages_es.js'); ?>"></script>

    <!-- Custom Js -->
    <script src="<?php echo base_url('public/js/admin.js'); ?>"></script>
    <script src="<?php echo base_url('public/js/pages/examples/sign-in.js'); ?>"></script>
</body>
</html>