<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="userpg2.css" crossorigin="anonymous">
    <link href="../../img/icon.ico" rel="shortcut icon"/>
    <title>管理帳戶(Leader)</title>
</head>

<body>
    <div class="container">        
        <div class="left">                
            <div class="text-center" style="margin:0px auto;">          
                <img src="prs2.png" title="Your ugly photo"/><br><br>
                <div class="user1">
                    <p class="word" ><?php    
                        $userid = $_COOKIE['userid'];
                        echo "ID： ".str_pad($userid,8,"0",STR_PAD_LEFT);
                    ?></p>
                </div>
            </div>                
        </div>
        <div class="right">                
            <div class="text"><br>           
                <div class="text-center" style="margin:0px auto;"><h1 class="panel-title">管理帳戶資訊</h1></div><br><br>
                <p class="word" ><?php    
                    $useremail = $_COOKIE['useremail'];
                    $userteam = $_COOKIE['userteam'];
                    $userlevel = $_COOKIE['userlevel'];
                    echo "&nbsp&nbsp&nbspAccount(email)：&nbsp".$useremail."<br>";
                    echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspTeam&nbsp&nbsp&nbsp&nbsp&nbsp：&nbsp".$userteam."<br>";
                    echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspLevel&nbsp&nbsp&nbsp&nbsp：&nbsp"."Leader"."<br>";
                ?></p>
                <form method="post" action="doing2.php">
                    <p class="word" >&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspName&nbsp&nbsp&nbsp&nbsp&nbsp：<input type="text" class="form" name="newname" placeholder="<?php echo $_COOKIE['username'];?>(1~10 characters,english only)"></p>
                    <p class="word" >&nbsp&nbsp&nbsp&nbsp&nbsp&nbspPassword&nbsp&nbsp&nbsp：<input type="text" class="form" name="newpassword" placeholder="(6~20 characters,english and number only)"></p>
                    <p class="word" >&nbsp&nbspInvitation&nbspCode：<input type="text" class="form" name="newinvitation" placeholder="(6~20 characters,english and number only)"></p>
                    <br><br><br><div class="text-center" style="margin:0px auto;">
                    <input class="btn" type="submit" name="check" value="查看團隊資訊">
                    <input class="btn" type="submit" name="yes" value="儲存修改">
                    <input class="btn" type="submit" name="no" value="取消(返回首頁)"><br><br><br>              
                </form>
                <?php
                    $change=isset($_GET['change']) ? $_GET['change'] : ''; //if判斷是否存在(條件):成立時顯示 ? 不成立顯示空值
                    if($change=='error'){ 
                        echo "<center><font color='red'>格式錯誤!</font></center>";
                    }
                ?></div>
            </div>
        </div>
        <div class="clearfix"></div>     
    </div> 
</body>
</html>
