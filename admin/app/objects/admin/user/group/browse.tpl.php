<div class="container" >
		<h2>User Manager: Groups	</h2>
        <ul class="nav nav-tabs" id="auth_tab" style="margin-bottom: 0px;">
        <?php if ((int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 1 || 
        		(int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 3 ||
        		(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 1 || 
        		(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 3 ) { ?>
            <li><a href="<?php echo strUrl('admin/user/user.php'); ?>"> &nbsp; <?php echo __LBL_USER__;?> &nbsp; </a></li>
            <li class="active"><a href="<?php echo strUrl('admin/user/group.php'); ?>"><?php echo __LBL_USER_GROUPS__;?></a></li>
            <li ><a href="<?php echo strUrl('admin/user/permission.php'); ?>"><?php echo __LBL_USER_PERMISSIONS__;?></a></li>
          <?php } ?>
          <?php if ((int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 2 || 
          			(int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 3 ||
          			(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 2 || 
          			(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 3 ) { ?>
            <li><a href="<?php echo strUrl('admin/table/index.php'); ?>"><?php echo __LBL_USER_TABLES__;?></a></li>
          <?php } ?>
          </ul>
        <?php echo $this->content; ?>
		
        <hr />
        <footer>
            <p><?php echo __LBL_COPYRIGHT__; ?></p>
        </footer>
</div>