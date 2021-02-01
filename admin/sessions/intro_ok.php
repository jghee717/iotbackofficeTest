<meta charset="utf-8">
<?php
	include './db.php';
  $conn = new DBC;
  $conn->DBI();

  $contents = $_POST['contents'];
  $opt_num = $_POST['opt'];

  $sql = "UPDATE intro SET contents='$contents' WHERE opt='$opt_num'";
  $conn -> DBQ($sql);
	$conn -> DBE();
  $conn -> DBO();
  ?>

  <script type="text/javascript">
    alert("수정되었습니다.");
    history.back();
  </script>
