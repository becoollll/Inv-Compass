<html>
    <head>
        <style><?php include 'userteam.css';?></style>
        <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>      
        <link href="../../img/icon.ico" rel="shortcut icon"/>
        <title>Team Check</title>
    </head>
    <body>    
        <div class="center">
            <input class="btn" type="button" value="返回" style="margin-top:5%;" onclick="javascript:window.location.href='../userpage/userpg2.php'">
            <div class="center" style="margin-top:2%;">
                <?php
                    $userteam = $_COOKIE['userteam']; //teamname
                    $conn = new PDO("mysql:host=localhost; dbname=database", "username", "password");
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql_team = "SELECT * FROM `team` WHERE `teamname` = '$userteam'";
                    $return_team = $conn->query($sql_team);
                    $row_team = $return_team->fetch();
                ?>
                <p>Team name: <?php echo $row_team['teamname'];?></p>
            </div>  
            <?php
                try {
                    if(!empty($userteam)){
                        echo '<br>';
                        if($row_team){
            ?>
                            <div class="table">
                                <ul class="responsive-table">
                                    <li class="table-header">
                                        <div class="col tcol-0">Team</div>
                                        <div class="col tcol-1">Leader Name</div>
                                        <div class="col tcol-2">Analyst</div>
                                        <div class="col tcol-3">Trader</div>
                                        <div class="col tcol-4">Invitation</div>
                                    </li>
                                    <li class="table-row">
                                        <div class="col tcol-0"><?php echo $row_team['teamname'];?></div>
                                        <div class="col tcol-1"><?php echo $row_team['leadername'];?></div>
                                        <div class="col tcol-2"><?php echo $row_team['analyst'];?></div>
                                        <div class="col tcol-3"><?php echo $row_team['trader'];?></div>
                                        <div class="col tcol-4"><?php echo $row_team['Invitation'];?></div>
                                    </li>
                                </ul>
                            </div>    
                            <br>
                            <div class="table">
                                <ul class="responsive-table">
                                    <li class="table-header">
                                        <div class="col ucol-0">Team</div>
                                        <div class="col ucol-1">Email</div>
                                        <div class="col ucol-3">Name</div>
                                        <div class="col ucol-4">Level</div>
                                    </li>
                            <?php
                                $sql_user = "SELECT * FROM `user` WHERE `user`.`team` = '$userteam' ORDER BY `user`.`level` DESC;";
                                $ret_user = $conn->query($sql_user);

                                foreach ($ret_user->fetchAll() as $user)
                                {
                            ?>
                                    <li class="table-row">
                                        <div class="col ucol-0"><?php echo $user['team'];?></div>
                                        <div class="col ucol-1"><?php echo $user['email'];?></div>
                                        <div class="col ucol-3"><?php echo $user['name'];?></div>
                                        <div class="col ucol-4"><?php echo $user['level']; 
                                            if ($user['level'] == '1'){echo"(Analyst)";}
                                            else if ($user['level'] == '2'){echo"(Trader)";}
                                            else if ($user['level'] == '3'){echo"(Leader)";}
                                            ?>
                                        </div>
                                    </li>
                            <?php
                                }
                            ?>
                                </ul>
                            </div>
                            <?php                        
                        }
                    }else{
                        echo '<p>';echo "You don't choose the team:(", '</p>';
                    }        
                }
                catch(PDOException $e){
                    echo $sql . "<br>" . $e->getMessage();
                }
                $conn = null;            
            ?> 
                <div class="wrapper"></div>
                <footer class="footer"><p class="ftext">Copyright © 2023 FIBDA, All Rights Reserved.</p></footer>
        </div>
    </body>
</html>

