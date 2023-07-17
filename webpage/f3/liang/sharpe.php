<?php
    $teamid = $_COOKIE['teamid'];
    $user = 'username';
    $pass = 'password';
    $db = 'database';
    $con = mysqli_connect("localhost", $user, $pass, $db);
    $sql = "SELECT  *  FROM focus,company WHERE  teamid = '{$teamid}' and f = 1 and `focus`.`companyid` = `company`.`cid` and buy = 1";
    $company = mysqli_query ($con, $sql);
?>
<html>
    <?php
        include ("../header/header.php");        
    ?>

    <head>
        <style><?php include '../CSS/Sharpe.css';?></style>
        <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>      
        <title>Sharpe Ratio</title>
    </head>
    <body>    
        <div class="center" style="margin-top:5%;">
            <p>Sharpe Ratio</p>
        </div>
        <!-- ########### 下拉選單 ########### -->
        <form id="form" name="form" method="post" class="center">
            <select name='NEW' class="form form-control"  style="display: inline; margin-top:1%;">
                <option value="">--- choose company ---</option>
                <?php
                    while ($cat = mysqli_fetch_array($company,MYSQLI_ASSOC)):;
                ?>
                <option value="<?php echo $cat['companyid'];?>">
                    <?php echo $cat['companyid']," ",$cat['cname'];?>
                </option>
                <?php
                    endwhile;
                ?>
            </select>
            <!-- ########### button ########### -->
            <br>
            <input class="button" type="submit" name="Submit" value="Select" />
            <br>
            <div class='ps'>*每日18:20更新</div>
            <br>
            <div class="center">
            <?php
                if(isset($_POST['NEW'])) {
                  //echo "Selected NEW: ".htmlspecialchars($_POST['NEW']);
                  $code = $_POST['NEW'];

                  try {
                    $conn = new PDO("mysql:host=localhost; dbname=database", "username", "password");
                    // set the PDO error mode to exception
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    if(!empty($code)){
                        /* ########## 取得最近交易日的股價 ########## */
                        $teamid = $_COOKIE['teamid'];
                        $sql = "SELECT * FROM `sharp` WHERE `sharp`.`teamid` = '$teamid' AND `sharp`.`companyid` = '$code' ORDER BY date DESC LIMIT 1 ";
                        $return = $conn->query($sql);
                        $row = $return->fetch();
                        $sql_company = "SELECT `company`.`cid`,`company`.`cname`,`company`.`cclass` FROM `company` WHERE `company`.`cid` = '$code'";
                        $return_company = $conn->query($sql_company);
                        $row_company = $return_company->fetch();
                        $temp = 0;
                        if(!empty($row) && !empty($row_company)){
                            $temp = 1;
                            echo '<p>company ID: ',$row_company['cid']." ".$row_company['cname'],'</p>';
                        }else if(!empty($row_company)){
                            echo '<br><p>company ID: ';
                            echo $row_company['cid']." ".$row_company['cname'],'</p>';
                            echo '<p>目前只購買一天</p>';
                            echo '<p>沒有sharpe ratio的資料</p>';
                        }
                        else{
                            echo '<br>';
                            echo $code;
                            echo " 不是上市股票代碼，請再輸入一次！";
                        }
                        /*echo '<hr style="background-color:#22b4de; height: 3px; border:none;">';*/
                        
                        if($temp == 1){
                            ?>
                            <div class="col">無風險利率(台灣銀行利率)：1.575％</div><br>
                            <div class="table">
                                <ul class="responsive-table">
                                    <li class="table-header">
                                        <div class="col col-0">公司 Id</div>
                                        <div class="col col-1">Date</div>
                                        <div class="col col-2">Sharpe Ratio</div>
                                    </li>
                            <?php
                            $date_count = 0;
                            $points1 = array();
                            $sql_s = "SELECT * FROM `sharp` WHERE `sharp`.`teamid` = '$teamid' AND `sharp`.`companyid` = '$code' ORDER BY date DESC LIMIT 5 ";
                            //$sql_pbr = "SELECT * FROM pbr WHERE `id` = '$code' ORDER BY date DESC LIMIT 5 ";
                            $ret_s = $conn->query($sql_s);
                            date_default_timezone_set("Asia/Taipei");
                            foreach ($ret_s->fetchAll() as $pbr)
                            {
                            	$sharpe = round($pbr['val'], 2);
                            	$sharpe_ratio = round($sharpe/100, 2);
                                ?>
                                <li class="table-row">
                                    <div class="col col-0"><?php echo $pbr['companyid']." ".$row_company['cname'];?></div>
                                    <div class="col col-1" data-label="date"><?php echo $pbr['date'];?></div>
                                    <div class="col col-2" data-label="sharp"><?php echo number_format($sharpe,2)."%&nbsp;(".($sharpe_ratio).") ";?></div>
                                </li>
                                <?php
                                $point1 = array();
                                $point1['x'] = strtotime($pbr['date']) * 1000 + 8*3600000; //
                                $point1['y'] = $sharpe; //price
                                array_push($points1, $point1);
                                $date_count ++;
                            }


                            $jsonPBR = json_encode($points1);
                            ?>
                                </ul>
                            </div>

                            <?php

                            echo <<<CHARTS
                            <div id="container" class="center"></div>
                            <script src="https://code.highcharts.com/highcharts.js"></script>
                            <script>
                            Highcharts.chart("container", {
                                chart: {
                                    type: "line",
                                    height: 400,
                                    width:800,
                                    style: {
                                        fontFamily: 'sans'
                                    }
                                },
                                title: {
                                    text: "過去{$date_count}日的 夏普率(Sharpe Ratio)",
                                        style:{
                                        fontSize:"20px"
                                        }
                                },
                                xAxis: {
                                    type: "datetime",
                                    labels: {
                                        style:{
                                        fontSize:"14px"
                                        }
                                    },
                                    title: {
                                        text: "Date",
                                        style:{
                                        fontSize:"16px"
                                        }
                                    }
                                },
                                yAxis: {
                                    labels: {
                                        format: '{text}%' ,
                                        style:{
                                        fontSize:"14px"
                                        }
                                    },
                                    title: {
                                        text: "Sharpe Ratio",
                                        style:{
                                        fontSize:"18px"
                                        }
                                    }
                                },
                                credits: {
                                    enabled: false
                                },
                                tooltip:{
                                    valueSuffix: '%',
                                        style:{
                                        fontSize:"12px"
                                        }
                                },
                                series: [{
                                    data: $jsonPBR,
                                    name: "Sharpe Ratio",
                                    color: '#c81c33'
                                }
                                ]
                            });
                            </script>
                            CHARTS;
                        }

                    }else{
                        echo '<p>';echo "You don't choose the company id:(", '</p>';
                    }        
                }
                catch(PDOException $e){
                    echo $sql . "<br>" . $e->getMessage();
                }
                $conn = null;
                }else{
                    echo'<br><div class="col">sharpe ratio = 期望報酬率 × 無風險利率 / 報酬率的標準差 x 100% </div><br>';
                }
                

            ?>
            </div>
            <div class="wrapper"></div>
            <footer class="footer"><p class="ftext">Copyright © 2023 FIBDA, All Rights Reserved.</p></footer>
    </body>
</html>

