<?php
require_once "db/dbConfig.php";
session_start();
$blogID = $_SESSION['blogID']; 
$db = new LabDB();
// Guardian: Make sure that blog_id is present
if ( ! isset($_SESSION['blogID']) ) {
    $_SESSION['message'] = "Missing blog_id";
    header('Location: blogAdmin.php');
    return;
}

if ( isset($_POST['delete']) ) {
    $result = LabDB::delete($db, 'tb_post', 'id = '.$_SESSION['blogID']);
    //delete failed
    if(!$result){    
        $_SESSION['message'] = 'Suppression échouée';
        header( 'Location: blogAdmin.php' ) ;
        return;
    } 
    //delete succeed
    else{
        $_SESSION['message'] = 'Un blog a été supprimé';
        header( 'Location: blogAdmin.php' ) ;
        return;
        
    }
    
}


$result = LabDB::find($db, 'tb_post', 'title', 'id = "'.$blogID.'"');
       
if($result == false){
        $_SESSION['message'] = 'Bad value for blog_id';
        header( 'Location: blogAdmin.php' ) ;
        return;
}else{
    $title = $result['title'];
}


?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <META http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>LabZZZ Blog</title>
        <link rel="stylesheet" href="css/blog.css">
       
        
    </head> 

    <body class = "blog-theme">
    
        <header id = "editor-info">
            <h2 class="blog-title">LabZZZ Blog
            </h2>  
        </header>
        
        <nav id="navbar">
            <i class="fa-solid fa-glasses"></i>&nbsp;<a href="readBlog.php">Lire Plus</a>&nbsp;&nbsp;&nbsp;
            <i class="fa-sharp fa-solid fa-paperclip"></i>&nbsp;<a href="blogAdmin.php">Mon Blog</a>
        </nav>

        <div id="del-info">
            <h1>Confirmez: Supprimer <?= htmlentities($title) ?> ?</h1>
            <br>
            <form method="post">
                <h3><input type="submit" value="Delete" name="delete">
                <a href="blogAdmin.php">Cancel</a></h3>
            </form>
        </div>
 
  </body>
</html>