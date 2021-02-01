<?
require_once '../dbconn.php';
$conn = new DBC();
$conn->DBI();

$channel = $_POST['영업담당'];

$bg_code = $_POST['지원팀'];

$agency_name = $_POST['운영자명'];

$mg_code = $_POST['투자유형'];

$pos_name = $_POST['매장명'];

$agency_code = $_POST['운영자코드'];

$pos_address = $_POST['매장주소'];

$pos_code = $_POST['매장코드'];

$condition = $_POST['상태'];

//echo $pos_address; echo "<br>";
//echo $agency_code; echo "<br>";
//echo $agency_name; echo "<br>";
//echo $mg_code;  echo "<br>";
//echo $bg_code; echo "<br>";
//echo $channel; echo "<br>";
//echo $condition; echo "<br>";
//echo $pos_code; echo "<br>";

 $updte =
  "UPDATE did_pos_code SET
  channel='".$channel."', bg_code='".$bg_code."', mg_code='".$mg_code."' ,
 agency_name='".$agency_name."', agency_code='".$agency_code."', pos_name='".$pos_name."', pos_address='".$pos_address."'
 WHERE pos_code = '".$pos_code."' ";
 $conn->DBQ($updte);
 $conn->DBE();



// print_r($content_image);


?>
<script type="text/javascript">alert("수정되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=http://iotdidsystem.cafe24.com:8080/store_info.php?pos_code=<?php echo $pos_code;?>&condition2=<?echo $condition?>">
