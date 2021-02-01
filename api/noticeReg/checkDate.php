<?
include '../dbconn.php';

$conn = new DBC();
$conn->DBI();

$date_from = $_POST['date_from'];
$date_to = $_POST['date_to'];
$no = $_POST['no'];

if($no == null){
	$sql = "
	SELECT *
	FROM did_notice
	WHERE end_day >= '".$date_from."' AND start_day <= '".$date_to."' and expose = '노출'
	";
}else{
	$sql = "
	SELECT *
	FROM did_notice
	WHERE end_day >= '".$date_from."' AND start_day <= '".$date_to."' and expose = '노출'
	AND idx NOT IN('".$no."')
	";
}
$conn->DBQ($sql);
$conn->DBE();
$cnt=$conn->resultRow();
$row=$conn->DBF();

try {
		## 마무리
		$result['success']	= true;
		$result['cnt']		  = $cnt;
    $result['data']		  = "
    <strong>
      <p style='text-align:center;'>노출기간을 확인해주세요</p>
      <p style='text-align:center;'>공지사항은 하루에 최대 1개만 노출됩니다.</p><br>
    </strong>
    <p style='text-align:center;'>중복된 기간: ".$row['start_day']." ~ ".$row['end_day']."</p>
    ";


	} catch(exception $e) {
	} finally {
		echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
	}
?>
