<?php
include 'layout/layout.php';
include 'api/dbconn.php';
include 'api/pageClass.php';
include 'api/common.php';
include 'api/selectbox.php';


$conn = new DBC();
$conn->DBI();
$layout = new Layout;

if($_GET['order'] == null){
	$order = " desc";
} else {
	$order = $_GET['order'];
}

if($_GET['list'] == null){
  $list = '10';
} else {
  $list = $_GET['list'];
}

switch ($_GET['search']) {
	case '전체':
	if($_GET['search_text'] == ''){
		$search = "where (a.agency_name like '%%' or a.agency_code like '%' or
		a.pos_name like '%%' or a.pos_id like '%%')  ";
	} else {
		$search = "where (a.agency_name like '%".$_GET['search_text']."%' or a.agency_code like '%".$_GET['search_text']."%' or
		a.pos_name like '%".$_GET['search_text']."%' or a.pos_id like '%".$_GET['search_text']."%') ";
	}
	break;

	case '운영자':
	$search = "where a.agency_name like '%".$_GET['search_text']."%' ";
	break;

	case '운영자코드':
	$search = "where a.agency_code like '%".$_GET['search_text']."%' ";
	break;

	case '매장명':
	$search = "where a.pos_name like '%".$_GET['search_text']."%' ";
	break;

	case '매장코드':
	$search = "where a.pos_id like '%".$_GET['search_text']."%' ";
	break;
}

if($_GET['channel'] == null){
	$searchChannel = " ";
} else {
	$searchChannel = " and replace(a.CHANNEL,'홈/미디어','스마트홈') = '".$_GET['channel']."' ";
}

if($_GET['bg_code'] == null){
	$searchBg = " ";
} else {
	$searchBg = " and a.bg_code = '".$_GET['bg_code']."' ";
}

//페이징
$query = "
SELECT replace(a.CHANNEL,'홈/미디어','스마트홈') AS '영업담당',
		 a.bg_code AS '지원팀', a.agency_name AS '운영자',
		 a.agency_code AS '운영자코드', a.pos_name AS '매장명',
		 a.pos_id AS '매장코드',
		 a.cnt AS '총PV',
		 c.cnt AS '총UV',
		 b.avg_dif
FROM
(
	SELECT a.pos_id, SUM(a.cnt)AS 'cnt',
	   a.CHANNEL, a.bg_code, a.agency_name,
	   a.agency_code, a.pos_name
	FROM
	(
	   SELECT a.pos_id, COUNT(pos_id) AS 'cnt',
	   did_pos_code.CHANNEL, did_pos_code.bg_code, did_pos_code.agency_name,
	   did_pos_code.agency_code, did_pos_code.pos_name
	   FROM did_pos_code
	   INNER JOIN
	   (
	      SELECT pos_id
	      FROM did_log_type_3
	      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
	      AND pos_id IS NOT NULL AND pos_id != ''
	      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
	   )a ON did_pos_code.pos_code = a.pos_id
	   WHERE pos_code IS NOT NULL AND pos_code != ''
	   GROUP BY pos_id
	   UNION ALL
	   SELECT a.pos_id, COUNT(pos_id) AS 'cnt',
	   did_pos_code.CHANNEL, did_pos_code.bg_code, did_pos_code.agency_name,
	   did_pos_code.agency_code, did_pos_code.pos_name
	   FROM did_pos_code
	   INNER JOIN
	   (
	      SELECT pos_id
	      FROM did_log_type_3
	      WHERE space_id IN('s000001','s000002','s000003','s000004')
	      AND pos_id IS NOT NULL AND pos_id != ''
	      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
	   )a ON did_pos_code.pos_code = a.pos_id
	   WHERE pos_code IS NOT NULL AND pos_code != ''
	   GROUP BY pos_id
	   UNION ALL
	   SELECT a.pos_id, COUNT(pos_id) AS 'cnt',
	   did_pos_code.CHANNEL, did_pos_code.bg_code, did_pos_code.agency_name,
	   did_pos_code.agency_code, did_pos_code.pos_name
	   FROM did_pos_code
	   INNER JOIN
	   (
	      SELECT pos_id
	      FROM did_log_type_4
	      WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
	      AND pos_id IS NOT NULL AND pos_id != ''
	   )a ON did_pos_code.pos_code = a.pos_id
	   WHERE pos_code IS NOT NULL AND pos_code != ''
	   GROUP BY pos_id
	)a
	GROUP BY a.pos_id
)a
LEFT JOIN
(
	SELECT a.pos_id, SUM(a.cnt)AS 'cnt'
	FROM
	(
	   SELECT a.pos_id, COUNT(a.TIMESTAMP) AS 'cnt'
	   FROM
	   (
	      SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id,
	         DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	      FROM did_pos_code
	      INNER JOIN
	      (
	         SELECT pos_id, device_id, TIMESTAMP, page_id
	         FROM did_log_type_2
	         WHERE page_id = 'p900005' AND is_enter = '1'
	         AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
					 GROUP BY pos_id, device_id, TIMESTAMP
	      )a ON did_pos_code.pos_code = a.pos_id
	   )a
	   INNER JOIN
	   (
	      SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id, a.space_id
	      FROM did_pos_code
	      INNER JOIN
	      (
	         SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
	         FROM did_log_type_3
	         WHERE space_id IS NOT NULL AND page_id = 'p900005'
	         AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
	         GROUP BY TIMESTAMP
	      )a ON did_pos_code.pos_code = a.pos_id
	   )b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	   AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
	   GROUP BY pos_id
	   UNION ALL
	   SELECT b.pos_id, COUNT(b.TIMESTAMP) AS 'cnt'
	   FROM
	   (
	      SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.space_id,
	         DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	      FROM did_pos_code
	      INNER JOIN
	      (
	         SELECT pos_id, device_id, TIMESTAMP, space_id
	         FROM did_log_type_3
	         WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
	         AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
					 GROUP BY pos_id, device_id, TIMESTAMP
	      )a ON did_pos_code.pos_code = a.pos_id
	   )b
	   INNER JOIN
	   (
	      SELECT a.log_seq, a.pos_id, a.device_id, a.TIMESTAMP, a.space_id, a.target_id
	      FROM did_pos_code
	      INNER JOIN
	      (
	         SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
	         FROM did_log_type_4
	         WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
	         AND page_id = 'p900003' AND target_id IS NOT NULL
	         GROUP BY TIMESTAMP
	      )a ON did_pos_code.pos_code = a.pos_id
	   )c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
	      AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
	   GROUP BY pos_id
	)a
	GROUP BY a.pos_id
)c ON c.pos_id = a.pos_id
LEFT JOIN
(
	SELECT *,
			 SEC_TO_TIME(AVG(TIME_TO_SEC(E.diff))) AS avg_dif
	FROM
	(
		SELECT A.pos_id,
				 A.device_id,
				 @var,
				 CASE WHEN @user = device_id THEN 0 ELSE @var := 0 END,
				 TIMEDIFF( STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s') , @var) AS diff,
				 @user := device_id,@var := STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')
		FROM
		(
			SELECT *
			FROM did_log_type_2
			WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
			GROUP BY device_id , TIMESTAMP ORDER BY device_id,TIMESTAMP
		)AS A,
		(
			SELECT @var := 0, @user := ''
		)AS t
	)AS E
	WHERE E.diff IS NOT NULL AND E.diff < '00:15:00'
	GROUP BY E.pos_id
)b ON a.pos_id = b.pos_id
$search $searchChannel $searchBg
GROUP BY a.pos_id
ORDER BY 총UV $order, 매장코드, 매장명, 운영자코드, 운영자, 영업담당, 지원팀, avg_dif
";



$conn->DBQ($query);
$conn->DBE(); //쿼리 실행
$cnt = $conn->resultRow();
$total_row = $cnt;         // db에 저장된 게시물의 레코드 총 갯수 값. 현재 값은 테스트를 위한 값
if($_GET['page'] == null){
 $i = 0;
} else if($_GET['list'] == 10  && $_GET['page'] != null){
 $i = $_GET['list'] * ($_GET['page'] - 1);
} else if($_GET['list'] == 20 && $_GET['page'] != null) {
 $i = $_GET['list'] * ($_GET['page'] - 1);
} else if($_GET['list'] == 30 && $_GET['page'] != null) {
 $i = $_GET['list'] * ($_GET['page'] - 1 );
}
$block = 5;                        // 화면에 보여질 블럭 단위 값[1]~[5]
$page = new paging($_GET['page'], $list, $block, $total_row);
if(isset($_GET['channel']) or isset($_GET['bg_code']) or isset($_GET['date_from']) or isset($_GET['date_to']) or isset($_GET['search']) or isset($_GET['search_text']) or isset($_GET['order'])
or isset($list))
{
  $page->setUrl("channel=".$_GET['channel']."&bg_code=".$_GET['bg_code']."&date_from=".$date_from."&date_to=".$date_to."&search=".$_GET['search']."&search_text=".$_GET['search_text']."&order=".$_GET['order']."&list=".$list);
}

$limit = $page->getVar("limit");   // 가져올 레코드의 시작점을 구하기 위해 값을 가져온다. 내부로직에 의해 계산된 값

$page->setDisplay("prev_btn", "<"); // [이전]버튼을 [prev] text로 변경
$page->setDisplay("next_btn", ">"); // 이와 같이 버튼을 이미지로 바꿀수 있음
$page->setDisplay("end_btn", ">>");
$page->setDisplay("start_btn", "<<");
$page->setDisplay("class","page-item");
$page->setDisplay("full");
$paging = $page->showPage();


      ?>
			<script type="text/javascript">
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
      <style>
      form{border:1px solid #E6E6E6;}
      hr{margin:1px;}
      </style>

      <script>

			function getFormatDate(date){
			  var year = date.getFullYear();                                 //yyyy
			  var month = (1 + date.getMonth());                     //M
			  month = month >= 10 ? month : '0' + month;     // month 두자리로 저장
			  var day = date.getDate();                                        //d
			  day = day >= 10 ? day : '0' + day;                            //day 두자리로 저장
			  return  year + '-' + month + '-' + day;
			}


         <?php $curDate = date('Y-m-d'); ?>
         <?php $curDate1 = date("Y-m-d",strtotime("-1 days"));?>
         <?php $curDate2 = date("Y-m-d",strtotime("-1 week"));?>
         <?$d = mktime(0,0,0, date("m"), 1, date("Y"));
         $prev_month = strtotime("-1 month", $d); ?>
         <?php $curDate3 = date("Y-m-01", $prev_month) ;?>
         <?php $curDate33 = date("Y-m-t", $prev_month) ;?>
         <?php $curDate4 = date("Y-m-d",strtotime("-1 months"));?>
         <?php $curDate5 = date("Y-m-d",strtotime("-3 months"));?>
      function setSearchDate(num){
        switch(num){
          case 0:
          document.getElementById('date_from').value = <?php echo json_encode($curDate); ?>;
          document.getElementById('date_to').value = <?php echo json_encode($curDate); ?>;
          break;

          case 1:
          document.getElementById('date_from').value = <?php echo json_encode($curDate1); ?>;
          document.getElementById('date_to').value = <?php echo json_encode($curDate1); ?>;
          break;

          case 2:
					var date_from = new Date();
			    var date_to = new Date();
			    var tempDate = new Date().getDay();

			    switch (tempDate) {
			      // 일요일
			      case 0:
			      new Date(date_from.setDate(date_from.getDate()-6));
			      new Date(date_to.setDate(date_to.getDate()+0));
			      break;

			      // 월요일
			      case 1:
			      new Date(date_from.setDate(date_from.getDate()-0));
			      new Date(date_to.setDate(date_to.getDate()+6));
			      break;

			      // 화요일
			      case 2:
			      new Date(date_from.setDate(date_from.getDate()-1));
			      new Date(date_to.setDate(date_to.getDate()+5));
			      break;

			      // 수요일
			      case 3:
			      new Date(date_from.setDate(date_from.getDate()-2));
			      new Date(date_to.setDate(date_to.getDate()+4));
			      break;

			      // 목요일
			      case 4:
			      new Date(date_from.setDate(date_from.getDate()-3));
			      new Date(date_to.setDate(date_to.getDate()+3));
			      break;

			      // 금요일
			      case 5:
			      new Date(date_from.setDate(date_from.getDate()-4));
			      new Date(date_to.setDate(date_to.getDate()+2));
			      break;

			      // 토요일
			      case 6:
			      new Date(date_from.setDate(date_from.getDate()-5));
			      new Date(date_to.setDate(date_to.getDate()+1));
			      break;
			    }

			    date_from = getFormatDate(date_from);
			    date_to = getFormatDate(date_to);
			    document.getElementById('date_from').value = date_from;
			    document.getElementById('date_to').value = date_to;
          break;

          case 3:
               document.getElementById('date_from').value = <?php echo json_encode($curDate3); ?>;
             document.getElementById('date_to').value = <?php echo json_encode($curDate33); ?>;
          break;

          case 4:
          document.getElementById('date_from').value = <?php echo json_encode($curDate4); ?>;
          document.getElementById('date_to').value = <?php echo json_encode($curDate); ?>;
          break;

          case 5:
          document.getElementById('date_from').value = <?php echo json_encode($curDate5); ?>;
          document.getElementById('date_to').value = <?php echo json_encode($curDate); ?>;
          break;

          default: return 0;
          break;
        }
      }

      </script>

      <body class="body-bg">
        <!-- preloader area start -->
        <div id="preloader">
          <div class="loader"></div>
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
                <div class="col-lg-6"><h5>매장 이용 순위 </h5></div>
                <div class="col-lg-6" style="text-align: right;"><small> Main > 매장 이용 순위 </small></div>
                <html><hr color="black" width=99%></html>
                <div class="card col-lg-12 mt-3">
                  <div class="card-body">
                    <form action="<?=$_SERVER['PHP_SELF']?>" method="get" class="col-lg-12" name='form' onsubmit="return nospecialKey()">
                      <div class="input-group">
                        <html><hr color="#E6E6E6" class="mt-2" width=100%></html>
                        <span style="margin-left:15px" class="input-group form-control2 form-control-sm col-lg-1" >영업담당</span>
                        <div class="col-lg-2">
                                       <select style="background-color: #E9ECEF" name="channel" class="form-control form-control-sm" id="selectID">
                                          <option value="">선택</option>
                                          <?
                                          $query = "SELECT distinct(replace(CHANNEL,'홈/미디어','스마트홈')) as 'channel' FROM did_pos_code  where channel is not NULL and CHANNEL != ''";
                                          $conn->DBQ($query);
                                          $conn->DBE(); //쿼리 실행
                                          while ($option = $conn->DBF()) {  ?>
                                             <option <?if($_GET['channel'] == $option['channel']){echo "selected";}?> value="<? echo $option['channel'];?>"><?echo $option['channel'];?></option>
                                             <?}?>
                                       </select>
                        </div>
                        <span style="margin-left:40px" class="input-group form-control2 form-control-sm  col-lg-1">지원팀</span>
                        <div class="col-lg-2">
                                       <select style="background-color: #E9ECEF" name="bg_code" class="form-control form-control-sm" id="good">
                                             <option value="">전체</option>
																						 <?
                                             if ($_GET['channel'] == '스마트홈'){
																							 $query = "select bg_code FROM did_pos_code where channel = '홈/미디어' and bg_code is not NULL and bg_code != '' GROUP BY bg_code";
                                             $conn->DBQ($query);
                                             $conn->DBE();
                                             while ($option1 = $conn->DBF()) {  ?>
                                             <option <?if($_GET['bg_code'] == $option1['bg_code']){echo "selected";}?> value="<?echo $option1['bg_code'];?>"><?echo $option1['bg_code'];?></option>
	                                          <?}
																					  }else if ($_GET['channel'] != null){
																							 $query = "select bg_code FROM did_pos_code where channel = '".$_GET['channel']."' and bg_code is not NULL and bg_code != '' GROUP BY bg_code";
                                             $conn->DBQ($query);
                                             $conn->DBE();
                                             while ($option1 = $conn->DBF()) {  ?>
                                             <option <?if($_GET['bg_code'] == $option1['bg_code']){echo "selected";}?> value="<?echo $option1['bg_code'];?>"><?echo $option1['bg_code'];?></option>
	                                          <?}
																						}?>
                                          </select>
                            </div>
                          </div>
                          <!--/input-group-->
                          <!-- 기간 -->
                          <html><hr color="#E6E6E6" width=100%></html>
                          <html><hr class="mt-2" color="#E6E6E6" width=100%></html>
                          <div class="form-group">
                            <div class="col-lg-12">
                              <div class="input-group">
                                <span class="input-group form-control2 form-control-sm col-lg-1">기간</span>
                                <input data-toggle="datepicker" type="text" class="form-control form-control-sm col-lg-2 " style="margin-left:18px" id="date_from" name="date_from" readonly="readonly" value="<?echo $date_from;?>">
                                <div class="input-group-prepend">
                                  <div class="input-group-text form-control form-control-sm">~</div>
                                </div>
                                <input data-toggle="datepicker" type="text" class="form-control form-control-sm col-lg-2 mr-1" id="date_to" name="date_to" readonly="readonly" value="<?echo $date_to;?>">
                                <button type="button" style="margin-left:10px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType1" value="" onclick="setSearchDate(0)"/>오늘</button>
                                <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType2" onclick="setSearchDate(1)"/>어제</button>
                                <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType3" onclick="setSearchDate(2)"/>일주일</button>
                                <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType4" onclick="setSearchDate(3)"/>지난달</button>
                                <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType5" onclick="setSearchDate(4)"/>1개월</button>
                                <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType6" onclick="setSearchDate(5)"/>3개월</button>
                              </div>
                            </div>
                            <html><hr color="#E6E6E6" width=100%></html>
                            <html><hr class="mt-2" color="#E6E6E6" width=100%></html>

                            <!--기간 끝-->
                            <div class="input-group">
                              <br><span style="margin-left:15px" class="input-group form-control2 form-control-sm col-lg-1">검색어</span>
                              <div class="col-lg-2">
                                <select style="background-color: #E9ECEF" name="search" class="form-control form-control-sm col-lg-12">
                                  <option value="전체">전체</option>
                                  <option <?if($_GET['search'] == "운영자"){echo "selected";}?> value="운영자">운영자</option>
                                  <option <?if($_GET['search'] == "운영자코드"){echo "selected";}?> value="운영자코드">운영자코드</option>
                                  <option <?if($_GET['search'] == "매장명"){echo "selected";}?> value="매장명">매장명</option>
                                  <option <?if($_GET['search'] == "매장코드"){echo "selected";}?> value="매장코드">매장코드</option>
                                </select>
                              </div>
                              <input type="text" style="background-color: #E9ECEF" name="search_text" class="col-lg-3 ml-5 form-control form-control-sm" value="<?if($_GET['search_text'] != null){echo $_GET['search_text'];}?>" style="margin-left:20px" id="search_text" />
                            </div>
                            <html><hr color="#E6E6E6" width=100%></html>
                          </div>
                          <!--/form_group-->
                          <!-- 검색 -->
                          <div class="input-group">
                            <div class="col-lg-6">
                              <button class="btn btn-lg mr-2 btn btn-xs" type="reset" style="display: none "  onclick="changes1Step2(value)"><i class="fa fa-refresh"></i></button></div>
                              <button class="btn btn-primary btn btn-xs" style="text-align:center;" type="submit" id="searchButton">검색</button>
                            </div><br>

                            <div class="row mb-2 mt-4">
                              <div class="col-lg-9 text-left"><p>total:<?echo $cnt;?></p></div>
                              <div class="">
                                  <select class="form-control form-control-sm" id="order" name='order' value="">
                                      <option <?if($_GET['order'] == "asc"){echo "selected";}?> value="asc">오름차순</option>
                                    <option <?if($_GET['order'] == "desc" or $_GET['order'] == null){echo "selected";}?> value="desc">내림차순</option>
                                  </select>
                              </div>
                              <div class="col-lg-1">
                                  <select class="form-control form-control-sm" id="list" name='list'>
                                    <option value="10" <?if($_GET['list'] == 10){echo "selected";}else if($_GET['list'] == null){echo "selected";}?>>10</option>
                                    <option value="20" <?if($_GET['list'] == 20){echo "selected";}?>>20</option>
                                    <option value="30" <?if($_GET['list'] == 30){echo "selected";}?>>30</option>
                                  </select>
                                </div>
                                  <div class="form-group text-right">
                                    <a href="api/statsReg/stats_access_excel.php?date_from=<?echo $date_from;?>&date_to=<?echo $date_to;?>&search=<?echo $search;?>&searchChannel=<?echo $searchChannel;?>
																			&searchBg=<?echo $searchBg;?>&order=<?echo $order;?>">
                                      <button type="button" class="btn btn-xs text-right" id="searchButton"><i class="fa fa-download"></i>데이터 저장</button></a>
                                    </div>
                                  </div>

                                <div class="single-table">
                                  <div class="table-responsive">
                                    <table class="table table-bordered progress-table text-center">
                                      <thead class="text-uppercase">
                                        <tr style="font-size:12px">
                                          <th scope="col" >순위</th>
                                          <th scope="col" >영업담당</th>
                                          <th scope="col" >지원팀</th>
                                          <th scope="col" >운영자</th>
                                          <th scope="col" >운영자코드</th>
                                          <th scope="col" >매장명</th>
                                          <th scope="col" >매장코드</th>
                                          <!-- <th scope="col" >방문횟수</th> -->
                                          <!-- <th scope="col" >비율</th> -->
                                          <th scope="col" >총 PV</th>
																					<th scope="col" >총 UV</th>
                                          <th scope="col" >평균체류시간</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      <?
																			$sql = "
																			SELECT replace(a.CHANNEL,'홈/미디어','스마트홈') AS '영업담당',
																					 a.bg_code AS '지원팀', a.agency_name AS '운영자',
																					 a.agency_code AS '운영자코드', a.pos_name AS '매장명',
																					 a.pos_id AS '매장코드',
																					 a.cnt AS '총PV',
																					 c.cnt AS '총UV',
																					 b.avg_dif
																			FROM
																			(
																				SELECT a.pos_id, SUM(a.cnt)AS 'cnt',
																				   a.CHANNEL, a.bg_code, a.agency_name,
																				   a.agency_code, a.pos_name
																				FROM
																				(
																				   SELECT a.pos_id, COUNT(pos_id) AS 'cnt',
																				   did_pos_code.CHANNEL, did_pos_code.bg_code, did_pos_code.agency_name,
																				   did_pos_code.agency_code, did_pos_code.pos_name
																				   FROM did_pos_code
																				   INNER JOIN
																				   (
																				      SELECT pos_id
																				      FROM did_log_type_3
																				      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
																				      AND pos_id IS NOT NULL AND pos_id != ''
																				      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
																				   )a ON did_pos_code.pos_code = a.pos_id
																				   WHERE pos_code IS NOT NULL AND pos_code != ''
																				   GROUP BY pos_id
																				   UNION ALL
																				   SELECT a.pos_id, COUNT(pos_id) AS 'cnt',
																				   did_pos_code.CHANNEL, did_pos_code.bg_code, did_pos_code.agency_name,
																				   did_pos_code.agency_code, did_pos_code.pos_name
																				   FROM did_pos_code
																				   INNER JOIN
																				   (
																				      SELECT pos_id
																				      FROM did_log_type_3
																				      WHERE space_id IN('s000001','s000002','s000003','s000004')
																				      AND pos_id IS NOT NULL AND pos_id != ''
																				      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
																				   )a ON did_pos_code.pos_code = a.pos_id
																				   WHERE pos_code IS NOT NULL AND pos_code != ''
																				   GROUP BY pos_id
																				   UNION ALL
																				   SELECT a.pos_id, COUNT(pos_id) AS 'cnt',
																				   did_pos_code.CHANNEL, did_pos_code.bg_code, did_pos_code.agency_name,
																				   did_pos_code.agency_code, did_pos_code.pos_name
																				   FROM did_pos_code
																				   INNER JOIN
																				   (
																				      SELECT pos_id
																				      FROM did_log_type_4
																				      WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
																				      AND pos_id IS NOT NULL AND pos_id != ''
																				   )a ON did_pos_code.pos_code = a.pos_id
																				   WHERE pos_code IS NOT NULL AND pos_code != ''
																				   GROUP BY pos_id
																				)a
																				GROUP BY a.pos_id
																			)a
																			LEFT JOIN
																			(
																				SELECT a.pos_id, SUM(a.cnt)AS 'cnt'
																				FROM
																				(
																				   SELECT a.pos_id, COUNT(a.TIMESTAMP) AS 'cnt'
																				   FROM
																				   (
																				      SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id,
																				         DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
																				      FROM did_pos_code
																				      INNER JOIN
																				      (
																				         SELECT pos_id, device_id, TIMESTAMP, page_id
																				         FROM did_log_type_2
																				         WHERE page_id = 'p900005' AND is_enter = '1'
																				         AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
																								 GROUP BY pos_id, device_id, TIMESTAMP
																				      )a ON did_pos_code.pos_code = a.pos_id
																				   )a
																				   INNER JOIN
																				   (
																				      SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id, a.space_id
																				      FROM did_pos_code
																				      INNER JOIN
																				      (
																				         SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
																				         FROM did_log_type_3
																				         WHERE space_id IS NOT NULL AND page_id = 'p900005'
																				         AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
																				         GROUP BY TIMESTAMP
																				      )a ON did_pos_code.pos_code = a.pos_id
																				   )b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
																				   AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
																				   GROUP BY pos_id
																				   UNION ALL
																				   SELECT b.pos_id, COUNT(b.TIMESTAMP) AS 'cnt'
																				   FROM
																				   (
																				      SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.space_id,
																				         DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
																				      FROM did_pos_code
																				      INNER JOIN
																				      (
																				         SELECT pos_id, device_id, TIMESTAMP, space_id
																				         FROM did_log_type_3
																				         WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
																				         AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
																								 GROUP BY pos_id, device_id, TIMESTAMP
																				      )a ON did_pos_code.pos_code = a.pos_id
																				   )b
																				   INNER JOIN
																				   (
																				      SELECT a.log_seq, a.pos_id, a.device_id, a.TIMESTAMP, a.space_id, a.target_id
																				      FROM did_pos_code
																				      INNER JOIN
																				      (
																				         SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
																				         FROM did_log_type_4
																				         WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
																				         AND page_id = 'p900003' AND target_id IS NOT NULL
																				         GROUP BY TIMESTAMP
																				      )a ON did_pos_code.pos_code = a.pos_id
																				   )c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
																				      AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
																				   GROUP BY pos_id
																				)a
																				GROUP BY a.pos_id
																			)c ON c.pos_id = a.pos_id
																			LEFT JOIN
																			(
																				SELECT *,
																						 SEC_TO_TIME(AVG(TIME_TO_SEC(E.diff))) AS avg_dif
																				FROM
																				(
																					SELECT A.pos_id,
																							 A.device_id,
																							 @var,
																							 CASE WHEN @user = device_id THEN 0 ELSE @var := 0 END,
																							 TIMEDIFF( STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s') , @var) AS diff,
																							 @user := device_id,@var := STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')
																					FROM
																					(
																						SELECT *
																						FROM did_log_type_2
																						WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
																						GROUP BY device_id , TIMESTAMP ORDER BY device_id,TIMESTAMP
																					)AS A,
																					(
																						SELECT @var := 0, @user := ''
																					)AS t
																				)AS E
																				WHERE E.diff IS NOT NULL AND E.diff < '00:15:00'
																				GROUP BY E.pos_id
																			)b ON a.pos_id = b.pos_id
																			$search $searchChannel $searchBg
																			GROUP BY a.pos_id
																			ORDER BY 총UV $order, 매장코드, 매장명, 운영자코드, 운영자, 영업담당, 지원팀, avg_dif
																			limit $limit, $list
																			";
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
                                      while($stats = $conn->DBF()) { ?>
                                        <tr style="text-align:center;">
                                          <td><?echo $i+1;?></td>
                                          <td><?if($stats['영업담당'] == null){echo '-';}else{echo $stats['영업담당'];}?></a></td>
                                          <td><?if($stats['지원팀'] == null){echo '-';}else{echo $stats['지원팀'];}?></td>
                                          <td><?if($stats['운영자'] == null){echo '-';}else{echo $stats['운영자'];}?></td>
                                          <td><?if($stats['운영자코드'] == null){echo '-';}else{echo $stats['운영자코드'];}?></td>
                                          <td><?if($stats['매장명'] == null){echo '-';}else{echo $stats['매장명'];}?></td>
                                          <td><?if($stats['매장코드'] == null){echo '-';}else{echo $stats['매장코드'];}?></td>
                                          <!-- <td><?//if($stats['방문횟수'] == null){echo '-';}else{echo number_format($stats['방문횟수']);}?></td> -->
                                          <!-- <td>
                                            <div class="progress">
                                              <div class="progress-bar" role="progressbar" style="width: 1<?//$stats['비율']?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <p style="font-size:10px;"><?//echo $stats ['비율'];?></p>
                                          </td> -->
                                          <td><? if($stats['총PV'] == null){echo '-';}else{echo number_format($stats['총PV']);}?></td>
																					<td data-title="총 UV"><?php echo number_format($stats['총UV']); ?></td>
																					<td data-title="평균체류시간">
																						<?php
																						if($stats['avg_dif'] == null){
																							echo '00분 01초';
																						}else{
																							echo substr($stats['avg_dif'],3,2).'분 '.substr($stats['avg_dif'],6,2).'초';
																						}
																						?>
																					</td>
                                        </tr>
                                      <?$i++;}?>
                                      </tbody>
                                    </table>
                                   </form>
                                  </div>
                                </div>
                                    <div class="row mt-4 " style="justify-content: center;">
                                    <ul class="pagination">
                                      <?echo $paging; ?>
                                    </ul>
                                  </div>

                            </div>
                            <!--/cardbody-->

                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- main content area end -->
                      <?$layout->footer($footer);?>
                    </div>
                    <!-- main wrapper end -->
                    <?$layout->JsFile("
                    <script src='api/statsReg/bar.js'></script>
                    ");?>
                    <?$layout->js($js);?>
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
												 $("#date_from").datepicker('setEndDate', document.getElementById('date_to').value);
	                       $("#date_to").datepicker('setStartDate', document.getElementById('date_from').value);
	                       $("#date_from").datepicker('setDate', document.getElementById('date_from').value);
	                       $("#date_to").datepicker('setDate', document.getElementById('date_to').value);
                         console.log(12);
                       });

                    </script>
                  </body>
                  </html>
