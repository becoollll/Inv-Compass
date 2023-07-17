<?php
//給使用者瀏覽的所有指標資訊的table
	$user = 'username';
	$pass = 'password';
	$db = 'database';
	$con = mysqli_connect("localhost", $user, $pass, $db);
    $userid = $_COOKIE['teamid']; 
?>

<html>
<head>
<?php
include("../header/header.php");
?>

</head>
    <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>
    <title>Reminder</title>

    <style><?php include '../CSS/remindertable.css';?></style>
<body>
    <p class="chinese center">指標提示設定總表</p>
    <div>    
    <div class = "table">
		<ul class="responsive-table center">
			<li class="table-header">
				<div class="col col-0">公司 id</div>
				<div class="col col-1">PBR</div>
				<div class="col col-2">PER</div>
				<div class="col col-3">Sharpe</div>
                		<div class="col col-4">最近一次自行<?php echo "<br>";?>更改條件日期</div>
				<div class="col col-5">最近一次符合<?php echo "<br>";?>條件日期</div>
				<div class="col col-6">最近一次<?php echo "<br>";?>檢查日期</div>
			</li>
			<?php
				$sql = "SELECT * FROM focus,company WHERE `teamid` = '{$userid}' and f= 1 and `focus`.`companyid` = `company`.`cid`";
				$company = mysqli_query ($con, $sql);
				while($cat = mysqli_fetch_array($company,MYSQLI_ASSOC)){
			?>
			<li class="table-row">
                <!--++++++++++++++++++++   companyid   ++++++++++++++++++++++++++++++++++++++++++++++-->
				<div class="col col-0"><?php echo $cat['companyid']," ",$cat['cname']; ?></div>
                <div class="col col-1"><?php  
                //++++++++++++++++++++++++++    PBR     +++++++++++++++++++++++++++++
                    $sql1 = "SELECT * FROM reminder WHERE `teamid`='{$userid}' and `companyid`='{$cat['companyid']}'and A=1";
                    $result1 = mysqli_query ($con, $sql1);
                    $temp1 = 0;
                    while($cat1 = mysqli_fetch_array($result1,MYSQLI_ASSOC)){  //PBR
                        if($cat1['symbol'] == 0){
                            echo "> ";
                        }
                        elseif($cat1['symbol'] == 1){
                            echo "< ";
                        }
                        elseif($cat1['symbol'] == 2){
                            echo "= ";
                        }
                        elseif($cat1['symbol'] == 3){ ?>
                            &ge;  <!--大於等於-->
                    <?php    }
                        elseif($cat1['symbol'] == 4){ ?>
                            &le;   <!--小於等於-->
                    <?php    }
                        echo $cat1['value'];
                        $temp1 = 1;
                    }
                    if($temp1 == 0){
                        echo "-";
                    }
                    ?></div>
                <div class="col col-2"><?php  
                //+++++++++++++++++++++   PER   ++++++++++++++++++++++++++++++++++++++++++++++
                    $sql2 = "SELECT * FROM reminder WHERE `teamid`='{$userid}' and `companyid`='{$cat['companyid']}'and A=2";
                    $result2 = mysqli_query ($con, $sql2);
                    $temp2 = 0;
                    while($cat2 = mysqli_fetch_array($result2,MYSQLI_ASSOC)){  #PER
                        if($cat2['symbol'] == 0){
                            echo "> ";
                        }
                        elseif($cat2['symbol'] == 1){
                            echo "< ";
                        }
                        elseif($cat2['symbol'] == 2){
                            echo "= ";
                        }
                        elseif($cat2['symbol'] == 3){ ?>
                            &ge;
                    <?php    }
                        elseif($cat2['symbol'] == 4){ ?>
                            &le;
                    <?php    }
                        echo $cat2['value'];
                        $temp2 = 1;
                    }
                    if($temp2 == 0){
                        echo "-";
                    }
                    ?></div>
                <div class="col col-3"><?php 
                //++++++++++++++++++++++++   Sharp  ++++++++++++++++++++++++++++++++++++++++++++++
                    $sql3 = "SELECT * FROM reminder WHERE `teamid`='{$userid}' and `companyid`='{$cat['companyid']}'and A=3";
                    $result3 = mysqli_query ($con, $sql3);
                    $temp3 = 0;
                    while($cat3 = mysqli_fetch_array($result3,MYSQLI_ASSOC)){  #Sharp
                        if($cat3['symbol'] == 0){
                            echo "> ";
                        }
                        elseif($cat3['symbol'] == 1){
                            echo "< ";
                        }
                        elseif($cat3['symbol'] == 2){
                            echo "= ";
                        }
                        elseif($cat3['symbol'] == 3){ ?>
                            &ge;
                    <?php    }
                        elseif($cat3['symbol'] == 4){ ?>
                            &le;
                    <?php    }
                        echo $cat3['value'],"%";
                        $temp3 = 1;
                    }
                    if($temp3 == 0){
                        echo "-";
                    }
                    ?></div>
                <div class="col col-4"><?php 
                //++++++++++++++++++++++++   上次條件更改時間  ++++++++++++++++++++++++++++++++++++++++++++++
                $sqlclass = "SELECT * FROM `company` WHERE `cid`='{$cat['companyid']}'";  
                $result13 = mysqli_query ($con, $sqlclass);  
                $companytable = mysqli_fetch_array($result13,MYSQLI_ASSOC);  #company table
                $cclass = $companytable['cclass'];

                if($cclass != "ETF" && $cclass != "ETN" && $cclass != "存託憑證"){  //判斷公司的類別
                    $sql4 = "SELECT * FROM reminder WHERE `teamid`='{$userid}' and `companyid`='{$cat['companyid']}'and A=1"; 
                    //這裡先用PBR的時間如果沒有PBR的公司再說呵
                    $result4 = mysqli_query ($con, $sql4);
                    while($cat4 = mysqli_fetch_array($result4,MYSQLI_ASSOC)){  #Sharp
                        if($cat4['modifydate'] == "0000-00-00"){
                            echo "預設";
                        }
                        else if($cat4['modifydate'] == NULL){
                            echo "預設";
                        }
                        else{
                            echo $cat4['modifydate'];
                        }
                    }
                }
                else{  //如果有Sharp 用Sharp的
                    $sql44 = "SELECT * FROM reminder WHERE `teamid`='{$userid}' and `companyid`='{$cat['companyid']}'and A=3"; 
                    $result44 = mysqli_query ($con, $sql44);
                    $temp44 = 0;
                    while($cat44 = mysqli_fetch_array($result44,MYSQLI_ASSOC)){  #Sharp
                        if($cat44['modifydate'] == "0000-00-00"){
                            echo "預設";
                        }
                        else if($cat44['modifydate'] == NULL){
                            echo "預設";
                        }
                        else{
                            echo $cat44['modifydate'];
                        }
                        $temp44 = 1;
                    }
                    if($temp44 == 0){
                        echo "-";
                    }

                }
                    ?></div>
                
                <div class="col col-5"><?php 
                //++++++++++++++++++++++++   最近一次符合  ++++++++++++++++++++++++++++++++++++++++++++++
                if($cclass != "ETF" && $cclass != "ETN" && $cclass != "存託憑證"){
                    $sql5 = "SELECT * FROM reminder WHERE `teamid`='{$userid}' and `companyid`='{$cat['companyid']}'"; 
                    $result5 = mysqli_query ($con, $sql5);
                    while($cat5 = mysqli_fetch_array($result5,MYSQLI_ASSOC)){  #Sharp
                        if($cat5['conformdate'] == "0000-00-00"){
                        	if($cat5['A'] == 1)
                        	{
                        		echo "&nbsp;&nbsp;PBR &nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;&nbsp;&nbsp;&nbsp;<br>";
							}
                            else if($cat5['A'] == 2)
                        	{
                        		echo "&nbsp;&nbsp;PER &nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;&nbsp;&nbsp;&nbsp;<br>";
							}
							else if($cat5['A'] == 3)
                        	{
                        		echo "Sharpe : &nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;&nbsp;&nbsp;&nbsp;<br>";
							}
                        }
                        else if($cat5['conformdate'] == NULL){
                            if($cat5['A'] == 1)
                        	{
                        		echo "&nbsp;&nbsp;PBR &nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;&nbsp;&nbsp;&nbsp;<br>";
							}
                            else if($cat5['A'] == 2)
                        	{
                        		echo "&nbsp;&nbsp;PER &nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;&nbsp;&nbsp;&nbsp;<br>";
							}
							else if($cat5['A'] == 3)
                        	{
                        		echo "Sharpe : &nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;&nbsp;&nbsp;&nbsp;<br>";
							}
                        }
                        else{
                        	if($cat5['A'] == 1)
                        	{
                        		echo "&nbsp;&nbsp;PBR &nbsp;: ";
							}
                            else if($cat5['A'] == 2)
                        	{
                        		echo "&nbsp;&nbsp;PER &nbsp;: ";
							}
							else if($cat5['A'] == 3)
                        	{
                        		echo "Sharpe  : ";
							}
                            echo $cat5['conformdate'],"<br>";
                        }
                    }
                }
                else{  //如果有Sharp 用Sharp的
                    $sql55 = "SELECT * FROM reminder WHERE `teamid`='{$userid}' and `companyid`='{$cat['companyid']}'and A=3"; 
                    $result55 = mysqli_query ($con, $sql55);
                    $temp55 = 0;
                    while($cat55 = mysqli_fetch_array($result55,MYSQLI_ASSOC)){  #Sharp
                        if($cat55['conformdate'] == "0000-00-00"){
                        	echo "Sharpe  - ";
                        }
                        else if($cat55['conformdate'] == NULL){
                        	echo "Sharpe  - ";
                        }
                        else{
                        	echo "Sharpe  ",$cat55['conformdate'];
                        }
                        $temp55 = 1;
                    }
                    if($temp55 == 0){
                        echo "-";
                    }

                }
                    ?></div>
                <div class="col col-6"><?php 
                //++++++++++++++++++++++++   上次檢查日期  ++++++++++++++++++++++++++++++++++++++++++++++
                if($cclass != "ETF" && $cclass != "ETN" && $cclass != "存託憑證"){
                    $sql6 = "SELECT * FROM reminder WHERE `teamid`='{$userid}' and `companyid`='{$cat['companyid']}'and A=1"; 
                    $result6 = mysqli_query ($con, $sql6);
                    while($cat6 = mysqli_fetch_array($result6,MYSQLI_ASSOC)){  #Sharp
                        if($cat6['checkdate'] == "0000-00-00"){
                            echo "-";
                        }
                        else if($cat6['checkdate'] == NULL){
                            echo "-";
                        }
                        else{
                            echo $cat6['checkdate'];
                        }
                    }
                }
                else{  //如果有Sharp 用Sharp的
                    $sql66 = "SELECT * FROM reminder WHERE `teamid`='{$userid}' and `companyid`='{$cat['companyid']}'and A=3"; 
                    $result66 = mysqli_query ($con, $sql66);
                    $temp66 = 0;
                    while($cat66 = mysqli_fetch_array($result66,MYSQLI_ASSOC)){  #Sharp
                        if($cat66['checkdate'] == "0000-00-00"){
                            echo "-";
                        }
                        else if($cat66['checkdate'] == NULL){
                            echo "-";
                        }
                        else{
                            echo $cat66['checkdate'];
                        }
                        $temp66 = 1;
                    }
                    if($temp66 == 0){
                        echo "-";
                    }

                }

                }
                    ?></div>
                

			</li>


		</ul>
    </div>
</div>
    <div class="wrapper"></div>
    <footer class="footer center"><p class="ftext">Copyright © 2023 FIBDA, All Rights Reserved.</p></footer>
    <!--<script src="../level.js"></script>-->

</body>
</html>
