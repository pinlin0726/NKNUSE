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
	<link rel="stylesheet" type="text/css" href="css/studentaccount.css">
	<link rel="stylesheet" type="text/css" href="../../css/common.css">
	<link rel="stylesheet" type="text/css" href="../../css/InputsStyle.css">
	<title>SED 平台管理系統</title>
</head>
<body>
<?
	$GradeError="";
	include("../../common.php");
	include("../../../Controller/Student.php");
	include("../../../Controller/Grade.php");
	include("../../../Controller/FieldRelate.php");
	include("../../../Controller/Field.php");
	include("../../../Module/mysql.php");
	$db=new Mysql();
	$view=new Common();
	$view->DrawHeader("WebAdmin");
	$Students=new Student($db);
	$Grades=new Grade($db);
	$FieldRelates=new FieldRelate($db);
	$Fields=new Field($db);
	
	//add grade
	if(isset($_POST['GradeName'])&&$_POST['GradeName']!="")
	{
		
		if(!is_numeric($_POST['GradeName']))
			$GradeError='<font color="red">提示 : 只可為數字!</font>';
		else if(!$Grades->IsNameUsed($_POST['GradeName']))
		{
			$Grades->Insert($_POST['GradeName']);
			$GradeError='<font color="green">提示 : 新增成功!</font>';
		}
		else
			$GradeError='<font color="red">提示 : 已被使用!</font>';
	}
	//del grade
	if(isset($_POST['DeleteGrade'])&&$_POST['DeleteGrade']!="")
	{
		
		if($_POST['DeleteGrade']!="-1")
		{
			
			$Grades->DeleteByID($_POST['DeleteGrade']);
			$Students->GetByGradeID($_POST['DeleteGrade']);
			while($Students->HasNext())
			{
				$FieldRelates->DeleteByStudentID($Students->StudentID);
			}
			$Students->DeleteByGradeID($_POST['DeleteGrade']);
			$GradeError='<font color="red">提示 : 刪除成功!</font>';
		}
	}
	//add account
	if($_POST['StartAccount']!=""&&$_POST['Offset']!=""&&$_POST['AddGrade']!="")
	{
		if(is_numeric($_POST['StartAccount'])&&is_numeric($_POST['Offset']))
		{
			$FieldIDList=array();
			$Fields->GetAll();
			while($Fields->HasNext())
			$FieldIDList[]=$Fields->FieldID;
			$StudentError="<font color='green'>提示 : 全數新增成功!</font>";
			for($i=0;$i<$_POST['Offset'];$i++)
			{
				$ac=$_POST['StartAccount']+$i;
				if(!$Students->IsAccountUsed($ac))
				{
					$Students->Insert($ac,$_POST['AddGrade']);
					$StudentID=$db->insert_id();
					for($k=0;$k<count($FieldIDList);$k++)
						$FieldRelates->Insert($StudentID,$FieldIDList[$k]);
				}
				else
				{
					$StudentError='<font color="red">提示 : 部分帳號新增失敗，因為重複使用!</font>';
				}
			}
		}
		else
			$StudentError='<font color="red">提示 : 起始帳號必須為數字!</font>';
		
	}
	if($_GET['Action']=="Reset"&&$_GET['SID']!="")
	{
		
		$Students->GetByID($_GET['SID']);
		if($Students->HasNext())
		{
			$StudentError='<font color="green">提示 : 重設'.$Students->StudentAccount.'成功!</font>';
			$Students->ResetPassword($_GET['SID']);
		}
		else
			$StudentError='<font color="red">提示 : 找不到該帳號'.$Students->StudentAccount.'!</font>';
	}	
	if($_GET['Action']=="Delete"&&$_GET['SID']!="")
	{
		$Students->GetByID($_GET['SID']);
		if($Students->HasNext())
		{
			$StudentError='<font color="green">提示 : 刪除'.$Students->StudentAccount.'成功!</font>';
			$Students->DeleteByID($_GET['SID']);
			$FieldRelates->DeleteByStudentID($_GET['SID']);
		}
		else
			$StudentError='<font color="red">提示 : 找不到該帳號'.$Students->StudentAccount.'!</font>';
	}
	if(isset($_GET['Grade']))
		$CurrentGrade=$_GET['Grade'];
?>
<div style="height: 20px;width:100%;"></div>
<div class="maincontent">	
	<div class="GradeArea">
	<div class="ExplainTitle" style="margin-left: 15px;">年級管理</div>
	
	<form method="POST" action="StudentAccount.php?Action=AddGrade" enctype="application/x-www-form-urlencoded">
	<div class="ExplainLine" style="margin-top: 20px; margin-left: 15px;">
	畢業年 : <input type="text" name="GradeName" size="8"/><input  type="submit" value="新增"/>
	</div>
	
	</form>
	
	<div class="ExplainLine" style="width:90%;margin-top: 20px; margin-left: 15px;">
	<? echo $GradeError;?>
	</div>
		<div class="GradeLine" >
		<ul>
		<?
			$Grades->GetAll();
			while($Grades->HasNext())
			{
				if(!isset($_GET['Grade']))
				{
					$CurrentGrade=$Grades->GradeID;
					$_GET['Grade']=$Grades->GradeID;
				}
				echo '<li><a href="studentaccount.php?Grade='.$Grades->GradeID.'">'.$Grades->GradeName.' ('.$Grades->IDToChinese($Grades->GradeName).')</a></li>';	
			}
		?>	
		</ul>
		</div>
	<form  method="POST" action="StudentAccount.php?Action=DeleteGrade" enctype="application/x-www-form-urlencoded">
	<div class="ExplainLine" style="margin-top: 20px; margin-left: 15px;">
	年級 : 
	<select name="DeleteGrade" >
		<option  value="-1">=請選擇=</option>
		<?
			$Grades->GetAll();
			while($Grades->HasNext())
			echo '<option size="8"  value="'.$Grades->GradeID.'">'.$Grades->GradeName.' ('.$Grades->IDToChinese($Grades->GradeName).')</option>';
		?>
	</select> <input  type="submit" value="刪除"/>
	</div>
	</form>
	</div>
	<div class="StudentArea">
		
		
		<div class="ExplainTitle" style="margin-left: 15px;width:100%;">
			<? echo $Grades->IDToName($CurrentGrade);?>學生管理
			<a target="_blank" href="PasswordTable.php?GID=<? echo $CurrentGrade;?>">
				<span style="color: blue; font-size: 20px;" ><點此匯出為Excel></span>
			</a> 
		</div>
		
		<form action="StudentAccount.php?Grade=<?echo $CurrentGrade;?>&Action=AddStudent" method="POST" enctype="application/x-www-form-urlencoded">
			<div class="ExplainLine" style="width:100%; margin-left: 15px; margin-top:10px;">
			<input type="hidden" name="AddGrade" value="<? echo $CurrentGrade;?>"/>
			
				從學號<input size="10" type="text" name="StartAccount"/>開始，連續新增
				<select name="Offset">
				<?
				for($i=1;$i<=100;$i++)
				echo '<option value="'.$i.'">'.$i.'</option>'; 
				?>
				</select>筆帳號 <input  type="submit" value="新增"/>
			</div>
		</form>
		<div class="ExplainLine" style="width:100%; margin-left: 15px; margin-top:10px;">
		<? echo $StudentError;?>
		</div>
		
		<div class="ExplainLine" style="width:100%; margin-left: 15px; margin-top:10px;">
		<table class="Table" style="width:95%;">
	<tr >
		<td class="HeaderRow" style="color: white;">帳號</td>
		<td class="HeaderRow" style="color: white;">密碼</td>
		<td class="HeaderRow" style="color: white;">最後填寫個資時間</td>
		<td class="HeaderRow" style="color: white;">刪除</td>
	</tr>
		<?
			$IsUseOddStyle=True;
			$Students->GetByGradeID($CurrentGrade);
			while($Students->HasNext())
			{
				if($IsUseOddStyle)
				$Styles="DatarowOdd";
				else
				$Styles="DatarowEven";
				$IsUseOddStyle=!$IsUseOddStyle;
				echo
				'
				<tr >
					<td class="'.$Styles.'" ><a target="_blank" href="ShowAStudent.php?SID='.$Students->StudentID.'" style="color:blue">'.$Students->StudentAccount.'</a></td>
					';
				if($Students->StudentIsPasswordDefault==1)
					echo'<td class="'.$Styles.'" >'.$Students->StudentPassword.'</td>';
				else
					echo'<td class="'.$Styles.'" ><a href="StudentAccount.php?Grade='.$CurrentGrade.'&Action=Reset&SID='.$Students->StudentID.'">Reset</a></td>';
					
				echo'
					<td class="'.$Styles.'" >'.$Students->StudentLastUpdate.'</td>
					<td class="'.$Styles.'" ><a href="StudentAccount.php?Grade='.$CurrentGrade.'&Action=Delete&SID='.$Students->StudentID.'"><font color="red">Delete</font></a></td>
				</tr>
				';
			}
		?>
	
	
</table>
		</div>
		
		
	</div>
</div>

<div style="height: 20px;width:100%;"></div>
<?
	$view->DrawFooter();
?>
</body>
</html>