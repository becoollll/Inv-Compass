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
        <style><?php include '../CSS/ClosingPrice.css';?></style>  
        <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>      
        <title>closing price</title>

    </head>
    <body>    
        <div class="center" style="margin-top:5%;">
            <p>Closing price</p>
        </div>
        <!-- ########### 下拉選單 ########### -->
        <form id="form" name="form" method="post" class="center">
            <select name='NEW' class="form" style="margin-top:1%;">
                <option value="">choose company</option>
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
            <div class='ps'>*每日18:00更新</div>
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
                      
                        /* ########## 取得最近交易日的日期 ########## */
                        $sql_date = "SELECT  MAX(date) from ClosingPrice;";
                        $ret_date = $conn->query($sql_date);
                        $datetemp = $ret_date->fetch();
                        $date = $datetemp[0];

                        echo '<br>';
                        
                        $sql_company = "SELECT `company`.`cid`,`company`.`cname` FROM `company` WHERE `company`.`cid` = '$code'";
                        $return_company = $conn->query($sql_company);
                        $row_company = $return_company->fetch();
                        
                        $sql_one = "SELECT DISTINCT date FROM ClosingPrice ORDER BY date DESC LIMIT 1 OFFSET 0";
                        $ret_one = $conn->query($sql_one);
                        $one = $ret_one->fetch();
                        
                        if ($one && $row_company) {
                            echo '<p>company ID: ',$code." ".$row_company['cname'],'</p>';
                            $sql1 = "SELECT * FROM `ClosingPrice` WHERE `id` = '$code' and `date` = '" . sprintf("%s", $one['date']) . "'";
                            $ret1 = $conn->query($sql1);
                            $p1 = $ret1->fetch();
                        } 
                        else {echo "No date found";}

                        $sql_two = "SELECT DISTINCT date FROM ClosingPrice ORDER BY date DESC LIMIT 1 OFFSET 1";
                        $ret_two = $conn->query($sql_two);
                        $two = $ret_two->fetch();
                        if ($two) {
                            $sql2 = "SELECT * FROM `ClosingPrice` WHERE `id` = '$code' and `date` = '" . sprintf("%s", $two['date']) . "'";
                            $ret2 = $conn->query($sql2);
                            $p2 = $ret2->fetch();
                        } 
                        else {echo "No date found";}

                        $sql_three = "SELECT DISTINCT date FROM ClosingPrice ORDER BY date DESC LIMIT 1 OFFSET 2";
                        $ret_three = $conn->query($sql_three);
                        $three = $ret_three->fetch();
                        if ($three) {
                            $sql3 = "SELECT * FROM `ClosingPrice` WHERE `id` = '$code' and `date` = '" . sprintf("%s", $three['date']) . "'";
                            $ret3 = $conn->query($sql3);
                            $p3 = $ret3->fetch();
                        } 
                        else {echo "No date found";}

                        $sql_four = "SELECT DISTINCT date FROM ClosingPrice ORDER BY date DESC LIMIT 1 OFFSET 3";
                        $ret_four = $conn->query($sql_four);
                        $four = $ret_four->fetch();
                        if ($four) {
                            $sql4 = "SELECT * FROM `ClosingPrice` WHERE `id` = '$code' and `date` = '" . sprintf("%s", $four['date']) . "'";
                            $ret4 = $conn->query($sql4);
                            $p4 = $ret4->fetch();
                        } 
                        else {echo "No date found";}

                        $sql_five = "SELECT DISTINCT date FROM ClosingPrice ORDER BY date DESC LIMIT 1 OFFSET 4";
                        $ret_five = $conn->query($sql_five);
                        $five = $ret_five->fetch();
                        if ($five) {
                            $sql5 = "SELECT * FROM `ClosingPrice` WHERE `id` = '$code' and `date` = '" . sprintf("%s", $five['date']) . "'";
                            $ret5 = $conn->query($sql5);
                            $p5 = $ret5->fetch();
                        } 
                        else {echo "No date found";}?>
                        <div class="table">
                        <ul class="responsive-table">
                          <li class="table-header">
                            <div class="col col-0">Id</div>
                            <div class="col col-1">Date</div>
                            <div class="col col-2">ClosingPrice收盤價</div>
                            <div class="col col-3">Volumn成交量</div>
                          </li>
                          <li class="table-row">
                            <div class="col col-0"><?php echo $p1[0];?></div>
                            <div class="col col-1" data-label="date"><?php echo $p1[1];?></div>
                            <div class="col col-2" data-label="price"><?php echo $p1[2];?></div>
                            <div class="col col-3" data-label="volumn"><?php echo number_format($p1[3]);?></div>
                          </li>
                          <li class="table-row">
                            <div class="col col-0"><?php echo $p2[0];?></div>
                            <div class="col col-1" data-label="date"><?php echo $p2[1];?></div>
                            <div class="col col-2" data-label="price"><?php echo $p2[2];?></div>
                            <div class="col col-3" data-label="volumn"><?php echo number_format($p2[3]);?></div>
                          </li>                          
                          <li class="table-row">
                            <div class="col col-0"><?php echo $p3[0];?></div>
                            <div class="col col-1" data-label="date"><?php echo $p3[1];?></div>
                            <div class="col col-2" data-label="price"><?php echo $p3[2];?></div>
                            <div class="col col-3" data-label="volumn"><?php echo number_format($p3[3]);?></div>
                          </li>                          
                          <li class="table-row">
                            <div class="col col-0"><?php echo $p4[0];?></div>
                            <div class="col col-1" data-label="date"><?php echo $p4[1];?></div>
                            <div class="col col-2" data-label="price"><?php echo $p4[2];?></div>
                            <div class="col col-3" data-label="volumn"><?php echo number_format($p4[3]);?></div>
                          </li>                          
                          <li class="table-row">
                            <div class="col col-0"><?php echo $p5[0];?></div>
                            <div class="col col-1" data-label="date"><?php echo $p5[1];?></div>
                            <div class="col col-2" data-label="price"><?php echo $p5[2];?></div>
                            <div class="col col-3" data-label="volumn"><?php echo number_format($p5[3]);?></div>
                          </li>
                        </ul>
                      </div>



<?php
                        $data = array(
                            array($p5[1], $p5[2], $p5[3]), // date, closing price, volume
                            array($p4[1], $p4[2], $p4[3]),
                            array($p3[1], $p3[2], $p3[3]),
                            array($p2[1], $p2[2], $p2[3]),
                            array($p1[1], $p1[2], $p1[3])
                        );
                        
                        // change to json
                        $linePoints = array();
                        $columnPoints = array();
                        date_default_timezone_set("Asia/Taipei");
                        foreach ($data as $item) {
                            $linePoint = array();
                            $linePoint['x'] = strtotime($item[0]) * 1000 + 8*3600000; //date
                            $linePoint['y'] = $item[1]; //closing price
                            array_push($linePoints, $linePoint);
                        
                            $columnPoint = array();
                            $columnPoint['x'] = strtotime($item[0]) * 1000 + 8*3600000; //date
                            $columnPoint['y'] = $item[2]; //volume
                            array_push($columnPoints, $columnPoint);
                        }
                        
                        $lineJsonData = json_encode($linePoints);
                        $columnJsonData = json_encode($columnPoints);
                        
                        echo <<<CHARTS
                        <div id="container"></div>
                        <script src="https://code.highcharts.com/highcharts.js"></script>
                        <script>
                        Highcharts.chart("container", {
                            chart: {
                                height: 400,
                                width: 800,
                                style: {
                                    fontFamily: 'sans'
                                }
                            },
                            title: {
                                text: "過去五天交易日之收盤價與成交量",
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
                            yAxis: [
                                {
                                labels: {
                                        style:{
                                        fontSize:"14px"
                                        }
                                    },
                                    title: {
                                        text: "Closing Price",
                                        style:{
                                        fontSize:"16px"
                                        }
                                    }
                                },
                                {
                                   labels: {
                                        style:{
                                        fontSize:"14px"
                                        }
                                    },
                                    title: {
                                        text: "Volume",
                                        style:{
                                        fontSize:"16px"
                                        }
                                    },
                                    opposite: true
                                }
                            ],
                            credits: {
                                enabled: false
                            },
                            tooltip:{
                                        style:{
                                        fontSize:"12px"
                                        }
                                },
                            series: [
                                {
                                    type: "line",
                                    data: $lineJsonData,
                                    name: "Closing Price",
                                    color: '#f27360',
                                    yAxis: 0
                                },
                                {
                                    type: "column",
                                    data: $columnJsonData,
                                    name: "Volume",
                                    color: '#bcbdc1',
                                    yAxis: 1
                                }
                            ],
                            plotOptions: {
                                line: {
                                  zIndex: 2
                                },
                                column: {
                                  zIndex: 1
                                }
                              }
                        });
                        </script>
                        CHARTS;
                        
                    }else{
                        echo '<p>';echo "You don't choose the company id:(", '</p>';
                    }        
                }
                catch(PDOException $e){
                    echo $sql . "<br>" . $e->getMessage();
                }
                $conn = null;
                }
            ?>
            </div>
            <div class="wrapper"></div>
            <footer class="footer"><p class="ftext">Copyright © 2023 FIBDA, All Rights Reserved.</p></footer>
    </body>
</html>

