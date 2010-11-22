<?php 
include('../edb-class/edb.class.php');

$db_data = array('resource.com','user','pa$$','dbname');
$db_data2 = array('resource.com','user','pa$$','dbname2');

$db = new edb($db_data);

$db2 = new edb($db_data2);



$one = $db->q("select * from users limit 3");

$one = $db->q("select * from users limit 5,3");

$one2 = $db->q("select * from users limit 9,3");

$one = $db->s("Set names utf8");

$two = $db2->q("select * from users");


echo 'time: '.$db->queryTime;
echo '<br>';
echo 'count: '.$db->queryCount;
echo '<br>';
echo 'time: '.$db2->queryTime;
echo '<br>';


print_r($db->queryAll);

print_r($db2->queryAll);
?>