<?php
//當未登入或是密碼輸入錯誤時被導回來的時$_GET['login'] 會等於 error
$login=isset($_GET['login']) ? $_GET['login'] : ''; //if判斷是否存在(條件):成立時顯示 ? 不成立顯示空值
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" crossorigin="anonymous">
    <link href="./f3/img/icon.ico" rel="shortcut icon"/>
    <title>login</title>
</head>
<body>
    <div class="center">
        <div class="w50 center">
            <div class="title">
                <img src="./f3/static/logo-3.png" alt="logo" width="100">
                <p>welcome<p>
                <p>投資指南針<p>
                <p>基金投資組合分析管理輔助系統<p>
            </div>
        </div>
        <div class="w50 center"> 
        <div class="container title2">
            <div>
                <div class="mg">
                    <div>
                        <h1>Login</h1><br>
                    </div>
                    <div>
                        <form action="f3/echo/login/adlogin.php" method="post">
                            <input type="text" class="form" name="account" placeholder=" Account(email)"><br>
                            <input type="password" class="form" name="password" placeholder=" Password"><br>
                            <button type="" class="btn btn-warning" id="loginin">Log in</button><br><br>
                        <form>
                        <input type="button" class="btn" value="註冊帳戶" onclick="javascript:window.location.href='f3/echo/regist/choose.php'">
                        <?php
                            if($login=='error'){ //當密碼錯誤，或尚未登入時，顯示提示文字
                                    echo "<center><font color='red'>*帳號或密碼錯誤!</font></center>";
                            }
                        ?>
                    </div>
                </div>
            </div>

        </div>
        </div>

    </div>

</body>
</html>

