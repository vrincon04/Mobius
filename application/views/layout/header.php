<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?php echo ucwords($this->router->method) . ' - ' . ucwords($this->router->class); ?> | <?php echo $this->config->item('app_name'); ?></title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url('public/plugins/bootstrap/css/bootstrap.css') ?>" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo base_url('public/plugins/node-waves/waves.css') ?>" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo base_url('public/plugins/animate-css/animate.css') ?>" rel="stylesheet" />

    <!-- SweetAlert -->
    <link href="<?= base_url('public/plugins/sweetalert/sweetalert.css') ?>" rel="stylesheet">
	
	<!-- Style that is loaded dynamically according to the need of the content page -->
    <?php if ( isset($styles) ): ?>
		<?php foreach ($styles as $style): ?>
			<link href="<?= base_url(resource_link($style)) ?>" rel="stylesheet">
		<?php endforeach; ?>
	<?php endif; ?>

    <!-- Custom Css -->
    <link href="<?php echo base_url(resource_link('public/css/style.css')) ?>" rel="stylesheet">
    <link href="<?php echo base_url(resource_link('public/css/custom.css')) ?>" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo base_url('public/css/themes/theme-red.min.css') ?>" rel="stylesheet" />

</head>