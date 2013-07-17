<?php
global $config_database;
global $tableAlias;
$auth = Auth::singleton();
?>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container"><a class="btn btn-navbar"
                                  data-toggle="collapse" data-target=".nav-collapse"> <span
                    class="icon-bar"></span> <span class="icon-bar"></span> <span
                    class="icon-bar"></span> </a> <a class="brand" href="<?php echo strUrl('admin/dashboard'); ?>"><?php echo __LBL_PROJECT_NAME__; ?></a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li <?php if ($this->type == 'dashboard') { ?>class="active"<?php } ?>><a href="<?php echo strUrl('admin/dashboard'); ?>"><?php echo __LBL_MAIN__; ?></a></li>
                    <?php if ((int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] != 0 || 
                    		(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] != 0) { ?>
                        <li class="dropdown <?php if ($this->type == 'user') { ?>active<?php } ?>">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">Users <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                            	<?php if ((int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 1 || 
                            			 (int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 3 ||
                            			 (int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 1 || 
                            			 (int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 3) { ?>
	                                <li><a href="<?php echo strUrl('admin/user/user.php'); ?>"><?php echo __LBL_USER_MANAGER__;?></a></li>
	                                <li><a href="<?php echo strUrl('admin/user/group.php'); ?>"><?php echo __LBL_USER_GROUPS__;?></a></li>
	                                <li><a href="<?php echo strUrl('admin/user/permission.php'); ?>"><?php echo __LBL_USER_PERMISSIONS__;?></a></li>
                                <?php } ?>
                    			<?php if ((int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 2 || 
                    					 (int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 3 ||
                    					 (int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 2 || 
                    					 (int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 3 ) { ?>            
                                	<li><a href="<?php echo strUrl('admin/table/index.php'); ?>"><?php echo __LBL_USER_TABLES__;?></a></li>
                                <?php } ?>
                                
                            </ul>
                        </li>
                    <?php } ?>
                    <li class="dropdown  <?php if ($this->type == 'browse') { ?>active<?php } ?>"  id="mnu_browse" style="display:none;">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo __LBL_BROWSER__; ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="nav-header"><?php echo __LBL_BROWSE_TABLE__;?></li>
                            <?php
                            foreach ($this->tables as $k => $table) {
								$permissions = $auth->getPermissionType($table);
                                if ($table == 'cruds')
                                    continue;
                                if (strpos($table, 'crud_') !== false)
                                    continue;
                                if (!in_array(4, $permissions)) continue;
                                $rs1 = null;
                                if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $table . '.php')) {
                                    $rs1 = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $table . '.php'));
                                }
                                ?>
                                <li <?php if (empty($rs1)) { ?>class="disabled"<?php } ?> >
                                    <a <?php if (empty($rs1)) { ?>onclick="return false;"<?php } ?> href="<?php echo strUrl('admin/scrud/browse.php?table='.$table); ?>" ><?php echo (isset($tableAlias[$table]))?$tableAlias[$table]:$table; ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li class="dropdown  <?php if ($this->type == 'config') { ?>active<?php } ?>" id="mnu_config" style="display:none;">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo __LBL_CONFIG__; ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="nav-header"><?php echo __LBL_TABLE__;?></li>
                            <?php
                            foreach ($this->tables as $k => $table) {
								$permissions = $auth->getPermissionType($table);
								//print_r($permissions);
                                if ($table == 'cruds')
                                    continue;
                                if (strpos($table, 'crud_') !== false)
                                    continue;
                                if (!in_array(5, $permissions)) continue;
                                ?>
                                <li><a href="<?php echo strUrl('admin/scrud/config.php?table='.$table); ?>"><?php echo $table; ?></a></li>
                            <?php } ?>
                        </ul>
                    </li>
                </ul>
                <ul class="nav pull-right">
                    <li class="dropdown   <?php if ($this->type == 'account') { ?>active<?php } ?>">
                        <a class=" dropdown-toggle" data-toggle="dropdown" href="#" > &nbsp;  <i class="icon icon-user icon-white"></i>&nbsp; <?php echo $_SESSION['CRUD_AUTH']['user_name']; ?><b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <?php if ($_SESSION['CRUD_AUTH']['group']['group_name'] != 'SystemAdmin') { ?>
                                <li><a href="<?php echo strUrl('user/editProfile.php'); ?>"> <i class="icon-user"></i> <?php echo __LBL_EDIT_PROFILE__;?></a></li>
                                <li><a href="<?php echo strUrl('user/changePassword.php'); ?>"> <i class="icon-pencil"></i> <?php echo __LBL_CHANGE_PASSWORD__;?></a></li>
                                <li class="divider"></li>
                            <?php } ?>
                            <li><a href="<?php echo strUrl('admin/logout.php'); ?>"><i class="icon-minus-sign"></i> <?php echo __LBL_LOGOUT__;?></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
       if ($('#mnu_config').children('ul').find('li').length <= 1){
           $('#mnu_config').hide();
       }else{
           $('#mnu_config').show();
       } 
       
       if ($('#mnu_browse').children('ul').find('li').length <= 1){
           $('#mnu_browse').hide();
       }else{
           $('#mnu_browse').show();
       } 
       
    });
</script>