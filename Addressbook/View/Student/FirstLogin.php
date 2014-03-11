<?
	@session_start();
	if(!isset($_SESSION['UserType'])||$_SESSION['UserType']!="Student"||!isset($_SESSION['UserID']))
	{
		header("Location:../../../index.php");
		die();
	}
?>
<!doctype html>
<html  lang="zh_tw">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/common.css">
	<link rel="stylesheet" type="text/css" href="../css/InputsStyle.css">
	<title>請修改密碼</title>
</head>
<body>
	<?
		$err="";
		include("../common.php");
		include("../../Controller/Student.php");
		include("../../Module/mysql.php");
		$view=new Common();
		$view->DrawHeader("Student");
		$Students=new Student(new Mysql());
		//獲取學生的資訊
		$Students->GetByID($_SESSION['UserID']);
		
		if($Students->HasNext())
		{
			//如果密碼不是預設的，就跳到學生首頁
			if($Students->StudentIsPasswordDefault!="1")
			{
				die("<meta http-equiv='refresh' content='0;url=index.php'>");
			}
		}
		else
		
			die("<meta http-equiv='refresh' content='0;url=../../../index.php'>");
		if($_POST['pw1']!=""&&$_POST['pw2']!="")
		{
			if(!ereg("^[A-Za-z0-9]+$", $_POST['pw1'])||!ereg("^[A-Za-z0-9]+$", $_POST['pw2']))
				$err="含有非法字元!";
			else if(($_POST['pw1']!=$_POST['pw2']))
			{
				$err="兩次輸入的密碼不同!";	
			}
			else if(strlen($_POST['pw2'])<8)
			{
				$err="密碼長度至少8碼!";
			}
			else
			{
				if($Students->SetPassword($_SESSION['UserID'],$Students->StudentPassword,$_POST['pw1']))
				{
					echo "<script language='javascript'>alert('密碼修改成功！');</script>";
					die("<meta http-equiv='refresh' content='0;url=index.php'>");
				}
				else
					echo "<script language='javascript'>alert('密碼修改失敗！');</script>";
			}
		}
		if($_POST['pw1']==""&&$_POST['pw2']!="")
		$err="新密碼請勿為空!";
		if($_POST['pw1']!=""&&$_POST['pw2']=="")
		$err="確認碼請勿為空!";
		
		
	?>
<div class="maincontent">
	
		<div class="LeftBox" style="background-image: none;">
		<div style="height: 110px; width: 100%;"></div>
			<div class="ExplainTitle">
				<font color="#0380fc">歡迎 , <? echo $_SESSION['UserAccount'];?></font> 
			</div>
			
			<div class="ExplainLine">
				<ul>
					<li>
						為了方便你日後的登入，與安全上的問題，<font color="red">請您設定新的密碼</font>，長度至少8碼。
					</li>
					
					</br>
					<li>
					如果您想要整合學校服務，請將密碼設為您當前學校系統的密碼。
					</li>					
					</br>
					<li>
					最後，歡迎您提供寶貴的意見到<a href="mailto:pinlin0726@gmail.com" style="color: #391ce3"> 服務信箱</a>。
					</li>
				</ul>
			</div>
				
		</div>
		<form action="FirstLogin.php" enctype="application/x-www-form-urlencoded" method="POST">
		<div class="RightBox">
			<div style="width:100%;height: 110px;"></div>
			<div class="LoginBox">
				<div class="EachLine"><span style="float: left;">設定您的新密碼</span>　　<font color="red"><? echo $err;?></font></div>
				<input class="InputText" autofocus="1" name="pw1" tabindex="1" type="password" placeholder=" 新密碼"/>
				<input class="InputText" type="password" name="pw2" tabindex="2"  placeholder=" 確認密碼"/>
				<div class="SubmitLine" style="background-image: none;">
					<input class="InputBuuton" tabindex="4" type="submit" id="submit" name="submit" value="確認"/>
				</div>
			</div>
		</div>
		</form>
		
		
</div>

	<?
	$view->DrawFooter();
	?>
</body>
</html>