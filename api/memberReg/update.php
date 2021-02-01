<?
require_once '../dbconn.php';
$conn = new DBC();

if(isset($_POST['rate'])) {
  $rate = $_POST['rate'];
}
if(isset($_POST['pw'])) {
  $pw = $_POST['pw'];
}
if(isset($_POST['no'])) {
  $no = $_POST['no'];
}

try{
  $conn->DBI();

  if($pw == null) {
    $sql = "update did_member set rate = '".$rate."' where pos_code = '".$no."'";
    $conn->DBQ($sql);
    $conn->DBE();
  } else if ($pw != null) {
    $sql = "update did_member set rate = '".$rate."', pw = '".md5($pw)."' where pos_code = '".$no."'";
    $conn->DBQ($sql);
    $conn->DBE();
  }
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>
