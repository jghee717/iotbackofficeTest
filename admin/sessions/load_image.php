<?php
include "./db.php";

$arr = $_POST['except'];
$count = count($arr);
$db = new DBC;
$db->DBI();
$sql= "SELECT idx, con_id FROM contents where res_type=2 and con_id like '%{$_POST['search']}%' ";
for($i=0; $i<$count; $i++){
  $sql .= " and con_id <>'".$arr[$i]."'";
}
$sql .= " order by idx desc";
$db->DBQ($sql);
$db->DBE();
while($img=$db->DBF()){?>
  <div id="<?=$img['idx']?>" style="width:33%; float:left; height:150px; text-align:center">
    <img src="../../io/images/<?=$img['con_id']?>" alt="<?=$img['con_id']?>" style="width:90%;height:100px; cursor:pointer" onClick="img_list('<?=$img['idx']?>','<?=$img['con_id']?>')">
    <div class="" style="height:20px; overflow:hidden"><?=$img['con_id']?></div>
  </div>
<?php }?>
