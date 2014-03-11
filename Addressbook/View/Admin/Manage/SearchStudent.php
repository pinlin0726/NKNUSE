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
		include("../../../Controller/Grade.php");
		include("../../../Module/mysql.php");
		$view=new Common();
		$view->DrawHeader("WebAdmin");
		$db=new Mysql();
		$Fields=new Field($db);
		$Grades=new Grade($db);
		$FieldRelates=new FieldRelate($db);
		$Students=new Student($db);
	
		
	?>
	
	<input type="hidden" name="save" value="True"/>
	<div class="maincontent">
	<form action="SearchStudent.php?>" enctype="application/x-www-form-urlencoded" method="POST">
	<div style="width: 100%;height:30px;"></div>
	<div class="ExplainLine" style="width:100%;margin-top: 25px;margin-left:15px;">想查詢的關鍵字 <input  type="text" name="KeyWord"/><input  type="submit"/> </div>
	</form>
	
	<div class="ExplainLine" style="margin-top: 25px;margin-left:15px;"></div>
			<table class="Table" style="width:95%;">
	<tr >
		<td class="HeaderRow" style="color: white;">年級</td>
		<td class="HeaderRow" style="color: white;">學號</td>
		<td class="HeaderRow" style="color: white;">符合的值</td>
	</tr>
		<?
		if(isset($_POST['KeyWord'])&&($_POST['KeyWord']!=""))
		$FieldRelates->GetByValue($_POST['KeyWord']);
		
		
			$IsUseOddStyle=True;
			while($FieldRelates->HasNext())
			{
				
				$Fields->GetByID($FieldRelates->FieldRelateFieldID);
				$Fields->HasNext();
				$Students->GetByID($FieldRelates->FieldRelateStudentID);
				if($Students->HasNext())
				{
					if($IsUseOddStyle)
						$Styles="DatarowOdd";
					else
						$Styles="DatarowEven";
					$IsUseOddStyle=!$IsUseOddStyle;
					echo
				'
				<tr >
				
					<td class="'.$Styles.'" >'.$Grades->IDToName($Students->StudentGrade).$Grades->IDToChinese($Grades->IDToName($Students->StudentGrade)).'</td>
					<td class="'.$Styles.'" ><a target="_blank" href="ShowAStudent.php?SID='.$Students->StudentID.'" style="color:blue">'.$Students->StudentAccount.'</a></td>
					
					<td class="'.$Styles.'" >'.$Fields->FieldName.' : '.$FieldRelates->FieldRelateValue.'</td>
					
				</tr>
				';
				}
			}
		?>
	
	
</table>
	
	</div>
	<div style="width: 100%;height:40px;"></div>
	<?
		$view->DrawFooter();
	?>
</body>
</html>