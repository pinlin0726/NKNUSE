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
	<title>登入高師大學生系統</title>
</head>
<body >
	<?
		$err="";
		include("../common.php");
		include("../../Controller/Student.php");
		include("../../Module/mysql.php");
		$view=new Common();
		$view->DrawHeader("Student");
		$Students=new Student(new Mysql());
		$Students->GetByID($_SESSION['UserID']);
		if(!$Students->HasNext())
		{
			die("<meta http-equiv='refresh' content='0;url=../../../index.php'>");
		}
	?>
<div class="maincontent">
	
		<div class="LeftBox" style="background-image: none;">
		<div style="height: 110px; width: 100%;"></div>
			<div class="ExplainTitle">
				<font color="#0380fc">登入高師大學生系統</font> 
			</div>
			
			<div class="ExplainLine">
				<ul>
					<li>
						請確認你的密碼與學校用的一致，若為一至，請直接按下登入，反之請修改。
					</li>
					
				</ul>
			</div>
				
		</div>
		<form name="form1" target="_blank" method="post" action="http://info.nknu.edu.tw/StuInfo/LLogin.aspx" 
		onsubmit="javascript\:return WebForm_OnSubmit()\;\" id=\"form1\">
		
		<div>
<input type="hidden" name="__LASTFOCUS" id="__LASTFOCUS" value="">
<input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="">
<input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="">
<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="/wEPDwUINDM2NTcwMjMPZBYCAgMPZBYCAg0PZBYCAgUPD2QWAh4Hb25DbGljawUNdGhpcy52YWx1ZT0nJ2RkxIpRPoeWT85ePsVnbi9t9v4wPgE=">
</div>

		<div class="RightBox">
			<div style="width:100%;height: 110px;"></div>
			<div class="LoginBox">
				<div class="EachLine">登入</div>
				<input class="InputText" name="txtStuID" type="text" id="txtStuID" tabindex="2"  placeholder=" 確認密碼"  value="<? echo  $Students->StudentAccount; ?>"/>
				<input class="InputText" name="txtStuPWD" type="password" id="txtStuPWD" tabindex="2"  placeholder=" 學號" value="<? echo  $Students->StudentPassword; ?>"/>
				<div class="SubmitLine" style="background-image: none;">
				
				
				<input class="InputBuuton" type="submit" name="btnLogin" value="登入" onclick="javascript:WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;btnLogin&quot;, &quot;&quot;, true, &quot;L&quot;, &quot;&quot;, false, false))" id="btnLogin">
				
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
<script type="text/javascript">
//<![CDATA[
var theForm = document.forms['form1'];
if (!theForm) {
    theForm = document.form1;
}
function __doPostBack(eventTarget, eventArgument) {
    if (!theForm.onsubmit || (theForm.onsubmit() != false)) {
        theForm.__EVENTTARGET.value = eventTarget;
        theForm.__EVENTARGUMENT.value = eventArgument;
        theForm.submit();
    }
}
//]]>
</script>
<script type="text/javascript">
//<![CDATA[
function WebForm_OnSubmit() {
if (typeof(ValidatorOnSubmit) == "function" && ValidatorOnSubmit() == false) return false;
return true;
}
//]]>
</script>