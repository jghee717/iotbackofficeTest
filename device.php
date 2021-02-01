<?php
include 'layout/layout.php';
include 'api/dbconn.php';
include 'api/pageClass.php';
include 'api/selectbox.php';

// DB 접속
$conn = new DBC();
$conn->DBI();
// 레이아웃 생성
$layout = new Layout;
//검색 쿼리 초기화
$searchSql;
//정렬 변수 생성
if(isset($_GET['order']))
{
	$order = $_GET['order'];
} else
{
	$order = "desc";
}

//검색
if($_GET['channel'] != "" && $_GET['search'] == "전체" && $_GET['bg_code'] == "" && $_GET['search_text'] != "")
{
	$channel = $_GET['channel'];
	$search = $_GET['search'];
  $text = $_GET['search_text'];
	$sCase = 7;
}
else if($_GET['channel'] != "" && $_GET['search'] != "" && $_GET['bg_code'] == "" && $_GET['search_text'] != "")
{
	$channel = $_GET['channel'];
	$search = $_GET['search'];
  $text = $_GET['search_text'];
	$sCase = 8;
}
else if($_GET['channel'] != "" && $_GET['bg_code'] == "")
{
	$channel = $_GET['channel'];
	$sCase = 6;
}
else if($_GET['channel'] != "" && $_GET['search'] == "전체" && $_GET['bg_code'] != "" && $_GET['search_text'] != "")
{
	$channel = $_GET['channel'];
	$bg_code = $_GET['bg_code'];
  $search = $_GET['search'];
  $text = $_GET['search_text'];
	$sCase = 5;
}

else if($_GET['search'] == "전체" && $_GET['search_text'] != "")
{
	$search = $_GET['search'];
  $text = $_GET['search_text'];
	$sCase = 4;
}

else if($_GET['channel'] != "" && $_GET['search'] != "" && $_GET['bg_code'] != "" && $_GET['search_text'] != "")
{
	$channel = $_GET['channel'];
	$bg_code = $_GET['bg_code'];
  $search = $_GET['search'];
  $text = $_GET['search_text'];
	$sCase = 3;
}
else if($_GET['channel'] != "" && $_GET['bg_code'] != "")
{
	$channel = $_GET['channel'];
	$bg_code = $_GET['bg_code'];
	$sCase = 2;
}
else if($_GET['search'] != "" && $_GET['search_text'] != ""){
	$search = $_GET['search'];
	$text = $_GET['search_text'];
	$sCase = 1;
}
//검색 스위치
switch($sCase)
{
	case 1:
	$searchSql = ' where '.$search.' like "%'.$text.'%" ';
	break;

	case 2:
	$searchSql = ' where REPLACE(CHANNEL,"홈/미디어","스마트홈") = "' .$channel. '" and bg_code = "' .$bg_code. '" ';
	break;

	case 3:
	$searchSql = ' where REPLACE(CHANNEL,"홈/미디어","스마트홈") = "' .$channel. '" and bg_code = "' .$bg_code. '" and '.$search.' like "%' .$text. '%" ';
	break;

	case 4:
	$searchSql = ' where a.agency_name like "%' .$text. '%" or a.agency_code like "%' .$text. '%" or a.pos_name like "%' .$text. '%" or
	a.pos_code like "%' .$text. '%" ';
	break;

	case 5:
	$searchSql = ' where REPLACE(CHANNEL,"홈/미디어","스마트홈") = "' .$channel. '" and bg_code = "' .$bg_code. '" and
	(a.agency_name like "%' .$text. '%" or a.agency_code like "%' .$text. '%" or a.pos_name like "%' .$text. '%" or
	 a.pos_code like "%' .$text. '%") ';
	break;
	case 6:
	$searchSql = ' where REPLACE(CHANNEL,"홈/미디어","스마트홈") = "' .$channel. '" ';
	break;
	case 7:
	$searchSql = ' where REPLACE(CHANNEL,"홈/미디어","스마트홈") = "' .$channel. '" and
	(a.agency_name like "%' .$text. '%" or a.agency_code like "%' .$text. '%" or a.pos_name like "%' .$text. '%" or
	 a.pos_code like "%' .$text. '%") ';
	break;
	case 8:
	$searchSql = ' where REPLACE(CHANNEL,"홈/미디어","스마트홈") = "' .$channel. '" and '.$search.' like "%' .$text. '%" ';
	break;
}


// 디바이스 ID, 최종접속일 배열 선언
$sql = "SELECT REPLACE(CHANNEL,'홈/미디어','스마트홈') AS '영업담당'
, bg_code AS '지원팀'
, a.agency_name AS '운영자명'
, a.agency_code AS '운영자코드'
, a.pos_name AS '매장명'
, a.pos_code AS '매장코드'
, a.pos_address AS '매장주소'
, COUNT(DISTINCT b.device_id) AS '등록수'
, b.device_id AS '디바이스'
, MAX(updated_at) AS '최종접속일'
FROM did_pos_code AS a
LEFT JOIN
(
	SELECT pos_id,device_id,MAX(timestamp) AS updated_at
	FROM did_log_type_1
	WHERE pos_id IS NOT NULL AND pos_id != '' AND pos_id != 'P123456'
	GROUP BY pos_id, device_id
) AS b
ON a.pos_code = b.pos_id ".$searchSql." GROUP BY a.pos_code, b.device_id ORDER BY MAX(updated_at) $order,channel,bg_code,a.agency_name,a.pos_name";

$conn->DBQ($sql);
$conn->DBE();

$count1 = 0;
$count2 = 0;
$count4 = 0;
$device_id_array = array();
$device_day_array = array();
while($row2 = $conn->DBF())
{
	$device_id_array2[$count1] = $row2[디바이스];
	$device_day_array2[$count1] = $row2[최종접속일];
	$count1++;
	}


$sql = "SELECT REPLACE(CHANNEL,'홈/미디어','스마트홈') AS '영업담당'
, bg_code AS '지원팀'
, a.agency_name AS '운영자명'
, a.agency_code AS '운영자코드'
, a.pos_name AS '매장명'
, a.pos_code AS '매장코드'
, a.pos_address AS '매장주소'
, COUNT(DISTINCT b.device_id) AS '등록수'
, b.device_id AS '디바이스'
, MAX(updated_at) AS '최종접속일'
FROM did_pos_code AS a
LEFT JOIN
(
	SELECT pos_id,device_id,Max(timestamp) AS updated_at
	FROM did_log_type_1
	WHERE pos_id IS NOT NULL AND pos_id != '' AND pos_id != 'P123456'
	GROUP BY pos_id, device_id
) AS b
ON a.pos_code = b.pos_id ".$searchSql." GROUP BY a.pos_code ORDER by MAX(updated_at) $order,channel,bg_code,a.agency_name,a.pos_name";

$conn->DBQ($sql);
$conn->DBE();

$pos_name_array[] = array();
$device_num_array[] = array();

while($row1 = $conn->DBF())
{
	$count3 = 0;
	if($row1[등록수] == 0)
	{
		$count4++;
	}
	while($count3 < $row1[등록수])
	{

		$device_id_array[$count2][$count3] = $device_id_array2[$count4];
		$device_day_array[$count2][$count3] = $device_day_array2[$count4];
		$count3++;
		$count4++;
	}
	$count2++;

}


//페이징
$query = "SELECT REPLACE(CHANNEL,'홈/미디어','스마트홈') AS '영업담당'
, bg_code AS '지원팀'
, a.agency_name AS '운영자명'
, a.agency_code AS '운영자코드'
, a.pos_name AS '매장명'
, a.pos_code AS '매장코드'
, COUNT(DISTINCT b.device_id) AS '등록수'
FROM did_pos_code AS a
LEFT JOIN
(
	SELECT pos_id,device_id,Max(timestamp) AS updated_at
	FROM did_log_type_1
	WHERE pos_id IS NOT NULL AND pos_id != '' AND pos_id != 'P123456'
	GROUP BY pos_id, device_id
) AS b
ON a.pos_code = b.pos_id ".$searchSql." GROUP BY a.pos_code";
$conn->DBQ($query);
$conn->DBE();
$cnt = $conn->resultRow();
$total_row = $cnt;
if(isset($_GET['list']))
{
	$list = $_GET['list'];
} else
{
	$list = 10;
}
$block = 5;
$page_count = $_GET['page'];
$page = new paging($_GET['page'], $list, $block, $total_row);

if(isset($channel) or isset($bg_code) or isset($search) or isset($text)or isset($order) or isset($list))
{
  $page->setUrl("channel=".$channel."&bg_code=".$bg_code."&search=".$search."&search_text=".$text."&order=".$order."&list=".$list);
}
$limit = $page->getVar("limit");

$page->setDisplay("prev_btn", "<");
$page->setDisplay("next_btn", ">");
$page->setDisplay("end_btn", ">>");
$page->setDisplay("start_btn", "<<");
$page->setDisplay("class","page-item");
$page->setDisplay("full");
$paging = $page->showPage();
?>
<script>
//인젝션 정규식
function nospecialKey()
{
	var re = /select|union|insert|update|delete|drop|[\'\"|#|\/\*|\*\/|\\\|\;]/gi;
	var input=$("#search_text").val();
	if(re.test(input) != false)
	{
		alert("입력 불가능한 문자가 있습니다.");
		$("#search_text").focus();
		return false;
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
    <div class="loader">
		</div>
	</div>
	<!-- preloader area end -->

	<!-- main wrapper start -->
	<div class="horizontal-main-wrapper">
		<?$layout->mainHeader($mainHeader);?>
		<?$layout->header($header);?><br>
		<!-- page title area end -->

		<div class="main-content-inner">
			<div class="container">
				<div class="row">
					<div class="col-lg-6"><h5>디바이스 관리</h5></div>
					<div class="col-lg-6 text-right"><small> Main > 디바이스 관리</small></div>
					<style>
					form{border:1px solid #E6E6E6;}
					hr{margin:1px;}
				</style>
				<html><hr color="black" width=100%></html>

				<div class="card col-lg-12 mt-3">
					<div class="card-body">
						<!-- form start -->
						<form name='form' class="col-lg-12" method="get" action="<?=$_SERVER['PHP_SELF']?>" onsubmit="return nospecialKey()">
							<div class="input-group">

                <!-- 영업담당 -->
                <html><hr color="#E6E6E6" class="mt-2" width=100%></html>
                <span style="margin-left:15px;" name="span" id="channel" class="form-control2 form-control-sm col-lg-1">영업담당</span>
                <div class="col-lg-2">
									<select style="background-color: #E9ECEF" name="channel" class="form-control form-control-sm" id="selectID">
										<option value="">선택</option>
										<?
										$query = "SELECT distinct(replace(CHANNEL,'홈/미디어','스마트홈')) as 'channel' FROM did_pos_code  where channel is not NULL and CHANNEL != ''";
										$conn->DBQ($query);
										$conn->DBE(); //쿼리 실행
										while ($option = $conn->DBF()) {  ?>
											<option <?if($_GET['channel'] == $option['channel']){echo "selected";}?> value="<?echo $option['channel'];?>"><?echo $option['channel'];?></option>
											<?}?>
										</select>
								</div>

                <!-- 지원팀 -->
                <span style="margin-left:15px;" name="span" id="bg_code" class="form-control2 form-control-sm col-lg-1" >지원팀</span>
								<div class="col-lg-2">
                  <select style="background-color: #E9ECEF" name="bg_code" class="form-control form-control-sm" id="good">
                    <option value="">전체</option>
										<?
										if ($_GET['channel'] != null){
										$query = "SELECT bg_code FROM did_pos_code where REPLACE(CHANNEL,'홈/미디어','스마트홈') = '".$_GET['channel']."' GROUP BY bg_code";
										$conn->DBQ($query);
										$conn->DBE();
										while ($option1 = $conn->DBF()) {  ?>
										<option <?if($_GET['bg_code'] == $option1['bg_code']){echo "selected";}?> value="<?echo $option1['bg_code'];?>"><?echo $option1['bg_code'];?></option>
									<?} }?>
                  </select>
                </div>
                </div>
                <html><hr color="#E6E6E6" width=100%></html>
                <html><hr color="#E6E6E6" class="mt-2" width=100%></html>

                <!--검색 -->

								<div class="input-group">
									<span style="margin-left:15px;" name="span" id="search" class="form-control2 form-control-sm col-lg-1" >검색어</span>
									<div class="col-lg-2">
										<select id="search" style="background-color: #E9ECEF" name="search" class="form-control form-control-sm col-lg-12">
											<option value="전체">전체</option>
											<option value="a.agency_name">운영자명</option>
											<option value="a.agency_code">운영자코드</option>
											<option value="a.pos_name">매장명</option>
											<option value="a.pos_code">매장코드</option>
										</select>
									</div>
									<div class="col-lg-4">
										<input  style="background-color: #E9ECEF" class="form-control form-control-sm" type="text"  id="search_text" name="search_text"
										value='<?if($_GET['search_text'] != null){?><?echo $_GET['search_text'];}?>'>
									</div>
								</div>
								<html><hr color="#E6E6E6" width=100%></html>

								<!--리셋-->
								<div class="input-group mt-3">
									<div class="col-lg-5">
										<button class="btn btn-lg mr-2 btn btn-xs" style="display: none" type="reset" name="btn-reset" onclick="categoryChange(this)"><i class="fa fa-refresh"></i></button></div>
                    <button class="btn btn-primary btn btn-xs" style="text-align:center;" type="submit" id="searchButton">검색</button>
									</div><br>

									<div class="row mb-2 mt-4">
										<!-- 토탈, 정렬, 리스트제한 -->
										<div class="col-lg-9"><p>total: <?echo $total_row;?></p></div>
										<div class="">
											<select class="form-control form-control-sm" id="order" name='order'>
												<option value="asc">오름차순</option>
												<option value="desc" selected>내림차순</option>
											</select>
										</div>
										<div class="col-lg-1">
											<select class="form-control form-control-sm" id="list" name='list'>
												<option value="10">10</option>
												<option value="20">20</option>
												<option value="30">30</option>
											</select>
										</div>

										<div class="form-group text-right">
                     <a href="api/deviceReg/excel.php?channel=<?echo $channel ;?>&bg_code=<?echo $bg_code;?>&search=<?echo $search;?>&search_text=<?echo $text;?>&order=<?echo $order;?>&list=<?echo $list;?>">
											<button type="button" class="btn btn-xs text-right" id="searchButton"><i class="fa fa-download"></i>데이터 저장</button></a>
										</div>

										<!-- 테이블 -->
										<div class="col-lg-12">
                      <section id="no-more-tables">
                        <br><table class="table table-bordered text-center">
                          <thead class="text-uppercase">
                            <tr style="text-align:center;">
                              <th class="numeric">NO.</th>
                              <th class="numeric"><font size="2">영업담당</th>
                              <th class="numeric">지원팀</th>
                              <th class="numeric">운영자명</th>
															<th class="numeric"><font size="2">운영자 코드</th>
                              <th class="numeric">매장명</th>
                              <th class="numeric"><font size="2">매장코드<br>(POS코드)</font></th>
                              <th class="numeric"><font size="2">등록 디바이스 수</th>
                              <th class="numeric">디바이스 ID</th>
                              <th class="numeric">최종 접속일</th>
														</tr>
                          </thead>
													<tbody id="table">
														<?
														$sql = "SELECT REPLACE(CHANNEL,'홈/미디어','스마트홈') AS '영업담당'
														, bg_code AS '지원팀'
														, a.agency_name AS '운영자명'
														, a.agency_code AS '운영자코드'
														, a.pos_name AS '매장명'
														, a.pos_code AS '매장코드'
                            , a.pos_address AS '매장주소'
														, COUNT(DISTINCT b.device_id) AS '등록수'
														, b.device_id AS '디바이스'
														, MAX(updated_at) AS '최종접속일'
														FROM did_pos_code AS a
														LEFT JOIN
														(
															SELECT pos_id,device_id,Max(timestamp) AS updated_at
															FROM did_log_type_1
															WHERE pos_id IS NOT NULL AND pos_id != '' AND pos_id != 'P123456'
															GROUP BY pos_id, device_id
														) AS b
														ON a.pos_code = b.pos_id ".$searchSql." GROUP BY a.pos_code ORDER BY MAX(updated_at) $order,channel,bg_code,a.agency_name,a.pos_name limit $limit, $list";

                            $conn->DBQ($sql);
                            $conn->DBE();

														if($_GET['page'] == null)
														{
															$i = 0;
														}
														else if($_GET['list'] == 10  && $_GET['page'] != null)
														{
															$i = $_GET['list'] * ($_GET['page'] - 1);
														}
														else if($_GET['list'] == 20 && $_GET['page'] != null)
														{
															$i = $_GET['list'] * ($_GET['page'] - 1);
														}
														else if($_GET['list'] == 30 && $_GET['page'] != null)
														{
															$i = $_GET['list'] * ($_GET['page'] - 1 );
														}

														while($device = $conn->DBF()) {
															$count5 = 1;

															?>
                            <tr style="text-align:center;">
                              <td class="numeric" data-title="NO." rowspan="<?php if($device[등록수] > 1) { echo $device[등록수]; } else { echo 1; } ?>"><?echo $i+1;?></td>
                              <td class="numeric" data-title="영업담당" rowspan="<?php if($device[등록수] > 1) { echo $device[등록수]; } else { echo 1; } ?>"><?echo $device[영업담당];?></td>
															<td class="numeric" data-title="지원팀" rowspan="<?php if($device[등록수] > 1) { echo $device[등록수]; } else { echo 1; } ?>"><?echo $device[지원팀];?></td>
															<td class="numeric" data-title="운영자명" rowspan="<?php if($device[등록수] > 1) { echo $device[등록수]; } else { echo 1; } ?>"><?echo $device[운영자명];?></td>
															<td class="numeric" data-title="운영자 코드" rowspan="<?php if($device[등록수] > 1) { echo $device[등록수]; } else { echo 1; } ?>"><?echo $device[운영자코드];?></td>
															<td class="numeric" data-title="매장명" rowspan="<?php if($device[등록수] > 1) { echo $device[등록수]; } else { echo 1; } ?>"><?echo $device[매장명];?></td>
															<td class="numeric" data-title="매장코드(POS코드)" rowspan="<?php if($device[등록수] > 1) { echo $device[등록수]; } else { echo 1; } ?>"><?echo $device[매장코드];?></td>
															<td class="numeric" data-title="등록 디바이스 수" rowspan="<?php if($device[등록수] > 1) { echo $device[등록수]; } else { echo 1; } ?>"><?echo $device[등록수];?></td>
															<td class="numeric" data-title="디바이스 ID"><? if($device_id_array[$i][0] != null) { echo $device_id_array[$i][0]; } else { echo "-"; }?></td>
															<td class="numeric" data-title="최종 접속일"><? if($device_day_array[$i][0] != null)
															{ echo substr($device_day_array[$i][0], 0, 4)."-".substr($device_day_array[$i][0], 4, 2)."-".substr($device_day_array[$i][0], 6, 2)." "
																.substr($device_day_array[$i][0], 8, 2).":".substr($device_day_array[$i][0], 10, 2).":".substr($device_day_array[$i][0], 12, 2); } else { echo "-"; }?></td>
                            </tr>
														<?
														while($count5 < $device[등록수])
														{  ?>
															<tr>
																<td class="numeric" data-title="디바이스 ID"><?echo $device_id_array[$i][$count5];?></td>
																<td class="numeric" data-title="최종 접속일"><? echo substr($device_day_array[$i][$count5], 0, 4)."-".substr($device_day_array[$i][$count5], 4, 2)."-".substr($device_day_array[$i][$count5], 6, 2)." "
																	.substr($device_day_array[$i][$count5], 8, 2).":".substr($device_day_array[$i][$count5], 10, 2).":".substr($device_day_array[$i][$count5], 12, 2);?></td>
															</tr>
														<?
														$count5++;
													}
														$i++;

													}?>








                          </tbody>
                        </table>
                      </section>
                    </div>
                  </div>
                  <!-- 테이블 -->

									<!-- form end -->

                </div>
								</form>
                <!-- <div class="card-body"> -->
								<!--페이지-->
								<ul class="pagination mb-2" style="justify-content: center;">
									<?echo $paging; ?>
								</ul>
								<!-- 엑셀버튼-->
              </div>
              <!-- <div class="card col-lg-12"> -->
            </div>
            <!-- <div class="row mt-2"> -->

          </div>
            <!-- <div class="container"> -->
          </div>
          <!-- <div class="main-content-inner"> -->
        </div>
        <!-- main wrapper end -->
  <?$layout->footer($footer);?>
  <!-- main wrapper end -->
  <?$layout->JsFile("");?>
  <?$layout->js($js);?>

	<script>
	  // 검색 기본값 선택 스크립트 //
	var order = "<?php echo $order; ?>";
	var list = "<?php echo $list; ?>";
	var channel = "<?php echo $channel; ?>";
	var search = "<?php echo $search; ?>";
	var bg_code = "<?php echo $bg_code; ?>"




  switch (order) {
    case 'asc':
      $("#order").val("asc").prop("selected", true);
      break;
    case 'desc':
      $("#order").val("desc").prop("selected", true);
      break;
		}
	switch (list) {
    case '10':
      $("#list").val("10").prop("selected", true);
      break;
    case '20':
      $("#list").val("20").prop("selected", true);
      break;
		case '30':
      $("#list").val("30").prop("selected", true);
      break;
		}
	switch (channel) {
    case '강남':
      $("#channel").val("강남").prop("selected", true);

      break;
    case '강동':
      $("#channel").val("강동").prop("selected", true);

      break;
		case '강북':
      $("#channel").val("강북").prop("selected", true);

      break;
		case '동부':
      $("#channel").val("동부").prop("selected", true);

      break;
    case '서부':
      $("#channel").val("서부").prop("selected", true);

      break;
		case '홈/미디어':
      $("#channel").val("홈/미디어").prop("selected", true);

      break;
		}
	switch (search) {
    case 'a.agency_name':
      $("#search").val("a.agency_name").prop("selected", true);
      break;
    case 'a.agency_code':
      $("#search").val("a.agency_code").prop("selected", true);
      break;
		case 'a.pos_name':
      $("#search").val("a.pos_name").prop("selected", true);
      break;
		case 'a.pos_code':
      $("#search").val("a.pos_code").prop("selected", true);
      break;
    case 'a.pos_address':
      $("#search").val("a.pos_address").prop("selected", true);
      break;

		}




		switch (bg_code) {
			case '전체':
				$("#good").val("전체").prop("selected", true);
				break;
	    case '강서소매':
	      $("#good").val("강서소매").prop("selected", true);
	      break;
			case '경인소매':
	      $("#good").val("경인소매").prop("selected", true);
	      break;
			case '남부소매':
	      $("#good").val("남부소매").prop("selected", true);
	      break;
			case '남서울소매':
	      $("#good").val("남서울소매").prop("selected", true);
	      break;
			case '수원소매':
	      $("#good").val("수원소매").prop("selected", true);
	      break;
			case '안산소매':
	      $("#good").val("안산소매").prop("selected", true);
	      break;
			case '인천소매':
	      $("#good").val("인천소매").prop("selected", true);
	      break;
			case '평택소매':
	      $("#good").val("평택소매").prop("selected", true);
	      break;
			case '강동소매':
	      $("#good").val("강동소매").prop("selected", true);
	      break;
			case '강원소매':
	      $("#good").val("강원소매").prop("selected", true);
	      break;
			case '광진소매':
	      $("#good").val("광진소매").prop("selected", true);
	      break;
			case '분당소매':
	      $("#good").val("분당소매").prop("selected", true);
	      break;
			case '서초소매':
	      $("#good").val("서초소매").prop("selected", true);
	      break;
			case '북서울소매':
	      $("#good").val("북서울소매").prop("selected", true);
	      break;
			case '서서울소매':
	      $("#good").val("서서울소매").prop("selected", true);
	      break;
			case '일산소매':
	      $("#good").val("일산소매").prop("selected", true);
	      break;
			case '중부소매':
	      $("#good").val("중부소매").prop("selected", true);
	      break;
			case '구미소매':
	      $("#good").val("구미소매").prop("selected", true);
	      break;
			case '대구경북소매':
	      $("#good").val("대구경북소매").prop("selected", true);
	      break;
			case '동대구소매매':
	      $("#good").val("동대구소매").prop("selected", true);
	      break;
			case '서대구소매':
	      $("#good").val("서대구소매").prop("selected", true);
	      break;
			case '서부경남소매':
	      $("#good").val("서부경남소매").prop("selected", true);
	      break;
			case '서부산소매':
	      $("#good").val("서부산소매").prop("selected", true);
	      break;
			case '울산소매':
	      $("#good").val("울산소매").prop("selected", true);
	      break;
			case '포항소매':
	      $("#good").val("포항소매").prop("selected", true);
	      break;
			case '광주소매':
	      $("#good").val("광주소매").prop("selected", true);
	      break;
			case '대전소매':
	      $("#good").val("대전소매").prop("selected", true);
	      break;
			case '목포도소매':
	      $("#good").val("목포도소매").prop("selected", true);
	      break;
			case '순천소매':
	      $("#good").val("순천소매").prop("selected", true);
	      break;
			case '전북소매':
	      $("#good").val("전북소매").prop("selected", true);
	      break;
			case '제주도소매':
	      $("#good").val("제주도소매").prop("selected", true);
	      break;
			case '충남소매':
	      $("#good").val("충남소매").prop("selected", true);
	      break;
			case '충북소매':
	      $("#good").val("충북소매").prop("selected", true);
	      break;
			case '강남영업':
	      $("#good").val("강남영업").prop("selected", true);
	      break;
			case '강북동부영업':
	      $("#good").val("강북동부영업").prop("selected", true);
	      break;
			case '경북영업':
	      $("#good").val("경북영업").prop("selected", true);
	      break;
			case '경인영업':
	      $("#good").val("경인영업").prop("selected", true);
	      break;

			}


  </script>

</body>

</html>
