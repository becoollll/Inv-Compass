<?php
    $userlevel = $_COOKIE['userlevel'];
    if($userlevel == 3){
        $URL="userpg2.php"; 
		header("Location: $URL");// 將網址導去pg2
    }else{
        $URL="userpg1.php"; 
		header("Location: $URL");// 將網址導去pg1
    }
?>