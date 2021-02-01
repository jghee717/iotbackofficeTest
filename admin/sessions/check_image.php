<?php
include './db.php';
$name = $_POST['name'];
$big = $_POST['big'];
$small = $_POST['small'];

$db = new DBC;
$db->DBI();
$sql = "SELECT idx FROM contents WHERE res_type=2 and con_id ='$name.$big'";
$db->DBQ($sql);
$db->DBE();
$result = $db->DBF();

$sql2 = "SELECT idx FROM contents WHERE res_type=2 and con_id ='$name.$small'";
$db->DBQ($sql2);
$db->DBE();
$result2 = $db->DBF();
$db->DBO();

if(isset($result['idx']) || isset($result2['idx'])){
  echo("YES");
}else{
  echo("NO");
}
?>
