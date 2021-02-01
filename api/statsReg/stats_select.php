<?php
include '../dbconn.php';


$conn = new DBC();
$conn->DBI();
$ajax = $_REQUEST['optVal'];

?>
<?if($ajax == 'IoT'){?>
  <select style="background-color: #E9ECEF" name="space_id" id="" class="form-control form-control-sm" onchange="categoryChange(this)">
      <option value="">전체</option>
      <option <?if($_GET['space_id'] == "s000001"){echo "selected";}?> value="s000001">침실</option>
      <option <?if($_GET['space_id'] == "s000002"){echo "selected";}?> value="s000002">거실</option>
      <option <?if($_GET['space_id'] == "s000003"){echo "selected";}?> value="s000003">주방</option>
      <option <?if($_GET['space_id'] == "s000004"){echo "selected";}?> value="s000004">아이방</option>
    </select>

<?}else {?>
  <select style="background-color: #E9ECEF" name="space_id" id="" disabled class="form-control form-control-sm" onchange="categoryChange(this)">
          <option value="">전체</option>
  </select>
<?}?>
