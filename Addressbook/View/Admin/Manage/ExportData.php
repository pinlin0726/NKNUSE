
<html  lang="zh_tw">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../../css/common.css">
	<link rel="stylesheet" type="text/css" href="../../css/InputsStyle.css">
	<link rel="stylesheet" type="text/css" href="css/StudentAccount.css">
	<title>SED 平台管理系統</title>
</head>

<?
	include("../../common.php");
	include("../../../Controller/Student.php");
	include("../../../Controller/Field.php");
	include("../../../Controller/FieldRelate.php");
	include("../../../Controller/Grade.php");
	include("../../../Module/mysql.php");
	
	$view=new Common();
	$view->DrawHeader("WebAdmin");
	
	$db=new Mysql();
	$Students=new Student($db);
	$Grades=new Grade($db);
	$FieldRelates=new FieldRelate($db);
	$Fields=new Field($db);

	if(isset($_GET['Grade'])){
		$currentGrade = $_GET['Grade'];
	}
?>	

<div style="height: 20px;width:100%;"></div>
<div class="maincontent">
	<div class="GradeArea">
		<div class="ExplainTitle" style="margin-left: 15px;">學生資料匯出</div>
		<div class="GradeLine">
			<ul>
				<?
					$Grades->GetAll();
					while($Grades->HasNext())
					{
						if(!isset($_GET['Grade']))
						{
							$currentGrade=$Grades->GradeID;
							$_GET['Grade'] = $currentGrade;
						}
						echo '<li><a href="ExportData.php?Grade='.$Grades->GradeID.'">'.$Grades->GradeName.' ('.$Grades->IDToChinese($Grades->GradeName).')</a></li>';
					}
				?>
			</ul>
		</div>
	</div>
	<form method="POST" action="StudentData.php?currentGrade=<?echo $currentGrade?>" enctype="application/x-www-form-urlencoded" target="_blank">
	<div class="StudentArea">
		<div class="ExplainTitle" style="margin-left: 15px;width:100%;"><?echo $Grades->IDToName($currentGrade)?>學生資料列表
		<input type = "submit" value="點此匯出為excel">
		</div>
		<table class="Table" style="width:95%;">
			<tr>
				<?
					$Fields->GetAll();
					while($Fields->HasNext())
					{
						echo'
						<td class="HeaderRow" style="color: white;">'.$Fields->FieldName.'
						<Input type="checkbox" name="excel[]" value='.$Fields->FieldName.' checked>
						</td>
							';
					}
				?>
			</tr>
		</form>	
			<?
				$IsUseOddStyle = True;
				$HasValue = False;
				$Students->GetByGradeID($currentGrade);
				while($Students->HasNext()){
					$FieldRelates->GetByStudentID($Students->StudentID);
					if($IsUseOddStyle)
						$Styles="DatarowOdd";
						else
						$Styles="DatarowEven";
						$IsUseOddStyle=!$IsUseOddStyle;
					while($FieldRelates->HasNext())
					{
						if($FieldRelates->FieldRelateValue!=""){
							$HasValue = True;
						echo
						'
						<td class="'.$Styles.'">'.$FieldRelates->FieldRelateValue.'</td>
						';
						}
						else{
							if($HasValue==True && $FieldRelates->FieldRelateValue=="")
							echo'<td class="'.$Styles.'">empty</td>';
						}
					}
					$HasValue = False;
					echo '</tr>';
				}
		?>
		</table>	
	</div>
</div>	
<?
	$view->DrawFooter();
?>