<?
//test
@session_start();
$err="";
if($_GET['Action']=="Logout")
session_destroy();

if($_POST['password']!=""&&$_POST['account']!=""&&$_POST['UserType']=="Admin")
{
	/*
	$_SESSION['UserType']="WebAdmin";
	$_SESSION['UserName']="Name";
	$_SESSION['UserAccount']="Account";
	*/
	if(!ereg("^[A-Za-z0-9]+$", $_POST['password'])||!ereg("^[A-Za-z0-9]+$", $_POST['account']))
		$err="含有非法字元!";
	else
	{
		/*include('../web/classlib/mysql.php');
		include('../web/classlib/user.php');
		$users=new user(new db_mysql());
		$users->UserAccount=$_POST['account'];
		$users->UserPassword=$_POST['password'];
		if($users->Login())
		{
			
			header("Location:addressbook/View/Admin/index.php");
			
			//header("Location:../web/mainsystem/admin.php?ftype=add&page=announcement");
			
			die();
		}
		else
		{ 
			$err='<帳號或密碼錯誤>';
		}	*/
			$_SESSION['UserType']="WebAdmin";
		$_SESSION['UserName']="Name";
		$_SESSION['UserAccount']="Account";
					header("Location:addressbook/View/Admin/index.php");

	}
}
if($_POST['password']!=""&&$_POST['account']!=""&&$_POST['UserType']=="Student")
{
	if(!ereg("^[A-Za-z0-9]+$", $_POST['password'])||!ereg("^[A-Za-z0-9]+$", $_POST['account']))
		$err="含有非法字元!";
	else
	{
		include('addressbook/module/mysql.php');
		include('addressbook/controller/student.php');
		$Students=new Student(new Mysql());
		if($Students->Login($_POST['account'],$_POST['password']))
		{
			
			$Students->GetByID($_SESSION['UserID']);
			if($Students->HasNext())
			{
				if($Students->StudentIsPasswordDefault=="1")
					die("<meta http-equiv='refresh' content='0;url=addressbook/View/Student/FirstLogin.php'>");
				else
					die("<meta http-equiv='refresh' content='0;url=addressbook/View/Student/index.php'>");

			}
		}
		else
		$err="帳號或密碼錯誤!";
	}
}
if(($_POST['password']!=""&&$_POST['account']=="")||($_POST['password']==""&&$_POST['account']!=""))
{
	
	$err='<帳號或密碼不可為空>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SED 單一登入平台</title>
<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="css/footer.css">
</head>

<body>
<div class="topbar"> 
	
  <div class="toplistboxstatic">
    <div style="width:0px;height:4px"></div>
    平台切換 </div>
     <a href="http://www.se.nknu.edu.tw/">
  <div class="toplistbox1" onmouseover="this.className='toplistbox'+Math.ceil(Math.random()*4)">
    <div style="width:0px;height:4px"></div>
    軟工首頁 </div>
  </a>
  
  <a href="http://www.se.nknu.edu.tw/nuic/">
  <div class="toplistbox" onmouseover="this.className='toplistbox'+Math.ceil(Math.random()*4)">
    <div style="width:0px;height:4px"></div>
    2014 大資杯 </div>
  </a>
</div>
<form action="index.php" method="post" enctype="application/x-www-form-urlencoded" target="_self">
<input type="hidden" value="1" name="send"/>
<div class="maincontent">
	
		<a href="images/SE.png"><div class="SELogo"></div></a>
		<form action="index.php" enctype="multipart/form-data">
		<div class="LoginArea">
			<div style="width:100%;height: 110px;"></div>
			<div class="LoginBox">
				<div class="TextLine"><span style="float: left;">登入 <font color="red"><? echo $err;?></font></span><a href="#"><span class="forget">忘記密碼</span></a></div>
				<input class="textinput" autofocus="1" name="account" tabindex="1" type="text" placeholder=" 學號或帳號"/>
				<input class="textinput" type="password" name="password" tabindex="2"  placeholder=" 密碼"/>
				<div class="InputLint">　　
					<input type="radio" value="Student" name="UserType" tabindex="3" checked="checked"/> 學生
					<input type="radio" value="Admin" name="UserType" tabindex="3" /> 管理員
					<input class="button" tabindex="4" type="submit" id="submit" name="submit" value="登入"/>
				</div>
			</div>
		</div>
		</form>
</div>
</form>
<?
	include('footer.php');
?>

</body>
</html>