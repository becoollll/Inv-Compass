<?php
    $teamid = $_COOKIE['teamid'];
    $user = 'username';
    $pass = 'password';
    $db = 'database';
    $con = mysqli_connect("localhost", $user, $pass, $db);
    $sql = "SELECT  *  FROM focus WHERE  teamid = '{$teamid}' and f = 1";
    $company = mysqli_query ($con, $sql);
?>
<html>
    <?php
        include ("../header/header.php");        
    ?>

    <head>
        <style><?php include '../CSS/holding.css';?></style>  
        <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>      
        <script type="text/javascript" src="excel.js"></script>
        <meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8" />                
        <title>Holding</title>
    </head>
    <body>    
        <p class='chinese center'>持有股票&nbsp&nbsp
        <button type="button" class="export" onclick="myexcel()">excel匯出</button></p>
        <p class='ps center'>*鼠標移至 ID or NAME 上即可查看該股票過去的交易紀錄</p>
    <div>
        <div class="table">
            <ul class="responsive-table">
                <li class="table-header">
                <div class="col col-0">Id</div>
                <div class="col col-1">Name</div>
                <div class="col col-2">持有總股數</div>
                <div class="col col-3">總成本</div>
                <div class="col col-4">均價</div>
                </li>
                <?php
                    $teamid = $_COOKIE['teamid'];
                    $con = new PDO("mysql:host=localhost;dbname=database", "username", "password");
                    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $queryC = "SELECT * FROM `StockAvgPrice` WHERE `teamid` = '$teamid' ";
                    $return = $con->query($queryC);
                    $query_runC = $return->fetchAll();

                    if ($query_runC != null) {
                        foreach ($query_runC as $rowC) {
                ?>
                <li class="table-row">
                <div class="col col-0 box"><?php echo $rowC['companyid']; ?>
                <div class="content"><?php
                        $detail = "SELECT * FROM `StockTrading` WHERE `teamid` = '$teamid' AND `companyid` = '{$rowC['companyid']}'";
                        $return = $con->query($detail);
                        $data = $return->fetchall();
                        foreach ($data as $query_detail){
                            echo "date: ".$query_detail["date"]." ";
                            if ($query_detail["buyORsell"]=='0'){
                                echo "買 ";
                            }
                            else{
                                echo "賣 ";
                            }
                            if($query_detail["unit"]=='0'){
                                echo $query_detail["shareANDlot"]." 股 "; 
                            }
                            else{
                                echo $query_detail["shareANDlot"]." 張 "; 
                            }
                            echo "price: ".$query_detail["price"]."<br>";      
                        }
                ?></div></div>
                <div class="col col-1 box"><?php
                        $queryC = "SELECT * FROM `company` WHERE `cid` = '{$rowC[1]}' ";
                        $return = $con->query($queryC);
                        $query_runC = $return->fetch();
                        echo $query_runC[1];
                        ?>
                                <div class="content"><?php
                        $detail = "SELECT * FROM `StockTrading` WHERE `teamid` = '$teamid' AND `companyid` = '{$rowC['companyid']}'";
                        $return = $con->query($detail);
                        $data = $return->fetchall();
                        foreach ($data as $query_detail){
                            echo "date: ".$query_detail["date"]." ";
                            if ($query_detail["buyORsell"]=='0'){
                                echo "買 ";
                            }
                            else{
                                echo "賣 ";
                            }
                            if($query_detail["unit"]=='0'){
                                echo $query_detail["shareANDlot"]." 股 "; 
                            }
                            else{
                                echo $query_detail["shareANDlot"]." 張 "; 
                            }
                            echo "price: ".$query_detail["price"]."<br>";     
                        }
                ?></div></div>
                <div class="col col-2"><?php
                        echo number_format($rowC['share']);
                        ?></div>
                <div class="col col-3"><?php
                        echo number_format($rowC['totalCost'],2);
                        ?></div>
                <div class="col col-3"><?php
                        echo number_format($rowC['avgPrice'],2);
                        ?></div>
                </li>      
                <?php                        
                    }
                }
                ?>                   
            </ul>
        </div>
    </div>

    <div id="exportTable" style="display:none">
        <table id="ttt" border='1' style="text-align:center;">
            <tr id = r1>
                <td class="excelTD">Id</td>
                <td class="excelTD">Name</td>
                <td class="excelTD">持有總股數</td>
                <td class="excelTD">總成本</td>
                <td class="excelTD">均價</td>
            </tr>
            <?php
                $teamid = $_COOKIE['teamid'];
                $con = new PDO("mysql:host=localhost;dbname=database", "username", "password");
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $queryC = "SELECT * FROM `StockAvgPrice` WHERE `teamid` = '$teamid' ";
                $return = $con->query($queryC);
                $query_runC = $return->fetchAll();

                if ($query_runC != null) {
                    foreach ($query_runC as $rowC) {
            ?>
            <tr>
                <td class="excelTD"><?php 
                        echo $rowC['companyid'];
                        ?></td>
                <td class="excelTD"><?php
                        $queryC = "SELECT * FROM `company` WHERE `cid` = '{$rowC[1]}' ";
                        $return = $con->query($queryC);
                        $query_runC = $return->fetch();
                        echo $query_runC[1];
                        ?></td>
                <td class="excelTD"><?php
                        echo number_format($rowC['share']);
                        ?></td>
                <td class="excelTD"><?php
                        echo number_format($rowC['totalCost'],2);
                        ?></td>
                <td class="excelTD"><?php
                        echo number_format($rowC['avgPrice'],2);
                        ?></td>
            </tr>      
            <?php                        
                }
            }
            ?>     
        </table>
    </div>

    </div>
        <div class="wrapper"></div>
        <footer class="footer center"><p class="ftext">Copyright © 2023 FIBDA, All Rights Reserved.</p></footer>
    </body>
</html>

