<?php 
include('../edb-class/edb.class.php');

$db_data = array('resource.com','user','pas$','dbname');

$db = new edb($db_data);

$line = $db->line("select * from users where id = '5'");


print_r($line);




$one = $db->q("select * from location_list ");

echo $one = $db->one("select email from users where id = '5' limit 1");


print_r($db->queryAll);
?>