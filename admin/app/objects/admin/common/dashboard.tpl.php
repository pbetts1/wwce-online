<?php
global $config_database;
global $tableAlias;
$auth = Auth::singleton();
?>
<div class="container">
        <h3 style="margin-top: 7px;"><?php echo __LBL_DASHBOARD__;?></h3>
        <?php if ((int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 2 || 
        		(int) $_SESSION['CRUD_AUTH']['group']['group_manage_flag'] == 3 ||
        		(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 2 || 
        		(int) $_SESSION['CRUD_AUTH']['user_manage_flag'] == 3) { ?>
        <p style="text-align: right;"><a class="btn btn-info" id="btn_table_manager" ><?php echo __LBL_TABLE_MANAGER__; ?></a></p>
        <?php } ?>
        <table class="table table-bordered table-hover table-condensed" id="dashboard_list_table">
            <thead>
                <tr>
                    <th style="text-align:center;width:30px; cursor:default;"><?php echo __LBL_NO__; ?></th>
                    <th style="cursor:default;" ><?php echo __LBL_TABLE__; ?></th>
                    <th style="text-align:center;width:70px; cursor:default;"><?php echo __LBL_RECORDS__; ?></th>
                    <th style="text-align:center; cursor:default; width:200px;"><?php echo __LBL_ACTIONS__; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($this->tables) > 2) { ?>
                    <?php
                    foreach ($this->tables as $k => $table) {
						$permissions = $auth->getPermissionType($table);
                        if ($table == 'cruds')
                            continue;
                        if (strpos($table, 'crud_') !== false)
                            continue;
                        if (!(in_array(4, $permissions) || in_array(5, $permissions))) continue;
                        ?>
                        <tr>
                            <td style="text-align:center;"><?php echo ($k + 1); ?></td>
                            <td><?php echo (isset($tableAlias[$table]))?$tableAlias[$table]:$table; ?></td>
                            <td style="text-align:right;">
                                <?php
                                $result = $this->dao->query('SELECT COUNT(1) FROM `' . $table . '`');
                                $num_rows = $result->fetchRow();
                                echo number_format($num_rows[0]);
                                ?>
                            </td>
                            <?php
                            $rs = null;
                            if (file_exists(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $table . '.php')) {
                                $rs = str_replace("<?php exit; ?>\n", "", file_get_contents(__DATABASE_CONFIG_PATH__ . '/' . $config_database['default']["database"] . '/' . $table . '.php'));
                            }
                            ?>
                            <td style="text-align:center;">
                            	<?php if (in_array(4, $permissions)){ ?>
                                <a type="button" class="btn btn-mini" onclick="window.location='<?php echo strUrl('admin/scrud/browse.php?table='.$table); ?>'" <?php if (empty($rs)) { ?>disabled="disabled" <?php } ?> ><?php echo __BTN_BROWSE__; ?></a>
                                <?php }?>
                                <?php if (in_array(5, $permissions)){ ?>
                                <a class="btn btn-info  btn-mini" onclick="window.location='<?php echo strUrl('admin/scrud/config.php?table='.$table);?>'"><?php echo __BTN_CONFIG__; ?></a>
                                <a type="button" class="btn btn-danger btn-mini"  <?php if (empty($rs)) { ?>disabled="disabled" <?php } ?> onclick="removeConfig(this,'<?php echo $table; ?>')"><?php echo __BTN_REMOVE_CONFIG__;?></a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>	
                <?php } else { ?>
                    <tr>
                        <td colspan="4"><?php echo __LBL_NO_DATA__;?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <footer>
	        <hr />
            <p><?php echo __LBL_COPYRIGHT__; ?></p>
            <br />
        </footer>
    </div>
</div>

<script>
    $('#btn_table_manager').click(function(){
        window.location = '<?php echo  strUrl('admin/table/index.php');?>';
    });
    function removeConfig(obj,table){
        $.get('<?php echo strUrl('admin/scrud/removeConfig.php');?>', {table:table}, function(data){
            $($(obj).parent().children('input').get(0)).attr({disabled:true});
            $($(obj).parent().children('input').get(2)).attr({disabled:true});
        }, 'json');
    }
    $(document).ready(function(){
        if ($('#dashboard_list_table > tbody > tr').length <= 0){
            $('#dashboard_list_table > tbody').append('<tr><td colspan="4"><?php echo __LBL_NO_DATA__;?></td></tr>');
        }
    });
</script>