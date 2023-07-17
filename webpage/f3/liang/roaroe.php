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
        <style><?php include '../CSS/RoaRoe.css';?></style>
        <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>      
        <title>ROA & ROE</title>
    </head>
    <body>    
        <div class="center" style="margin-top:5%;">
            <p>ROA & ROE</p>
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
                        $sql = "SELECT `roa`.`id`,`roa`.`year`,`roa`.`val` roa ,`roe`.`val` roe FROM `roa`,`roe` WHERE `roa`.`id` = '$code' and `roe`.`id` = '$code'  and `roa`.`year`  = `roe`.`year`  ORDER BY year DESC LIMIT 1 ";
                        $return = $conn->query($sql);
                        $row = $return->fetch();
                        $sql_company = "SELECT `company`.`cid`,`company`.`cname`,`company`.`cclass` FROM `company` WHERE `company`.`cid` = '$code'";
                        $return_company = $conn->query($sql_company);
                        $row_company = $return_company->fetch();
                        $temp = 0;
                        if(!empty($row)){
                            $temp = 1;
                            echo '<p>company ID: ',$row_company['cid']." ".$row_company['cname'],'</p>';
                        }else if(!empty($row_company)){
                            echo '<br><p>company ID: ';
                            echo $row_company['cid']." ".$row_company['cname'],'</p>';
                            echo '<p>產業類別:';
                            echo $row_company['cclass'],'</p>';
                            echo '<p>沒有ROA、ROE的資料</p>';
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
                                        <div class="col col-1">Year</div>
                                        <div class="col col-2">ROA</div>
                                        <div class="col col-3">ROE</div>
                                    </li>
                            <?php
                            $year_count = 0;
                            $points1 = array();
                            $points2 = array();

                            $sql_roa = "SELECT `roa`.`id`,`roa`.`year`,`roa`.`val` roa,`roe`.`val` roe FROM roa,roe WHERE `roa`.`id` = '$code'and `roe`.`id` = '$code' and `roa`.`year` = `roe`.`year` ORDER BY year DESC LIMIT 4 ";
                            $ret_roa = $conn->query($sql_roa);

                            foreach ($ret_roa->fetchAll() as $roa)
                            {
                                ?>
                                <li class="table-row">
                                    <div class="col col-0"><?php echo $roa['id']." ".$row_company['cname'];?></div>
                                    <div class="col col-1" data-label="year"><?php echo $roa['year'];?></div>
                                    <div class="col col-2" data-label="roa"><?php echo $roa['roa'];?>%</div>
                                    <div class="col col-3" data-label="roe"><?php echo $roa['roe'];?>%</div>
                                </li>
                                <?php
                                $point1 = array();
                                $point1['x'] = (int)($roa['year']); //year
                                $point1['y'] = (float)($roa['roa']); //roa
                                array_push($points1, $point1);
                                $point2 = array();
                                $point2['x'] = (int)($roa['year']); //year
                                $point2['y'] = (float)($roa['roe']); //roe
                                array_push($points2, $point2);
                                $year_count++;
                            }
                            
                            $jsonROA = json_encode($points1);
                            $jsonROE = json_encode($points2);
                            ?>
                                </ul>
                            </div>
                            <div class="roaroe"><b>※ 若ROE高、ROA很低須注意，投資風險較高。</b></div><br><br>
                            <?php
                            echo <<<CHARTS
                            <div id="container"></div>
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
                                    text: "過去{$year_count}年的資產報酬率(ROA)、股東權益報酬率(ROE)",
                                        style:{
                                        fontSize:"20px"
                                        }
                                },
                                xAxis: {
                                    allowDecimals: false,
                                    labels: {
                                        style:{
                                        fontSize:"14px"
                                        }
                                    },
                                    title: {
                                        text: "Year",
                                        style:{
                                        fontSize:"18px"
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
                                        text: "ROA , ROE",
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
                                        fontSize:"14px"
                                        }
                                },
                                series: [{
                                    data: $jsonROA,
                                    name: "ROA",
                                    color: '#c81c33'
                                },{
                                    data: $jsonROE,
                                    name: "ROE",
                                    color: '#1c61c8'
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
                    echo'<div class="col">ROA  = 本期淨利（淨損） / ((期初資產總額 + 期末資產總額) / 2) x 100% &nbsp;&nbsp;&emsp;&emsp;</div><br>';
                    echo'<div class="col">ROE  = 本期淨利（淨損） / ((期初股東權益總額 + 期末股東權益總額) / 2) x 100%</div><br>';
                }
            ?>
            </div>
            <div class="wrapper"></div>
            <footer class="footer"><p class="ftext">Copyright © 2023 FIBDA, All Rights Reserved.</p></footer>
    </body>
</html>

