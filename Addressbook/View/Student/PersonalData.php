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
	<link rel="stylesheet" type="text/css" href="css/PersonalData.css">
	<title>SED 平台系統</title>
</head>
<body>
	<?
		$err="";
		include("../common.php");
		include("../../Controller/Field.php");
		include("../../Controller/FieldRelate.php");
		include("../../Controller/Student.php");
		include("../../Module/mysql.php");
		$view=new Common();
		$view->DrawHeader("Student");
		$db=new Mysql();
		$Fields=new Field($db);
		$FieldRelates=new FieldRelate($db);
		$Students=new Student($db);
		$Students->GetByID($_SESSION['UserID']);
		if($Students->HasNext())
		{
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
				//重新獲取學生資料
				$Students->GetByID($_SESSION['UserID']);
				$Students->HasNext();
			}
		}
		

	?>
	<form action="personaldata.php" enctype="application/x-www-form-urlencoded" method="POST">
	<input type="hidden" name="save" value="True"/>
	<div class="maincontent">
	<div style="height: 30px;width:100%"></div>
	<div class="ExplainTitle" style="width:100%;">修改您的個人資料<? echo $err;?></div>
	<?				
		//獲取表中欄位清單
		$Fields->GetAll();
		//獲取該學生的欄位值清單
		$FieldRelates->GetByStudentID($Students->StudentID);
		
		while($Fields->HasNext())
		{			
			
			$IsFieldIDCompatible=TRUE;
			//理論上欄位清單與學生值清單應該是對應的
			if($FieldRelates->HasNext())
			{
				$value=$FieldRelates->FieldRelateValue;
				//如果欄位跟學生欄位ID不符，則防止別欄覆蓋，以空白顯示，FieldRelate的UpdateValue有錯誤回復的功能。
				if($FieldRelates->FieldRelateFieldID!=$Fields->FieldID)
					$value="";
			}
			else
				$value="";
			
			echo '
			<div class="EachLine"  >
				<div class="FieldName" >'.$Fields->FieldName.'  </div>
				<div class="FieldValue" >
					<input name="Field'.$Fields->FieldID.'" class="InputText" style="margin-top:0px;" value="'.$value.'" type="text" />
				</div>
			</div>';
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