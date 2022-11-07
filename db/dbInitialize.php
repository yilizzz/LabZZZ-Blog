<?php
/*
*	Create tables 
*==============================================
*/

//select database labzzz
$db = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => 'labtest1*0',
    'database' => 'db_labzzz'
    ); 

//create tables and insert demo datas
$sqlInit = file_get_contents('dbinitialize.txt');

$mysql = new Mysqli($db['hostname'],$db['username'],$db['password'], $db['database']);
if(mysqli_multi_query($mysql, $sqlInit)){
    echo '<br /><br />Database Initialized.';
} else {
    echo '<br /><br /><span style="color:red; ">FAILED</span>';	
}


?>