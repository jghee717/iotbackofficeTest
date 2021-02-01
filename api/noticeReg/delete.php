<?
require_once '../dbconn.php';
$conn = new DBC();

$no = $_POST['delete_no'];
try{
  $conn->DBI();

  $sql = "delete from did_notice where idx = '".$no."'";
  $conn->DBQ($sql);
  $conn->DBE();
  ?>
  <script type="text/javascript">window.location.href="../../notice.php"</script>
  <?
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>
