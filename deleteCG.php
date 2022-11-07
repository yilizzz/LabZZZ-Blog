<?php
session_start();
include('db/dbConfig.php');

$cgID = isset($_POST['cgID']) ? htmlspecialchars($_POST['cgID']) : '';
$db = new LabDB();
$result = LabDB::delete($db, 'tb_category', 'id = "'.$cgID.'"');
//Execution succeed
if($result){    
    $_SESSION['message'] = 'La catégorie a été supprimée';
} 
//failed
else{
    $_SESSION['message'] = 'Suppression échouée';

}

?>