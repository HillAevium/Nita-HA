<?
include 'iSQL.php';

$SQL = new iSQL();

$SQL->mySQL("SELECT * FROM `a_states` WHERE `country_id` = '%d';","225'");
echo $SQL." ".$SQL->touched."<br />";

echo "Exicuted in ".$SQL->time."<br />Total Rows: ".$SQL->total."<br />";
echo "Fields: ";
var_dump($SQL->fields);
echo "<br />";

echo "<br />First Row: ";
var_dump($SQL->Row[0]);
var_dump($SQL->Row[0]["state_id"]);
echo "<br />";
echo "<br />Second Row: ";
var_dump($SQL->Row[1]);
var_dump($SQL->Row[1]["state_id"]);
echo "<br />";
echo "<br />Last Row: ";
var_dump($SQL->Row[$SQL->Last]);
var_dump($SQL->Row[$SQL->Last]["state_id"]);
echo "<br /><br />";

var_dump(isset($SQL->Row[60]));

echo "<br />";

foreach($SQL->Rows() as $key => $value){
	echo $key.": ";
	var_dump($value);
	echo "<br />";
}

echo "<br />".$SQL->KEY."<br /><br />";

foreach($SQL->Rows() as $key => $value){
	echo $key.": ";
	var_dump($value);
	echo "<br />";
}

?>