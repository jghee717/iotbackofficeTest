<?php
include "./db.php";

$db = new DBC;
$db->DBI();
$sql= "SELECT idx, con_id FROM contents where res_type=1 and con_id like '%{$_POST['search']}%' order by idx desc";
$db->DBQ($sql);
$db->DBE();
while($video=$db->DBF()){?>
  <div style="width:33%; float:left; height:125px; text-align:center; padding-top:5px; margin-top:5px" id="<?=$video['idx']?>">
    <img src="https://img.youtube.com/vi/<?=$video['con_id']?>/0.jpg" alt="<?=$video['con_id']?>" style="width:90%;height:100px; cursor:pointer" onClick="select_video('<?=$video['idx']?>','<?=$video['con_id']?>')">
    <div class="" style="height:20px; overflow:hidden"><?=$video['con_id']?></div>
  </div>
<?php }?>
