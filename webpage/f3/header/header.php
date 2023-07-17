<script>@import { config } from '@fortawesome/fontawesome-svg-core'  \
config.autoA11y = true
</script>
<?php
  //判斷是否登入,未登入導回登入頁
  session_start();

  if(!$_SESSION['mylogin']){
    echo "導回登入頁";
    $URL="../echo/login/login.php?login=error"; 
    header("Location: $URL"); //將網址導回登入頁
  }
?>

<html>
    <head>
        <link href="../img/icon.ico" rel="shortcut icon"/>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="../header/heads.css">
        <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>
    </head>
    <div class="header">
    <img src="../static/logo-3.png" alt="logo" width="100">
        
        
        <div class="nav-wrapper">
            <nav>
                <ul class="flex-nav">
             
                    <li class="dropdown ri"><a href="../echo/focus.php" style="cursor: pointer;">Focus</a></li>
                    <li class="dropdown ri">
                        <a class="dropdown-toggle" data-toggle="dropdown">Index <i class="fas fa-angle-down "></i></a>
                        <ul class="dropdown-menu ">
                            <li class="center">基礎指標</li>
                            <li><a class = "ba" href="../liang/roaroe.php">ROA & ROE (Yearly)</a></li>
                            <li><a class = "ba" href="../liang/pbrperYear.php">PBR & PER (Yearly)</a></li>
                            <li><a class = "ba" href="../liang/pbrperdaily.php">PBR & PER (Daily)</a></li>
                            <li class="center">進階指標</li>
                            <li><a class = "ba" href="../liang/sharpe.php">Sharpe Ratio</a></li>
                        </ul>
                    </li>
                    <li class="dropdown ri" >
                        <a class="dropdown-toggle" data-toggle="dropdown">Stock Trading <i class="fas fa-angle-down "></i></a>
                        <ul class="dropdown-menu ">
                            <li><a class = "ba" href="../stock/StockTrading.php" >Buy/Sell</a></li>
                            <li><a class = "ba" href="../stock/ClosingPrice.php">Closing Price</a></li>
                            <li><a class = "ba" href="../stock/record.php">Records</a></li>
                            <li><a class = "ba" href="../stock/holding.php">Holding</a></li>
                        </ul>
                    </li>
                    <li class="dropdown ri">
                    	<a class="dropdown-toggle" data-toggle="dropdown">Reminder <i class="fas fa-angle-down "></i></a>
                        <ul class="dropdown-menu ">
                            <li><a class = "ba" href="../alert/remindertable.php" >Reminder table</a></li>
                            <?php
                            $userlevel = $_COOKIE['userlevel']; 
                            if ($userlevel > 1 ){?>                            
                            <li><a class = "ba" href="../alert/inpput.php">Reminder Setting </a></li>
                            <?php }?>
                        </ul>
                    </li>

                </ul>
            </nav>
        </div>
        <div class="user">
            <nav>
                <a href="../echo/userpage/judge.php" style="font-size:25px; padding-top:10%;text-decoration: none;"><?php echo $_COOKIE['username'];?></a>
                    <div class="usercontent">
                        <?php echo "Name : ".$_COOKIE['username'];
                            echo "<br>Team : ".$_COOKIE['userteam'],"<br>";
                            if ($_COOKIE['userlevel']==1)
                                echo "Identity : Analyst";
                            else if ($_COOKIE['userlevel']==2)
                                echo "Identity : Trader";
                            else if ($_COOKIE['userlevel']==3)
                                echo "Identity : Leader";
                        ?>
                    </div>   
            </nav><br>            
            <a href="../echo/login/logout.php"><button class="btn btn-default">Log out</button></a>
        </div>
    </div>
    
</html>
