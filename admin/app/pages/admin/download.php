<?php
global $layout;
$layout = 'admin/download.php';

$this->com->set('file',ROOT.'/public/media/files/'.$_GET['file']);