<!-- User Info -->
<div class="user-info">
    <div class="image">
        <img src="<?php echo base_url($this->session->userdata('avatar'));?>" width="68" height="68" alt="User" />
    </div>
    <div class="info-container">
        <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $this->session->userdata('short_name'); ?></div>
        <div class="email"><?php echo $this->session->userdata('email'); ?></div>
        <div class="btn-group user-helper-dropdown">
            <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
            <ul class="dropdown-menu pull-right">
                <li><a href="javascript:void(0);"><i class="material-icons">person</i><?php echo lang('profile'); ?></a></li>
                <li role="separator" class="divider"></li>
                <li><a href="javascript:void(0);"><i class="material-icons">add_shopping_cart</i><?php echo lang('new_sale'); ?></a></li>
                <li><a href="javascript:void(0);"><i class="material-icons">shopping_cart</i><?php echo lang('sales'); ?></a></li>
                <li><a href="javascript:void(0);"><i class="material-icons">remove_shopping_cart</i><?php echo lang('pending_sales'); ?></a></li>
                <li role="separator" class="divider"></li>
                <li><a href="<?php echo base_url('user/logout'); ?>"><i class="material-icons">input</i><?php echo lang('sign_out'); ?></a></li>
            </ul>
        </div>
    </div>
</div>
<!-- #END# User Info -->
<!-- Menu -->
<div class="menu">
    <ul class="list">
        <!-- MAIN NAVIGATION -->
        <li class="header"><?php echo lang('main_navigation'); ?></li>
        <!-- Dashboard -->
        <?php echo sidebar_item('welcome', lang('dashboard'), 'dashboard'); ?>
        <!-- Expenses Group -->
        <li>
            <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                <i class="material-icons">call_made</i>
                <span><?php echo lang('expenses') ?></span>
            </a>
            <ul class="ml-menu">
                <!-- Order Model -->
                <?php echo sidebar_item('purchase_order', lang('purchase_order'), 'description') ?>
                <!-- Purchases Model -->
                <?php echo sidebar_item('purchase', lang('purchase'), 'shopping_cart'); ?>
                <!-- Payments Model -->
                <?php echo sidebar_item('purchase_payment', lang('payment'), 'payment'); ?>
            </ul>
        </li>
        <!-- Incomes Group -->
        <li>
            <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                <i class="material-icons">call_received</i>
                <span><?php echo lang('incomes') ?></span>
            </a>
            <ul class="ml-menu">
                <!-- Cash Drawer Model -->
                <?php echo sidebar_item('cash_drawer', lang('cash_drawer'), 'fas fa-cash-register font-24', '', false) ?>
                <!-- Purchases Model -->
                <?php echo sidebar_item('order', lang('order'), 'fas fa-receipt font-24', '', false) ?>
                <!-- Invoices Model -->
                <?php echo sidebar_item('invoice', lang('invoice'), 'fas fa-file-invoice font-24', '', false) ?>
            </ul>
        </li>
        <!-- Contacts Group -->
        <li>
            <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                <i class="material-icons">contacts</i>
                <span><?php echo lang('contacts') ?></span>
            </a>
            <ul class="ml-menu">
                <!-- Providers Model -->
                <?php echo sidebar_item('provider', lang('provider'), 'local_shipping'); ?>
                <!-- Customers Model -->
                <?php echo sidebar_item('customer', lang('customer'), 'sentiment_very_satisfied'); ?>
            </ul>
        </li>
        <!-- Articles Group -->
        <li>
            <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                <i class="material-icons">shopping_basket</i>
                <span><?php echo lang('articles') ?></span>
            </a>
            <ul class="ml-menu">
                <!-- Product Model -->
                <?php echo sidebar_item('product', lang('product'), 'shopping_basket'); ?>
                <!-- Category Model -->
                <?php echo sidebar_item('category', lang('category'), 'group_work'); ?>
                <!-- Warehouse Model -->
                <?php echo sidebar_item('warehouse', lang('warehouse'), 'local_convenience_store'); ?>
            </ul>
        </li>
        <!-- #END# MAIN NAVIGATION -->

        <!-- REPORT NAVIGATION -->
        <li class="header"><?php echo lang('report_navigation'); ?></li>
        <!-- #END# REPORT NAVIGATION -->

        <!-- CONFIGURATIONS -->
        <li class="header"><?php echo lang('configurations'); ?></li>
        <!-- User Module -->
        <?php echo sidebar_item('user', lang('users'), 'person') ?>
        <!-- Rol Module -->
        <?php echo sidebar_item('role', lang('roles'), 'vpn_lock') ?>
        <!-- Grant Module -->
        <?php echo sidebar_item('grant', lang('grants'), 'security') ?>
        <!-- tenant Module -->
        <?php echo sidebar_item('tenant', lang('company'), 'business') ?>
        <!-- #END# CONFIGURATIONS -->
    </ul>
</div>
<!-- #Menu -->
<!-- Footer -->
<div class="legal">
    <div class="copyright">
        &copy; 2016 - <?php echo date('Y'); ?> <a href="<?php echo $this->config->item('powered_by_url'); ?>"><?php echo $this->config->item('app_name'); ?> - <?php echo $this->config->item('powered_by'); ?></a>.
    </div>
    <div class="version">
        <b>Version: </b> <?php echo $this->config->item('version'); ?>
    </div>
</div>
<!-- #Footer -->