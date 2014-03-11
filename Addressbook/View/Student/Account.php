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
		//如果找不到該名學生的資料就退出
		if(!$Students->HasNext())
		{
			die("<meta http-equiv='refresh' content='0;url=../../../index.php'>");
		}
		//觸發改密碼的變數
		if($_POST['change']=="True")
		{
				
			//確認三欄不為空
			if($_POST['pw1']!=""&&$_POST['pw2']!=""&&$_POST['old']!="")
			{
				//只可以是中+英
				if(!ereg("^[A-Za-z0-9]+$", $_POST['pw1'])||!ereg("^[A-Za-z0-9]+$", $_POST['pw2'])||!ereg("^[A-Za-z0-9]+$", $_POST['old']))
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
					if($Students->SetPassword($Students->StudentID,$_POST['old'],$_POST['pw1']))
					{
						$err='<font color="green">密碼修改成功！</font>';
					}
					else
						$err='舊的密碼錯誤！';
				}
			}
			else
			{
				$err="請勿為空!";
			}
		}
		
		
	?>
<div class="maincontent">
	
		<div class="LeftBox" style="background-image: none;">
		<div style="height: 110px; width: 100%;"></div>
			<div class="ExplainTitle">
				<font color="#0380fc">您好 , <? echo $_SESSION['UserAccount'];?></font> 
			</div>
			
			<div class="ExplainLine">
				<ul>
					
					<li>
					如果您想要整合學校服務，請將密碼設為您當前學校系統的密碼。
					</li>					
					</br>
					<li>
					歡迎您提供寶貴的意見到<a href="mailto:pinlin0726@gmail.com" style="color: #391ce3"> 服務信箱</a>。
					</li>
				</ul>
			</div>
				
		</div>
		<form action="Account.php" enctype="application/x-www-form-urlencoded" method="POST">
		<input  type="hidden" value="True" name="change"/>
		<div class="RightBox">
			<div style="width:100%;height: 110px;"></div>
			<div class="LoginBox">
				<div class="EachLine">修改密碼　　<font color="red"><? echo $err;?></font></div>
				<input class="InputText" autofocus="1" name="old" tabindex="1" type="password" placeholder=" 舊密碼"/>
				<input class="InputText" autofocus="1" name="pw1" tabindex="2" type="password" placeholder=" 新密碼"/>
				<input class="InputText" type="password" name="pw2" tabindex="3"  placeholder=" 確認密碼"/>
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