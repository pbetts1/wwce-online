<?php
$server   = "localhost";
$database = "charwork_admin"; 
$username = "charwork_legacy";
$password = "!Daisaku0513";

$mysqlConnection = mysql_connect($server, $username, $password);
if (!$mysqlConnection)
{
  echo "Please try later.";
}
else
{
mysql_select_db($database, $mysqlConnection);
}
?>