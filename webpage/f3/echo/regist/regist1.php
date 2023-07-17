<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="regist1.css" crossorigin="anonymous">
    <link href="../../img/icon.ico" rel="shortcut icon"/>
    <title>regist1</title>
</head>

<body><br>
    <div class="container">
        <div class="row">
            <div class="text-center" style="margin:0px auto;">
                <div class="panel-heading">
                <div >
                <br><input class="btn" type="button"  value="返回首頁" onclick="javascript:window.location.href='../../../index.php'">
            </div><br><br>
    
            <h1 class="panel-title">註冊帳戶(Team Leader)</h1><br>
            <form method="post" action="regist1.php">
            <p class="word" >&nbsp&nbsp&nbsp&nbspEmail：&nbsp&nbsp&nbsp&nbsp<input type="text" class="form" name="email" ></p>
            <p class="word" >&nbsp&nbspPassword：&nbsp&nbsp&nbsp<input type="text" class="form" name="password" placeholder="(6~20 characters, english and number only)"></p>
            <p class="word" >&nbsp&nbsp&nbsp&nbspName：&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" class="form" name="name" placeholder="(1~10 characters, english only)"></p>
            
            <br><h1 class="panel-title">建立團隊</h1><br>
            <p class="word" >&nbsp&nbsp&nbspTeam Name：&nbsp&nbsp&nbsp<input type="text" class="form" name="teamname" placeholder="(1~20 characters, english only)"></p>
            <p class="word" >Invitation Code：<input type="text" class="form" name="invitation" placeholder="(6~20 characters, english and number only)"></p><br>
            <input class="btn" type="submit" name="send" value="確認註冊"><br>
            </form><br>

            <?php
                if(isset($_POST["send"])){
                    $result1 = $_POST["email"]; 
                    $result2 = $_POST["password"]; 
                    $result3 = $_POST["name"];
                    $result4 = $_POST["teamname"];
                    $result5 = $_POST["invitation"];
                    
                    try {
                        $conn = new PDO("mysql:host=localhost;dbname=database", "username", "password");
                        // set the PDO error mode to exception
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        if(!empty($result1)&&!empty($result2)&&!empty($result3)&&!empty($result4)&&!empty($result5)){
                            if((filter_var($result1, FILTER_VALIDATE_EMAIL)) && (preg_match("/^[a-z\d]*$/i",$result2)) && (preg_match("/^[a-z]*$/i",$result3)) && (preg_match("/^[a-z]*$/i",$result4)) && (preg_match("/^[a-z\d]*$/i",$result5)) && (strlen($result2)>=6) && (strlen($result2)<=20) && (strlen($result3)<=10) && (strlen($result4)<=20) && (strlen($result5)>=6) && (strlen($result5)<=20)){
                                $sql = "SELECT * FROM `user` WHERE `email` = '$result1' OR `name` = '$result3' OR `team` = '$result4'";
                                $return = $conn->query($sql);
                                $re = $return->fetchALL(); 
                                if(!$re){
                                    $sql = "INSERT INTO `user` (`email`, `password`, `name`, `team`, `level`) VALUES ('$result1', '$result2', '$result3', '$result4', '3');";    
                                    $conn->exec($sql);
                                    $sql = "INSERT INTO `team` (`teamname`, `leadername`, `Invitation`) VALUES ('$result4', '$result3', '$result5');";    
                                    $conn->exec($sql);

                                    echo '<br>'.'<span style="color: white;font-size: 25px;">'. "New record created successfully." .'</span>';
                                    echo '<br>'.'<span style="color: white;font-size: 25px;">'."註冊成功！".'</span>';
                                }else{
                                    echo '<br>'.'<span style="color: red;font-size: 25px;">'."重複的帳戶或團隊名稱".'</span>';
                                }   
                            }else{
                                echo '<br>'.'<span style="color: red; font-size: 25px;">'."格式不正確！".'</span>';
                            }
                        }else{
                            echo '<br>'.'<span style="color: red;font-size: 25px;">'. "Some space can't be Empty！" .'</span>';
                            echo '<br>'.'<span style="color: red;font-size: 25px;">'."某些欄位不可為空".'</span>';
                        }        
                    }
                    catch(PDOException $e){
                        echo $sql . "<br>" . $e->getMessage();
                    }
                    $conn = null;
                }
            ?>
            </div>
            </div>
        </div>
    </div><br><br>
</body>
</html>