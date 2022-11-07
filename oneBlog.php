<?php
include('db/dbConfig.php');
session_start();
$db = new LabDB();
// Check the account
if ( !isset($_SESSION['blogID']) ) {
    die("Missing blogID");
}
else{
    $blogID = $_SESSION['blogID'];
}
/*--------------------
Deal with the comments
---------------------*/

if(isset($_POST['comment'])){ 
    // Data validation
    if ( strlen(trim($_POST['comment'])) < 1 ) {
        header("Location: oneBlog.php");
        return;
    }
    //show the user's email address in the comment
    if(isset($_SESSION['userID'])){
        $user = LabDB::find($db, 'tb_user', ['mailaddr'], 'id = "'.$_SESSION['userID'].'"');
        if($user){
        $addComment = LabDB::insert($db, 'tb_comment', array('post_id'=>$blogID, 'email'=>$user['mailaddr'], 'comment'=>$_POST['comment']));
            
        if($addComment){
            header("Location: oneBlog.php");
            return;
        }
    }
    // Or show default name "Internaute"
    }else{
        $addComment = LabDB::insert($db, 'tb_comment', array('post_id'=>$blogID, 'comment'=>$_POST['comment']));
        if($addComment){
            header("Location: oneBlog.php");
            return;
        }
    }
}

$result = LabDB::find($db, 'tb_post', ['title', 'body'], 'id = "'.$blogID.'"');
$comment = LabDB::select($db, 'tb_comment', ['time_insert', 'email','comment'], 'post_id = "'.$blogID.'"' );

?>
<?php ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Lab ZZZ</title>
    <link rel="stylesheet" href="css/blog.css">
    <?php require_once "css/require.php"; ?>
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
    <div id = "blog-content">
        <?php
        if($result){ ?> 
            <h2 class="blog-title">
                <?php echo htmlentities($result['title']); ?>
            </h2>
            <br>
            <div id = "blog-readlist">
                <?php echo $result['body']; ?>
            </div>  
            
        <?php } 
        ?>
        <br>
        <div id = "blog-comment">
            <?php
            /*--------------------
            Show the comments of a certain blog
            ---------------------*/
            if($comment != false){

                echo '<table class="list-table" style="font-size: 14px; ">';
                    
                    echo'<tr class="list-tr">';
                    echo '<th class="list-th" width="100%">Commentaires</th>';
                    echo '</tr>';
                    foreach($comment as $row){
                        echo '<tr class="list-tr">';
                            echo '<td width="30%" style="text-align:left; color:var(--main-green);">'.$row['email'];
                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['time_insert'].'</td>';
                        echo '</tr>';
                        echo '<tr class="list-tr" style="color:var(--main-blue)";>';
                            echo '<td style="text-align:left" width="100%">&nbsp;&nbsp;'.htmlentities($row['comment']).'</td>';
                        echo '</tr>';
                        
                    }
                
                echo '</table>';
                echo '<br>';   
            }
            ?>
            <form action="oneBlog.php" method="post">
                <i class="fa-solid fa-pen"></i>
                <!-- <input style="line-height: 3rem;" type="text" name="comment" id="comment" value="Laissez quelques mots..."> -->
                <textarea name="comment" id="comment" value="Laissez quelques mots..." class="auto-input" name="comment"></textarea>
                <button type="submit" id="btnSave">Commenter</button>
            </form>
            
        </div>
    </div>
  
</body>
</html>
