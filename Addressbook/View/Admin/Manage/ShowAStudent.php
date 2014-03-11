<?
	@session_start();
	if(!isset($_SESSION['UserType'])||$_SESSION['UserType']!="WebAdmin"||!isset($_SESSION['UserName']))
	{
		header("Location:../../../../index.php");
		die();
	}
	$SID=$_GET['SID'];
?>
<!doctype html>
<html  lang="zh_tw">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../../css/common.css">
	<link rel="stylesheet" type="text/css" href="../../css/InputsStyle.css">
	<link rel="stylesheet" type="text/css" href="../../student/css/PersonalData.css">
	<title>SED 平台系統</title>
</head>
<body>
	<?
		$err="";
		include("../../common.php");
		include("../../../Controller/Field.php");
		include("../../../Controller/FieldRelate.php");
		include("../../../Controller/Student.php");
		include("../../../Module/mysql.php");
		$view=new Common();
		$view->DrawHeader("WebAdmin");
		$db=new Mysql();
		$Fields=new Field($db);
		$FieldRelates=new FieldRelate($db);
		$Students=new Student($db);
		$Students->GetByID($SID);
		if(!$Students->HasNext())
		{
			echo $err='<div class="ExplainTitle" style="margin-left:20px;margin-top:20px;"> <font color="red">很抱歉，找不到學生資料!</font></br></br></div>';
			$view->DrawFooter();
			echo "</body></html>";
			die();
		}
		if($_POST['save']=="True")
		{
			$Fields->GetAll();
			while($Fields->HasNext())
			{
				$TempValue=mysql_real_escape_string(strip_tags($_POST['Field'.$Fields->FieldID]));
				$FieldRelates->UpdateValue($Students->StudentID,$Fields->FieldID,$TempValue);
			}
			$Students->OnUpdateData($Students->StudentID);
			$err="<font color=\"#00CC00\">-修改資料成功</font>";
		}
		
		
		$FieldRelates->GetByStudentID($Students->StudentID);
		$Fields->GetAll();
		
		$Students->GetByID($SID);
		$Students->HasNext();
		
	?>
	<form action="ShowAStudent.php?SID=<? echo $SID; ?>" enctype="application/x-www-form-urlencoded" method="POST">
	<input type="hidden" name="save" value="True"/>
	<div class="maincontent">
	<div style="height: 30px;width:100%"></div>
	<div class="ExplainTitle" style="width:100%;margin-left: 15px;"><? echo $Students->StudentAccount; ?>的個人資料<? echo $err;?></div>
	<?
		while($Fields->HasNext())
		{
			$value="";
			if($FieldRelates->HasNext())
			$value=$FieldRelates->FieldRelateValue;
			echo '<div class="EachLine" ">';
			
			if($Fields->FieldIsVisible=="1")
			{
				
				echo '<div class="FieldName">'.$Fields->FieldName.'  </div>';
				echo '
				<div class="FieldValue" >
				<input name="Field'.$Fields->FieldID.'" class="InputText" style="margin-top:0px;" value="'.$value.'" type="text"/>
				</div>';
			}
			else
			{
				
				echo '<div class="FieldName">'.$Fields->FieldName.'  </div>';
				echo '
				<div class="FieldValue">
				<input name="Field'.$Fields->FieldID.'" class="InputText" style="margin-top:0px;" value="'.$value.'" type="password" title="'.$value.'"/>
				</div>';
			}
			echo '</div>';
		}
	?>
		<div class="SubmitLine" style="background-image: none;">
			<input class="InputBuuton" tabindex="4" type="submit" id="submit" name="submit" value="存檔"/>　
	<?
		if($Students->StudentLastUpdate!="0000-00-00 00:00:00")
			echo '最後存檔時間 '. $Students->StudentLastUpdate;
	?>
		</div>
	</div>
	</form>
	<div style="width: 100%;height:40px;"></div>
	<?
		$view->DrawFooter();
	?>
</body>
</html>