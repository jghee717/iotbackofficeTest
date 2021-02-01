<?
include '../dbconn.php';

$conn = new DBC();
$conn->DBI();

$sql = "select * from pre_dashboard order by idx desc limit 1";
$conn->DBQ($sql);
$conn->DBE();
$row=$conn->DBF();

// print_r($row);
$regist_store = explode(',',$row[1]);
$exe_new_store = explode(',',$row[2]);
$exe_per_pro = explode(',',$row[3]);
$app_use = explode(',',$row[4]);
$pv_doughnut = explode(',',$row[5]);
$pv_line_day = explode(',',$row[6]);
$pv_line_week = explode(',',$row[7]);
$pv_line_month = explode(',',$row[8]);
$uv_doughnut = explode(',',$row[9]);
$uv_line_day = explode(',',$row[10]);
$uv_line_week = explode(',',$row[11]);
$uv_line_month = explode(',',$row[12]);
$app_contents = explode(',',$row[13]);

echo $uv_doughnut[6];
?>
