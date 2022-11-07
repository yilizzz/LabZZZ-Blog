<?php
   
/************************************
*   Proceed user's login datas 
*************************************/
include('db/dbConfig.php');
session_start();
if(isset($_POST['userName']) && isset($_POST['userPW'])){
 
    $db = new LabDB();
    $userName = trim($_POST['userName']);
    $userPW = trim($_POST['userPW']);
    // Concatenate the salt plus password together and check it
    $salt = 'labzzz2333*_';
    $storedPW = hash('md5', $salt.$userPW);
    // Execute a query              
    $result = LabDB::find($db, 'tb_user', ['id','username'], 'username = "'.$userName.'" AND userpw = "'.$storedPW.'"');  
    // find a match user
    if($result != false){    	
        $_SESSION['userID'] = $result['id'];
        $_SESSION['userName'] = $result['username'];
        $_SESSION["message"] = "Connexion réussie";
        header("Location: blogAdmin.php");
        return; 
    //No query result
    }else{
        $_SESSION["message"] = "Le mot de passe est incorrect";
        header( 'Location: blogLogin.php' ) ;
        return;
    }
}


?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <META http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>Login My Blog</title>
        <link rel="stylesheet" href="css/blog.css">
        <?php require_once "css/require.php"; ?>
        <!--check register infors: username and password--> 
        <script>
            window.onload = function(){
                document.getElementById("reg-form").onsubmit = function(){
                    return checkUsername() && checkPassword1() && checkPassword2() && nameExist;
                };
                document.getElementById("regName").onblur = checkUsername;
                document.getElementById("regPW1").onblur = checkPassword1;
                document.getElementById("regPW2").onblur = checkPassword2;

                document.getElementById("login-form").onsubmit = function(){
                    return checkLogin();
                };
            };

            function checkLogin(){
                var loginName = document.getElementById("userName").value.trim();
                var loginPW = document.getElementById("password").value.trim();
                if(loginName == ''|| loginPW == ''){
                    alert('Le nom d\'utilisateur ou le mot de passe est vide.');
                    return false;
                }
                return true;
             
            }
            
            function checkUsername(){
                var username = document.getElementById("regName").value;
                var flag = true;
                //prohibit special character
                var regex = /^[0-9A-Za-z]{6,20}$/;
                if (!regex.test(username)) flag = false;
                if(flag){

                    document.getElementById('alert_username').textContent = 'OK';
                }else{
                    document.getElementById('alert_username').textContent = '6-20 caracters(majuscule/minuscule/chiffres)';
                }
                return flag;
             
            }
            function checkPW(pw){
                var regex = /^[0-9A-Za-z]{6,20}$/;
                var onlynum_regex = /^[0-9]{6,20}$/;
                var onlyupper_regex = /^[A-Z]{6,20}$/;
                var onlylower_regex = /^[a-z]{6,20}$/;
                var flag = true;
                //if there is other character [0-9A-Za-z],false
                if (!regex.test(pw)) flag = false;
                //if there is only number,false
                if (onlynum_regex.test(pw)) flag = false;
                //if there is only upper,false
                if (onlyupper_regex.test(pw)) flag = false;
                //if there is only lower,false
                if (onlylower_regex.test(pw)) flag = false;
                return flag;
            }
            
            function checkPassword1(){
                var password = document.getElementById("regPW1").value;
                var flag = checkPW(password);
                if(flag){
                    document.getElementById('alert_password').textContent = 'OK';
                }else{
                    document.getElementById('alert_password').textContent = '6-20 caracters(majuscule/minuscule/chiffres, au moins 2 genres)';
                }
                return flag;
            }

            function checkPassword2(){
                var pw1 = document.getElementById("regPW1").value;
                var pw2 = document.getElementById("regPW2").value;
                if(!checkPW(pw2)) {
                    return false;
                }else{
                    if(pw1 === pw2){
                        document.getElementById('alert_pw2').textContent = 'OK';
                        return true;
                    }
                    document.getElementById('alert_pw2').textContent = 'Les deux mots de passe sont différentes';
                    return false;
                }
            }
     
        </script>

        <!--If userName is unique -->
        <script>
        var nameExist;
        $(document).ready(function(){
            $("#regName").blur(function(){
                var regName = $("#regName").val();
                $.post("regNameExist.php",{
                    name:regName
                },
                function(result){

                    if(result == 1){
                        nameExist = false;
                        $("#regName").val("");
                        $("#alert_username").text("Le nom d\'utilisateur est déjà pris.");
                    }else{
                        nameExist = true;
                    }
              
                });
            });
        });
        </script> 
    </head> 

    <body class = "blog-theme">
    
        <header id = "user-infor">

            <h2 class="blog-title">Blog LabZZZ</h2>
            <br>
            <?php
            if ( isset($_SESSION['message']) ) {
                echo('<p style="color:var(--main-red);">'.htmlentities($_SESSION['message'])."</p>\n");
                unset($_SESSION["message"]);
            }
            ?>
            <nav id="navbar">
                <i class="fa-solid fa-glasses"></i>&nbsp;<a href="readBlog.php">Lire Plus</a>    
            </nav>
            <form class= "infor-form" id="login-form" action="blogLogin.php" method="POST">
                <ul class="ul-infor">
                <li><label>Identifiant: </label><input type="text" name="userName" id="userName"></li>
                <li><label>Mot de passe: </label><input type="text" name="userPW" id="password"></li>
                </ul>
                <span id="notValid" class="error"></span>
                <button type="submit" name="submit" id="connectBtn">Je me connecte</button>
                
               
            </form>
            
        </header>
        
        <section id = "blog-register">
            <strong>Nouvel utilisateur ? </strong>
            <form class= "infor-form" id = "reg-form" action="blogReg.php" method="POST">
                <ul class="ul-infor">
                <li>Identifiant: 
                    <span id="alert_username" class="error">6-20 caracters(majuscule/minuscule/chiffres)</span>
                    <input type="text" name="regName" id="regName" required></li>
                <li>Mot de passe: 
                    <span id="alert_password" class="error">6-20 caracters(majuscule/minuscule/chiffres, au moins 2 genres)</span>
                    <input type="text" name="regPW1" id="regPW1" required></li>
                <li>Une seconde fois: <span id="alert_pw2" class="error"></span>
                    <input type="text" id="regPW2" required></li>
                <li>E-mail: <input type="email" name="regEmail" required></li>
                </ul>
                <button type="submit" name="submit">Je crée mon compte</button>
            </form>
        </section>  
    </body>
</html>

