<?
//This class was generated by PHPDBParser(Java) at 2014/03/02 18:30:11
class Student
{
	//Mysql connection
	private $db;
	//Execute result
	private $Result;
	//rows number of result
	private $Rows;

	//Database fields
	public $StudentID;
	public $StudentAccount;
	public $StudentPassword;
	public $StudentIsPasswordDefault;
	public $StudentLastUpdate;
	public $StudentGrade;

	public function __construct($db)
	{
		$this->db=$db;
	}
	
	public function IsAccountUsed($Account)
	{
		$sql = 'SELECT * FROM `student` WHERE `StudentAccount` = \''.$Account.'\' ';
		$result=$this->db->query($sql);
		$rows=$this->db->num_rows($result);
		if($rows==1)
		{
			return true;
		}else
		{
			return false;
		}
	}
	
	public function Login($Account,$Password)
	{
		$sql = 'SELECT * FROM `student` WHERE `StudentAccount` = \''.$Account.'\' and `StudentPassword`=\''.$Password.'\' ';
		$result=$this->db->query($sql);
		$rows=$this->db->num_rows($result);
		if($rows==1)
		{
			$temp=$this->db->fetch_array($result);
			$_SESSION['UserType']="Student";
			$_SESSION['UserAccount']=$temp['StudentAccount'];
			$_SESSION['UserID']=$temp['StudentID'];
			return true;
		}else
		{
			return false;
		}
	}
	
	public function SetPassword($StudentID,$old,$new)
	{
		$sql = 'SELECT * FROM `student` WHERE `StudentID` = \''.$StudentID.'\' and `StudentPassword`=\''.$old.'\' ';
		$result=$this->db->query($sql);
		$rows=$this->db->num_rows($result);
		if($rows==1)
		{
			$sql = 'UPDATE `student` SET `StudentPassword`=\''.$new.'\' ,`StudentIsPasswordDefault`=\'0\' WHERE `StudentID`=\''.$StudentID.'\';';
			$this->db->query($sql);
			return true;
		}else
		{
			return false;
		}
	}
	
	public function Insert($StudentAccount,$StudentGrade)
	{
		$length=10;
		$pattern = "01234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		
		for($i=0;$i<$length;$i++)
			$key .= $pattern{rand(0,62)};
		$sql = 'INSERT INTO `student` (`StudentAccount`,`StudentPassword`,`StudentGrade`) VALUES (\''.$StudentAccount.'\',\''.$key.'\',\''.$StudentGrade.'\');';
		$this->db->query($sql);
	}
	public function GetByAccount($Account)
	{
		$this->Rows=0;
		$sql = 'SELECT * FROM  `student` where   `StudentAccount`=\''.$Account.'\' ';
		//store return data to $this->Result
		$this->Result=$this->db->query($sql);
		//count the numbers of return data and save to $this->Rows
		$this->Rows=$this->db->num_rows($this->Result);
	}
	public function GetAll()
	{
		$sql = 'SELECT * FROM  `student` ORDER BY   `StudentAccount` DESC ';
		$this->Result=$this->db->query($sql);
		$this->Rows=$this->db->num_rows($this->Result);
	}
	
	public function GetByGradeID($GradeID)
	{
		$this->Rows=0;
		$sql = 'SELECT * FROM `student` WHERE `StudentGrade` = \''.$GradeID.'\'  ORDER BY   `StudentID` ASC ';
		//store return data to $this->Result
		$this->Result=$this->db->query($sql);
		//count the numbers of return data and save to $this->Rows
		$this->Rows=$this->db->num_rows($this->Result);
	}
	
	public function GetByID($StudentID)
	{
		$this->Rows=0;
		$sql = 'SELECT * FROM `student` WHERE `StudentID` = \''.$StudentID.'\' ';
		//store return data to $this->Result
		$this->Result=$this->db->query($sql);
		//count the numbers of return data and save to $this->Rows
		$this->Rows=$this->db->num_rows($this->Result);
	}
	
	
	public function DeleteByID($StudentID)
	{
		$sql='DELETE FROM `student` WHERE `StudentID`=\''.$StudentID.'\' ;';
		$this->db->query($sql);
	}
	
	public function DeleteByGradeID($GradeID)
	{
		$sql='DELETE FROM `student` WHERE `StudentGrade`=\''.$GradeID.'\' ;';
		$this->db->query($sql);
	}
	public function OnUpdateData($StudentID)
	{
		date_default_timezone_set("Asia/Taipei");
		$sql = 'UPDATE `student` SET `StudentLastUpdate`=\''.date("Y-m-d H:i:s").'\' WHERE `StudentID`=\''.$StudentID.'\';';
		$this->db->query($sql);
	}
	public function ResetPassword($StudentID)
	{
		$length=10;
		$pattern = "01234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		
		for($i=0;$i<$length;$i++)
			$key .= $pattern{rand(0,62)};
			
		$sql = 'UPDATE `student` SET `StudentPassword`=\''.$key.'\' ,`StudentIsPasswordDefault`=\'1\'  WHERE  `StudentID` =\''.$StudentID.'\' ;';
		$this->db->query($sql);
	}
	
	public function HasNext()
	{
		if($this->Rows>0)
		{
			$temp=$this->db->fetch_array($this->Result);
			$this->StudentID=$temp['StudentID'];
			$this->StudentAccount=$temp['StudentAccount'];
			$this->StudentPassword=$temp['StudentPassword'];
			$this->StudentIsPasswordDefault=$temp['StudentIsPasswordDefault'];
			$this->StudentLastUpdate=$temp['StudentLastUpdate'];
			$this->StudentGrade=$temp['StudentGrade'];
			$this->Rows--;
			return true;
		}
		return false;
	}
}
/*
include("mysql.php");
$Students=new Student(new Mysql());

$Students->GetByID(2);
if($Students->HasNext())
	echo $Students->StudentAccount;
	
$Students->GetByGradeID(2);
while($Students->HasNext())
	echo $Students->StudentAccount , '<br>';
*/

//$Students->Login("410175000","000");

//$Students->SetPassword(2,"1456","123");

//$Students->IsAccountUsed("410175000");

//$Students->Insert("410175013","111","2");

//$Students-> DeleteByID(9);

//$Students-> ResetPassword(5);

/*
$Students->Select();
while($Students->HasNext()){
	$Students->DeleteByGradeID(1);
}

$Students->Select();
while($Students->HasNext()){
	echo '<br>';
	echo $Students->StudentAccount , '<br>';
}
*/
?>