<?php
include './db.php';
$idx = $_POST['checkbox'];

$conn = new DBC;
$conn->DBI();

for($i=0; $i<count($idx); $i++){
  $query = "UPDATE board_list SET b_state='2' where idx='$idx[$i]'";
  $conn -> DBQ($query);
  $conn -> DBE();
}
$conn->DBO();
echo("복구가 완료되었습니다.");
?>
