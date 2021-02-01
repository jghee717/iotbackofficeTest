<!DOCTYPE html>
<head>
  <meta charset="utf-8"/>
</head>

<body>
  <?php
  include "./db.php";
  $id=$_POST['ID'];
  $hash= password_hash($_POST['PW'], PASSWORD_DEFAULT);
  $db = new DBC;
  $query= "INSERT INTO member (id,hash,root) VALUES ('$id','$hash','0')";
  $db->DBI();
  $db->DBQ($query);
  if ($db->DBE()) {
      $db->DBO();
    echo "<script type='text/javascript'>alert('서버에러로 인해 다시 시도해주시기 바랍니다.');
    history.back();</script>";
  }else {
    $db->DBO();
    echo "<script type='text/javascript'>alert('정상적으로 가입승인요청되었습니다.');
    location.href='../index.php';</script>";
  }

  ?>
</body>
