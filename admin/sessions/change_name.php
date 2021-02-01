<!doctype html>
<html lang="ko">
<?php include "./db.php"; ?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
</head>

<body>
  <?php
  $orgin_name=$_POST['orgin_name'];
  $id=$_POST['name'];
  $idx=$_POST['idx'];
  $type=$_POST['type'];

if ($type==2) {
  $test_name = rename("../../io/images/$orgin_name","../../io/images/$id");

    if($test_name){
      $db=new DBC();
      $db->DBI();
      $query= "UPDATE contents SET con_id='$id' WHERE idx='$idx'";
      $db->DBQ($query);
      $db->DBE();
      $db->DBO();
        echo "<script type='text/javascript'>alert('변경되었습니다.');
        location.href='../pages/contents.php?mc=리소스관리&sc=이미지관리&page=1';</script>";
    }else{
        echo "<script type='text/javascript'>alert('오류');
        location.href='../pages/contents.php?mc=리소스관리&sc=이미지관리&page=1';</script>";
    }
}else {
  $regExp = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/';
  preg_match($regExp, $id, $matches);
  $id = $matches[7];
  $db=new DBC();
  $db->DBI();
  $query= "UPDATE contents SET con_id='$id' WHERE idx='$idx'";
  $db->DBQ($query);
  $db->DBE();
  $db->DBO();
    echo "<script type='text/javascript'>alert('변경되었습니다.');
    location.href='../pages/contents.php?mc=리소스관리&sc=영상관리&page=1';</script>";
}


  ?>

</body>
