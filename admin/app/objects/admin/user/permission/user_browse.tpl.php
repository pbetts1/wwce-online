<div class="container">
		<h2><?php echo __LBL_USER_MANAGER_PERMISSIONS__;?>	</h2>
        <ul class="nav nav-tabs" id="auth_tab" style="margin-bottom: 10px;">
            <?php if ((int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 1 || 
            		(int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 3 ||
            		(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 1 || 
            		(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 3 ) { ?>
            <li><a href="<?php echo strUrl('admin/user/user.php'); ?>"> &nbsp; <?php echo __LBL_USER__;?> &nbsp; </a></li>
            <li><a href="<?php echo strUrl('admin/user/group.php'); ?>"><?php echo __LBL_USER_GROUPS__;?></a></li>
            <li  class="active"><a href="<?php echo strUrl('admin/user/permission.php'); ?>"><?php echo __LBL_USER_PERMISSIONS__;?></a></li>
          <?php } ?>
          <?php if ((int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 2 || 
          			(int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 3 ||
          			(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 2 || 
          			(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 3 ) { ?>
            <li><a href="<?php echo strUrl('admin/table/index.php'); ?>"><?php echo __LBL_USER_TABLES__;?></a></li>
          <?php } ?>
        </ul>
        
         <ul class="nav nav-tabs" id="auth_tab" style="margin-bottom: 10px;">
         	<li><a href="<?php echo strUrl('admin/user/permission.php'); ?>"><?php echo __LBL_GROUP_PERMISSION__;?></a></li>
         	<li class="active" ><a href="<?php echo strUrl('admin/user/user_permission.php'); ?>"><?php echo __LBL_USER_PERMISSION__;?></a></li>
         </ul>
         <div>
         	<label><strong><?php echo __LBL_CHOOSE_USER__;?></strong></label> 
         		<div id="user_permission" style="width:400px;"></div>
         </div>
         <br/>
         <div id="user_permission_container"></div>
</div>
<script>
$("#user_permission").select2({
    placeholder: "<?php echo __LBL_SEARCH_USER__;?>",
    minimumInputLength: 1,
    ajax: {
        url: "<?php echo strUrl('admin/user/user_json.php'); ?>",
        dataType: 'jsonp',
        data: function(term, page) {
            return {
                q: term, // search term
            };
        },
        results: function(data, page) { // parse the results into the format expected by Select2.
            return {results: data};
        }
    },
    initSelection: function(element, callback) {},
    formatResult: movieFormatResult, // omitted for brevity, see the source of this page
    formatSelection: movieFormatSelection, // omitted for brevity, see the source of this page
    dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
    escapeMarkup: function(m) {
        return m;
    } 
});

$("#user_permission").on('change',function(e){
	$.get("<?php echo strUrl('admin/user/user_json.php?id='); ?>"+e.val,{},function(data){
		$('#user_permission_container').html('');
		$('#user_permission_container').append(data);
	},'html');
});

function movieFormatResult(user) {
    return user.user_name;;
}

function movieFormatSelection(user) {
    return user.user_name;
}

</script>