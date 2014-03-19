<?  
include("../../../Controller/Student.php");
include("../../../Controller/Grade.php");
include("../../../Controller/FieldRelate.php");
include("../../../Controller/Field.php");
include("../../../Module/mysql.php");


$db=new Mysql();
$Students=new Student($db);
$Grades=new Grade($db);
$FieldRelates=new FieldRelate($db);
$Fields=new Field($db);

$content.="number"."\t";
$FieldID = array();
foreach ($_POST['excel'] as $value){
	$FieldID[] = $value;
	$content.=$Fields->IDToName($value)."\t";
}

$currentGrade = $_GET['currentGrade'];
$Students->GetByGradeID($currentGrade);
while($Students->HasNext()){
	$content.="\n";
	$content.=$Students->StudentAccount."\t";
	$FieldRelates->GetByStudentID($Students->StudentID);
	for($i=0;$i<sizeof($FieldID);$i++)
	{
		while($FieldRelates->HasNext())
		{
			if(in_array($FieldRelates->FieldRelateFieldID, $FieldID))
			$content.=$FieldRelates->FieldRelateValue."\t";
		}
	}
}
	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition:filename=".$Grades->IDToName($currentGrade)."級學生資料表.xls");
    echo $content;
?>