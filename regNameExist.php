<?php
include('db/dbConfig.php');
//check if the username already exists
$userName = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
$db = new LabDB();
$result = LabDB::find($db, 'tb_user', 'username', 'username = "'.$userName.'"');
//the username does not exist
if(!$result){    
    echo "0";
} 
//the username exists
else{
    echo "1";
}


?>