<?php
$userlevel = $_COOKIE['userlevel'];
$user = 'username';
$pass = 'password';
$db = 'database';
$con = mysqli_connect("localhost", $user, $pass, $db);
$sql = "SELECT  *  FROM company ";
$company = mysqli_query($con, $sql);
?>

<html>
<?php
include("../header/header.php");
?>

<head>
    <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>
    <title>FOCUS</title>

    <style><?php include '../CSS/focus.css';?></style>    
    
    <!--警示彈窗-->
    <meta charset="utf-8">
      <link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
      <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
      <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
</head>

<body>
    <?php    
        if ($userlevel > 1){
    ?>
    <div class="center" style="margin-top:3%;">
        <p class="ti">新增關注公司名單</p>
    </div>

    <form id="form" name="form" method="post" class="center" action="focus2.php">
        <input list="company" name="companyid" class="form chinese" placeholder="公司代碼/公司名稱">
        <datalist id="company">
            <?php
            while ($cat = mysqli_fetch_array($company, MYSQLI_ASSOC)) :;
            ?>
                <option>
                    <?php echo $cat['cid'], " ", $cat['cname']; ?>
                </option>
            <?php
            endwhile;
            ?>
        </datalist><br>

        <input class="button" type="submit" name="Submit" value="add" /><br>
        <div class="center">
            <?php
            $teamid = $_COOKIE['teamid'];
            $conn = new PDO("mysql:host=localhost;dbname=database", "username", "password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM `focus` WHERE `focus`.`teamid` = '$teamid' AND `focus`.`f` = '1'";
            $return = $conn->query($sql);
            $row = $return->fetchAll(); //$row[] = 該團隊當前關注的公司名單
            if (isset($_POST["Submit"])) {
                $cc = $_POST["companyid"];
                if ($cc != null) {
                    $ccs = explode(" ", $cc);
                    $result1 = $ccs[0];
                    $sql = "SELECT * FROM `company` WHERE `cid` = '$result1' ;";
                    $return = $conn->query($sql);
                    $row2 = $return->fetch(); //查詢上市公司
                    if ($row2 != null) {
                        //判斷是否重複
                        $sql = "SELECT * FROM `focus` WHERE `focus`.`teamid` = '$teamid' AND `focus`.`companyid` = '$result1' ;";
                        $return = $conn->query($sql);
                        $row2 = $return->fetch();
                        if ($row2 == null) { //未關注
                            try { //新增
                                $sql = "INSERT INTO `focus` (`teamid`, `companyid`, `f`, `buy`) VALUES ('$teamid', '$result1', '1', '0');";
                                $conn->exec($sql);
                                echo "<p class='chinese'>關注成功！！</p>";
                            } catch (PDOException $e) {
                                echo $sql . "<br>" . $e->getMessage();
                            }
                        } else if ($row2['f'] == 2) { //曾取消
                            $sql = "UPDATE `focus` SET `f` = '1' WHERE `focus`.`teamid` = '$teamid' AND `focus`.`companyid` = '$result1'";
                            $conn->exec($sql);
                            echo "<p class='chinese'>關注成功！！</p>";
                        } else if ($row2['f'] == 1) {
                            echo "<p class='chinese'>重複關注</p>";
                        }
                    } else {
                        echo "<p class='chinese'>查無此上市公司</p>";
                    }
                } else {
                    echo "<p class='chinese'>請輸入公司代號...</p>";
                }

                //若有新關注則新增reminder資料表
                //ETF ETN '存託憑證'
                if(isset($_POST['Submit']) && $cc != null){
                      //如果reminder資料表已經有這個teamid companyid A那就不加新的, *並把舊的資料UPDATE成預設值*
                      $teamid = $_COOKIE['teamid'];
                      $sqldata = "SELECT * FROM `reminder` WHERE `teamid`='{$teamid}'"; #reminder table
                      $result10 = mysqli_query ($con, $sqldata);
                      
                      $sqlclass = "SELECT * FROM `company` WHERE `cid`='{$result1}'";  
                      $result13 = mysqli_query ($con, $sqlclass);  
                      $companytable = mysqli_fetch_array($result13,MYSQLI_ASSOC);  #company table
                      $cclass = $companytable['cclass'];
                      

                      $flag = 0;
                      while($data = mysqli_fetch_array($result10,MYSQLI_ASSOC)){
                        if($data['companyid'] == $result1){  //$result1 是公司id
                            $flag = 1;
                        }
                      }
                      if($cclass != "ETF" && $cclass != "ETN" && $cclass != "存託憑證"){
                        $sqlpbr = "SELECT * FROM `pbr` WHERE `id`='{$result1}' ORDER BY `date` DESC LIMIT 1";  
                        $result11 = mysqli_query ($con, $sqlpbr);
                        $pbr = mysqli_fetch_array($result11,MYSQLI_ASSOC);
                        $pbrval = $pbr['avg'];  #pbr預設值
                        $sqlper = "SELECT * FROM `per` WHERE `id`='{$result1}' ORDER BY `date` DESC LIMIT 1";
                        $result12 = mysqli_query ($con, $sqlper);
                        $per = mysqli_fetch_array($result12,MYSQLI_ASSOC);
                        $perval = $per['avg'];  #per預設值

                        if($flag == 0){  //若沒這間公司, 新增欄位
		                    try { //新增
		                        $sqlre = "INSERT INTO `reminder`(`teamid`,`companyid`,`A`, `value`) VALUE('{$teamid}', '{$result1}', '1', '{$pbrval}')"; //value設置成預設
		                        $resultre = $con->query($sqlre);
		                        $sqlre2 = "INSERT INTO `reminder`(`teamid`,`companyid`,`A`, `value`) VALUE('{$teamid}', '{$result1}', '2', '{$perval}')";
		                        $resultre2 = $con->query($sqlre2);
		                    }catch (Exception $e) {
                                echo $sql . "<br>" . $e->getMessage();
                            }
                          }
                          else if($flag == 1){  //若已有這間公司, 重設為預設值
		                      try { //新增
		                        $sqlre3 = "UPDATE `reminder` SET `value`= '{$pbrval}' , `symbol` = '0' , `flag` = '0',  `flag2` = '0', `checkdate`=NULL, `conformdate`=NULL, `modifydate`=NULL WHERE `teamid`='{$teamid}' and `companyid` = '{$result1}' and `A` = '1'";
		                        $resultre3 = mysqli_query ($con, $sqlre3);
		                        $sqlre4 = "UPDATE `reminder` SET `value`= '{$perval}' , `symbol` = '0', `flag` = '0', `flag2` = '0' , `checkdate`=NULL, `conformdate`=NULL, `modifydate`=NULL WHERE `teamid`='{$teamid}' and `companyid` = '{$result1}' and `A` = '2'";
		                        $resultre4 = mysqli_query ($con, $sqlre4);
							  } catch (Exception $e) {
								echo $sql . "<br>" . $e->getMessage();
							  }
                          }
                        }  
                } 
            }
            $conn = null;
        }
            ?>
    </form>
    <div class="center" style="margin-top:3%;">
        <p class="center ti">當前關注公司一覽：&nbsp&nbsp&nbsp
        <input class="display" type="button" value="只顯示關注中" onclick="javascript:window.location.href='focus.php'">
        </p> 
    </div>
    <div>
        <?php
            $teamid = $_COOKIE['teamid'];
            $con = new PDO("mysql:host=localhost;dbname=database", "username", "password");
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //取消名單內容
            if (isset($_POST['delete_car_btn'])) {
                $id_c = $_POST['delete_company_id'];
                $queryC = "UPDATE `focus` SET `f` = '2' WHERE `focus`.`teamid` = '$teamid' AND `focus`.`companyid` = '$id_c' ";
                $return = $con->query($queryC);
                $query_runCC = $return->fetchAll();
            }
        ?>
        <div class="table">
            <ul class="responsive-table center">
                <li class="table-header">
                <div class="col col-0">Id</div>
                <div class="col col-1">Name</div>
                <div class="col col-2">關注狀態</div>
                <div class="col col-3">是否持有</div>
                <div class="col col-4">取消關注</div>
                </li>
                <?php
                    $teamid = $_COOKIE['teamid'];
                    $con = new PDO("mysql:host=localhost;dbname=database", "username", "password");
                    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $queryC = "SELECT * FROM `focus` WHERE `teamid` = '$teamid' ";
                    $return = $con->query($queryC);
                    $query_runC = $return->fetchAll();

                    if ($query_runC != null) {
                        foreach ($query_runC as $rowC) {
                            if ($rowC['f'] < 3) {
                ?>
                <li class="table-row">
                <div class="col col-0"><?php echo $rowC['companyid']; ?></div>
                <div class="col col-1"><?php
                        $queryC = "SELECT * FROM `company` WHERE `cid` = '$rowC[1]' ";
                        $return = $con->query($queryC);
                        $query_runC = $return->fetch();
                        echo $query_runC[1];
                        ?></div>
                <div class="col col-2"><?php
                        if ($rowC['f'] == 1) {
                            echo "關注中";
                        } else if ($rowC['f'] == 2) {
                            echo "已取消";
                        }
                        ?></div>
                <div class="col col-3"><?php
                        if ($rowC['buy'] == 0) {
                            echo "無持有";
                        } else if ($rowC['buy'] == 1) {
                            echo "持有中";
                        } else if ($rowC['buy'] == 2) {
                            echo "已售完";
                        }
                        ?></div>
                <div class="col col-4">   <?php
                    if($rowC['buy']==0  || $rowC['buy']==2){
                        if($userlevel  > 1 && $rowC['f'] == 1) {
                            ?>
                        <form method="post" action="" > 
                            <!-- 下面有個 input type="hidden" 是讓待會的PHP 知道要刪除哪一筆資料 -->
                            <input type="hidden" name="delete_company_id" value="<?php echo $rowC['companyid']; ?>">
                            <button  name="delete_car_btn" class="btnn" >取消</button>
                        </form>
                    <?php
                        }
                    }else if($rowC['buy']==1 ){
                        echo "<br><br>";
                    }
                    ?></div>
                </li>     
                <?php
                        }
                    }
                }
                ?>                   
                        </ul>
                      </div>

    </div>
    </div>
    <div class="wrapper"></div>
    <footer class="footer center"><p class="ftext">Copyright © 2023 FIBDA, All Rights Reserved.</p></footer>    
    
    </boby>

</html>
