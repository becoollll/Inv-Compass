<?php
	session_start();//啟用Session功能

	$acc = $_POST["account"]; //讀入帳號
	$pwd = md5($_POST["password"]);//將使用者傳進的值使用md5()編碼方式

	try {
		$conn = new PDO("mysql:host=localhost;dbname=database", "username", "password");
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT  *  FROM `user` WHERE  `email` = '$acc' ;";
		//回傳一個查詢結果的物件
		$return = $conn->query($sql);
		$row = $return->fetch();
		if(!empty($row)){
			if(md5($row['password']) == $pwd){
				echo "登入成功,寫入session與cookie<br>";
				$_SESSION['mylogin'] = true;//將此值記錄於Session變數
				echo "登入狀態:".$_SESSION['mylogin']."<Br>";//測試讀出Session
				
				setcookie("useremail", (string)$row['email'], 0, "/", "", "");
				setcookie("userid", (string)$row['id'], 0, "/", "", "");
				setcookie("username", (string)$row['name'], 0, "/", "", "");
				setcookie("userteam", (string)$row['team'], 0, "/", "", "");
				setcookie("userlevel", (string)$row['level'], 0, "/", "", "");
				$teamname=(string)$row['team'];
				$sql = "SELECT  *  FROM `team` WHERE  `teamname` = '$teamname'";
				$return = $conn->query($sql);
				$gettid = $return->fetch();				
				setcookie("teamid", (string)$gettid['teamid'], 0, "/", "", "");
				setcookie("reminder", 0, 0, "/", "", "");
				
				//成功登入導到首頁
				$URL="../focus.php"; 
				header("Location: $URL");// 將網址導回首頁
			}else{
				//echo "登入失敗";
				$URL="../../../index.php?login=error"; //在網址中帶錯誤訊息回去
				header("Location: $URL");// 將網址導回登入
			}
		}else{
			//echo "登入失敗";
			$URL="../../../index.php?login=error"; //在網址中帶錯誤訊息回去
			header("Location: $URL");// 將網址導回登入
		}		
	}
	catch(PDOException $e){
		echo $sql . "<br>" . $e->getMessage();
	}
	$conn = null;
?>
