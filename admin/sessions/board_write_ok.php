<meta charset="utf-8">
<?php
session_start();
$user = $_SESSION['user_idx'];
$menu = $_POST['menu'];
$category = $_POST['category'];
if($category == "IoT"){
  $title = "IoT";
  $note = "IoT";
}else{
  $title = urlencode ($_POST['title']);
  $note = urlencode ($_POST['note']);
}
$con_num = $_POST['content_source'];
$arr = $_POST['send_image'];
if($con_num == '1'){
  $count = '1';
}else{
  $count = count($arr);
}
$idx = $_POST['idx'];

include "./db.php";
$db=new DBC;
$db->DBI();
$sql2 = "SELECT MAX(content_count)as count FROM board_list;";
$db->DBQ($sql2);
$db->DBE();
$result = $db->DBF();
$db_count = $result['count'];

$sql = "INSERT INTO board_list(menu_num,title,note,user,datetime,b_state,con_source,content_count) VALUES('$menu','$title','$note','$user',now(),'1','$con_num','$count')";
$db->DBQ($sql);
$db->DBE();
$last = $db -> lastId();
if($con_num == 1){  //동영상
  $sql7 = "UPDATE board_list SET content1 ='$idx' where idx=$last";
  $db->DBQ($sql7);
  $db->DBE();
}else{  // 이미지
  if($count <= $db_count) {
    for($i=0; $i<$count; $i++){
      $a = $i+1;
      $sql3 = "UPDATE board_list SET content$a ='{$arr[$i]}' where idx=$last";
      $db->DBQ($sql3);
      $db->DBE();
    }
    $db->DBO();
  }else if($count > $db_count){
    $add = $count-$db_count;
    for($i=1; $i<$add+1; $i++){  //칼럼추가
      $sum = $i+$db_count;
      $sql4 = "ALTER TABLE board_list ADD content$sum VARCHAR(30)";
      $db->DBQ($sql4);
      $db->DBE();
    }
    for($i=0; $i<$count; $i++){  //데이터넣기
      $b = $i+1;
      $sql6 = "UPDATE board_list SET content$b ='{$arr[$i]}' where idx=$last";
      $db->DBQ($sql6);
      $db->DBE();
    }
    $db->DBO();
  }
}
echo "<script type='text/javascript'>alert('글쓰기가 완료되었습니다.');
window.location.href='../pages/board_list.php?mc=게시글관리&sc=게시글%20목록';</script>";
 ?>
