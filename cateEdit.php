<?php
session_start(); 

include('db/dbConfig.php');
$db = new LabDB(); 

// Guardian: Make sure that user_id is present
if ( ! isset($_SESSION['userID']) ) {
    die("ACCESS DENIED");
  }else{
    $userID = $_SESSION['userID']; 
}


?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <META http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>LabZZZ Blog</title>
        <link rel="stylesheet" href="css/blog.css">
        <?php require_once "css/require.php"; ?>
        <script>
        /*------------------------------------------------------
        - Delete category event 
        --------------------------------------------------------*/
        $(document).ready(function(){
            $(document).on("click", ".delete", function(){
        
                var flag = confirm("Supprimer tous les articles de cette catégorie et de sous-catégories ?");
                
                if(flag){
                     $.post("deleteCG.php",{
                        cgID:$(this).val()
                        
                    });
                }
                window.location.reload();
            
            });
            /*------------------------------------------------------
            - Edit category event 
            --------------------------------------------------------*/
            $(document).on("click", ".edit", function(){
            var newCate = window.prompt("Le nom de la catégorie","");
            if(newCate){
                $.post("updateCG.php",{
                    cgName: newCate,
                    cgID:$(this).val()
                    
                });
        
            }
            window.location.reload();
        }); 
    });
 
    </script>
    </head>

    <body class = "blog-theme">
    
        <header id = "editor-info">
            <h2>Mes Catégories</h2>
            <strong><a href="cateAdd.php">&nbsp;&nbsp;&nbsp;Ajoutez une nouvelle category</a></strong>&nbsp;&nbsp;&nbsp;
            
            <?php
            // Flash pattern
            if ( isset($_SESSION['message']) ) {
                echo('<p style="color:var(--main-red);">'.htmlentities($_SESSION['message'])."</p>\n");
                unset($_SESSION["message"]);
            }
            ?>
            
        </header>

        <nav id="navbar">
            
            <i class="fa-solid fa-glasses"></i>&nbsp;<a href="readBlog.php">Lire Plus</a>&nbsp;&nbsp;&nbsp;
            <i class="fa-sharp fa-solid fa-paperclip"></i>&nbsp;<a href="blogAdmin.php">Mon Blog</a>
        </nav>


        <div id = "blog-list">
            
            <form method="post">
                
                <?php
                /*********************************************************
                *a list of user's categoy
                *********************************************************/
                //query for the first class categories
                $result = LabDB::select($db, 'tb_category', ['id', 'cgname'], 'user_id = "'.$userID.'" AND parent IS NULL'); 
            
                if($result != false){
                    echo '<form id="all-blog" action="cateEdit.php" method="post">';
                        echo '<table class="list-table">';  
                            echo'<tr class="list-tr">';
                            echo '<th class="list-th" width="100%" colspan="3">Liste Category</th>';
                            echo '</tr>';
                            foreach($result as $row){
                                echo '<tr class="list-tr">';
                                echo '<td style="text-align:left" value = "'.$row['id'].'" width="40%">&nbsp;&nbsp;'.htmlentities($row['cgname']).'</td>';
                                echo '<td><button type="submit" class="edit" name="editBtn" value = "'.$row['id'].'">Editer</button>
                                          <button type="submit" class="delete" name="dlteBtn" value = "'.$row['id'].'">Supprimer</button></td>';
                                echo '</tr>';
                                //query for the sub categories
                                $sub_result = LabDB::select($db, 'tb_category', ['id', 'cgname'], 'user_id = "'.$userID.'" AND parent = "'.$row['id'].'"');
                                if($sub_result != false){
                                    foreach($sub_result as $sub_row){
                                        echo '<tr class="list-tr">';
                                            echo '<td style="text-align:left" value = "'.$sub_row['id'].'" width="40%">&nbsp;&nbsp;'.'----'.htmlentities($sub_row['cgname']).'</td>';
                                            echo '<td><button type="submit" class="edit" name="editBtn" value = "'.$sub_row['id'].'">Editer</button>
                                                    <button type="submit" class="delete" name="dlteBtn" value = "'.$sub_row['id'].'">Supprimer</button>
                                                    </td>';
                                        echo '</tr>';
                                    }
                                }
                            }
                        echo '</table>';              
                }
                ?>
            </form>       
        </div>
    </body>
</html>