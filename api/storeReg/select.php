<?php
include '../dbconn.php';


$conn = new DBC();
$conn->DBI();
$ajax = $_REQUEST['optVal'];
if ($ajax == '스마트홈') {
  $ajax = '홈/미디어';
}else {
  $ajax = $_REQUEST['optVal'];
}

?>
<?if($ajax == '') {?>
  <option value="">전체</option>
  <?}else {?>
<option value="">전체</option>
<?
$query = "SELECT bg_code FROM did_pos_code where channel = '".$ajax."' and bg_code is not NULL and bg_code != '' GROUP BY bg_code";
  $conn->DBQ($query);
  $conn->DBE();
  while ($option1 = $conn->DBF()) {  ?>
    <option value="<?echo $option1['bg_code'];?>"><?echo $option1['bg_code'];?>
</option>
<?} }?>
