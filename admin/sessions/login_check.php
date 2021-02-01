<!DOCTYPE html>
<head>
  <meta charset="utf-8"/>
</head>

<body>
  <?php
  session_start();
  include "./db.php";
  $id=$_POST['ID'];
  $hash=$_POST['PW'];
  $db = new DBC;
  $query= "SELECT idx,id,hash,root FROM member WHERE id='$id'";
  $db->DBI();
  $db->DBQ($query);
  $db->DBE();

  if($db->resultRow()==1){
    $result=$db->DBF();
    if(password_verify($hash, $result['hash'])){
      if ($result['root']==0) {
        echo "<script type='text/javascript'>alert('미승인 계정입니다.');
        history.back();</script>";
      }else {
        $_SESSION['user_idx']=$result['idx'];
        $_SESSION['user_id']=$result['id'];
        $_SESSION['user_root']=$result['root'];
        if(isset($_SESSION['user_id'])){
          header("Location:http://iotdidsystem.cafe24.com:8080/admin/pages/dashboard.php");
        }
        else{
          echo "<script type='text/javascript'>alert('서버오류');
          history.back();</script>";
        }
      }
    }
    else{
      echo "<script type='text/javascript'>alert('비밀번호가 일치하지 않습니다.');
      history.back();</script>
      ";
    }
  }
  else {
    echo "<script type='text/javascript'>alert('일치하는 계정이 없습니다.');
    history.back();</script>
    ";
  }
  ?>
</body>
