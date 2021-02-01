<?php
include 'layout/layout.php';
include 'api/dbconn.php';
include 'api/pageClass.php';


// DB 접속
$conn = new DBC();
$conn->DBI();

// 레이아웃 선언
$layout = new Layout;

// 날짜 변수 초기화 & 검색조건
$today = date("Y-m-d");
if($_GET['date_from'] == null && $_GET['date_to'] == null)
{
	$temp = date('w', strtotime($today));

	switch ($temp) {
    // 일요일
    case 0:
    $date_from = date("Y-m-d", strtotime("-3 days",  strtotime($today)));
    $date_to = date("Y-m-d", strtotime("+3 days",  strtotime($today)));
    break;

    // 월요일
    case 1:
    $date_from = date("Y-m-d", strtotime("-4 days",  strtotime($today)));
    $date_to = date("Y-m-d", strtotime("+2 days",  strtotime($today)));
    break;

    // 화요일
    case 2:
    $date_from = date("Y-m-d", strtotime("-5 days",  strtotime($today)));
    $date_to = date("Y-m-d", strtotime("+1 days",  strtotime($today)));
    break;

    // 수요일
    case 3:
    $date_from = date("Y-m-d", strtotime("-6 days",  strtotime($today)));
    $date_to = date("Y-m-d", strtotime("+0 days",  strtotime($today)));
    break;

    // 목요일
    case 4:
    $date_from = date("Y-m-d", strtotime("-0 days",  strtotime($today)));
    $date_to = date("Y-m-d", strtotime("+6 days",  strtotime($today)));
    break;

    // 금요일
    case 5:
    $date_from = date("Y-m-d", strtotime("-1 days",  strtotime($today)));
    $date_to = date("Y-m-d", strtotime("+5 days",  strtotime($today)));
    break;

    // 토요일
    case 6:
    $date_from = date("Y-m-d", strtotime("-2 days",  strtotime($today)));
    $date_to = date("Y-m-d", strtotime("+4 days",  strtotime($today)));
    break;
  }

	$searchSql = " where TIMESTAMP >= '" .$date_from. "' and TIMESTAMP <= '" .$date_to. " 23:59:59' ";
}
else if($_GET['date_from'] != null && $_GET['date_to'] != null && $_GET['search_text'] != "")
{
  $date_from = $_GET['date_from'];
  $date_to = $_GET['date_to'];
	$search_text = $_GET['search_text'];
	$searchSql = " where TIMESTAMP >= '" .$date_from. "' and TIMESTAMP <= '" .$date_to. " 23:59:59'
	and (a.pos_name like '%".$_GET['search_text']."%' or a.pos_code like '%".$_GET['search_text']."%'
	or b.channel like '%".$_GET['search_text']."%' or b.bg_code like '%".$_GET['search_text']."%')";
}
else if($_GET['date_from'] != null && $_GET['date_to'] != null)
{
  $date_from = $_GET['date_from'];
  $date_to = $_GET['date_to'];
	$searchSql = " where TIMESTAMP >= '" .$date_from. "' and TIMESTAMP <= '" .$date_to. " 23:59:59' ";
}

// 직전당첨일시 배열 선언
$dayArray = array();
$posArray = array();
$sql2 = "SELECT event_check as '당첨확인', TIMESTAMP as '당첨일시', b.CHANNEL as '영업담당', b.bg_code as '지원팀',
a.pos_code as '매장코드', b.pos_name as '매장명' from did_event AS a LEFT JOIN did_pos_code AS b ON a.pos_code = b.pos_code order by TIMESTAMP desc";

$conn->DBQ($sql2);
$conn->DBE();
$count = $conn->resultRow();

while($event2 = $conn->DBF())
{
	$dayArray[$count] = $event2[당첨일시];
	$posArray[$count] = $event2[매장명];
	$count--;
}

// 페이징
$query = "SELECT count(*) FROM did_event as a left join did_pos_code as b on a.pos_code = b.pos_code".$searchSql;
$conn->DBQ($query);
$conn->DBE();
$cnt = $conn->DBF();
$total_row = $cnt['count(*)'];
if(isset($_GET['list']))
{
	$list = $_GET['list'];
} else
{
	$list = 10;
}
$block = 5;
$page = new paging($_GET['page'], $list, $block, $total_row);

if(isset($date_from) or isset($date_to) or isset($search_text) or isset($list))
{
  $page->setUrl("date_from=".$date_from."&date_to=".$date_to."&search_text=".$search_text."&list=".$list);
}

$limit = $page->getVar("limit");

$page->setDisplay("prev_btn", "<");
$page->setDisplay("next_btn", ">");
$page->setDisplay("end_btn", ">>");
$page->setDisplay("start_btn", "<<");
$page->setDisplay("class","page-item");
$page->setDisplay("full");
$paging = $page->showPage();


//배틀
$day_0502 = "2019-05-02";
$day_0508 = "2019-05-08";

if(date("Y-m-d") >= $day_0502 and date("Y-m-d") <= $day_0508)
{
	$week_from = $day_0502;
	$week_to = $day_0508;
}
else if(date("Y-m-d") >= date("Y-m-d", strtotime("+7 days",  strtotime($day_0502))) and date("Y-m-d") <= date("Y-m-d", strtotime("+7 days",  strtotime($day_0508))))
{
	$week_from = date("Y-m-d", strtotime("+7 days",  strtotime($day_0502)));
	$week_to = date("Y-m-d", strtotime("+7 days",  strtotime($day_0508)));
}
else if(date("Y-m-d") >= date("Y-m-d", strtotime("+14 days",  strtotime($day_0502))) and date("Y-m-d") <= date("Y-m-d", strtotime("+14 days",  strtotime($day_0508))))
{
	$week_from = date("Y-m-d", strtotime("+14 days",  strtotime($day_0502)));
	$week_to = date("Y-m-d", strtotime("+14 days",  strtotime($day_0508)));
}
else if(date("Y-m-d") >= date("Y-m-d", strtotime("+21 days",  strtotime($day_0502))) and date("Y-m-d") <= date("Y-m-d", strtotime("+21 days",  strtotime($day_0508))))
{
	$week_from = date("Y-m-d", strtotime("+21 days",  strtotime($day_0502)));
	$week_to = date("Y-m-d", strtotime("+21 days",  strtotime($day_0508)));
}

$sql2 = "select posCode.channel
							,posCode.all_cnt AS `매장수`
							,deviceCnt.cnt AS `디바이스수`
							,setup.cnt AS `설치수`
							,app_start.cnt AS `실행수`
							FROM (
								 SELECT *,COUNT(*)AS `all_cnt` FROM did_pos_code GROUP BY channel
							) AS `posCode`
							LEFT JOIN
							(
								 SELECT c.channel , SUM(d.cnt) AS `cnt`
								 FROM
								 (
										SELECT UPPER(pos_exec.pos_id)AS `pos_id`,COUNT(DISTINCT pos_exec.device_id)AS `cnt`
										FROM
										did_log_type_1 AS `pos_exec`
										WHERE
										pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
										GROUP BY pos_exec.pos_id,pos_exec.device_id
								 )AS `d`
								 LEFT JOIN
								 did_pos_code AS `c`
								 ON d.pos_id = c.pos_code
								 GROUP BY c.channel
							)AS `deviceCnt`
							ON
							posCode.channel = deviceCnt.channel
							LEFT JOIN
							(
								 SELECT c.channel , COUNT(d.pos_id) AS `cnt`
								 FROM
								 (
										SELECT UPPER(pos_exec.pos_id)AS `pos_id`
										FROM
										did_log_type_1 AS `pos_exec`
										WHERE
										pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
										GROUP BY pos_exec.pos_id
								 )AS `d`
								 LEFT JOIN
								 did_pos_code AS `c`
								 ON d.pos_id = c.pos_code
								 GROUP BY c.channel
							)AS `setup`
							ON
							posCode.channel = setup.channel
							LEFT JOIN
							(
								 SELECT c.channel , COUNT(d.pos_id) AS `cnt`
								 FROM
								 (
										SELECT UPPER(pos_exec.pos_id)AS `pos_id`
										FROM
										did_log_type_1 AS `pos_exec`
										WHERE
										pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
										AND
										DATE(pos_exec.timestamp) BETWEEN '".$week_from." 00:00:01' AND '".$week_to." 23:59:59'
										GROUP BY pos_exec.pos_id
								 )AS `d`
								 LEFT JOIN
								 did_pos_code AS `c`
								 ON d.pos_id = c.pos_code
								 GROUP BY c.channel
							)AS `app_start`
							ON
							posCode.channel = app_start.channel
							GROUP BY posCode.channel
							";
$conn->DBQ($sql2);
$conn->DBE();
$resCnt = $conn->resultRow();

$store = 0;
$device = 0;
$install = 0;
$execute = 0;

$row2 = $conn->DBP();


if(date("Y-m-d") >= $day_0502 and date("Y-m-d") <= $day_0508)
{
	$total_week = array();
	$channel_array = array('강남영업담당', '강동영업담당', '서부영업담당', '강북영업담당', '동부영업담당', '스마트홈부문');
	$install_array = array($row2[0]['설치수'], $row2[1]['설치수'], $row2[4]['설치수'], $row2[2]['설치수'], $row2[3]['설치수'], $row2[5]['설치수']);
	$execute_array = array($row2[0]['실행수'], $row2[1]['실행수'], $row2[4]['실행수'], $row2[2]['실행수'], $row2[3]['실행수'], $row2[5]['실행수']);
	$device_array = array($row2[0]['디바이스수'], $row2[1]['디바이스수'], $row2[4]['디바이스수'], $row2[2]['디바이스수'], $row2[3]['디바이스수'], $row2[5]['디바이스수']);
	for($total_count = 0; $total_count < 6; $total_count++)
	{
			$total_week[] = number_format((number_format(($install_array[$total_count]/$device_array[$total_count])*100, 1) + number_format(($execute_array[$total_count]/$install_array[$total_count])*100, 1))/200*100);
	}
	for($total_count = 0; $total_count < 6; $total_count++)
	{
			$total_sql = "update did_battle set week = '".$total_week[$total_count]."' where channel = '".$channel_array[$total_count]."'";
			$conn->DBQ($total_sql);
		  $conn->DBE();
	}
}
else if(date("Y-m-d") >= date("Y-m-d", strtotime("+7 days",  strtotime($day_0502))) and date("Y-m-d") <= date("Y-m-d", strtotime("+7 days",  strtotime($day_0508))))
{
	$total_week = array();
	$channel_array = array('강남영업담당', '강북영업담당', '강동영업담당', '동부영업담당', '서부영업담당', '스마트홈부문');
	$install_array = array($row2[0]['설치수'], $row2[2]['설치수'], $row2[1]['설치수'], $row2[3]['설치수'], $row2[4]['설치수'], $row2[5]['설치수']);
	$execute_array = array($row2[0]['실행수'], $row2[2]['실행수'], $row2[1]['실행수'], $row2[3]['실행수'], $row2[4]['실행수'], $row2[5]['실행수']);
	$device_array = array($row2[0]['디바이스수'], $row2[2]['디바이스수'], $row2[1]['디바이스수'], $row2[3]['디바이스수'], $row2[4]['디바이스수'], $row2[5]['디바이스수']);
	for($total_count = 0; $total_count < 6; $total_count++)
	{
			$total_week[] = number_format((number_format(($install_array[$total_count]/$device_array[$total_count])*100, 1) + number_format(($execute_array[$total_count]/$install_array[$total_count])*100, 1))/200*100);
	}
	for($total_count = 0; $total_count < 6; $total_count++)
	{
			$total_sql = "update did_battle set week_2 = '".$total_week[$total_count]."' where channel = '".$channel_array[$total_count]."'";
			$conn->DBQ($total_sql);
		  $conn->DBE();
	}
}
else if(date("Y-m-d") >= date("Y-m-d", strtotime("+14 days",  strtotime($day_0502))) and date("Y-m-d") <= date("Y-m-d", strtotime("+14 days",  strtotime($day_0508))))
{
	$total_week = array();
	$channel_array = array('강남영업담당', '동부영업담당', '강동영업담당', '서부영업담당', '스마트홈부문', '강북영업담당');
	$install_array = array($row2[0]['설치수'], $row2[3]['설치수'], $row2[1]['설치수'], $row2[4]['설치수'], $row2[5]['설치수'], $row2[2]['설치수']);
	$execute_array = array($row2[0]['실행수'], $row2[3]['실행수'], $row2[1]['실행수'], $row2[4]['실행수'], $row2[5]['실행수'], $row2[2]['실행수']);
	$device_array = array($row2[0]['디바이스수'], $row2[3]['디바이스수'], $row2[1]['디바이스수'], $row2[4]['디바이스수'], $row2[5]['디바이스수'], $row2[2]['디바이스수']);
	for($total_count = 0; $total_count < 6; $total_count++)
	{
			$total_week[] = number_format((number_format(($install_array[$total_count]/$device_array[$total_count])*100, 1) + number_format(($execute_array[$total_count]/$install_array[$total_count])*100, 1))/200*100);
	}
	for($total_count = 0; $total_count < 6; $total_count++)
	{
			$total_sql = "update did_battle set week_3 = '".$total_week[$total_count]."' where channel = '".$channel_array[$total_count]."'";
			$conn->DBQ($total_sql);
		  $conn->DBE();
	}
}
else if(date("Y-m-d") >= date("Y-m-d", strtotime("+21 days",  strtotime($day_0502))) and date("Y-m-d") <= date("Y-m-d", strtotime("+21 days",  strtotime($day_0508))))
{
	$total_week = array();
	$channel_array = array('강남영업담당', '서부영업담당', '강북영업담당', '강동영업담당', '동부영업담당', '스마트홈부문');
	$install_array = array($row2[0]['설치수'], $row2[4]['설치수'], $row2[2]['설치수'], $row2[1]['설치수'], $row2[3]['설치수'], $row2[5]['설치수']);
	$execute_array = array($row2[0]['실행수'], $row2[4]['실행수'], $row2[2]['실행수'], $row2[1]['실행수'], $row2[3]['실행수'], $row2[5]['실행수']);
	$device_array = array($row2[0]['디바이스수'], $row2[4]['디바이스수'], $row2[2]['디바이스수'], $row2[1]['디바이스수'], $row2[3]['디바이스수'], $row2[5]['디바이스수']);
	for($total_count = 0; $total_count < 6; $total_count++)
	{
			$total_week[] = number_format((number_format(($install_array[$total_count]/$device_array[$total_count])*100, 1) + number_format(($execute_array[$total_count]/$install_array[$total_count])*100, 1))/200*100);
	}
	for($total_count = 0; $total_count < 6; $total_count++)
	{
			$total_sql = "update did_battle set week_4 = '".$total_week[$total_count]."' where channel = '".$channel_array[$total_count]."'";
			$conn->DBQ($total_sql);
		  $conn->DBE();
	}
}



$sql3 = "select posCode.CHANNEL, posCode.bg_code, posCode.all_cnt AS `매장수`, deviceCnt.cnt AS `디바이스수`, setup.cnt AS `설치수` , app_start.cnt AS `실행수`
FROM (
	SELECT *,COUNT(*)AS `all_cnt` FROM did_pos_code GROUP BY bg_code ) AS `posCode`
LEFT JOIN (
	SELECT c.channel ,c.bg_code, SUM(d.cnt) AS `cnt` FROM (
		SELECT UPPER(pos_exec.pos_id)AS `pos_id`,COUNT(DISTINCT pos_exec.device_id)AS `cnt` FROM did_log_type_1 AS `pos_exec`
		WHERE pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
		GROUP BY pos_exec.pos_id,pos_exec.device_id ) AS `d`
		LEFT JOIN  did_pos_code AS `c`
		ON d.pos_id = c.pos_code
		GROUP BY c.bg_code ) AS `deviceCnt`
		ON posCode.bg_code = deviceCnt.bg_code
		LEFT JOIN (
			SELECT c.channel,c.bg_code , COUNT(d.pos_id) AS `cnt` FROM (
				SELECT UPPER(pos_exec.pos_id)AS `pos_id` FROM did_log_type_1 AS `pos_exec`
				WHERE pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
				GROUP BY pos_exec.pos_id ) AS `d`
				LEFT JOIN did_pos_code AS `c`
				ON d.pos_id = c.pos_code
				GROUP BY c.bg_code ) AS `setup`
				ON posCode.bg_code = setup.bg_code
				LEFT JOIN (
					SELECT c.channel,c.bg_code  , COUNT(d.pos_id) AS `cnt`
					FROM (
						SELECT UPPER(pos_exec.pos_id)AS `pos_id`
						FROM did_log_type_1 AS `pos_exec`
						WHERE pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != '' AND DATE(pos_exec.timestamp) BETWEEN '2019-05-01 00:00:01' AND '2019-05-31 23:59:59'
						GROUP BY pos_exec.pos_id ) AS `d`
						LEFT JOIN did_pos_code AS `c`
						ON d.pos_id = c.pos_code
						GROUP BY c.bg_code ) AS `app_start`
						ON posCode.bg_code = app_start.bg_code
						GROUP BY posCode.bg_code
						order by channel";

$install2_array = array();
$execute2_array = array();
$bg_code_array = array();
$sum_array = array();

						$conn->DBQ($sql3);
						$conn->DBE();
						while($row3=$conn->DBF())
						{
							$bg_code_array[] = $row3['bg_code'];
							if(number_format(($row3['설치수']/$row3['디바이스수'])*100,1) == nan)
							{
								$install2_array[]	= "-";
							}
							else
							{
							$install2_array[]	= number_format(($row3['설치수']/$row3['디바이스수'])*100, 1);

							}

							if(number_format(($row3['실행수']/$row3['설치수'])*100,1) == nan)
							{
								$execute2_array[]	= "-";
							}
							else
							{
								$execute2_array[] = number_format(($row3['실행수']/$row3['설치수'])*100,1);
							}

							if(number_format((number_format(($row3['설치수']/$row3['디바이스수'])*100, 1) + number_format(($row3['실행수']/$row3['설치수'])*100,1))/200*100) == nan)
							{
								$sum_array[] = "-";
							}
							else
							{
								$sum_array[] = number_format((number_format(($row3['설치수']/$row3['디바이스수'])*100, 1) + number_format(($row3['실행수']/$row3['설치수'])*100,1))/200*100);
							}
						}


						$key = max(array_keys($sum_array));



?>
<script>

//특수문자 입력 제거
function nospecialKey()
{
	var RegExp = /[select|union|insert|update|delete|drop|\"|\'|#|\/\*|\*\/|\\\|\;]/gi;
		var input = $("#search_text").val();

		if(RegExp.test(input) != false)
		{
			alert("한글 / 영문 / 숫자만 입력 가능합니다.");
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
        <div class="row mt-2">
          <div class="col-lg-6"><h5>이벤트 당첨 관리</h5></div>
          <div class="col-lg-6 text-right"><small> Main > 이벤트 당첨 관리</small></div>
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
                <!-- 당첨기간 -->
                <html><hr color="#E6E6E6" class="mt-2" width=100%></html>
                <span style="margin-left:15px;" name="span" id="span" class="form-control2 form-control-sm col-lg-1" >당첨기간</span>
								<input data-toggle="datepicker" type="text" class="form-control form-control-sm col-lg-2" style="margin-left:15px;" id="date_from" name="date_from" readonly="readonly" value="<?php echo $date_from ?>">
								<div class="input-group-prepend">
									<div class="input-group-text form-control form-control-sm">~
									</div>
								</div>
								<input data-toggle="datepicker" type="text" class="form-control form-control-sm col-lg-2" id="date_to" name="date_to" readonly="readonly" value="<?php echo $date_to ?>">
								<!-- 검색 -->
                <span style="margin-left:15px;" name="search" id="span" class="form-control2 form-control-sm col-lg-1">검색어</span>
								<div class="col-lg-5">
									<input style="background-color: #E9ECEF;" class="form-control form-control-sm" type="text" placeholder="영업담당, 지원팀, POS코드, 매장명으로 검색이 가능합니다." id="search_text" name="search_text" value="<?php echo $search_text ?>">
								</div>
								<div class="col-lg-2">
									<button class="btn btn-primary btn btn-xs" style="text-align:right;" type="submit" id="searchButton">검색</button>
								</div>
							</div>
							<html><hr color="#E6E6E6" width=100%></html>



							<!-- 테이블 -->
              <div class="row mb-2 mt-4">
								<div class="col-lg-11"><p>total: <?echo $cnt[0];?></p>
								</div>
								<div class="col-lg-1">
									<select class="form-control form-control-sm" id="list" name="list">
										<option value="10" selected>10</option>
										<option value="20">20</option>
										<option value="30">30</option>
									</select>
								</div>
								<div class="col-lg-12">
									<section id="no-more-tables">
										<br><table class="table table-bordered text-center" id="table">
											<thead class="text-uppercase">
												<tr style="text-align:center;">
													<th class="numeric">NO.</th>
													<th class="numeric">당첨일시</th>
													<th class="numeric">영업담당</th>
													<th class="numeric">지원팀</th>
													<th class="numeric">POS코드</th>
													<th class="numeric">매장명</th>
													<th class="numeric">직전당첨일시</th>
													<th class="numeric">당첨확인</th>
												</tr>
											</thead>
											<tbody>
												<?
												$sql = "SELECT idx, event_check as '당첨확인', TIMESTAMP as '당첨일시', b.CHANNEL as '영업담당', b.bg_code as '지원팀',
												a.pos_code as '매장코드', a.pos_name as '매장명' from did_event AS a LEFT JOIN did_pos_code AS b ON a.pos_code = b.pos_code ".$searchSql."
												order by TIMESTAMP desc LIMIT $limit, $list";


												$conn->DBQ($sql);
												$conn->DBE();

												if($_GET['page'] == null)
												{
													$a = 0;
												}
												else if($_GET['list'] == 10  && $_GET['page'] != null)
												{
													$a = $_GET['list'] * ($_GET['page'] - 1);
												}
												else if($_GET['list'] == 20 && $_GET['page'] != null)
												{
													$a = $_GET['list'] * ($_GET['page'] - 1);
												}
												else if($_GET['list'] == 30 && $_GET['page'] != null)
												{
													$a = $_GET['list'] * ($_GET['page'] - 1 );
												}

												while($event = $conn->DBF()) {
													?>
													<tr style="text-align:center;">
														<td class="numeric" data-title="NO."><?php echo $a+1; ?></td>
														<td class="numeric" data-title="당첨일시"><?php echo $event[당첨일시]; ?></td>
														<td class="numeric" data-title="영업담당"><?php echo $event[영업담당]; ?></td>
														<td class="numeric" data-title="지원팀"><?php echo $event[지원팀]; ?></td>
														<td class="numeric" data-title="POS코드"><?php echo $event[매장코드]; ?></td>
														<td class="numeric" data-title="매장명"><?php echo $event[매장명]; ?></td>
														<td class="numeric" data-title="직전당첨일시">
															<?php
															for($i = count($posArray); $i > 0; $i--)
															{
																if($posArray[$event['idx']] == $posArray[$i] and $event['idx'] > $i)
																{
																	echo $dayArray[$i];
																	break;
																}
																else if(($posArray[$event['idx']] != $posArray[$i] or $posArray[$event['idx']] == $posArray[$i]) and $i == 1)
																{
																	echo "최초 당첨";
																	break;
																}
															}
															?>
														</td>
														<td class="numeric" data-title="당첨확인"><?php echo $event[당첨확인]; ?></td>
													</tr>
													<?
												$a++;
											}
													?>
												</tbody>
											</table>
										</section>

									</div>

								</form>
								<!-- form end -->
							</div>
							<!-- <div class="card-body"> -->
							<div class="form-group text-right mt-2" style="text-align:right; margin: right;">
								<a href="event_winner.php"><button class="btn btn-primary btn btn-xs text-right" type="submit">당첨자 명단보기</button>
								</div>
						</div>
						<!-- <div class="card col-lg-12"> -->
						<!--페이지-->
						<ul class="pagination" style="justify-content: center;">
							<?echo $paging; ?>
						</ul>
						<div class="form-group text-right mt-2" style="text-align:right; margin: right;"></div>
						<!-- <div class="row mt-2"> -->
					</div>
					<!-- <div class="container"> -->
				</div>
				<!-- <div class="main-content-inner"> -->
			</div>
			<!-- main wrapper end -->
		</div>
		<?$layout->footer($footer);?>
		<!-- main wrapper end -->
		<?$layout->JsFile("");?>
		<?$layout->js($js);?>
   <!-- 리스트수 선택 보존 -->
		<script>
		var list = "<?php echo $list; ?>";


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
	</script>

	<!-- 데이트 피커 -->
	<script>
	$(function() {
		$('[data-toggle = "datepicker"]').datepicker({
			autoHide: true,
			zIndex: 2048,
			language: 'ko-KR',
			startDate: '1980-01-01',
			endDate: '2020-12-31',

		});

		$("#date_from").datepicker('setEndDate', $("#date_to").datepicker('getDate', true));
		$("#date_to").datepicker('setStartDate', $("#date_from").datepicker('getDate', true));
		console.log(12);
	});

	$('[data-toggle = "datepicker"]').click(function() {
		$('[data-toggle = "datepicker"]').datepicker({
			autoHide: true,
			zIndex: 2048,
			language: 'ko-KR',
			startDate: '1980-01-01',
			endDate: '2020-12-31',

		});

		$("#date_from").datepicker('setEndDate', $("#date_to").datepicker('getDate', true));
		$("#date_to").datepicker('setStartDate', $("#date_from").datepicker('getDate', true));
		console.log(12);
	});

  </script>
  <!--  -->
</body>
</html>
