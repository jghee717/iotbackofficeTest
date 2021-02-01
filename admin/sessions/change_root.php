<!doctype html>
<html lang="ko">
<?php include "./db.php"; ?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
</head>

<body>
  <?php
  $root=$_POST['root'];
  $idx=$_POST['idx'];
  $db=new DBC();
  $db->DBI();
  $query= "UPDATE member SET root='$root' WHERE idx='$idx'";
  $db->DBQ($query);
  $db->DBE();
  $db->DBO();
  ?>
</body>
