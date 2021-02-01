<?php include "./db.php";
$idx=$_POST['idx'];
$name=$_POST['name'];
$type=$_POST['con'];
if ($type=="2") {
  $test_name = rename("../../io/images/$name","../../io/images/$name.bak");
  $test_name=true;
  if($test_name){
    $db=new DBC();
    $db->DBI();
    $query= "UPDATE contents SET con_id='$name.bak', res_type='4' WHERE idx='$idx'";
    $db->DBQ($query);
    $db->DBE();
    $db->DBO();
    echo "이미지가 삭제되었습니다.";
  }else{
    echo "오류";
  }
}else {
  $db=new DBC();
  $db->DBI();
  $query= "UPDATE contents SET res_type='3' WHERE idx='$idx'";
  $db->DBQ($query);
  $db->DBE();
  $db->DBO();
  echo "영상이 삭제되었습니다.";
}
?>
