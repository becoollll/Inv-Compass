<?php
    $user = 'username';
    $pass = 'password';
    $db = 'database';
    $con = mysqli_connect("localhost", $user, $pass, $db);
    $userid = $_COOKIE['userid'];
    $teamid = $_COOKIE['teamid'];
    $userlevel = $_COOKIE['userlevel'];
    $sql = "SELECT `focus`.`teamid`,`company`.`cid`,`focus`.`f`,`focus`.`buy`,`company`.`cname` FROM `focus`,`company` WHERE teamid = '{$teamid}' and f = 1 and `focus`.`companyid` = `company`.`cid`";
    $company = mysqli_query ($con, $sql);
?>

<html>
    <head>
        <title>closing price</title>
        <?php include ("../header/header.php");?>
        <style><?php include '../CSS/StockTrading.css';?></style>
    </head>

    <body>
    <?php    
        if ($userlevel > 1){
    ?>        
        <div class="center" style="padding-top:10%;">
        <form id="form" name="form" method="post" class="center">

            <!-- ########### buy or sell ########### -->
            <select class="form"  name="bs">
                <option>-- buy/sell --</option>
                <option>buy</option>
                <option>sell</option>
            </select>

            <!-- ########### 公司名稱 ########### -->
            <select class="form"  name="ChooseCompany">
                <option>-- choose company --</option>
                <?php
                    while ($cat = mysqli_fetch_array($company,MYSQLI_ASSOC)):;
                ?>
                <option>
                    <?php echo $cat['cid']," ", $cat['cname'];?>
                </option>
                <?php
                    endwhile;
                ?>
                <option disabled>若要選擇買賣請先關注該公司</option>
            </select>

            <input type="date" class="form_10 chinese" name="date" placeholder="請輸入時間"/>

            <!-- ########### 單位(股 or 張) ########### -->
            <select class="form_10 chinese" name="unit">
                <option>張</option>
                <option>股</option>
            </select>

            <!-- ########### 股數 or 張數 ########### -->
            <input type="number" id="num" name="num" min="0" class="form_10" placeholder="number" >
            
            <!-- ########### 單價 ########### -->
            <input type="number" id="price" name="price" min="0" class="form_10" step="0.01" placeholder="price">

            <!-- ########### button ########### -->
            <br>
            <?php
                if ($userlevel == 1) {
            ?>
                <button class="button" onclick="ShowMe()" >ok</button>
            <?php
                }else if ($userlevel > 1) {
            ?>
                <input class="button" type="submit" name="Submit" value="ok" />
            </div>
            <div class="center">
            <?php
                $userid = $_COOKIE['userid'];
                $teamid = $_COOKIE['teamid'];
                if(isset($_POST['bs'])){

                    $bs = $_POST['bs'];
                    $cc = $_POST['ChooseCompany'];
                    $unit = $_POST['unit'];
                    $num = $_POST['num'];
                    $price = $_POST['price'];
                    $date = $_POST['date'];

                    
                    $today = date("Y-m-d");

                    $end= strtotime($today)+86400;
                    $datee= strtotime($date);
                    
                    $connn = new PDO("mysql:host=localhost;dbname=database", "username", "password");
                    $connn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql_d = "SELECT `date` FROM `date`";
                    $return = $connn->query($sql_d);
                    $date_h = $return->fetchall();

                    $flag = 0;
                    
                    //國定假日
                    foreach($date_h as $d){
                        if($d == $date){
                            $flag = 1;
                        }
                    }

                    //假日
                    if((date('w',strtotime($date))==6) || (date('w',strtotime($date)) == 0)){
                        $flag = 1;
                    }

                    //未來
                    if($datee >= $end){
                        $flag = 2;
                    }


                    try{    
                        $conn = new PDO("mysql:host=localhost; dbname=database", "username", "password");
                        // set the PDO error mode to exception
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        if($flag != 0){
                            if ($flag == 1){
                                echo "<b>您輸入的日期為台股休市日</b>";
                            }
                            elseif($flag == 2){
                                echo "<b>不可輸入尚未到來的日期:(</b>";
                            }
                        }
                        elseif($cc and (is_numeric($cc[0])) and $num and $price and ($bs == "buy" or $bs == "sell") and ($unit == "股" or $unit == "張") and $flag == 0){
                            $ccs=explode(" ", $cc);

                            if($bs == "buy"){
                                $bors = 0;
                            }
                            else{
                                $bors = 1;
                            }
                            if($unit == "股"){
                                $u = 0;
                                $cal_share = $num;
                            }
                            else{
                                $u = 1;
                                $cal_share = $num * 1000;
                            }

                            $buyPrice = $cal_share * $price;

                            $sql_compare = "SELECT * FROM `StockAvgPrice` WHERE `teamid` = '{$teamid}' and `companyid` = '" . sprintf("%s", $ccs[0]) . "'";
                            $ret = mysqli_query($con, $sql_compare);

                            if (mysqli_num_rows($ret) == 0) {
                                if($bors == 0){
                                    $sql_insert = "INSERT INTO `StockAvgPrice` (`teamid`, `companyid`, `share`, `totalCost`, `avgPrice`) VALUES ('{$teamid}', '{$ccs[0]}', '{$cal_share}', '{$buyPrice}', '{$price}')";
                                    mysqli_query($con, $sql_insert);

                                    $sql = "INSERT INTO `StockTrading` ( `teamid`, `userid`, `companyid`, `date`, `buyORsell`, `unit`, `shareANDlot`, `price`) VALUES ('{$teamid}','{$userid}','{$ccs[0]}','{$date}','{$bors}','{$u}','{$num}','{$price}')";
                                    $conn->query($sql);

                                    $sql_compare_focus = "SELECT * FROM `focus` WHERE `teamid` = '{$teamid}' and `companyid` = '{$ccs[0]}'";
                                    $ret_focus = mysqli_query($con, $sql_compare_focus);

                                    if (mysqli_num_rows($ret_focus) == 0) {
                                        $sql_insert_focus = "INSERT INTO `focus`(`teamid`, `companyid`, `f`, `buy`) VALUES ('{$teamid}','{$ccs[0]}','1','1')";
                                        mysqli_query($con, $sql_insert_focus); 
                                    }
                                    else{
                                        $sql_focus = "UPDATE `focus` SET `buy`='1',f = '1' WHERE teamid='{$teamid}' and companyid='{$ccs[0]}'";
                                        mysqli_query($con, $sql_focus);
                                    }
                                    /* ------------- remainder -------------*/
                                    $sqldata = "SELECT * FROM `reminder` WHERE `teamid`='{$teamid}'"; #reminder table
                                    $resultdata = mysqli_query ($con, $sqldata);
                                    $flag = 0;
                                    while($data = mysqli_fetch_array($resultdata,MYSQLI_ASSOC)){
                                        if($data['companyid'] == $ccs[0] && $data['A'] == 3){  //如果這間公司的A=3 已經存在 則 update
                                            $flag = 1;
                                        }
                                    }
                                    if($flag == 1){  //已有 UPDATE
                                        $sql_A3_update = "UPDATE `reminder` SET `symbol` = '0', `value` = '0',`flag` = '0', `flag2` = '0', `checkdate` = NULL, `conformdate` = NULL, `modifydate` = NULL WHERE `teamid` = '{$teamid}' and `companyid` = '{$ccs[0]}' and `A` = '3'";
                                        mysqli_query($con, $sql_A3_update);
                                    }
                                    else{  //新增
                                        $sql_A3_insert = "INSERT INTO `reminder`(`teamid`,`companyid`,`A`,`value`) VALUE('{$teamid}', '{$ccs[0]}', '3', '0')";
                                        mysqli_query($con, $sql_A3_insert); 
                                    }
                                    /*---------------------------------------------*/
                                }
                                else{
                                    echo "<b>你尚未買過這支股票</b>";
                                }
                            }
                            else{
                                $data = mysqli_fetch_assoc($ret);
                                $totalcost = $data['totalCost'];
                                $share = $data['share'];
                                if($bors == 0){
                                    $totalcost = $totalcost + $buyPrice;
                                    $share = $share + $cal_share; 
                                }
                                else{
                                    $totalcost = $totalcost - $buyPrice;
                                    $share = $share - $cal_share; 
                                }
                                if($share < 0){
                                    echo "<b>賣出股票多於擁有股票，輸入有誤:(</b>";
                                }
                                elseif ($share == 0) {
                                    $sql = "INSERT INTO `StockTrading` ( `teamid`, `userid`, `companyid`, `date`, `buyORsell`, `unit`, `shareANDlot`, `price`) VALUES ('{$teamid}','{$userid}','{$ccs[0]}','{$date}','{$bors}','{$u}','{$num}','{$price}')";
                                    $conn->query($sql);
                                    
                                    $avgPrice = 0;
                                    $sql_update = "UPDATE `StockAvgPrice` SET `share`='{$share}' ,`totalCost`='{$totalcost}', `avgPrice`= '{$avgPrice}' WHERE teamid='{$teamid}' and companyid='{$ccs[0]}'";
                                    mysqli_query($con, $sql_update);

                                    $sql_focus = "UPDATE `focus` SET `buy`='2',f = '1' WHERE teamid='{$teamid}' and companyid='{$ccs[0]}'";
                                    mysqli_query($con, $sql_focus);
                                    /* ------------- remainder -------------*/
                                    $sqldata = "SELECT * FROM `reminder` WHERE `teamid`='{$teamid}'"; #reminder table
                                    $resultdata = mysqli_query ($con, $sqldata);
                                    $flag = 0;
                                    while($data = mysqli_fetch_array($resultdata,MYSQLI_ASSOC)){
                                        if($data['companyid'] == $ccs[0] && $data['A'] == 3){  //如果這間公司的A=3 已經存在 則 update
                                            $flag = 1;
                                        }
                                    }
                                    if($flag == 1){  //已有 UPDATE
                                        $sql_A3_update = "UPDATE `reminder` SET `symbol` = '0', `value` = '0',`flag` = '0', `flag2` = '0', `checkdate` = NULL, `conformdate` = NULL, `modifydate` = NULL WHERE `teamid` = '{$teamid}' and `companyid` = '{$ccs[0]}' and `A` = '3'";
                                        mysqli_query($con, $sql_A3_update);
                                    }
                                    else{  //新增
                                        $sql_A3_insert = "INSERT INTO `reminder`(`teamid`,`companyid`,`A`,`value`) VALUE('{$teamid}', '{$ccs[0]}', '3', '0')";
                                        mysqli_query($con, $sql_A3_insert); 
                                    }
                                    /*---------------------------------------------*/
                                }
                                else{
                                    $sql = "INSERT INTO `StockTrading` ( `teamid`, `userid`, `companyid`, `date`, `buyORsell`, `unit`, `shareANDlot`, `price`) VALUES ('{$teamid}','{$userid}','{$ccs[0]}','{$date}','{$bors}','{$u}','{$num}','{$price}')";
                                    $conn->query($sql);
                                    $avgPrice = $totalcost / $share;
                                    $sql_update = "UPDATE `StockAvgPrice` SET `share`='{$share}' ,`totalCost`='{$totalcost}', `avgPrice`= '{$avgPrice}' WHERE teamid='{$teamid}' and companyid='{$ccs[0]}'";
                                    mysqli_query($con, $sql_update);
                                    $sql_focus = "UPDATE `focus` SET `buy`='1',f = '1' WHERE teamid='{$teamid}' and companyid='{$ccs[0]}'";
                                    mysqli_query($con, $sql_focus);
                                    /* ------------- remainder -------------*/
                                    $sqldata = "SELECT * FROM `reminder` WHERE `teamid`='{$teamid}'"; #reminder table
                                    $resultdata = mysqli_query ($con, $sqldata);
                                    $flag = 0;
                                    while($data = mysqli_fetch_array($resultdata,MYSQLI_ASSOC)){
                                        if($data['companyid'] == $ccs[0] && $data['A'] == 3){  //如果這間公司的A=3 已經存在 則 update
                                            $flag = 1;
                                        }
                                    }
                                    if($flag == 1){  //已有 UPDATE
                                        $sql_A3_update = "UPDATE `reminder` SET `symbol` = '0', `value` = '0',`flag` = '0', `flag2` = '0', `checkdate` = NULL, `conformdate` = NULL, `modifydate` = NULL WHERE `teamid` = '{$teamid}' and `companyid` = '{$ccs[0]}' and `A` = '3'";
                                        mysqli_query($con, $sql_A3_update);
                                    }
                                    else{  //新增
                                        $sql_A3_insert = "INSERT INTO `reminder`(`teamid`,`companyid`,`A`,`value`) VALUE('{$teamid}', '{$ccs[0]}', '3', '0')";
                                        mysqli_query($con, $sql_A3_insert); 
                                    }
                                    /*---------------------------------------------*/
                                }
                            }
                        }
                        else{
                            echo "<b>欄位不可為空:(</b>";
                        }
                    }
                    catch(PDOException $e){
                        echo $sql . "<br>" . $e->getMessage();
                    }
                    $conn = null;
                }
            }
            }
            ?>
            </div>
            <div>

		<p class='chinesee center' style="margin-top:5%;">今日交易紀錄</p>
                        <div class="table">
                        <ul class="responsive-table">
                            <li class="table-header">
                            <div class="col col-0">Id</div>
                            <div class="col col-1">Name</div>
                            <div class="col col-2">Date</div>
                            <div class="col col-3">Buy/Sell</div>
                            <div class="col col-4">數量</div>
                            <div class="col col-5">單位</div>
                            <div class="col col-6">Price</div>
                            </li>
                <?php
                try{    
                    $teamid = $_COOKIE['teamid'];
                    $today = date("Y-m-d");
                    $con = new PDO("mysql:host=localhost;dbname=database", "username", "password");
                    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $queryC = "SELECT * FROM `StockTrading` WHERE `teamid` = '$teamid' AND `date` = '$today'";
                    $return = $con->query($queryC);
                    $query_runC = $return->fetchAll();

                    if ($query_runC != null) {
						foreach ($query_runC as $rowC) {
                ?>
                <li class="table-row">
                <div class="col col-0"><?php echo $rowC['companyid']; ?></div>
                <div class="col col-1"><?php
                        $queryC = "SELECT * FROM `company` WHERE `cid` = '{$rowC['companyid']}' ";
                        $return = $con->query($queryC);
                        $query_runC = $return->fetch();
                        echo $query_runC['cname'];
                        ?></div>
                <div class="col col-2"><?php
                        echo $rowC['date'];
                        ?></div>
                <div class="col col-3"><?php
                        if ($rowC['buyORsell'] == 0) {
                            echo "buy";
                        } else if ($rowC['buyORsell'] == 1) {
                            echo "sell";
                        }
                        ?></div>
                <div class="col col-4"><?php
                        if ($rowC['unit'] == 0) {
                            echo "股";
                        } else if ($rowC['unit'] == 1) {
                            echo "張";
                        }
                        ?></div>
                <div class="col col-5"><?php
                        echo number_format($rowC['shareANDlot']);
                        ?></div>
                <div class="col col-6"><?php
                        echo number_format($rowC['price'],2);
                        ?></div>
                </li>  
                <?php
                        
                    }
                }
                
                }
                    catch(PDOException $e){
                        echo $sql . "<br>" . $e->getMessage();
                    }
                ?>                   
                </ul>
                </div>

    </div>
    </div>
            <div class="wrapper"></div>
            <footer class="footer center"><p class="ftext">Copyright © 2023 FIBDA, All Rights Reserved.</p></footer>
            <script src="../level.js"></script>
    </body>
</html>

