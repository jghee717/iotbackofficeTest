<?
// 로그인
header('Content-Type: text/html; charset=UTF-8');
require_once '../dbconn.php';

$conn = new DBC();
$conn->DBI();

if($_POST['admin-id'] == ''){
  ?>
  <script type="text/javascript">alert("아이디를 입력하여 주세요.");
  window.location.href="../../index.php"</script>
  <?
  return false;
}
if($_POST['admin-pass'] == '')
{
  ?>
  <script type="text/javascript">alert("비밀번호를 입력하여 주세요.");
  window.location.href="../../index.php"</script>
  <?
  return false;
}

// echo $_POST['admin-id'];

$sql = "select * from did_member where pos_code = '".$_POST['admin-id']."'";
$conn->DBQ($sql);
$conn->DBE();
$row = $conn->DBF();

// 아이디 체크
if($row['pos_code'] == $_POST['admin-id']) {
  // 비밀번호 체크
  if(md5($_POST['admin-pass']) != $row['pw'])
  {
    ?>
    <script type="text/javascript">alert("비밀번호가 일치하지 않습니다!");
    window.location.href="../../index.php"</script>
    <?
  }
  else
  {
    $_SESSION["id"] = $_POST["admin-id"];
    // 세션 추가시 이곳에 추가
    ?>
    <script type="text/javascript">
    window.location.href="../../dashboard.php"</script>
    <?
  }

} else {
  ?>
  <script type="text/javascript">alert("올바른 아이디가 아닙니다!");
  window.location.href="../../index.php"</script>
  <?
}
?>
