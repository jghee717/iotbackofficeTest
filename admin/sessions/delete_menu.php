<meta charset="utf-8">
<?php
	include './db.php';
	$idx = $_POST['delete_idx'];

  $conn = new DBC;
  $conn->DBI();

	$sql2 = "SELECT idx FROM board_list where menu_num ='$idx' and b_state <> 4";
	$conn -> DBQ($sql2);
	$conn -> DBE();
	$result = $conn->DBF();

	if(isset($result['idx'])){?>
		<script type="text/javascript">
			alert("해당 메뉴에 게시된 글이 존재합니다.\n해당 메뉴에 게시글이 없어야 삭제가 가능합니다.");
			history.back();
		</script>
<? }else{
		$sql = "UPDATE menu_list SET state='4' where menu_num='$idx'";
		$conn -> DBQ($sql);
		$conn -> DBE();
		$conn -> DBO();
	  ?>
		<script type="text/javascript">
			alert("삭제되었습니다.");
			location.href="../pages/category_list.php?mc=컨텐츠관리&sc=분류%20관리";
		</script>
<? }?>
