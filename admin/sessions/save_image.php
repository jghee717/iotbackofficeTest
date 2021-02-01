<?php
include "./db.php";
session_start();
$user = $_SESSION['user_idx'];
$dir = "../../io/images/";

$db = new DBC;
$db->DBI();
for($i=0; $i<$_POST['image_count']; $i++) {

  $image_id = "image_".$i;
  $image_file = $_FILES[$image_id]['name'];

  if(isset($_FILES[$image_id]) && !$_FILES[$image_id]['error']) {
    if(move_uploaded_file($_FILES[$image_id]['tmp_name'], $dir.$image_file)) {
      $sql = "INSERT INTO contents(res_type,con_id,user) VALUES(2,'$image_file','$user')";
      $db->DBQ($sql);
      $db->DBE();
    }else {
      echo "파일 업로드 에러";
      exit;
    }
  }else {
    echo "파일 불러오기 에러";
    exit;
  }
}
echo "이미지가 등록되었습니다.";
$db->DBO();
?>
