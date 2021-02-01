<?php
include "./db.php";
session_start();
$user = $_SESSION['user_idx'];
$id = $_POST['video'];

$db = new DBC;
$db->DBI();
$sql2 = "SELECT idx from contents where con_id ='$id'";
$db->DBQ($sql2);
$db->DBE();
$result = $db->DBF();
if(isset($result['idx'])){
  echo "이미 존재하는 영상 ID입니다.";
}else{
  $sql = "INSERT INTO contents(res_type,con_id,user,upload_date) VALUES(1,'$id','$user',now())";
  $db->DBQ($sql);
  $db->DBE();
  echo "영상이 등록되었습니다.";
}
$db->DBO();
?>
