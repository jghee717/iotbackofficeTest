<?
require_once '../dbconn.php';
$conn = new DBC();

if(isset($_POST['expose'])){
  $expose = $_POST['expose'];
}
if(isset($_POST['date_from'])){
  $date_from = $_POST['date_from'];
}
if(isset($_POST['date_to'])){
  $date_to = $_POST['date_to'];
}
if(isset($_POST['link'])){
  $link = $_POST['link'];
}
if(isset($_POST['notice_type'])){
  $notice_type = $_POST['notice_type'];
}
if(isset($_POST['title'])){
  $title = $_POST['title'];
}
if(isset($_POST['content_text'])){
  $content_text = $_POST['content_text'];
}
if(isset($_POST['content_image'])){
$content_image = $_FILES['content_image']['name'];
}

// print_r($content_image);

try{
  $conn->DBI();

  $sql = "select image from did_notice";
  $conn->DBQ($sql);
  $conn->DBE();
  while($row=$conn->DBF())
  {
    if($row['image'] == $upload)
    {
      $filename = '1'.$_FILES['content_image']['name'];
      $upload = $_SERVER['HTTP_HOST'].'/data'.'/'.$filename;
    }
    else if($_FILES['content_image']['name'] != null)
    {
      $filename = $_FILES['content_image']['name'];
      $upload = $_SERVER['HTTP_HOST'].'/data'.'/'.$filename;
    }
  }


  $sql = "select idx from did_notice";
  $conn->DBQ($sql);
  $conn->DBE();
  $lastId = $conn->lastId();

  if($_FILES['content_image']['name'] != null) {
    $tmpfile =  $_FILES['content_image']['tmp_name'];
    $content_image = $_FILES['content_image']['name'];

    $folder = "../../data/".$filename;
    move_uploaded_file($tmpfile,$folder);

  }



  switch ($_POST['compare']) {
    case 등록:
    if($content_image == null){
      $sql = "insert into did_notice (expose, start_day, end_day, link, type, title, content, date)
      values ('".$expose."','".$date_from."','".$date_to."','".$link."','".$notice_type."','".$title."','".$content_text."','".date('Y-m-d')."')";
      $conn->DBQ($sql);
      $conn->DBE();
    } else {
      $sql = "insert into did_notice (expose, start_day, end_day, link, type, title, image, date)
      values ('".$expose."','".$date_from."','".$date_to."','".$link."','".$notice_type."','".$title."','".$upload."','".date('Y-m-d')."')";
      $conn->DBQ($sql);
      $conn->DBE();
    }
    ?>
    <script type="text/javascript">window.location.href="../../notice.php"</script>
    <?
    break;

    case 수정:
    if($content_image == null){
      $sql = "update did_notice set expose = '".$expose."', start_day = '".$date_from."', end_day = '".$date_to."', link = '".$link."', type = '".$notice_type."'
      , title = '".$title."', content = '".$content_text."' where idx = '".$_POST['idx']."'";
      $conn->DBQ($sql);
      $conn->DBE();
    } else {
      $sql = "update did_notice set expose = '".$expose."', start_day = '".$date_from."', end_day = '".$date_to."', link = '".$link."', type = '".$notice_type."'
      , title = '".$title."', image = '".$upload."' where idx = '".$_POST['idx']."'";
      $conn->DBQ($sql);
      $conn->DBE();
    }
    ?>
    <script type="text/javascript">window.location.href="../../notice.php"</script>
    <?
    break;
  }

} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>
