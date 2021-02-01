<?php include "./db.php";
$activ=$_POST['activ'];
$idx=$_POST['idx'];
$num = $_POST['num'];
$db=new DBC();
$db->DBI();
if($activ=="1"){   //미발행으로 변경 <제한 X>
  $query1= "UPDATE board_list SET b_state='$activ' WHERE idx='$idx'";
  $db->DBQ($query1);
  $db->DBE();
  $db->DBO();
}else{  //발행으로 변경 <제한 O>
  $sql = "SELECT b_state FROM board_list where menu_num='$num' and b_state ='2'";
  $db->DBQ($sql);
  $db->DBE();
  $result=$db->DBF();
  if(isset($result['b_state'])){  //이미 발행된 글이 있다면 불가
    echo("이미 발행된 글이 있습니다.\n기존의 글을 미발행으로 바꾸고 진행하시겠습니까?");
  }else{
    $query= "UPDATE board_list SET b_state='$activ' WHERE idx='$idx'";
    $db->DBQ($query);
    $db->DBE();
    $db->DBO();
  }
}
?>
