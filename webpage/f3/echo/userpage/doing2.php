<?php 
    session_start();//啟用Session功能
    $userid = $_COOKIE['userid'];
    $userlevel = $_COOKIE['userlevel'];
    $userteam = $_COOKIE['userteam'];
    $url_flag = 1;

    if(isset($_POST["yes"])){
        $newname=$_POST["newname"];
        $newpassword=$_POST["newpassword"];
        $newinvitation=$_POST["newinvitation"];
        try{
            $conn = new PDO("mysql:host=localhost;dbname=database", "username", "password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if($newpassword){ // !=null
                if((preg_match("/^[a-z\d]*$/i",$newpassword) && (strlen($newpassword)>=6) && (strlen($newpassword)<=20))){
                    $sql = "UPDATE `user` SET `password` = '$newpassword' WHERE `id` = '$userid'";
                    $conn->exec($sql);  
                }else{
                    $url_flag = 0;
                }
            }
            if($newname){ // !=null
                if((preg_match("/^[a-z]*$/i",$newname)) && (strlen($newname)<=10)){
                    $sql = "UPDATE `user` SET `name` = '$newname' WHERE `id` = '$userid'";
                    $conn->exec($sql);
                    $sql = "UPDATE `team` SET `leadername` = '$newname' WHERE `teamname` = '$userteam'";
                    $conn->exec($sql);
                    setcookie("username", $newname, 0, "/", "", "");
                }else{
                    $url_flag = 0;
                }
            }
            if($newinvitation){ // !=null
                if((preg_match("/^[a-z\d]*$/i",$newinvitation)) && (strlen($newinvitation)>=6) && (strlen($newinvitation)<=20)){
                    $sql = "UPDATE `team` SET `Invitation` = '$newinvitation' WHERE `teamname` = '$userteam'";
                    $conn->exec($sql);
                }else{
                    $url_flag = 0;
                }
            }
        }
        catch(PDOException $e){
            echo $sql . "<br>" . $e->getMessage();
        }
        $conn = null;

        if($url_flag == 1){
            $URL="../focus.php"; 
		}else{  //$url_flag == 0 && $userlevel==3
            $URL="userpg2.php?change=error"; 
        }				
        header("Location: $URL");
    }
    else if(isset($_POST["check"])){
        $URL="../teamck/teamcheck.php"; 
		header("Location: $URL");
    }   
    else{ //isset($_POST["no"])
        $URL="../focus.php"; 
		header("Location: $URL");
    }
?>