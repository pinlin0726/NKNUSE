<?php
@session_start();
if(!isset($_SESSION['UserType'])||$_SESSION['UserType']!="WebAdmin"||!isset($_SESSION['UserName']))
{
	header("Location:../../../../index.php");
	die();
}
include("../../../Controller/Student.php");
include("../../../Controller/Grade.php");
include("../../../Controller/FieldRelate.php");
include("../../../Controller/Field.php");
include("../../../Module/mysql.php");
$db=new Mysql();
$Students=new Student($db);
$Grades=new Grade($db);
$Students->GetByGradeID($_GET['GID']);
$HasFind=FALSE;
while($Students->HasNext())
{
	
	$HasFind=TRUE;
	if($Students->StudentIsPasswordDefault=="1")
    	$content.=$Students->StudentAccount."\t".$Students->StudentPassword."\n";
	else
    	$content.=$Students->StudentAccount."\t*********\n";
}
if($HasFind)
{
		
	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition:filename=".$Grades->IDToName($_GET['GID']).$Grades->IDToChinese( $Grades->IDToName($_GET['GID']))."學生密碼表.xls");
    echo $content;
}
else
{
?>
	<html  lang="zh_tw">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/studentaccount.css">
	<link rel="stylesheet" type="text/css" href="../../css/common.css">
	<link rel="stylesheet" type="text/css" href="../../css/InputsStyle.css">
	<title>SED 平台管理系統</title>
</head>
<body>
<div style="margin-left: 20px;margin-top: 30px;">

	     <h1>匯出學生帳號密碼表單失敗!!</h1>
	     <h3>錯誤原因 : 該年級沒有學生資料，或者是查無該年級資料!</h3>
		 <ul>
		 	<li>如果您確認該年級含有學生還是發生此錯誤，請聯絡系統維護者!</li>
			
		 	<li>pinlin0726@gmail.com</li>
		 </ul>
</div>
</body>
</html>
<? } ?>