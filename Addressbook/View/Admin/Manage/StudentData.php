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

$FieldID = array();
foreach ($_POST['excel'] as $value){
	$content.=$value."\t";
	$FieldID[] = $Fields->GetIDByName($value);
}
$content.="\n";
$HasValue = False;
$HasChecked = False;
$currentGrade = $_GET['currentGrade'];
$Students->GetByGradeID($currentGrade);
while($Students->HasNext()){
	for($i=0;$i<sizeof($FieldID);$i++){
		$FieldRelates->GetByStudentIDAndFieldID($Students->StudentID,$FieldID[$i]);
		while($FieldRelates->HasNext()){
			if($FieldRelates->FieldRelateValue!="" && $HasChecked==False){
				$HasValue =True;
				$HasChecked =True;
			}
			if($FieldRelates->FieldRelateValue!=""){
				$content.=$FieldRelates->FieldRelateValue."\t";
			}
			if($i==sizeof($FieldID)-1&&$HasValue==True){
			$content.="\n";
			}
		}
		}
		$HasValue = False;
		$HasChecked = False;
}
	header("Content-type:application/vnd.ms-excel");
	header("Content-Disposition:filename=".$Grades->IDToName($_GET['GID']).$Grades->IDToChinese( $Grades->IDToName($_GET['GID']))."學生密碼表.xls");
    echo $content;
?>