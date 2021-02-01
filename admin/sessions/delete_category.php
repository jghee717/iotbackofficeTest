<meta charset="utf-8">
<?php
	include './db.php';
	$cate = $_POST['delete_category'];

  $conn = new DBC;
  $conn->DBI();

	$sql2 = "SELECT menu_num, menu FROM menu_list where category ='$cate' and state <>4";
	$conn -> DBQ($sql2);
	$conn -> DBE();
  $ccc = "0";
	while($result = $conn->DBF()){
    $db = new DBC;
    $db->DBI();
    $query="SELECT idx FROM board_list where menu_num='{$result['menu_num']}' and state <> '4'";
    $db->DBQ($query);
    $db->DBE();
    $idx = $db->DBF();
    if(isset($idx['idx'])){
      $ccc++;
    } $db->DBO();
  }
  if($ccc != "0"){?>
    <script type="text/javascript">
      alert("해당 카테고리의 메뉴에 게시된 글이 존재합니다.\n모든 메뉴에 게시글이 없어야 삭제가 가능합니다.");
      history.back();
      </script>
<?}else{
	$sql = "UPDATE menu_list SET state='4' where category='$cate'";
	$conn -> DBQ($sql);
	$conn -> DBE();
	$conn -> DBO();
  ?>
	<script type="text/javascript">
		alert("삭제되었습니다.");
		location.href="../pages/category_list.php?mc=컨텐츠관리&sc=분류%20관리";
	</script>
<? }?>
