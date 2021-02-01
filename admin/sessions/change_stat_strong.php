<?php include "./db.php";
$activ=$_POST['activ'];
$idx=$_POST['idx'];
$num = $_POST['num'];
$db=new DBC();
$db->DBI();
$query1= "UPDATE board_list SET b_state='1' WHERE menu_num='$num' and b_state='2'";
$db->DBQ($query1);
$db->DBE();

$query= "UPDATE board_list SET b_state='$activ' WHERE idx='$idx'";
$db->DBQ($query);
$db->DBE();
$db->DBO();
?>
