<?php
require APP . '/objects/admin/table/Table.php';

global $layout;
$layout = null;

$table = new AdminTable($this->da);
$table->insert();