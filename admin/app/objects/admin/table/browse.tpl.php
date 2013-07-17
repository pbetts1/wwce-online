<div class="container">
		<h2>User Manager: Tables	</h2>
        <ul class="nav nav-tabs" id="auth_tab" style="margin-bottom: 10px;">
            <?php if ((int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 1 || 
            		(int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 3 ||
            		(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 1 || 
            		(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 3 ) { ?>
            <li><a href="<?php echo strUrl('admin/user/user.php'); ?>"> &nbsp; Users &nbsp; </a></li>
            <li><a href="<?php echo strUrl('admin/user/group.php'); ?>">Groups</a></li>
            <li ><a href="<?php echo strUrl('admin/user/permission.php'); ?>">Permissions</a></li>
          <?php } ?>
          <?php if ((int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 2 || 
          			(int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 3 ||
          			(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 2 || 
          			(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 3 ) { ?>
            <li  class="active"><a href="<?php echo strUrl('admin/table/index.php'); ?>">Tables</a></li>
          <?php } ?>
        </ul>
        <div>
            <p style="text-align: right;"><a class="btn btn-info" href="<?php echo strUrl('admin/table/new.php'); ?>">Add table</a></p>
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th style="text-align:center;width:30px; cursor:default; color:#333333;text-shadow: 0 1px 0 #FFFFFF;background-color: #e6e6e6;"><?php echo __LBL_NO__; ?></th>
                        <th style=" cursor:default; color:#333333;text-shadow: 0 1px 0 #FFFFFF;background-color: #e6e6e6;"><?php echo __LBL_TABLE__; ?></th>
                        <th style="text-align:center; width: 120px;  cursor:default; color:#333333;text-shadow: 0 1px 0 #FFFFFF;background-color: #e6e6e6;"><?php echo __LBL_ACTIONS__; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($this->tables) > 2) {
                        foreach ($this->tables as $k => $table) {
                            if ($table == 'cruds')
                                continue;
                            if (strpos($table, 'crud_') !== false)
                                continue;
                            ?>
                            <tr>
                                <td style="text-align:center;"><?php echo ($k + 1); ?></td>
                                <td><?php echo $table; ?></td>
                                <td style="text-align: center;">
                                    <a type="button" class="btn btn-mini btn-info" id="table_btn_fields" onclick="edit_table('<?php echo $table; ?>')">Edit</a>
                                    <a type="button" class="btn btn-mini btn-danger" id="table_btn_delete" onclick="modal_delete_table('<?php echo $table; ?>')">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="4">No tables to display.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <footer>
	        <hr />
            <p><?php echo __LBL_COPYRIGHT__; ?></p>
        </footer>
</div>
<div id="delModal" class="modal hide fade" tabindex="-1" aria-hidden="true" style="width: 290px; margin: 70px 0 0 -180px;">
    <div class="modal-body">
        <p>Are you sure to delete <strong id="del_table"></strong> table ?</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-danger" onclick="delete_table();">Delete</button>
    </div>
</div>
<script>
    function edit_table(table){
        window.location = '<?php echo strUrl('admin/table/edit.php?table='); ?>'+table;
    }
    function modal_delete_table(table){
        $('#del_table').text(table);
        $('#delModal').modal('show');
    }
    function delete_table(){
        $.post('<?php echo strUrl('admin/table/delete.php'); ?>', {table:$('#del_table').text()}, function(data){
            $('#delModal').modal('hide');
            window.location = '<?php echo strUrl('admin/table/index.php'); ?>';
        },'html');
    }
    $(document).ready(function(){
        $('title').html($('h2').html());
    });
</script>