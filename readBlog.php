<!------------------------------------------------------
- Show all user's blogs.
-------------------------------------------------------->
<?php
session_start();

include('db/dbConfig.php');
$db = new LabDB();

/***************************************************
*Button Read event
****************************************************/
if(isset($_POST['readBtn'])){
    $_SESSION['blogID'] = $_POST['readBtn'];
    echo "<meta http-equiv='Refresh' content='0;URL=oneBlog.php'>";   
}

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <META http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>Mon Blog</title>
        <link rel="stylesheet" href="css/blog.css">
        <?php require_once "css/require.php"; ?>
        <script>
            $(document).ready(function(){
                $("#search").click(function(){
                    $("#search").val("");
                });
            });
        </script>
    </head> 

    <body class = "blog-theme">
    
        <header id = "editor-info">
            <h2 class="blog-title">Bonjour
            </h2>
            <nav id="navbar">
                <i class="fa-sharp fa-solid fa-paperclip"></i>&nbsp;<a href="blogAdmin.php">Mon Blog</a>&nbsp;&nbsp;&nbsp;
                <i class="fa-solid fa-door-open"></i>&nbsp;<a href="logOut.php">Logout</a>
            </nav> 
        </header>
        
        <div id = "blog-readlist">
        <br>
        <?php
        //query for all blogs
        $result = LabDB::select_everyblog($db); 
       
        if($result != false){

            echo '<form id="all-blog" action="readBlog.php" method="post">';
            echo '<table class="list-table">';
                
                echo'<tr class="list-tr">';
                echo '<th class="list-th" width="100%" colspan="4">Liste de blog</th>';
                echo '</tr>';
                foreach($result as $row){
                    echo '<tr class="list-tr">';
                        echo '<td style="text-align:left" width="40%">&nbsp;&nbsp;'.$row['title'].'</td>';
                        echo '<td width="18%" style="font-size:0.8rem">'.$row['mailaddr'].'</td>';
                        echo '<td width="18%" style="font-size:0.8rem">'.$row['time_update'].'</td>';
                        echo '<td width="15%">['.$row['cgname'].']</td>';
                        echo '<td><button type="submit" name="readBtn" value = "'.$row['id'].'">Lire</button></td>';
                    echo '</tr>';
                    
                }
            
            echo '</table>';
            echo '<br>';   
        echo '</form>';
        }
       /********************************************************
        *blog list searched by keyword in title
        ********************************************************/
        if(isset($_POST['search'])){
            $keyword = $_POST['search'];
            //keyword not valid
            if(strlen($keyword) < 1){
                echo'<script>document.getElementById("all-blog").style.display="";</script>';
            }
            else{
                echo'<script>document.getElementById("all-blog").style.display="none";</script>';
                $result = LabDB::select_bytitle($db, $keyword);
            
                if($result != false){
                    $num = count($result);
                    $_SESSION['message'] = $num." blog trouvé";
                    echo '<form action="readBlog.php" method="post">';
                        echo '<table class="list-table">';  
                            echo'<tr class="list-tr">';
                            echo '<th class="list-th" width="100%" colspan="3">Liste de blog</th>';
                            echo '</tr>';
                            foreach($result as $row){
                                echo '<tr class="list-tr">';
                                echo '<td style="text-align:left" width="40%">&nbsp;&nbsp;'.$row['title'].'</td>';
                                //echo '<td width="18%" style="font-size:0.8rem">'.$row['mailaddr'].'</td>';
                                echo '<td width="18%" style="font-size:0.8rem">'.$row['time_update'].'</td>';
                                //echo '<td width="15%">['.$row['cgname'].']</td>';
                                echo '<td><button type="submit" name="readBtn" value = "'.$row['id'].'">Lire</button></td>';
                                echo '</tr>';
                            
                            }
                        
                        echo '</table>';
                        echo '<br>';   
                    echo '</form>';
                }
                else{
                    $_SESSION['message'] = "Aucun blog trouvé";
                    echo'<script>document.getElementById("all-blog").style.display="";</script>';
                }
            }
                
        }
        ?>
        </div>


        <div id="blog-search">
            <form action="readBlog.php" method="post">
                <input value="Rechercher du titre..." id="search" name="search" type="text">
                <input type="submit" value="Confirmer">
            </form>
            <br>
            <?php
             if ( isset($_SESSION['message']) ) {
                echo('<p style="color:var(--main-red);">'.htmlentities($_SESSION['message'])."</p>\n");
                unset($_SESSION["message"]);
            }
            ?>
            <br>
            <i class="fa-solid fa-glasses"><a href="readBlog.php">&nbsp;Tous les blogs</a></i>
        </div>
    </body>

</html>
