<?
class Grade
{
	//Mysql connection
	private $db;
	//Execute result
	private $Result;
	//rows number of result
	private $Rows;

	//Database fields
	public $GradeID;
	public $GradeName;
	public $Grade;

	public function __construct($db)
	{
		$this->db=$db;
	}
	public function IDToChinese($GradeName)
	{
		date_default_timezone_set("Asia/Taipei");
		$thisyear=date("Y")-1911;
		if($GradeName-$thisyear<0)
		return "畢業";
		if($GradeName==$thisyear)
		return "大四";
		if($GradeName-1==$thisyear)
		return "大三";
		if($GradeName-2==$thisyear)
		return "大二";
		if($GradeName-3==$thisyear)
		return "大一";
		if($GradeName-$thisyear>3)
		return "新生";
		
	}
	public function IDToName($GradeID)
	{
		$sql = 'SELECT * FROM `Grade` WHERE `GradeID` = \''.$GradeID.'\'';
		$result = $this->db->query($sql);
		$temp = $this->db->fetch_array($result);
		return $temp['GradeName'];
	}
	
	public function Insert($GradeName)
	{
		$sql = 'INSERT INTO `Grade` (`GradeName`) 
		                     VALUES (\''.$GradeName.'\');';
		$this->db->query($sql);
	}

	public function Update($GradeID,$GradeName)
	{
		$sql = 'UPDATE `Grade` SET `GradeID`=\''.$GradeID.'\' 
		WHERE `GradeID`=\''.$GradeID.'\' ;';
		$this->db->query($sql);
	}
	public function DeleteByID($GradeID)
	{
		$sql='DELETE from `Grade` WHERE `GradeID`=\''.$GradeID.'\';';
		$this->db->query($sql);
	}
	public function IsNameUsed($Name)
	{
		$sql = 'SELECT * FROM `grade` WHERE `GradeName` = \''.$Name.'\' ';
		$result=$this->db->query($sql);
		$rows=$this->db->num_rows($result);
		if($rows=="1")
		{
			return true;
		}else
		{
			return false;
		}
	}
		public function GetAll()
	{
		$sql = 'SELECT * FROM `Grade` ORDER BY  `GradeName` DESC ';
		$this->Result=$this->db->query($sql);
		$this->Rows=$this->db->num_rows($this->Result);
	}

	public function HasNext()
	{
		if($this->Rows>0)
		{
			$temp=$this->db->fetch_array($this->Result);
			$this->GradeID=$temp['GradeID'];
			$this->GradeName=$temp['GradeName'];
			$this->Grade=$temp['Grade'];
			$this->Rows--;
			return true;
		}
		return false;
	}
}
/*
include("mysql.php");
$grade = new Grade(new Mysql());
$grade->GetAll();
while($grade->HasNext()){
	echo $grade->GradeID,'<br/>';
}*/
?>