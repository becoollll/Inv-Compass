<?php
    $teamid = $_COOKIE['teamid'];
    $user = 'username';
    $pass = 'password';
    $db = 'database';
    $con = mysqli_connect("localhost", $user, $pass, $db);
    $sql = "SELECT  *  FROM focus,company WHERE  teamid = '{$teamid}' and f = 1 and `focus`.`companyid` = `company`.`cid`";
    $company = mysqli_query ($con, $sql);
?>
<html>
    <?php
        include ("../header/header.php");        
    ?>

    <head>
        <style><?php include '../CSS/PbrPerDaily.css';?></style>
        <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>      
        <title>PBR & PER(Daily)</title>
    </head>
    <body>    
        <div class="center" style="margin-top:5%;">
            <p>PBR & PER(Daily)</p>
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
            <div class='ps'>*每日18:10更新</div>
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
                        $sql = "SELECT `pbr`.`id`,`pbr`.`date`,`pbr`.`val` pbr,`pbr`.`avg` pbravg ,`per`.`val` per,`per`.`avg` peravg FROM `pbr`,`per` WHERE `pbr`.`id` = '$code' and `per`.`id` = '$code'  and `pbr`.`date`  = `per`.`date`  ORDER BY date DESC LIMIT 1 ";
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
                            echo '<p>產業類別:';
                            echo $row_company['cclass'],'</p>';
                            echo '<p>沒有PBR、PER的資料</p>';
                        }
                        else{
                            echo '<br>';
                            echo $code;
                            echo " 不是上市股票代碼，請再輸入一次！";
                        }
                        /*echo '<hr style="background-color:#22b4de; height: 3px; border:none;">';*/
                        echo '<br>';
                        if($temp == 1){
                            ?>
                            <div class="table">
                                <ul class="responsive-table">
                                    <li class="table-header">
                                        <div class="col col-0">公司 Id</div>
                                        <div class="col col-1">Date</div>
                                        <div class="col col-2">PBR</div>
                                        <div class="col col-3">PBR AVG<br><?php echo $row_company['cclass'];?>業</div>
                                        <div class="col col-4">PER</div>
                                        <div class="col col-5">PER AVG<br><?php echo $row_company['cclass'];?>業</div>
                                    </li>
                            <?php
                            $date_count = 0;
                            $points1 = array();
                            $points1avg = array();
                            $points2 = array();
                            $points2avg = array();
                            $sql_pbr = "SELECT `pbr`.`id`,`pbr`.`date`,`pbr`.`val` pbr,`pbr`.`avg` pbravg ,`per`.`val` per,`per`.`avg` peravg FROM `pbr`,`per` WHERE `pbr`.`id` = '$code' and `per`.`id` = '$code'  and `pbr`.`date`  = `per`.`date`  ORDER BY date DESC LIMIT 5 ";
                            //$sql_pbr = "SELECT * FROM pbr WHERE `id` = '$code' ORDER BY date DESC LIMIT 5 ";
                            $ret_pbr = $conn->query($sql_pbr);
                            date_default_timezone_set("Asia/Taipei");
                            foreach ($ret_pbr->fetchAll() as $pbr)
                            {
                                ?>
                                <li class="table-row">
                                    <div class="col col-0"><?php echo $pbr['id']." ".$row_company['cname'];?></div>
                                    <div class="col col-1" data-label="date"><?php echo $pbr['date'];?></div>
                                    <div class="col col-2" data-label="pbr"><?php echo $pbr['pbr'];?></div>
                                    <div class="col col-3" data-label="pbravg"><?php echo $pbr['pbravg'];?></div>
                                    <div class="col col-4" data-label="per"><?php echo $pbr['per'];?></div>
                                    <div class="col col-5" data-label="peravg"><?php echo $pbr['peravg'];?></div>
                                </li>
                                <?php
                                $point1 = array();
                                $avg1 = array();
                                $point1['x'] = strtotime($pbr['date']) * 1000 + 8*3600000; //
                                $point1['y'] = (float)($pbr['pbr']); //price
                                $avg1['x'] = strtotime($pbr['date']) * 1000 + 8*3600000;//
                                $avg1['y'] = (float)($pbr['pbravg']); //price
                                array_push($points1, $point1);
                                array_push($points1avg, $avg1);

                                $point2 = array();
                                $avg2 = array();
                                $point2['x'] = strtotime($pbr['date']) * 1000 + 8*3600000; //
                                $point2['y'] = (float)($pbr['per']); //price
                                $avg2['x'] = strtotime($pbr['date']) * 1000 + 8*3600000;//
                                $avg2['y'] = (float)($pbr['peravg']); //price
                                array_push($points2, $point2);
                                array_push($points2avg, $avg2);
                                $date_count ++;
                            }


                            $jsonPBR = json_encode($points1);
                            $jsonPER = json_encode($points2);
                            $jsonPBRAVG = json_encode($points1avg);
                            $jsonPERAVG = json_encode($points2avg);

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
                                    text: "過去{$date_count}日的 股價淨值比(PBR)、本益比(PER)",
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
                                        style:{
                                        fontSize:"14px"
                                        }
                                    },
                                    title: {
                                        text: "PBR , PER",
                                        style:{
                                        fontSize:"18px"
                                        }
                                    }
                                },
                                credits: {
                                    enabled: false
                                },
                                tooltip:{                                 
                                        style:{
                                        fontSize:"12px"
                                        }
                                },
                                series: [{
                                    data: $jsonPBR,
                                    name: "PBR",
                                    color: '#c81c33'
                                },{
                                    data: $jsonPBRAVG,
                                    name: "PBR AVG",
                                    color: '#ffbb4eb8'
                                },{
                                    data: $jsonPER,
                                    name: "PER",
                                    color: '#1c61c8'
                                },{
                                    data: $jsonPERAVG,
                                    name: "PER AVG",
                                    color: '#9fbbe4'
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
                    echo'<br><div class="col">PBR  = 當日股價 / 每股淨值(bps) </div><br>';
                    echo'<div class="col">PER  = 當日股價 / 每股盈餘(eps) </div><br>';
                }

            ?>
            </div>
            <div class="wrapper"></div>
            <footer class="footer"><p class="ftext">Copyright © 2023 FIBDA, All Rights Reserved.</p></footer>
    </body>
</html>

