<?php
require APP.'/objects/admin/scrud/Scrud.php';

global $layout;
$layout = null;
$scrud = new AdminScrud($this->da);
$scrud->getFields();
