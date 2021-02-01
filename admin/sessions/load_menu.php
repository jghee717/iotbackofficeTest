<?
include "./db.php";
$db = new DBC;
$db->DBI();
$sql="SELECT menu_num, menu FROM menu_list where category='{$_POST['category']}' and state <> 4 ";
$db->DBQ($sql);
$db->DBE();
while($menu=$db->DBF()){?>
  <option value="<?=$menu['menu_num']?>"><?=$menu['menu']?></option>
<? }
$db->DBO();?>
