<meta charset="utf-8">
<?php
	include './db.php';
	$cate = $_POST['category'];
	$name = $_POST['menu'];
	$num = $_POST['num'];

  $conn = new DBC;
  $conn->DBI();

	$sql2 = "SELECT menu,category FROM menu_list where menu ='$name' and category='$cate' and state <>4";
	$conn -> DBQ($sql2);
	$conn -> DBE();
	$result = $conn->DBF();
	$a = $result['category'];

	if(isset($result['menu'])){?>
		<script type="text/javascript">
			alert('<?=$a?>'+" 카테고리에서 이미 사용중인 메뉴명 입니다.");
			history.back();
		</script>
<? }else{
		$sql = "UPDATE menu_list SET menu='$name', category='$cate' where menu_num='$num'";
		$conn -> DBQ($sql);
		$conn -> DBE();
		$conn -> DBO();
	  ?>
		<script type="text/javascript">
			alert("수정되었습니다.");
			location.href="../pages/category_list.php?mc=컨텐츠관리&sc=분류%20관리";
		</script>
<? }?>
