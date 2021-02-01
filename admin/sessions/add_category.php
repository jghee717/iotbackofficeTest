<meta charset="utf-8">
<?php
	include './db.php';
	$cate = $_POST['new_category'];
	$name = $_POST['new_menu'];
	$num = count($name);

  $conn = new DBC;
  $conn->DBI();

	$sql2 = "SELECT category FROM menu_list where category='$cate' and state <>4";
	$conn -> DBQ($sql2);
	$conn -> DBE();
	$result = $conn->DBF();

	if(isset($result['category'])){ ?>
		<script type="text/javascript">
			alert("사용중인 카테고리명 입니다.");
			history.back();
		</script>
<? }else{
		for($i=0; $i<$num; $i++){
			$sql = "INSERT INTO menu_list(category, menu) VALUES ('$cate', '$name[$i]')";
			$conn -> DBQ($sql);
			$conn -> DBE();
		}?>
		<script type="text/javascript">
			alert("추가되었습니다.");
			location.href="../pages/category_list.php?mc=컨텐츠관리&sc=분류%20관리";
		</script>
<? }
$conn -> DBO();?>
