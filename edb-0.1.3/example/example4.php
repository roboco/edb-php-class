<?php 
include('c://temp/edb_google_code/edb-0.1.3/edb-class/edb.class.php');

$db_data = array('hostedresource.com','user','pass','dbname');

$db = new edb($db_data);

//$line = $db->line("select * from users where id = '5'");

//$all = $db->selectAll('users',true,3600);

//$db->insert('users',array('email'=>'edds'.rand().'@mail.com','pass'=>'pass','name'=>'edds','surname'=>'edio'));

//echo $db->lastID();

//$db->update('users',array('pass'=>'pa$$','name'=>'Eduards'),array('id'=>'5','email'=>'edds@2p.lv'));

echo $db->countTable('users',true,60);
echo '<br>';
echo $db->countOf('users',"name = 'edds' ",true,60);



print_r($db->queryAll);
?>