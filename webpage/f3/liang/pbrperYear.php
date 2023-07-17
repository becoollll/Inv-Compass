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
        <style><?php include '../CSS/pbrperYear.css';?></style>
        <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>      
        <title>PBR & PER (Yearly)</title>
    </head>
    <body>    
        <div class="center" style="margin-top:5%;">
            <p>PBR & PER (Yearly)</p>
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
                        $sql = "SELECT `id`,`year`,`type`,`value` FROM `AvgPERPBR` WHERE `id` = '$code' ORDER BY year DESC LIMIT 1 ";
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
                            echo '<p>沒有PER, PBR的資料</p>';
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
                                        <div class="col col-2">AVG_PBR</div>
                                        <div class="col col-3">AVG_PER</div>
                                    </li>
                            <?php
                            $year_count = 0;
                            $points1 = array();
                            $points2 = array();

                            $sql_avg = "SELECT `id`,`year`,`type`,`value` FROM `AvgPERPBR` WHERE `id` = $code ";
                            
                            $ret_data = $conn->query($sql_avg);
                            $data = $ret_data->fetchAll();
                            ?>
                            <li class="table-row">
                                <div class="col col-0"><?php echo $data[0][0]." ".$row_company['cname'];?></div>
                                <div class="col col-1"><?php echo $data[7][1];?></div>
                                <div class="col col-2"><?php echo $data[7][3];?></div>
                                <div class="col col-3"><?php echo $data[6][3];?></div>
                            </li>
                            <li class="table-row">
                                <div class="col col-0"><?php echo $data[0][0]." ".$row_company['cname'];?></div>
                                <div class="col col-1"><?php echo $data[5][1];?></div>
                                <div class="col col-2"><?php echo $data[5][3];?></div>
                                <div class="col col-3"><?php echo $data[4][3];?></div>
                            </li>
                            <li class="table-row">
                                <div class="col col-0"><?php echo $data[0][0]." ".$row_company['cname'];?></div>
                                <div class="col col-1"><?php echo $data[3][1];?></div>
                                <div class="col col-2"><?php echo $data[3][3];?></div>
                                <div class="col col-3"><?php echo $data[2][3];?></div>
                            </li>
                            <li class="table-row">
                                <div class="col col-0"><?php echo $data[0][0]." ".$row_company['cname'];?></div>
                                <div class="col col-1"><?php echo $data[1][1];?></div>
                                <div class="col col-2"><?php echo $data[1][3];?></div>
                                <div class="col col-3"><?php echo $data[0][3];?></div>
                            </li>

                            <?php
                            
                            /*$ret_data_2 = $conn->query($sql_avg);
                            
                            foreach ($ret_data_2->fetchAll() as $data)
                            {
                                if ($data['type'] == 0 && $data['value'] !=null)
                                {
                                	$point1 = array();
                                	$point1['x'] = (int)($data['year']); //year
                                	$point1['y'] = (float)($data['value']); //roa
                                	array_push($points1, $point1);
                                
                                }
                                else if ($data['type'] == 1 && $data['value']!=null)
                                {
                                	$point2 = array();
                                	$point2['x'] = (int)($data['year']); //year
                                	$point2['y'] = (float)($data['value']); //roe
                                	array_push($points2, $point2);
                                }
                            }*/
                            
                            $points1 = array(
                                array($data[1][1], $data[1][3]),
                                array($data[3][1], $data[3][3]),
                                array($data[5][1], $data[5][3]),
                                array($data[7][1], $data[7][3])
                            );
			    
                            $points2 = array(
                                array($data[0][1], $data[0][3]),
                                array($data[2][1], $data[2][3]),
                                array($data[4][1], $data[4][3]),
                                array($data[6][1], $data[6][3])
                            );
			    
                            $jsonPBR = json_encode($points1);
                            $jsonPER = json_encode($points2);
                            ?>
                                </ul>
                            </div>
			    <div class="pbrper"><b>※未顯示數值代表為負數。</b></div><br><br>
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
                                    text: "過去4年的PBR, PER",
                                        style:{
                                        fontSize:"20px"
                                        }
                                },
                                xAxis: {
                                    categories: ["2019", "2020", "2021", "2022"],
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
                                tooltip:{                                 
                                        style:{
                                        fontSize:"14px"
                                        }
                                },
                                credits: {
                                    enabled: false
                                },
                                series: [{
                                    data: $jsonPBR,
                                    name: "PBR",
                                    color: '#f27360'
                                },{
                                    data: $jsonPER,
                                    name: "PER",
                                    color: '#9aaaba'
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
                    echo'<div class="col">PBR  = 年度平均股價 / 每股淨值(bps) </div><br>';
                    echo'<div class="col">PER  = 年度平均股價 / 每股盈餘(eps) </div><br>';
                }
            ?>
            </div>
            <div class="wrapper"></div>
            <footer class="footer"><p class="ftext">Copyright © 2023 FIBDA, All Rights Reserved.</p></footer>
    </body>
</html>

