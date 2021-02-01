<?
include './dbconn.php';

$conn = new DBC();
$conn->DBI();

$sql = "select rate from did_member where pos_code = '".$_SESSION['id']."'";
$conn->DBQ($sql);
$conn->DBE();
$row = $conn->DBF();

switch ($row[0]) {
  case 'B':
  ?>
  <script type="text/javascript">alert("접근 권한이 없습니다")
  window.history.back(-1); </script>
  <?
  break;
}
// switch ($row[0]) {
//   case 'A':
//   $rate = 'AB';
//   break;
//
//   case 'B':
//   $rate = 'AB';
//   if($_SERVER['PHP_SELF'] != '/stats_access.php' || $_SERVER['PHP_SELF'] != '/stats_period.php' || $_SERVER['PHP_SELF'] != '/stats_pv.php'
//   || $_SERVER['PHP_SELF'] != '/stats_store.php' || $_SERVER['PHP_SELF'] != '/stats_use.php' || $_SERVER['PHP_SELF'] != '/dashboard.php') {
    ?>
    <!-- <script type="text/javascript">alert("접근 권한이 없습니다")
	  window.history.back(-1); </script> -->
    <?
  // }
  // break;
  //
  // case 'C':
  // $rate = 'C';
  // if($_SERVER['PHP_SELF'] != '/stats_access.php' || $_SERVER['PHP_SELF'] != '/stats_period.php' || $_SERVER['PHP_SELF'] != '/stats_pv.php'
  // || $_SERVER['PHP_SELF'] != '/stats_store.php' || $_SERVER['PHP_SELF'] != '/stats_use.php' || $_SERVER['PHP_SELF'] != '/dashboard.php') {
    ?>
    <!-- <script type="text/javascript">alert("접근 권한이 없습니다")
	  window.history.back(-1); </script> -->
    <?
//   }
//   break;
// }
?>
