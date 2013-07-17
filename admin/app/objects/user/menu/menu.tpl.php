<div class="container">
<h2><?php echo __LBL_ACCOUNT_SETTING__;?></h2>
<ul class="nav nav-tabs" id="my_settings" style="margin-bottom: 0px;">
    <li <?php if ($this->type == 'profile'){ ?> class="active" <?php } ?>>
        <a href="<?php echo strUrl('user/editProfile.php'); ?>"><?php echo __LBL_EDIT_PROFILE__;?></a>
    </li>
    <li  <?php if ($this->type == 'password'){ ?> class="active" <?php } ?>>
        <a href="<?php echo strUrl('user/changePassword.php'); ?>"><?php echo __LBL_CHANGE_PASSWORD__;?></a>
    </li>
    <li class="divider"></li>
    <li><a href="<?php echo strUrl('admin/logout.php'); ?>"><?php echo __LBL_LOGOUT__;?></a></li>
</ul>
</div>