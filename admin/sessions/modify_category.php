<meta charset="utf-8">
<?php
	include './db.php';
	$new = $_POST['rename_category'];
	$name = $_POST['search_category'];

  $conn = new DBC;
  $conn->DBI();

	$sql2 = "SELECT category FROM menu_list where state <> 4 and category ='$new'";
	$conn -> DBQ($sql2);
	$conn -> DBE();
	$result = $conn->DBF();

	if(isset($result['category'])){?>
		<script type="text/javascript">
			alert("이미 사용중인 카테고리명 입니다.");
			history.back();
		</script>
<? }else{
		$sql = "UPDATE menu_list SET category='$new' where category='$name'";
		$conn -> DBQ($sql);
		$conn -> DBE();
		$conn -> DBO();
	  ?>
		<script type="text/javascript">
			alert("수정되었습니다.");
			location.href="../pages/category_list.php?mc=컨텐츠관리&sc=분류%20관리";
		</script>
<? }?>
