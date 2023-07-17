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
        <style><?php include '../CSS/record.css';?></style>  
        <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>      
        <script type="text/javascript" src="excel.js"></script>
        <meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8" />        
        <title>Records</title>
    </head>
    <body>    
        <p class='chinese center'>所有交易紀錄&nbsp&nbsp
        <button type="button" class="export" onclick="myexcel()">excel匯出</button></p>
    
    <div>
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
                    $teamid = $_COOKIE['teamid'];
                    $con = new PDO("mysql:host=localhost;dbname=database", "username", "password");
                    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $queryC = "SELECT * FROM `StockTrading` WHERE `teamid` = '$teamid' ";
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
                        echo number_format($rowC['shareANDlot']);
                        ?></div>
                <div class="col col-5"><?php
                        if ($rowC['unit'] == 0) {
                            echo "股";
                        } else if ($rowC['unit'] == 1) {
                            echo "張";
                        }
                        ?></div>
                <div class="col col-6"><?php
                        echo number_format($rowC['price'],2);
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
                <td class="excelTD">Date</td>
                <td class="excelTD">Buy/Sell</td>
                <td class="excelTD">數量</td>
                <td class="excelTD">單位</td>
                <td class="excelTD">Price</td>
            </tr>
            <?php
                $teamid = $_COOKIE['teamid'];
                $con = new PDO("mysql:host=localhost;dbname=database", "username", "password");
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $queryC = "SELECT * FROM `StockTrading` WHERE `teamid` = '$teamid' ";
                $return = $con->query($queryC);
                $query_runC = $return->fetchAll();

                if ($query_runC != null) {
                    foreach ($query_runC as $rowC) {
            ?>
            <tr>
                <td class="excelTD"><?php echo $rowC['companyid']; ?></td>
                <td class="excelTD"><?php
                    $queryC = "SELECT * FROM `company` WHERE `cid` = '{$rowC['companyid']}' ";
                    $return = $con->query($queryC);
                    $query_runC = $return->fetch();
                    echo $query_runC['cname'];
                    ?></td>
                <td class="excelTD"><?php
                    echo $rowC['date'];
                    ?></td>
                <td class="excelTD"><?php
                    if ($rowC['buyORsell'] == 0) {
                        echo "buy";
                    } else if ($rowC['buyORsell'] == 1) {
                        echo "sell";
                    }
                    ?></td>
            
                <td class="excelTD"><?php
                    echo number_format($rowC['shareANDlot']);
                    ?></td>
                <td class="excelTD"><?php
                    if ($rowC['unit'] == 0) {
                        echo "股";
                    } else if ($rowC['unit'] == 1) {
                        echo "張";
                    }
                    ?></td>
                <td class="excelTD"><?php
                    echo number_format($rowC['price'],2);
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
