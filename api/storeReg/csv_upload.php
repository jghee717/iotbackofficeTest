<?php
include '../dbconn.php';

$conn = new DBC();
$conn->DBI();



// 저장될 디렉토리
$upfile_dir = "../../data";

//CSV데이타 추출시 한글깨짐방지
//setlocale(LC_CTYPE, 'ko_KR.utf8');
setlocale(LC_CTYPE, 'ko_KR.eucKR'); // CSV 한글 깨짐 문제

//장시간 데이터 처리될경우
set_time_limit(0);

echo ('<meta http-equiv="content-type" content="text/html; charset=utf-8">');
$f = 0;
$upfile_name = array();
$upfile_type = array();
$upfile_size = array();
$upfile_tmp = array();
$uploadfile = array();

while(isset($_FILES['upfile']['name'][$f])) {
$upfile_name[] = $_FILES['upfile']['name'][$f]; // 파일이름
$upfile_type[] = $_FILES['upfile']['type'][$f]; // 확장자
$upfile_size[] = $_FILES['upfile']['size'][$f]; // 파일크기
$upfile_tmp[]  = $_FILES['upfile']['tmp_name'][$f]; // 임시 디렉토리에 저장된 파일명
$uploadfile[] = $uploaddir . $_FILES['userfile']['name'][$f];

//확장자 확인
if(preg_match("/(\.(csv|CSV))$/i",$upfile_name[$f])) {
} else {
  echo ("<script>window.alert('업로드를 할수 없는 파일 입니다.\\n\\r확장자가 csv 인경우만 업로드가 가능합니다.'); history.go(-1) </script>");
  exit;
}

if ($upfile_name[$f]){
  //폴더내에 동일한 파일이 있는지 검사하고 있으면 삭제
  if (file_exists("{$upfile_dir}/{$upfile_name[$f]}") ) { unlink("{$upfile_dir}/{$upfile_name[$f]}"); }

  if (!$upfile) {
    //echo ("<script>window.alert('지정된 용량(2M)을 초과'); history.go(-1) </ script>");
    // exit;
  }

  if ( strlen($upfile_size[$f]) < 7 ) {
    $filesize = sprintf("%0.2f KB", $upfile_size[$f]/1000);
  } else{
    $filesize = sprintf("%0.2f MB", $upfile_size[$f]/1000000);
  }

  if (move_uploaded_file($upfile_tmp[$f],"{$upfile_dir}/{$upfile_name[$f]}")) {
  } else {
    echo ("<script>window.alert('디렉토리에 복사실패'); history.go(-1) </script>");
    exit;
  }
}


// 저장된 파일을 읽어 들인다
$csvLoad  = file("{$upfile_dir}/{$upfile_name[$f]}");
// 행으로 나누어서 배열에 저장
$csvArray = explode("\r\n",implode($csvLoad));        // 문장의 끝라인은 \r\n 입니다. (2014-11-14 RYO)

// 행으로 나눠진 배열 갯수 만큼 돌린다($csvArray[0]에는 필드 이름이 있으므로 $i는 1번 부터 시작하고 총 갯수는 $csvArray에서 1를 뺌니다
for($i=1;$i<count($csvArray) - 1 ; $i++){
    // 각 행을 콤마를 기준으로 각 필드에 나누고 DB입력시 에러가 없게 하기위해서 addslashes함수를 이용해 \를 붙입니다
    $csvArray[$i] = iconv("euc-kr", "utf-8", $csvArray[$i]); // CSV 한글 깨짐 문제 2014-11-14 해피정닷컴
    $field = explode(",",addslashes($csvArray[$i]));



    // 나누어진 각 필드에 앞뒤에 공백을 뺸뒤 ''따옴표를 붙이고 ,콤마로 나눠서 한줄로 만듭니다.
    $value = "'" . trim(implode("','",$field)) . "'";
    $value = iconv("euc-kr", "utf-8", $value);  // CSV 한글 깨짐 문제 2014-11-14 해피정닷컴
    // $deleteSQL = "delete from did_pos_code where pos_code = '".$field[6]."' ";
    // $conn->DBQ($deleteSQL);
    // $conn->DBE();

    $query_check = "select * from did_pos_code where pos_code='".$field[6]."'  ";
        //echo $query_check ."<br><br>";
        $conn->DBQ($query_check);
        $conn->DBE();
        $data_check = $conn->DBF();
        $isset_check = $data_check["pos_code"];

        if(isset($isset_check)) { // 자료 있을때
  } else {
      $insertSQL = sprintf("insert into did_pos_code ( channel, bg_code, mg_code, agency_name, agency_code, pos_name, pos_code, pos_address) values ('".$field[0]."', '".$field[1]."', '".$field[2]."','".$field[3]."','".$field[4]."','".$field[5]."','".$field[6]."','".$field[7]."')",
       "did_pos_code" , $csvArray[0],
       $value);
        $conn->DBQ($insertSQL);
        $conn->DBE();
}
}
// 입력이 된후 업로드된 파일을 삭제한다
unlink("{$upfile_dir}/{$upfile_name[$f]}");

$f++;
}

?>
 <script type="text/javascript">alert("등록되었습니다.");  history.go(-1)</script>
