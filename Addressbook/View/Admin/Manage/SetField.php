<?
	@session_start();
	if(!isset($_SESSION['UserType'])||$_SESSION['UserType']!="WebAdmin"||!isset($_SESSION['UserName']))
	{
		header("Location:../../../../index.php");
		die();
	}
?>

<!doctype html>
<html  lang="zh_tw">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../../css/common.css">
	<link rel="stylesheet" type="text/css" href="../../css/InputsStyle.css">
	<title>SED 平台管理系統</title>
</head>

<body>

<?
	$FieldError="";
	include("../../common.php");
	include("../../../Controller/Student.php");
	include("../../../Controller/Field.php");
	include("../../../Controller/FieldRelate.php");
	include("../../../Module/mysql.php");
	$db=new Mysql();
	$view=new Common();
	$view->DrawHeader("WebAdmin");
	$Students=new Student($db);
	$FieldRelates=new FieldRelate($db);
	$Fields=new Field($db);
	
	//add field
	if(isset($_POST['AddFieldName'])&&$_POST['AddFieldName']!="")
	{
		if(!$Fields->IsFieldUsed($_POST['AddFieldName']))
		{
			$Fields->Insert($_POST['AddFieldName']);
			$NewFieldID=$db->insert_id();
			$Students->GetAll();
			while($Students->HasNext())
			{
				$FieldRelates->Insert($Students->StudentID,$NewFieldID);
			}
			$FieldError='<font color="green">提示 : 新增欄位'.$_POST['AddFieldName'].'成功!</font>';
		}
		else
			$FieldError='<font color="red">提示 : 欄位'.$_POST['AddFieldName'].'已被使用!</font>';
	}
	
	//update field
	if($_GET['Action']=="Update"&&$_POST['FID']!="")
	{
		if(isset($_POST['FieldName'])&&$_POST['FieldName']!="")
		{
			$Fields->Update($_POST['FID'],$_POST['FieldName']);
			$FieldError='<font color="green">提示 : 修改為'.$_POST['FieldName'].'成功!</font>';
		}
		else
			$FieldError='<font color="red">提示 : 請輸入欄位名稱!</font>';
	}
	
	//delete field
	if($_GET['Action']=="Delete"&&$_GET['SID']!="")
	{
		$Fields->GetByID($_GET['SID']);
		if($Fields->HasNext())
		{
			$FieldError='<font color="green">提示 : 刪除'.$Fields->FieldName.'成功!</font>';
			$Fields->DeleteByID($_GET['SID']);
			$FieldRelates->DeleteByFieldID($_GET['SID']);
		}
		else
			$FieldError='<font color="red">提示 : 找不到該欄位!</font>';
	}
	
	// field IsVisible
	if($_GET['Action']=="IsVisible"&&$_GET['SID']!="")
	{
		if($_GET['Visibility']==1)
		{
			$Fields->SetIsVisible($_GET['SID'],false);
			$FieldError='<font color="green">提示 : 隱藏欄位!</font>';
		}
		else
		{
			$Fields->SetIsVisible($_GET['SID'],true);
			$FieldError='<font color="green">提示 : 開啟欄位!</font>';
		}	
	}
?>

	<div class="maincontent">
	
	
	
	<form method="POST" action="SetField.php?Action=AddField" enctype="application/x-www-form-urlencoded">
	<div class="ExplainTitle" style="margin-left: 15px;margin-top:20px;">學生欄位管理</div>
	<div class="ExplainLine" style="width:100%;margin-top: 25px;margin-left:15px;">新增欄位 <input  type="text" name="AddFieldName"/><input  type="submit"  value="新增"/> </div>
	</form>
	
	<div class="ExplainLine" style="width:100%; margin-left: 15px; margin-top:10px;">
		<? echo $FieldError;?>
	</div>
	
	<div class="ExplainLine" style="margin-top: 25px;margin-left:15px;"></div>
	<table class="Table" style="width:95%;">
	<tr >
		<td class="HeaderRow" style="color: white;">欄位</td>
		<td class="HeaderRow" style="color: white;">修改</td>
		<td class="HeaderRow" style="color: white;">隱藏</td>
		<td class="HeaderRow" style="color: white;">刪除</td>
	</tr>
	<?
			$IsUseOddStyle=True;
			$Fields->GetAll();
			while($Fields->HasNext())
			{
				if($IsUseOddStyle)
				$Styles="DatarowOdd";
				else
				$Styles="DatarowEven";
				$IsUseOddStyle=!$IsUseOddStyle;
				
				if($Fields->FieldIsVisible==1)
					$ShowVisibility="X";
				else
					$ShowVisibility="V";
					
				echo
				'
				<form method="POST" action="SetField.php?Action=Update" enctype="application/x-www-form-urlencoded">
				<input type="hidden" name="FID" value="'.$Fields->FieldID.'"/>
				<tr >
					<td class="'.$Styles.'" ><input type="text" name="FieldName"  value="'.$Fields->FieldName.'"/></td>
					<td class="'.$Styles.'" ><input  type="submit"  value="修改"/></td>
					<td class="'.$Styles.'" ><a href="SetField.php?Action=IsVisible&SID='.$Fields->FieldID.'&Visibility='.$Fields->FieldIsVisible.'"><font color="red">'.$ShowVisibility.'</font></a></td>
					<td class="'.$Styles.'" ><a href="SetField.php?Action=Delete&SID='.$Fields->FieldID.'"><font color="red">Delete</font></a></td>
				</tr>
				</form>
				';
			}
	?>
	</table>
	</div>
	

<div style="height: 20px;width:100%;"></div>
<?
	$view->DrawFooter();
?>
</body>
</html>