<?php
include '../../layout/layout.php';
include '../dbconn.php';
include '../selectbox.php';


$conn = new DBC();
$conn->DBI();
$pos_code = $_GET['pos_code'];
$condition = $_GET["condition2"];

if ($_GET['condition2'] == '미설치매장') {
  $sql = "SELECT        c.pos_code AS '매장코드', c.CHANNEL AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
  c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
  FROM did_pos_code AS `c`
  left OUTER JOIN
  (
    SELECT UPPER(pos_exec.pos_id)AS `pos_id`,min(pos_exec.TIMESTAMP) AS `time`, max(pos_exec.TIMESTAMP) AS `time2`
    FROM
    did_log_type_1 AS `pos_exec`
    WHERE
    pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
    GROUP BY pos_exec.pos_id
  )AS `d`
  ON d.pos_id = c.pos_code
  where c.pos_code = '".$pos_code."'";}
  else {
    $sql =
    "SELECT
    c.pos_code as '코드', d.pos_id AS '매장코드', c.CHANNEL AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
    c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
    FROM
    (
      SELECT UPPER(pos_exec.pos_id)AS `pos_id`,min(pos_exec.TIMESTAMP) AS `time`, max(pos_exec.TIMESTAMP) AS `time2`
      FROM
      did_log_type_1 AS `pos_exec`
      WHERE
      pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
      GROUP BY pos_exec.pos_id
    )AS `d`
    left OUTER JOIN
    did_pos_code AS `c`
    ON d.pos_id = c.pos_code
    where c.pos_code = '".$pos_code."'";}
    $conn->DBQ($sql);
    $conn->DBE();
$row = $conn->DBF();
$layout = new Layout;
?>
<script type="text/javascript">

// 공백
function white() {

  var idw= document.getElementById("운영자명").value;
  if(idw==""){
    alert("운영자명를 입력해주세요");
    document.getElementById('운영자명').focus();
    return false;
  }
  var pww= document.getElementById("매장명").value;
  if(pww==""){
    alert("매장명를 입력해주세요");
    document.getElementById('매장명').focus();
    return false;
  }
  var pwwc= document.getElementById("운영자코드").value;
  if(pwwc==""){
    alert("운영자코드를 입력해주세요");
    document.getElementById('운영자코드').focus();
    return false;
  }
  var namew= document.getElementById("매장주소").value;
  if(namew==""){
    alert("매장주소를 입력해주세요");
    document.getElementById('매장주소').focus();
    return false;
  }

  var pattern = /[select|union|insert|update|delete|drop|\"|\'|#|\/\*|\*\/|\\\|\;]/g;
  if(pattern.test(document.getElementById('운영자명').value)){
    alert("운영자명에 입력 불가능한 문자가 있습니다.");
    document.getElementById('운영자명').focus();
    return false;

  }

  if(pattern.test(document.getElementById('매장명').value)){
    alert("메장명에 입력 불가능한 문자가 있습니다.");
    document.getElementById('매장명').focus();
    return false;
  }

//숫자만 입력
  if(pattern.test(document.getElementById('운영자코드').value)){
    alert("운영자코드에 입력 불가능한 문자가 있습니다.");
    document.getElementById('운영자코드').focus();
    return false;
  }

//특수문자  * ; & 만 입력 제거

  if(pattern.test(document.getElementById('매장주소').value)){
    alert("매장주소에 입력 불가능한 문자가 있습니다.");
    document.getElementById('매장주소').focus();
    return false;
  }
}

// 글자수(60byte)제한
function fnChkByte(obj) {
  var maxByte = 60; //최대 입력 바이트 수
  var str = obj.value;
  var str_len = str.length;

  var rbyte = 0;
  var rlen = 0;
  var one_char = "";
  var str2 = "";

  for (var i = 0; i < str_len; i++) {
    one_char = str.charAt(i);

    if (escape(one_char).length > 4) {
      rbyte += 2; //한글2Byte
    } else {
      rbyte++; //영문 등 나머지 1Byte
    }

    if (rbyte <= maxByte) {
      rlen = i + 1; //return할 문자열 갯수
    }
  }

  if (rbyte > maxByte) {
    str2 = str.substr(0, rlen); //문자열 자르기
    obj.value = str2;
    fnChkByte(obj, maxByte);
  } else {
    document.getElementById('byteInfo').innerText = rbyte;
  }
}
// 글자수(6byte)제한
function fnChkByte6(obj) {
  var maxByte = 6; //최대 입력 바이트 수
  var str = obj.value;
  var str_len = str.length;

  var rbyte = 0;
  var rlen = 0;
  var one_char = "";
  var str2 = "";

  for (var i = 0; i < str_len; i++) {
    one_char = str.charAt(i);

    if (escape(one_char).length > 4) {
      rbyte += 2; //한글2Byte
    } else {
      rbyte++; //영문 등 나머지 1Byte
    }

    if (rbyte <= maxByte) {
      rlen = i + 1; //return할 문자열 갯수
    }
  }

  if (rbyte > maxByte) {
    str2 = str.substr(0, rlen); //문자열 자르기
    obj.value = str2;
    fnChkByte(obj, maxByte);
  } else {
    document.getElementById('byteInfo').innerText = rbyte;
  }
}

</script>

<!doctype html>
<html class="no-js" lang="kr">
<?$layout->CssJsFile('<link href="assets/css/table-responsive.css" rel="stylesheet">');?>
<?$layout->head($head);?>

<body class="body-bg">
  <!-- preloader area start -->
  <div id="preloader">
    <div class="loader"></div>
  </div>
  <!-- preloader area end -->

  <!-- main wrapper start -->
  <div class="horizontal-main-wrapper">
    <?$layout->mainHeader($mainHeader);?>
    <?$layout->header($header);?>
    <!-- page title area end -->
    <div class="main-content-inner">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 mt-4"><h5>매장관리 상세보기</h5></div>
          <div class="col-lg-6 mt-4" style="text-align: right;"><small>  Main > 매장관리 상세보기</small></div>
          <html><hr style="margin:1px; "color="black" width=99%></html>
          <!---->
            <div class="card col-lg-12 mt-3">
              <div class="card-body">
                <form action="modify.php" id="submit_form" onsubmit="return white();" method="post">
                  <input type="hidden" name="매장코드" value="<?echo $pos_code;?>">
                  <input type="hidden" name="상태" value="<?echo $condition;?>">
                  <div class="row">
                    <!-- 영업담당 -->
                    <div class="col-lg-12 mt-3">
                      <h5><strong><font color="blue">영업담당</font></strong></h5></div>
                      <html><hr color="" width=99%></html>
                      <div class="col-lg-2">
                        <span style='font-weight: 550;'>영업담당</span>
                      </div>
                      <div class="col-lg-3">
                        <select name="영업담당" style="background-color: #E9ECEF" class="form-control form-control-sm col-sm-7" id="selectID">
                          <option value="">선택</option>
                          <?
                          $query = "SELECT channel FROM did_pos_code GROUP BY CHANNEL";
                          $conn->DBQ($query);
                          $conn->DBE(); //쿼리 실행
                          while ($option = $conn->DBF()) {  ?>
                            <option <?if($row['영업담당'] == $option['channel']){echo "selected";}?> value="<?echo $option['channel'];?>"><?echo $option['channel'];?></option>
                            <?}?>
                        </select>
                      </div>
                      <!-- 지원팀 -->
                      <div class="col-lg-2">
                        <span style='font-weight: 550;'>지원팀</span>
                      </div>
                      <div class="col-lg-3">
                        <select style="background-color: #E9ECEF" name="지원팀" class="form-control form-control-sm col-sm-7" id="good">
                          <option value="">전체</option>
                          <?
                            $query = "SELECT bg_code FROM did_pos_code where channel = '".$row['영업담당']."' GROUP BY bg_code";
                            $conn->DBQ($query);
                            $conn->DBE();
                            while ($option1 = $conn->DBF()) {  ?>
                              <option <?if($row['지원팀'] == $option1['bg_code']){echo "selected";}?> value="<?echo $option1['bg_code'];?>"><?echo $option1['bg_code'];?></option>
                              <?} ?>
                        </select>
                        </div>
                        <!--밑줄-->
                        <html><hr color="black" width=99%></html>
                        <!-- 투자 유형 -->
                        <div class="col-lg-5">
                          <!-- 대리점 정보 -->
                          <h5><strong><font color="blue">운영자 정보</font></strong></h5>
                          <html><hr color="" width=99%></html></div>
                          <div class="col-lg-7">
                            <h5><strong><font color="blue">매장 정보</font></strong></h5>
                            <html><hr color="" width=99%></html></div>
                            <div class="col-lg-2">
                              <span style='font-weight: 550;'>운영자명</span></div>
                              <div class="col-lg-3">
                                <input name="운영자명" style="background-color: #E9ECEF" id='운영자명'  type="text" class="col-sm-7 form-control form-control-sm" value="<?echo $row['운영자명'];?>" onkeyup=" fnChkByte(this);"></div>
                                <div class="col-lg-2">
                                  <span style='font-weight: 550;'>매장명</span></div>
                                  <div class="col-lg-3">
                                    <input name="매장명" style="background-color: #E9ECEF" id='매장명' type="text" class="col-sm-7 form-control form-control-sm" value="<?echo $row['매장명'];?>" onkeyup="fnChkByte(this);"></div>
                                    <div class="col-lg-5">
                                      <html><hr color="" width=99%></html></div>
                                      <div class="col-lg-7">
                                        <html><hr color="" width=99%></html></div>
                                        <div class="col-lg-2">
                                          <span style='font-weight: 550;'>운영자코드</span></div>
                                          <div class="col-lg-3">
                                            <input name="운영자코드" style="background-color: #E9ECEF" id='운영자코드' type="text" class="col-sm-7 form-control form-control-sm" value="<?echo $row['운영자코드'];?>"  onkeyup="fnChkByte6(this);"></div>
                                            <div class="col-lg-2">
                                              <span style='font-weight: 550;'>매장코드</span></div>
                                              <div class="col-lg-3">
                                                <span><?echo $row['pos_code'];?></span></div>
                                                <div class="col-lg-5">
                                                  <html><hr color="" width=99%></html></div>
                                                  <div class="col-lg-7">
                                                    <html><hr color="" width=99%></html></div>
                                                    <div class="col-lg-5 mt-5">
                                                      <h5><strong><font color="blue">투자유형</font></strong></h5>
                                                      <html><hr color="" width=99%></html></div>
                                                      <div class="col-lg-2">
                                                        <span style='font-weight: 550;'>매장주소</span></div>
                                                        <div class="col-lg-5">
                                                          <textarea  style="background-color: #E9ECEF" name="매장주소" id='매장주소' rows="5" style="resize: none;"  type="text" class="col-sm-11 form-control form-control-sm"  ><?echo $row['매장주소'];?></textarea></div>
                                                          <div style=""class="col-lg-2">
                                                            <span style='font-weight: 550;'>투자유형</span></div>
                                                            <div class="col-lg-3">
                                                              <select style="background-color: #E9ECEF"  name="투자유형" class="form-control form-control-sm col-sm-7">
                                                                <?
                                                                $query = "SELECT mg_code FROM did_pos_code GROUP BY mg_code";
                                                                $conn->DBQ($query);
                                                                $conn->DBE(); //쿼리 실행
                                                                while ($option = $conn->DBF()) {  ?>
                                                                  <option <?if($row['투자유형'] == $option['mg_code']){echo "selected";}?> value="<?echo $option['mg_code'];?>"><?echo $option['mg_code'];?></option>
                                                                  <?}?>
                                                              </select></div>
                                                              <div class="col-lg-7"></div>
                                                              <div class="col-lg-5">
                                                                <html><hr color="" width=99%></html></div>
                                                                <div class="col-lg-7">
                                                                  <html><hr color="" width=99%></html></div>
                                                                  <div class="col-lg-5">
                                                                    <h5><strong><font color="blue">상태</font></strong></h5></div>
                                                                    <!-- 등록일 -->
                                                                    <div class="col-lg-7">
                                                                      <h5><strong><font color="blue">등록일</font></strong></h5></div>
                                                                      <div class="col-lg-5">
                                                                        <html><hr color="" width=99%></html></div>
                                                                        <div class="col-lg-7">
                                                                          <html><hr color="" width=99%></html></div>
                                                                          <div class="col-lg-2">
                                                                            <span style='font-weight: 550;'>인증매장여부</span></div>
                                                                            <div class="col-lg-3">
                                                                              <span>
                                                                                <?if($_GET['condition2'] == '미설치매장'  )
                                                                                {
                                                                                  echo "미설치매장";
                                                                                }
                                                                                else {
                                                                                  echo "인증매장";
                                                                                }?></span></div>
                                                                                <div class="col-lg-2">
                                                                                  <span style='font-weight: 550;'>등록일</span></div>
                                                                                  <div class="col-lg-3">
                                                                                  <?echo substr($row[등록일],0,4);?>-<?echo substr($row[등록일],4,2);?>-<? echo substr($row[등록일],6,2);?></div>
                                                                                  </div>
                                                                                  <!--밑줄 -->
                                                                                  <html><hr color="black" width=99%></html>
                                                                                  <!--리셋-->
                                                                                  <div class="input-group">
                                                                                    <div class="col-lg-2 container">
                                                                                      <button class="btn btn-lg mr-2 btn btn-xs" type="reset" value=""><a href="store.php">취소</a></button>
                                                                                      <button class="btn btn-primary btn btn-xs" style="text-align:center;"   type="" id="searchButton">수정</button>
                                                                                    </div>
                                                                                  </div>
                                                                                </form>
                                                                              </div>
                                                                            </div>
                                                                        </div>
                                                                      </div>
                                                                      <!-- <div class="row mt-2"> -->
                                                                    </div>
                                                                    <!-- <div class="container"> -->
                                                                  </div>
                                                                  <!-- <div class="main-content-inner"> -->
                                                                </div>
                                                                <!-- main content area end -->
                                                                <?$layout->footer($footer);?>
                                                                <!-- main wrapper end -->
                                                                <?$layout->JsFile("");?>
                                                                <?$layout->js($js);?>


                                                              </body>

                                                              </html>
