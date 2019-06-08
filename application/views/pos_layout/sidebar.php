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
        <?php echo sidebar_item('pos', lang('new_sale'), 'add_shopping_cart'); ?>
        <!-- Penden  -->
        <?php echo sidebar_item('pos/list', lang('pending_sales'), 'shopping_cart'); ?>
        <!-- Cash Drawer Group -->
        <li>
            <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block">
                <i class="fas fa-cash-register font-24"></i>
                <span><?php echo lang('cash_drawer') ?></span>
            </a>
            <ul class="ml-menu">
                <!-- Input Cash Drawer -->
                <li>
                    <a href="#" id="input_cash" data-toggle="modal" data-target="#userModal" data-open="#inputModal">
                        <i class="material-icons">call_received</i>
                        <span><?php echo lang('input_cash_drawer') ?></span>
                    </a>
                </li>
                <!-- Output Cash Drawer -->
                <li>
                    <a href="#" id="output_cash" data-toggle="modal" data-target="#userModal" data-open="#outputModal">
                        <i class="material-icons">call_made</i>
                        <span><?php echo lang('output_cash_drawer') ?></span>
                    </a>
                </li>
                <!-- Close Cash Drawer -->
                <li>
                    <a href="#" id="close_cash" data-toggle="modal" data-target="#userModal" data-open="#closeCashDrawerModal">
                        <i class="material-icons">lock</i>
                        <span><?php echo lang('close_cash_drawer') ?></span>
                    </a>
                </li>
            </ul>
        </li>
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
