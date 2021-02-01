<meta charset="utf-8">
<?php
	include './db.php';
	$cate = $_POST['add_menu_category'];
	$name = $_POST['add_menu_name'];
	$num = count($name);

  $conn = new DBC;
  $conn->DBI();

	for($i=0; $i<$num; $i++){
		$sql2 = "SELECT menu FROM menu_list where menu ='$name[$i]' and category='$cate' and state <>4";
		$conn -> DBQ($sql2);
		$conn -> DBE();
		$result = $conn->DBF();
		if(isset($result['menu'])){ ?>
			<script type="text/javascript">
				alert("<?=$result['menu']?>가 사용중이어서 추가되지 않았습니다.");
				location.history.back();
			</script>
	<? }else{
			$sql = "INSERT INTO menu_list(category, menu) VALUES ('$cate', '$name[$i]')";
			$conn -> DBQ($sql);
			$conn -> DBE();
		}
	}
	$conn -> DBO();
  ?>
	<script type="text/javascript">
		alert("추가되었습니다.");
		location.href="../pages/category_list.php?mc=컨텐츠관리&sc=분류%20관리";
	</script>
