<?php 
    session_start();//啟用Session功能
    $userid = $_COOKIE['userid'];
    $userlevel = $_COOKIE['userlevel'];
    $url_flag = 1;

    if(isset($_POST["yes"])){
        $newname=$_POST["newname"];
        $newpassword=$_POST["newpassword"];
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
                    setcookie("username", $newname, 0, "/", "", "");
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
		}else{  //$url_flag == 0 && $userlevel==1or2
            $URL="userpg1.php?change=error"; 
        }				
        header("Location: $URL");
    }else{ //isset($_POST["no"])
        $URL="../focus.php"; 
		header("Location: $URL");// 將網址導去pg2
    }
?>