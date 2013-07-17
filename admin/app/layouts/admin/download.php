<?php
if (file_exists($this->com->toString('file'))){
    header("Pragma: public", true);
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    header("Content-Disposition: attachment; filename=".basename($this->com->toString('file')));
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".filesize($this->com->toString('file')));
    die(file_get_contents($this->com->toString('file')));
}