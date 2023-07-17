<?php
	$user = 'username';
	$pass = 'password';
	$db = 'database';
	$con = mysqli_connect("localhost", $user, $pass, $db);

    $teamid = $_COOKIE['teamid'];
    $userlevel = $_COOKIE['userlevel']; 

	if(isset($_POST['Submit2'])){
		$companyID = $_POST['company'];
		$buyornot = $_POST['buyornot'];
	$sqlclass = "SELECT * FROM `company` WHERE `cid`='{$companyID}'";  
	    $result13 = mysqli_query ($con, $sqlclass);  
	    $companytable = mysqli_fetch_array($result13,MYSQLI_ASSOC);  #company table
	    $cclass = $companytable['cclass'];

	    if($cclass != "ETF" && $cclass != "ETN" && $cclass != "存託憑證"){	
		    $PBR_symbol = $_POST['PBR_symbol'];  //將各值存進變數中
		    $PBR_value = $_POST['PBR_value'];
		    $PER_symbol = $_POST['PER_symbol'];
		    $PER_value = $_POST['PER_value'];

		    if($PBR_symbol != NULL){
		        $sql1 = "UPDATE `reminder` SET `symbol` = '{$PBR_symbol}' WHERE `teamid` = '{$teamid}' and `A` = 1 and`companyid` = '{$companyID}'"; //儲存PBR symbol
		        $result1 = mysqli_query($con,$sql1);  //存進資料庫 PBR symbol
		        $sqlflag1 = "UPDATE `reminder` SET `flag`=1 WHERE `teamid` = '{$teamid}' and `A` = 1 and`companyid` = '{$companyID}'";
		        $resultflag1 = mysqli_query($con,$sqlflag1);
		    }
		    if($PBR_value != NULL){
		        $sql11 = "UPDATE `reminder` SET `value` = '{$PBR_value}' WHERE `teamid` = '{$teamid}' and `A` = 1 and`companyid` = '{$companyID}'"; //儲存PBR value
		        $result11 = mysqli_query($con,$sql11);  //存進資料庫 PBR value
		        $sqlflag2 = "UPDATE `reminder` SET `flag`=1 WHERE `teamid` = '{$teamid}' and `A` = 1 and`companyid` = '{$companyID}'";
		        $resultflag2 = mysqli_query($con,$sqlflag2);
		    }
		    if($PER_symbol != NULL){
		        $sql2 = "UPDATE `reminder` SET `symbol` = '{$PER_symbol}'WHERE `teamid` = '{$teamid}' and `A` = 2 and`companyid` = '{$companyID}'";  //儲存PER symbol
		        $result2 = mysqli_query($con,$sql2);  //存進資料庫 PER symbol
		        $sqlflag3 = "UPDATE `reminder` SET `flag`=1 WHERE `teamid` = '{$teamid}' and `A` = 2 and`companyid` = '{$companyID}'";
		        $resultflag3 = mysqli_query($con,$sqlflag3);
		    }
		    if($PER_value != NULL){
		        $sql22 = "UPDATE `reminder` SET `value` = '{$PER_value}' WHERE `teamid` = '{$teamid}' and `A` = 2 and`companyid` = '{$companyID}'";  //儲存PER value
		        $result22 = mysqli_query($con,$sql22); //存進資料庫 PER value
		        $sqlflag4 = "UPDATE `reminder` SET `flag`=1 WHERE `teamid` = '{$teamid}' and `A` = 2 and`companyid` = '{$companyID}'";
		        $resultflag4 = mysqli_query($con,$sqlflag4);
		    }
		}
    

		if($buyornot == 1){
			$Sharp_symbol = $_POST['Sharp_symbol'];
            $Sharp_value = $_POST['Sharp_value'];
            if($Sharp_symbol != NULL){
                $sql3 = "UPDATE `reminder` SET `symbol` = '{$Sharp_symbol}'WHERE `teamid` = '{$teamid}' and `A` = 3 and`companyid` = '{$companyID}'";  //儲存Sharp
                $result3 = mysqli_query($con,$sql3);
                $sqlflag5 = "UPDATE `reminder` SET `flag`=1 WHERE `teamid` = '{$teamid}' and `A` = 3 and`companyid` = '{$companyID}'";;
                $resultflag5 = mysqli_query($con,$sqlflag5);
            }
            if($Sharp_value != NULL){
                $sql33 = "UPDATE `reminder` SET `value` = '{$Sharp_value}' WHERE `teamid` = '{$teamid}' and `A` = 3 and`companyid` = '{$companyID}'";  //儲存Sharp
                $result33 = mysqli_query($con,$sql33);
                $sqlflag55 = "UPDATE `reminder` SET `flag`=1 WHERE `teamid` = '{$teamid}' and `A` = 3 and`companyid` = '{$companyID}'";;
                $resultflag55 = mysqli_query($con,$sqlflag55);
            }
			
		}
		
		//------------ checkbox -------------
        $check_arr = array(); 
        $check_arr = $_POST['check']; //checkbox
        $count = count($check_arr);
        if($count < 5){
            if($count == 1){
                array_shift($check_arr);
                array_push($check_arr,0,0);
            }
            else if($count == 2){
                array_shift($check_arr);
                array_push($check_arr,0,0);
            }
            else if($count == 3){
                array_shift($check_arr);
                array_push($check_arr,0);
            }
            else if($count == 4){
                array_shift($check_arr);
            }
        }


        //有1代表PBR要提醒，如果沒有傳1進來代表不用提醒
        if($check_arr[0] == NULL){  // 000  3個都不用提示
            $sqlc0 = "UPDATE `reminder` SET `flag3` = '0' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}'";  
            $resultc0 = mysqli_query($con,$sqlc0);
        }
        else if($check_arr[0] == 1){  //  100 120 123 130  
            $sqlc01 = "UPDATE `reminder` SET `flag3` = '1' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}' and `A`='1'";  //將A=1 設成要提示,flag3=1
            $resultc01 = mysqli_query($con,$sqlc01);

            if($check_arr[1] == 0){  //  100  只有 A=1 要提示,flag3=1  
                $sqlc1 = "UPDATE `reminder` SET `flag3` = '0' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}' and `A`='2'";  //將A=2設成不要提示, flag3=0
                $resultc1 = mysqli_query($con,$sqlc1);

                $sqldata = "SELECT * FROM `reminder` WHERE `teamid`='{$teamid}' and`companyid` = '{$companyID}'"; #reminder table
                $resultdata = mysqli_query ($con, $sqldata);
                while($data = mysqli_fetch_array($resultdata,MYSQLI_ASSOC)){  //判斷這個公司有沒有A=3 如果有的話，再把A=3的flag3設成0
                    if($data['companyid'] == $companyID && $data['A'] == 3){  
                        $sqlc11 = "UPDATE `reminder` SET `flag3` = '0' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}' and `A`='3'";  //將A=3 設成不要提示, flag3=0
                        $resultc11 = mysqli_query($con,$sqlc11);
                    }
                }  //endwhile
            }
            else if($check_arr[1] == 2){  //  120 123   只有 A=1和A=2 要提示,flag3=1  
                $sqlc2 = "UPDATE `reminder` SET `flag3` = '1' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}' and `A`='2'";  //將A=2設成要提示 flag3=1 
                $resultc2 = mysqli_query($con,$sqlc2);

                if($check_arr[2] == 0){  //120
                    $sqldata2 = "SELECT * FROM `reminder` WHERE `teamid`='{$teamid}' and`companyid` = '{$companyID}'"; #reminder table
                    $resultdata2 = mysqli_query ($con, $sqldata2);
                    while($data2 = mysqli_fetch_array($resultdata2,MYSQLI_ASSOC)){  //判斷這個公司有沒有A=3 如果有的話，再把A=3的flag3設成0
                        if($data2['companyid'] == $companyID && $data2['A'] == 3){  
                            $sqlc21 = "UPDATE `reminder` SET `flag3` = '0' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}' and `A`='3'";  
                            $resultc21 = mysqli_query($con,$sqlc21);
                        }
                    }  //endwhile
                }
                else if($check_arr[2] == 3){  //123
                    $sqlc22 = "UPDATE `reminder` SET `flag3` = '1' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}' and (`A`='1'or`A`='3')";  //將A=1 A=2 A=3 設成要提示, flag3=1
                    $resultc22 = mysqli_query($con,$sqlc22);
                }
            }
            else if($check_arr[1] == 3){  //130
                $sqlc3 = "UPDATE `reminder` SET `flag3` = '1' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}' and `A`='3'";  
                $resultc3 = mysqli_query($con,$sqlc3);
                $sqlc31 = "UPDATE `reminder` SET `flag3` = '0' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}' and `A`='2'";  //將A=2設成不要提示
                $resultc31 = mysqli_query($con,$sqlc31);
            }
        }
        else if($check_arr[0] == 2){  //  200 230
            $sqlc4 = "UPDATE `reminder` SET `flag3` = '1' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}' and `A`='1'";  //將A=2設成要提示 flag3=1 
            $resultc4 = mysqli_query($con,$sqlc4);

            if($check_arr[1] == 0){  //200
                $sqlc41 = "UPDATE `reminder` SET `flag3` = '0' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}' and `A`='1'";  //將A=1設成不要提示 flag3=0
                $resultc41 = mysqli_query($con,$sqlc41);

                $sqldata3 = "SELECT * FROM `reminder` WHERE `teamid`='{$teamid}' and`companyid` = '{$companyID}'"; #reminder table
                $resultdata3 = mysqli_query ($con, $sqldata3);
                while($data3 = mysqli_fetch_array($resultdata3,MYSQLI_ASSOC)){  //判斷這個公司有沒有A=3 如果有的話，再把A=3的flag3設成0
                    if($data3['companyid'] == $companyID && $data3['A'] == 3){  
                        $sqlc411 = "UPDATE `reminder` SET `flag3` = '0' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}' and `A`='3'";  //將A=3 設成不要提示 flag3=0
                        $resultc411 = mysqli_query($con,$sqlc411);
                    }
                }  //endwhile

            }
            else if($check_arr[1] == 3){  //230
                $sqlc51 = "UPDATE `reminder` SET `flag3` = '1' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}' and `A`='3'";   //將A=3設成要提示 flag3=1 
                $resultc51 = mysqli_query($con,$sqlc51);
                $sqlc52 = "UPDATE `reminder` SET `flag3` = '0' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}' and `A`='1'";    //將A=1設成不要提示 flag3=0
                $resultc52 = mysqli_query($con,$sqlc52);
            }

        }
        else if($check_arr[0] == 3){  //300
            $sqlc6 = "UPDATE `reminder` SET `flag3` = '1' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}' and `A`='3'";  //將A=3設成要提示 flag3=1 
            $resultc6 = mysqli_query($con,$sqlc6);
            $sqlc61 = "UPDATE `reminder` SET `flag3` = '0' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}' and (`A`='1'or `A`='2')";  //將A=1 A=2 設成不要提示 flag3=0
            $resultc61 = mysqli_query($con,$sqlc61);
        }
        //----------------------------------------------------
        
        
        //把修改資料的日期存進資料庫
        $date = date('Y-m-d');
        $sqldate = "UPDATE `reminder` SET `modifydate` = '{$date}' WHERE `teamid` = '{$teamid}' and`companyid` = '{$companyID}'";  //儲存PER value
        $resultdate = mysqli_query($con,$sqldate);
	}
    else if(isset($_POST['Submit3'])){
        //取消按鍵
    }

    //取出該使用者的公司
	$sql = "SELECT  *  FROM focus,company WHERE  teamid = '{$teamid}' and f = 1 and `focus`.`companyid` = `company`.`cid`";
	$company = mysqli_query ($con, $sql);


?>
<html>
		<?php
		include("../header/header.php");
		?>
<head>
    <style><?php include '../CSS/alert.css';?></style>  
    <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>      
    <title>Reminder Setting</title>
	<meta charset="utf-8">
    
</head>


<body>
<div class="center" style="margin-top:5%;">
            <p>Reminder Setting</p>
        </div>
 <!--+++++++++++下拉式選單 選公司++++++++++++++-->  
	<form id="form" name="form" method="post" class="in center" style="margin-top:1%;">
		<select name='Selectcom' class="form"> 
			<option value="" selected disabled hidden>--- choose company ---</option>
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
			
 <!--++++++++++++++++選公司按鈕+++++++++++++++++++++++++-->
	<br>
	<?php
                if ($userlevel > 1) {
            ?>
            <input class="button" type="submit" name="Submit" value="Select" >
	<div class="in">
	</form>
	<?php
    //++++++++++++++++++++++表單++++++++++++++++++++++++++
    $whichbutt = 0; #判斷出現哪些按鈕
	if(isset($_POST['Submit']) && isset($_POST['Selectcom'])){
		$companyID = $_POST['Selectcom'];  //$--companyID--
		if(!empty($companyID)){
		?>
		<div >
		<p class="ti" style="font-size:25px;"> <?php 
		$sqlcpnname = "SELECT * FROM `company` WHERE `cid` = '$companyID'";
		$result = mysqli_query($con, $sqlcpnname);
		$cpntable = mysqli_fetch_array($result, MYSQLI_ASSOC);
		echo $companyID, " ", $cpntable['cname'];
		?></p>
		<?php
			$sql01 = "SELECT * FROM focus WHERE teamid = '{$teamid}'  and companyid = '{$companyID}'";
			$buy = mysqli_query ($con, $sql01);
			$buyornot = mysqli_fetch_assoc($buy);
			$buyornot = $buyornot['buy'];  //buyornot的值 0或1

            $sqlclass = "SELECT * FROM `company` WHERE `cid`='{$companyID}'";  
            $result13 = mysqli_query ($con, $sqlclass);  
            $companytable = mysqli_fetch_array($result13,MYSQLI_ASSOC);  #company table
            $cclass = $companytable['cclass'];

            if($cclass != "ETF" && $cclass != "ETN" && $cclass != "存託憑證"){
                $whichbutt = 1;
                //從資料庫取出預設值
                //PBR的value(1)
                $sql01 = "SELECT * FROM reminder WHERE teamid = '{$teamid}'  and A = 1 and companyid = '{$companyID}'";
                $PBRvaldbb = mysqli_query ($con, $sql01);
                $PBRvaldb = mysqli_fetch_assoc($PBRvaldbb);
                $PBRvaldb = $PBRvaldb['value'];  //PBR value的預設值
                $PBRopdbb = mysqli_query ($con, $sql01);
                $PBRop = mysqli_fetch_assoc($PBRopdbb);
                $PBRop = $PBRop['symbol']; //PBR大小於的預設值
                $PBRflag33 = mysqli_query ($con, $sql01);
                $PBRflag3 = mysqli_fetch_assoc($PBRflag33);
                $PBRflag3 = $PBRflag3['flag3']; //PBR lfag3的預設值
                //PER的value(2)
                $sql02 = "SELECT * FROM reminder WHERE teamid = '{$teamid}'  and A = 2 and companyid = '{$companyID}'";
                $PERvaldbb = mysqli_query ($con, $sql02);
                $PERvaldb = mysqli_fetch_assoc($PERvaldbb);
                $PERvaldb = $PERvaldb['value'];  //PER value的預設值
                $PERopdbb = mysqli_query ($con, $sql02);
                $PERop = mysqli_fetch_assoc($PERopdbb);
                $PERop = $PERop['symbol'];  //PER大小於的預設值
                $PERflag33 = mysqli_query ($con, $sql02);
                $PERflag3 = mysqli_fetch_assoc($PERflag33);
                $PERflag3 = $PERflag3['flag3']; //PBR lfag3的預設值

                ?>
                <?php
                //++++++++++++++++++++ 隱藏的checkbox  +++++++++++++++++
                ?>
                <input type="hidden" name="check[]" value = "0" >
                <p class="ti">
                    <?php
                //++++++++++++++++++++++++++++++++++++
                //-------------- PBR checkbox  ---------------
                
                    if($PBRflag3 == 0){
                        ?>
                        <input type="checkbox" name="check[]" value="1" class="ckbox"><label></label>      <!--PBR的Checkbox  flag3=1 要提示-->
                        <?php
                    }
                    elseif($PBRflag3 == 1){
                        ?>
                        <input type="checkbox" name="check[]" value="1" class="ckbox" checked><label></label>      <!--PBR的Checkbox  flag3=0 不要提示-->
                        <?php
                    }
                
                ?>
				&nbsp;&nbsp;&nbsp;PBR &emsp;<select name="PBR_symbol" class="form_10">
                        <?php
                        if($PBRop == 0){
                        ?>
                            <option value="0" selected>&gt;</option>
                            <option value="1">&lt;</option>
                            <option value="2">=</option>
                            <option value="3">&ge;</option>
                            <option value="4">&le;</option>

                        <?php
                        }
                        elseif($PBRop == 1){
                        ?>
                            <option value="0">&gt;</option>
                            <option value="1"selected>&lt;</option>
                            <option value="2">=</option>
                            <option value="3">&ge;</option>
                            <option value="4">&le;</option>
                        <?php
                        }
                        elseif($PBRop == 2){
                        ?>
                            <option value="0">&gt;</option>
                            <option value="1">&lt;</option>
                            <option value="2"selected>=</option>
                            <option value="3">&ge;</option>
                            <option value="4">&le;</option>
                        <?php
                        }
                        elseif($PBRop == 3){
                        ?>
                            <option value="0">&gt;</option>
                            <option value="1">&lt;</option>
                            <option value="2">=</option>
                            <option value="3" selected>&ge;</option>
                            <option value="4">&le;</option>
                        <?php
                        }
                        elseif($PBRop == 4){
                        ?>
                            <option value="0">&gt;</option>
                            <option value="1">&lt;</option>
                            <option value="2">=</option>
                            <option value="3">&ge;</option>
                            <option value="4" selected>&le;</option> 
                        <?php
                        } 
                        ?>                       
					</select>
					<input type="value" name="PBR_value"  class="form" value="<?php echo $PBRvaldb; ?>">&emsp;&nbsp;</p><br>
					<p class="ti">
                    <?php
                    if($PERflag3 == 0){
                        ?>
                        <input type="checkbox" name="check[]" value="2" class="ckbox" id="ckbox_id3"><label></label>      <!--PER的Checkbox  flag3=1 要提示-->
                        <?php
                    }
                    elseif($PERflag3 == 1){
                        ?>
                        <input type="checkbox" name="check[]" value="2" class="ckbox" id="ckbox_id4" checked ><label></label>      <!--PER的Checkbox  flag3=0 不要提示-->
                        <?php
                    }
                    ?>
					&nbsp;&nbsp;&nbsp;PER &emsp;<select name="PER_symbol" class="form_10">
                    <?php
                        if($PERop == 0){
                        ?>
                            <option value="0" selected>&gt;</option>
                            <option value="1">&lt;</option>
                            <option value="2">=</option>
                            <option value="3">&ge;</option>
                            <option value="4">&le;</option>

                        <?php
                        }
                        elseif($PERop == 1){
                        ?>
                            <option value="0">&gt;</option>
                            <option value="1"selected>&lt;</option>
                            <option value="2">=</option>
                            <option value="3">&ge;</option>
                            <option value="4">&le;</option>
                        <?php
                        }
                        elseif($PERop == 2){
                        ?>
                            <option value="0">&gt;</option>
                            <option value="1">&lt;</option>
                            <option value="2"selected>=</option>
                            <option value="3">&ge;</option>
                            <option value="4">&le;</option>
                        <?php
                        }
                        elseif($PERop == 3){
                        ?>
                            <option value="0">&gt;</option>
                            <option value="1">&lt;</option>
                            <option value="2">=</option>
                            <option value="3" selected>&ge;</option>
                            <option value="4">&le;</option>
                        <?php
                        }
                        elseif($PERop == 4){
                        ?>
                            <option value="0">&gt;</option>
                            <option value="1">&lt;</option>
                            <option value="2">=</option>
                            <option value="3">&ge;</option>
                            <option value="4" selected>&le;</option> 
                        <?php
                        } 
                        ?>
					</select>
					<input type="value" name="PER_value"  class="form" value="<?php echo $PERvaldb; ?>">&emsp;&nbsp;</p><br>
                   <?php
                    }  #if($cclass != "ETF" && $cclass != "ETN" && $cclass != "存託憑證") 的右括號
                    else{
                        if($buyornot != 1){

                        ?>
                        <p> 該公司沒有指標</p>
                        <input class="button" type="submit" name="Submit3" value="確認">
                    <?php
                        }
                    }
                    ?> 

			
                <?php
                if($buyornot == 1){  //+++++++++++如果有買該公司++++++++++++
                    $whichbutt = 1;
                    //從資料庫取出預設值(Sharp)
                    $sql03 = "SELECT * FROM reminder WHERE teamid = '{$teamid}'  and A = 3 and companyid = '{$companyID}'";
                    $Sharpvaldbb = mysqli_query ($con, $sql03);
                    $Sharpvaldb = mysqli_fetch_assoc($Sharpvaldbb);
                    $Sharpvaldb = $Sharpvaldb['value'];  //Sharp從reminder資料庫取出的的值
                    $Sharpopdbb = mysqli_query ($con, $sql03);
                    $Sharpop = mysqli_fetch_assoc($Sharpopdbb);
                    $Sharpop = $Sharpop['symbol'];  //Sharp從reminder資料庫取出的的值
                    $Sharpflag33 = mysqli_query ($con, $sql03);
                    $Sharpflag3 = mysqli_fetch_assoc($Sharpflag33);
                    $Sharpflag3 = $Sharpflag3['flag3']; //Sharp lfag3的預設值
                
                ?>
                <p class="ti">
                		<?php
                        if($Sharpflag3 == 0){
                            ?>
                            <input type="checkbox" name="check[]" value="3" class="ckbox" id="ckbox_id5">
                            <?php
                        }
                        elseif($Sharpflag3 == 1){
                            ?>
                            <input type="checkbox" name="check[]" value="3" class="ckbox" id="ckbox_id6" checked><label></label>      <!--Sharp的Checkbox  flag3=0 不要提示-->
                            <?php
                        }
                        ?>
                        &nbsp;Sharpe&nbsp;<select name="Sharp_symbol"  class="form_10">
                        <?php
                        if($Sharpop == 0){
                        ?>
                            <option value="0" selected>&gt;</option>
                            <option value="1">&lt;</option>
                            <option value="2">=</option>
                            <option value="3">&ge;</option>
                            <option value="4">&le;</option>

                        <?php
                        }
                        elseif($Sharpop == 1){
                        ?>
                            <option value="0">&gt;</option>
                            <option value="1"selected>&lt;</option>
                            <option value="2">=</option>
                            <option value="3">&ge;</option>
                            <option value="4">&le;</option>
                        <?php
                        }
                        elseif($Sharpop == 2){
                        ?>
                            <option value="0">&gt;</option>
                            <option value="1">&lt;</option>
                            <option value="2"selected>=</option>
                            <option value="3">&ge;</option>
                            <option value="4">&le;</option>
                        <?php
                        }
                        elseif($Sharpop == 3){
                        ?>
                            <option value="0">&gt;</option>
                            <option value="1">&lt;</option>
                            <option value="2">=</option>
                            <option value="3" selected>&ge;</option>
                            <option value="4">&le;</option>
                        <?php
                        }
                        elseif($Sharpop == 4){
                        ?>
                            <option value="0">&gt;</option>
                            <option value="1">&lt;</option>
                            <option value="2">=</option>
                            <option value="3">&ge;</option>
                            <option value="4" selected>&le;</option> 
                        <?php
                        } 
                        ?>
                        </select>
                        <input type="value" name="Sharp_value"  class="form" value="<?php echo $Sharpvaldb; ?>"> %</p><br>
                <?php
                }
                ?>
                </div>
                    <!--++++++++++++++++將值存進資料庫的按鈕+++++++++++++++++++++++++-->

                        <input type = "hidden" name="company" value = "<?php echo $companyID;?>">
                        <input type = "hidden" name="buyornot" value = "<?php echo $buyornot;?>">
                    <?php
                    if($whichbutt == 1){ ?>
                        <input class="button" type="submit" name="Submit2" value="確定">
                        <input class="button" type="submit" name="Submit3" value="取消">
                    <?php
                    }
                    ?>
                        
                    </form>
                </div>
                <?php
	    }
    }
    }
 
 ?>


 </div>
 <script src="../level.js"></script>

</body>

 
</html>
