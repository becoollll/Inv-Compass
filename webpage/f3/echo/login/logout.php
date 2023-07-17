<?php
    //清除session和cookie導回登入頁
    session_start();//啟用Session功能
    session_destroy();//清除所有session

    //清除cookie，將過期時間定為之前的時間即可清除
    setcookie ( "name", "", time () - 100 ); //將時間設定成過去的時間

    $URL="../../../index.php"; 
    header("Location: $URL");// 將網址導回登入頁
?>