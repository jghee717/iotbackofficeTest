<?php
	include './db.php';
  $conn = new DBC();
  $conn->DBI();

  $menu_num = $_GET["menu_num"]

  $sql = "DELETE FROM menu_list WHERE ='".$menu_num."'";
	$conn->DBQ($sql);
	$conn->DBE();
?>
<script type="text/javascript">alert("삭제되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=../pages/category_list.php?mc=컨텐츠관리&sc=분류%20관리" />
