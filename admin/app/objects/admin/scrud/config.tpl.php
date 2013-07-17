<script type="text/javascript">
    ScrudCForm.wpage = '<?php echo __SELF__; ?>';
    ScrudCForm.urlGetOptions = '<?php echo strUrl('admin/scrud/getOptions.php'); ?>';
    ScrudCForm.urlGetFields = '<?php echo strUrl('admin/scrud/getFields.php?table='); ?>';
    ScrudCForm.urlSaveConfig = '<?php echo strUrl('admin/scrud/saveConfig.php'); ?>';
    ScrudCForm.table = '<?php echo $_GET['table']; ?>';
<?php
if (!empty($this->crudConfigTable)) {
    ?>
            ScrudCForm.config = <?php echo $this->crudConfigTable; ?>;
    <?php
}
?>
<?php if (!empty($this->tables)) { ?>
    <?php
    foreach ($this->tables as $t) {
        if (strpos($t, 'crud_') !== false)
            continue;
        ?>
                    ScrudCForm.tables[ScrudCForm.tables.length] = '<?php echo $t; ?>';
    <?php } ?>
<?php } ?>

<?php foreach ($this->fields as $f) { ?>
        ScrudCForm.fields[ScrudCForm.fields.length] = '<?php echo $f['Field']; ?>'
<?php } ?>
<?php foreach ($this->fieldConfig as $f => $c) { ?>
        ScrudCForm.elements['<?php echo $f; ?>'] = <?php echo $c; ?>;
<?php } ?>
</script>

<div class="container">
    <div class="row-fluid" >
        <div class="tabbable">
        	<h2>Config <?php echo $_GET['table']; ?></h2>
            <ul  class="nav nav-tabs" id="myTab" style="margin-bottom: 0px;">
                <li class="active"><a data-toggle="tab" href="#form"><?php echo __LBL_FORM__;?></a></li>
                <li><a data-toggle="tab" href="#searchList" onclick="__clickList();"> &nbsp; <?php echo __LBL_LIST__;?> &nbsp; </a></li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div id="form" class="tab-pane fade  in active">
                    <?php require_once 'include/config_form.php'; ?>
                </div>
                <div id="searchList" class="tab-pane fade">
                    <div class="span12">
                        <div style="padding: 5px;">
                            <?php require_once 'include/config_data_list.php'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<script>
    function __clickList(){
        $('#filter_elements > li:first > a > a').trigger('click');
        $('#column_elements > li:first > a > a').trigger('click');
    };
</script>
