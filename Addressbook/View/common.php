<?php
class Common
{
	private $host="http://127.0.0.1/NKNUSE/";
	public function DrawHeader($UserType="Stduent")
	{	
		$Items=array();
		$Links=array();
		$Target=array();
		if($UserType=="WebAdmin")
		{
			$Items[]="軟工系首頁";
			$Links[]=$this->host;
			$Target[]="_blank";
			
			$Items[]="系網管理";
			$Links[]=$this->host."web/mainsystem/admin.php?page=announcement&ftype=add";
			$Target[]="_blank";
			
			$Items[]="學生帳戶管理";
			$Links[]=$this->host."sub/addressbook/view/admin/manage/studentaccount.php";
			$Target[]="_self";
			
			$Items[]="學生資料查詢";
			$Links[]=$this->host."sub/addressbook/view/admin/manage/SearchStudent.php";
			$Target[]="_self";
						
			$Items[]="學生欄位設定";
			$Links[]=$this->host."sub/addressbook/view/admin/manage/SetField.php";
			$Target[]="_self";
			
			$Items[]="學生資料匯出";
			$Links[]=$this->host."sub/addressbook/view/admin/manage/ExportData.php";
			$Target[]="_self";
		}
		else
		{
			$Items[]="軟工系首頁";
			$Links[]=$this->host;
			$Target[]="_blank";
			
			
			$Items[]="個人資料填寫";
			$Links[]=$this->host."sub/addressbook/view/student/personaldata.php";
			$Target[]="_self";
			
			$Items[]="帳戶管理";
			$Links[]=$this->host."sub/addressbook/view/student/account.php";
			$Target[]="_self";
			
			$Items[]="學校系統";
			$Links[]=$this->host."sub/addressbook/view/student/schoolbridge.php";
			$Target[]="_self";
		}
		echo '
		<div class="topbar"> 
		';
		$counter=0;
		foreach($Items as $Name)
		{ 
			echo '
			<a target="'.$Target[$counter].'" href="'.$Links[$counter].'">
	  			<div class="toplistbox" onmouseover="this.className=\'toplistbox\'+Math.ceil(Math.random()*4)">
	    		<div style="width:0px;height:4px"></div>
	    		'.$Name.' </div>
	  		</a> ';
			$counter++;
		}
			
	  	echo'	
	  			<div class="loginbox">
			';
				if($UserType=="WebAdmin")
				echo 'User Name　　'. $_SESSION['UserName'] .'　<a href="'.$this->host.'sub/index.php?Action=Logout" style="color:white;">登出</a>';
				else
					echo 'User Account　　'. $_SESSION['UserAccount'] .'　<a href="'.$this->host.'sub/index.php?Action=Logout" style="color:white;">登出</a>';
		echo'
	    		 
				</div>
	  		
		</div>';
	}
	public function DrawFooter()
	{
		date_default_timezone_set("Asia/Taipei");
		echo'
		<div class="footer">
			<div class="text_center">© '.date("Y").' NKNU Software Engineering Department</div>
		</div>';
	}
}
/*
@session_start();

<html>
	<head>
		<link rel="stylesheet" href="css/header.css">
	</head>
	<body>
		
	</body>
</html>

$t=new Common();
$t->DrawHeader("WebAdmin");https://www.facebook.com/photo.php?fbid=644055442323676&set=a.644101668985720&type=3&theater*/
?>