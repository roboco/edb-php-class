<?php 
include('c://temp/edb_google_code/edb-0.1.1/edb-class/edb.class.php');

$db_data = array('divaspuses.db.5696236.hostedresource.com','divaspuses','killersite32A','divaspuses');
// $db_data2 = array('steidzami.db.5696236.hostedresource.com','steidzami','killersite32A','steidzami');

$db = new edb($db_data);

$line = $db->line("select * from users where id = '5'");


print_r($line);



// $db2 = new edb($db_data2);



$one = $db->q("select * from location_list ");

echo $one = $db->one("select email from users where id = '5' limit 1");


// $one = $db->q("select * from users limit 5,3");

// $one2 = $db->q("select * from users limit 9,3");

// $one = $db->s("Set names utf8");

// $two = $db2->q("select * from users");


// echo 'time: '.$db->queryTime;
// echo '<br>';
// echo 'count: '.$db->queryCount;
// echo '<br>';
// echo 'time: '.$db2->queryTime;



print_r($db->queryAll);
?>