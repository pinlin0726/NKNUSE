<?
class Field
{
	//Mysql connection
	private $db;
	//Execute result
	private $Result;
	//rows number of result
	private $Rows;

	//Database fields
	public $FieldID;
	public $FieldName;
	public $FieldIsVisible;

	public function __construct($db)
	{
		$this->db=$db;
	}
	
	public function IsFieldUsed($Field)
	{
		$sql = 'SELECT * FROM `field` WHERE `FieldName` = \''.$Field.'\' ';
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
	
	public function Insert($FieldName)
	{
		$sql = 'INSERT INTO `Field` (`FieldName`) 
		VALUES                      (\''.$FieldName.'\');';
		$this->db->query($sql);
	}
	public function Update($FieldID,$FieldName)
	{
		$sql = 'UPDATE `Field` SET `FieldID`=\''.$FieldID.'\' ,`FieldName`=\''.$FieldName.'\' ;';
		$this->db->query($sql);
	}
	public function DeleteByID($FieldID)
	{
		$sql='DELETE FROM `Field` WHERE `FieldID`=\''.$FieldID.'\'';
		$this->db->query($sql);
	}
	public function IDToName($FieldID)
	{
		$sql = 'SELECT * FROM `Field` WHERE `FieldID` = \''.$FieldID.'\' ';
		$TResult=$this->db->query($sql);
		$TRows=$this->db->num_rows($TResult);
		if($TRows==1)
		{
			$TEach=$this->db->fetch_array($TResult);
			return $TEach['FieldName'];
		}
		return "Not found";
	}
	public function GetIDByName ($FieldName)/*write by LSC*/{
		$sql = 'SELECT * FROM `Field` WHERE `FieldName` = \''.$FieldName.'\' ';
		$res = $this->db->query($sql);
		$row = $this->db->fetch_array($res);
		return $row['FieldID'];
	}
	public function GetByID($FieldID)
	{
		$this->Rows=0;
		$sql = 'SELECT * FROM `Field` WHERE `FieldID` = \''.$FieldID.'\' ';
		//store return data to $this->Result
		$this->Result=$this->db->query($sql);
		//count the numbers of return data and save to $this->Rows
		$this->Rows=$this->db->num_rows($this->Result);
	}
	public function GetAll()
	{
		$sql = 'SELECT * FROM `Field` ORDER BY  `FieldID` ASC ';
		$this->Result=$this->db->query($sql);
		$this->Rows=$this->db->num_rows($this->Result);
	}
	public function SetIsVisible($FieldID,$IsVisible)
	{
		if($IsVisible)
			$VisibleValue=1;
		else
			$VisibleValue=0;
		$sql = 'UPDATE `Field` SET `FieldIsVisible`=\''.$VisibleValue.'\' WHERE `FieldID`=\''.$FieldID.'\';';
		$this->db->query($sql);
		
	}
	public function HasNext()
	{
		if($this->Rows>0)
		{
			$temp=$this->db->fetch_array($this->Result);
			$this->FieldID=$temp['FieldID'];
			$this->FieldName=$temp['FieldName'];
			$this->FieldIsVisible=$temp['FieldIsVisible'];
			$this->Rows--;
			return true;
		}
		return false;
	}
}

/*include('mysql.php');
$Field=new Field(new Mysql());
echo $Field->GetIDByName(name);
/*
$Field->GetByID(1);
if($Field->HasNext())
echo $Field->FieldName;


echo $Field->IDToName(1);

$Field->Insert("性別");
$Field->DeleteByID(2);
$Field->Update(1,"住家");
$Field->GetAll();
while($Field->HasNext())
{
	echo $Field->FieldName."<br/>";
}
$Field->SetIsVisible(1,1);
*/
?>