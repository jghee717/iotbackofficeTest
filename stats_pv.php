<?php
include 'layout/layout.php';
include 'api/dbconn.php';
include 'api/pageClass.php';
include 'api/common.php';
// include 'api/checkRate.php';
$conn = new DBC();
$conn->DBI();

$layout = new Layout;


// $space1 = 0;
// $space2 = 0;
// $space3 = 0;
// $space4 = 0;
//
// // for($i=0; $i<$stay_cnt; $i++) {
// //    switch($stay_time[$i]['space_id']) {
// //       case 's000001':
// //       $diff = strtotime($stay_time[$i]['MAX']) - strtotime($stay_time[$i]['MIN']);
// //       $space1 += $diff;
// //       break;
// //
// //       case 's000002':
// //       $diff = strtotime($stay_time[$i]['MAX']) - strtotime($stay_time[$i]['MIN']);
// //       $space2 += $diff;
// //       break;
// //
// //       case 's000003':
// //       $diff = strtotime($stay_time[$i]['MAX']) - strtotime($stay_time[$i]['MIN']);
// //       $space3 += $diff;
// //       break;
// //
// //       case 's000004':
// //       $diff = strtotime($stay_time[$i]['MAX']) - strtotime($stay_time[$i]['MIN']);
// //       $space4 += $diff;
// //       break;
// //    }
// // }

//오름차순 내림차순
if($_GET['order'] == null){
  $order = 'desc';
} else {
  $order = $_GET['order'];
}

if($_GET['selectID'] == null){
  $searchCate = " where 구분 IN ('U+tv','U+IoT') ";
} else if($_GET['selectID'] == 'TV'){
  $searchCate = " where 구분 IN ('U+tv','') ";
} else if($_GET['selectID'] == 'IoT'){
  $searchCate = " where 구분 IN ('','U+IoT') ";
} else if($_GET['selectID'] == '전체'){
  $searchCate = " where 구분 IN ('U+tv','U+IoT') ";
}

if($_GET['space_id'] == null){
  $searchSpace1 = " and space_id IN('s000001', 's000002', 's000003', 's000004') ";
  $searchSpace2 = " ";
}else if($_GET['space_id'] == '전체'){
  $searchSpace1 = " and space_id IN('s000001', 's000002', 's000003', 's000004') ";
  $searchSpace2 = " ";
}else if($_GET['space_id'] == 's000001'){
  $searchSpace1 = " and space_id = 's000001' ";
  $searchSpace2 = " and space_id = 's000001' ";
}else if($_GET['space_id'] == 's000002'){
  $searchSpace1 = " and space_id = 's000002' ";
  $searchSpace2 = " and space_id = 's000002' ";
}else if($_GET['space_id'] == 's000003'){
  $searchSpace1 = " and space_id = 's000003' ";
  $searchSpace2 = " and space_id = 's000003' ";
}else if($_GET['space_id'] == 's000004'){
  $searchSpace1 = " and space_id = 's000004' ";
  $searchSpace2 = " and space_id = 's000004' ";
}

//페이징
$query = "
SELECT a.구분, a.pos_id, a.공간, a.컨텐츠, SUM(a.사용횟수) AS '사용횟수', a.avg_dif
FROM
(
	SELECT 'U+tv' AS '구분', a.pos_id, '-' AS '공간', a.DESC AS '컨텐츠', COUNT(a.pos_id) AS '사용횟수', b.avg_dif
	FROM did_pos_code
	INNER JOIN
	(
		SELECT pos_id, b.desc
		FROM
		(
			SELECT pos_id, space_id
			FROM did_log_type_3
			WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
			AND space_id NOT IN('s000001', 's000002', 's000003', 's000004')
		)a LEFT JOIN
		(
			SELECT did_bookmark_config.DESC, target_id
			FROM did_bookmark_config
		)b ON a.space_id = b.target_id
	)a ON did_pos_code.pos_code = a.pos_id
	LEFT JOIN
	(
		SELECT *, SEC_TO_TIME(AVG(TIME_TO_SEC(E.diff))) AS avg_dif
      FROM
      (
          SELECT A.pos_id, A.device_id , @var ,CASE WHEN @user = device_id THEN 0 ELSE  @var := 0 END,
          TIMEDIFF( STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s') , @var) AS diff,
          @user := device_id,@var := STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')
          FROM
          (
            SELECT *
	         FROM did_log_type_3
	         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
	         AND space_id NOT IN('s000001', 's000002', 's000003', 's000004')
	         GROUP BY device_id , TIMESTAMP
	         ORDER BY device_id,TIMESTAMP
          )AS A,(SELECT @var := 0, @user := '' )AS t
        )AS E
        WHERE E.diff IS NOT NULL AND E.diff < '00:15:00'
        GROUP BY E.pos_id
	)b ON a.pos_id = b.pos_id
	GROUP BY 공간, 컨텐츠
	UNION ALL
	SELECT 'U+IoT' AS '구분', a.pos_id, a.DESC AS '공간', '-' AS '컨텐츠', COUNT(a.pos_id) AS '사용횟수', b.avg_dif
	FROM did_pos_code
	INNER JOIN
	(
		SELECT pos_id, b.desc
		FROM
		(
			SELECT pos_id, space_id
			FROM did_log_type_3
			WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
			$searchSpace1
		)a LEFT JOIN
		(
			SELECT did_space_config.DESC, space_id
			FROM did_space_config
		)b ON a.space_id = b.space_id
	)a ON did_pos_code.pos_code = a.pos_id
	LEFT JOIN
	(
		SELECT *, SEC_TO_TIME(AVG(TIME_TO_SEC(E.diff))) AS avg_dif
      FROM
      (
          SELECT A.pos_id, A.device_id , @var ,CASE WHEN @user = device_id THEN 0 ELSE  @var := 0 END,
          TIMEDIFF( STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s') , @var) AS diff,
          @user := device_id,@var := STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')
          FROM
          (
            SELECT *
	         FROM did_log_type_3
	         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
           $searchSpace1
	         GROUP BY device_id , TIMESTAMP
	         ORDER BY device_id,TIMESTAMP
          )AS A,(SELECT @var := 0, @user := '' )AS t
        )AS E
        WHERE E.diff IS NOT NULL AND E.diff < '00:15:00'
        GROUP BY E.pos_id
	)b ON a.pos_id = b.pos_id
	GROUP BY 공간, 컨텐츠
	UNION ALL
	SELECT 'U+IoT' AS '구분', a.pos_id, a.DESC AS '공간', IFNULL(a.contents,'-') AS '컨텐츠', COUNT(a.pos_id) AS '사용횟수', b.avg_dif
	FROM did_pos_code
	INNER JOIN
	(
		SELECT pos_id, b.DESC, IFNULL(c.TEXT,d.TEXT) AS 'contents'
		FROM
		(
			SELECT pos_id, page_id, space_id, target_id
			FROM did_log_type_4
			WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
      $searchSpace2
		)a
		LEFT JOIN
		(
			SELECT did_space_config.DESC, space_id
			FROM did_space_config
		)b ON a.space_id = b.space_id
		LEFT JOIN
		(
			SELECT did_usecase_config.TEXT, target_id, usescene_id
			FROM did_usecase_config
		)c ON a.space_id = c.target_id AND a.target_id = c.usescene_id
		LEFT JOIN
    (
      SELECT did_product_config.TEXT, product_id
      FROM did_product_config
    )d ON a.target_id = d.product_id
	)a ON did_pos_code.pos_code = a.pos_id
	LEFT JOIN
	(
		SELECT *, SEC_TO_TIME(AVG(TIME_TO_SEC(E.diff))) AS avg_dif
      FROM
      (
          SELECT A.pos_id, A.device_id , @var ,CASE WHEN @user = device_id THEN 0 ELSE  @var := 0 END,
          TIMEDIFF( STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s') , @var) AS diff,
          @user := device_id,@var := STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')
          FROM
          (
            SELECT *
	         FROM did_log_type_4
	         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
           $searchSpace2
	         GROUP BY device_id , TIMESTAMP
	         ORDER BY device_id,TIMESTAMP
          )AS A,(SELECT @var := 0, @user := '' )AS t
        )AS E
        WHERE E.diff IS NOT NULL AND E.diff < '00:15:00'
        GROUP BY E.pos_id
	)b ON a.pos_id = b.pos_id
	GROUP BY 공간, 컨텐츠
)a
$searchCate
GROUP BY 공간, 컨텐츠
ORDER BY 사용횟수 $order, pos_id, 공간, 컨텐츠, avg_dif
";
$conn->DBQ($query);
$conn->DBE(); //쿼리 실행
$cnt = $conn->resultRow();
if($order == 'desc') {
  if($_GET['page'] == null){
    $i = 0;
  } else if($_GET['list'] == 10 && $_GET['page'] != null){
    $i = $_GET['list'] * ($_GET['page'] - 1);
  } else if($_GET['list'] == 20 && $_GET['page'] != null) {
    $i = $_GET['list'] * ($_GET['page'] - 1);
  } else if($_GET['list'] == 30 && $_GET['page'] != null) {
    $i = $_GET['list'] * ($_GET['page'] - 1 );
  }
}
else if($order == 'asc')
{
  if($_GET['page'] == null){
    $i = $cnt+1;
  } else if($_GET['list'] == 10 && $_GET['page'] != null){
    $i = ($cnt+1) - ($_GET['list'] * ($_GET['page'] - 1));
  } else if($_GET['list'] == 20 && $_GET['page'] != null) {
    $i = ($cnt+1) - ($_GET['list'] * ($_GET['page'] - 1));
  } else if($_GET['list'] == 30 && $_GET['page'] != null) {
    $i = ($cnt+1) - ($_GET['list'] * ($_GET['page'] - 1));
  }
}
$total_row = $cnt;         // db에 저장된 게시물의 레코드 총 갯수 값. 현재 값은 테스트를 위한 값
if($_GET['list'] == null) {
  $list = 10;                     // 화면에 보여질 게시물 갯수
} else {
  $list = $_GET['list'];  // 화면에 보여질 게시물 갯수
}
$block = 5;                        // 화면에 보여질 블럭 단위 값[1]~[5]
$page = new paging($_GET['page'], $list, $block, $total_row);
if(isset($date_from) or isset($date_to) or isset($_GET['selectID']) or isset($_GET['space_id']) or isset($list) or isset($_GET['order']))
{
  $page->setUrl("selectID=".$_GET['selectID']."&space_id=".$_GET['space_id']."&date_from=".$date_from."&date_to=".$date_to."&order=".$_GET['order']."&list=".$list);
}

$limit = $page->getVar("limit");   // 가져올 레코드의 시작점을 구하기 위해 값을 가져온다. 내부로직에 의해 계산된 값

$page->setDisplay("prev_btn", "<"); // [이전]버튼을 [prev] text로 변경
$page->setDisplay("next_btn", ">"); // 이와 같이 버튼을 이미지로 바꿀수 있음
$page->setDisplay("end_btn", ">>");
$page->setDisplay("start_btn", "<<");
$page->setDisplay("class","page-item");
$page->setDisplay("full");
$paging = $page->showPage();

// echo $query;
?>

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
<script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
<!-- main wrapper start -->
<div class="horizontal-main-wrapper">
  <?$layout->mainHeader($mainHeader);?>
  <?$layout->header($header);?><br>

  <script>
  function getFormatDate(date){
    var year = date.getFullYear();                                 //yyyy
    var month = (1 + date.getMonth());                     //M
    month = month >= 10 ? month : '0' + month;     // month 두자리로 저장
    var day = date.getDate();                                        //d
    day = day >= 10 ? day : '0' + day;                            //day 두자리로 저장
    return  year + '-' + month + '-' + day;
  }
  // datepicker period
  <?php $curDate = date('Y-m-d'); ?>
  <?php $curDate1 = date("Y-m-d",strtotime("-1 days"));?>
  <?php $curDate2 = date("Y-m-d",strtotime("-6 days"));?>
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
  function ascdesc() {
    var schField = $("#order option:selected").val();

    if(schField == "asc")
    {
      $.ajax({
        type: 'post',
        url: 'api/deviceReg/asc.php',
        dataType : 'html',

        success: function(data){
          $("#no-more-tables").html(data);
        }
      });
    } else if(schField == "desc")
    {
      $.ajax({
        type: 'post',
        url: 'api/deviceReg/desc.php',
        dataType : 'html',

        success: function(data){
          $("#no-more-tables").html(data);
        }
      });
    }
  }
  </script>
  <!--AJAX-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script>
  $(document).ready(function(){ // html 문서를 다 읽어들인 후
    $('#selectID').on('change', function(){
      if(this.value !== ""){
        var optVal = $(this).find(":selected").val();
        //alert(optVal);
        $.post('./api/statsReg/stats_select.php',{optVal:optVal}, function(data) {
          $('#good').html(data);   // data는 ajaxPHP.php 파일에서 ehco 문의 결과 값
        });

      }
    });
  });
  </script>

  <!-- 카테고리-->
  <div class="main-content-inner">
    <div class="container">
      <div class="row">
        <div class="col-lg-6"><h5>메뉴별 통계 </h5></div>
        <div class="col-lg-6" style="text-align: right;"><small> Main > 메뉴별 통계 </small></div>
        <style>
        form{border:1px solid #E6E6E6;}
        hr{margin:1px;}
        </style>
        <html><hr color="black" width=100%></html>
        <div class="card col-lg-12 mt-3">
          <div class="card-body">
            <form action="<?=$_SERVER['PHP_SELF']?>" method="get" class="col-lg-12" name='form'>
              <div class="input-group">
                <html><hr color="#E6E6E6" class="mt-2" width=100%></html>
                <span style="margin-left:15px;" name="span" id="span" class="form-control2 form-control-sm col-lg-1 color-white" >구분</span>
                <!--select-->
                <div class="col-lg-2">
                  <select style="background-color: #E9ECEF" name="selectID" id="selectID" class="form-control form-control-sm" onchange="categoryChange(this)">
                    <option <?if($_GET['selectID'] == "전체"){echo "selected";}?> value="전체">전체</option>
                    <option  <?if($_GET['selectID'] == "TV"){echo "selected";}?> value="TV">U+tv</option>
                    <option  <?if($_GET['selectID'] == "IoT"){echo "selected";}?> value="IoT">U+IoT</option>
                  </select>
                </div>

                <span style="margin-left:15px;" name="span" id="span" class="form-control2 form-control-sm col-lg-1 color-white" >공간</span>
                <!--select-->
                <div id="good" class="col-lg-2">
                  <?if($_GET['selectID'] == "IoT"){?>
                    <select style="background-color: #E9ECEF" name="space_id" id="" class="form-control form-control-sm" onchange="categoryChange(this)">
                      <option value="전체">전체</option>
                      <option <?if($_GET['space_id'] == "s000001"){echo "selected";}?> value="s000001">침실</option>
                      <option <?if($_GET['space_id'] == "s000002"){echo "selected";}?> value="s000002">거실</option>
                      <option <?if($_GET['space_id'] == "s000003"){echo "selected";}?> value="s000003">주방</option>
                      <option <?if($_GET['space_id'] == "s000004"){echo "selected";}?> value="s000004">아이방</option>
                    </select>
                    <?} else {?>
                      <select style="background-color: #E9ECEF" name="space_id" id="" disabled class="form-control form-control-sm" onchange="categoryChange(this)">
                        <option value="">전체</option>
                      </select>
                      <?}?>
                    </div>
                    <!-- <div class="col-lg-2">
                    <select style="background-color: #E9ECEF" name="product_id" class="form-control form-control-sm" id="good">
                    <option value="">컨텐츠 전체</option>
                  </select>
                </div> -->
              </div>
              <!--/input-group-->

              <!-- 기간-->
              <html><hr color="#E6E6E6" width=100%></html>
              <html><hr color="#E6E6E6" class="mt-2" width=100%></html>
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="input-group">
                    <span class="input-group form-control2 form-control-sm col-lg-1">기간</span>
                    <input data-toggle="datepicker" type="text" class="form-control form-control-sm col-lg-2" style="margin-left:20px" id="date_from" name="date_from" readonly="readonly" value="<?echo $date_from;?>">
                    <div class="input-group-prepend">
                      <div class="input-group-text form-control form-control-sm">~</div>
                    </div>
                    <input data-toggle="datepicker" type="text" class="form-control form-control-sm col-lg-2" id="date_to" name="date_to" readonly="readonly" value="<?echo $date_to;?>">
                    <button type="button" style="margin-left:10px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType1" value="" onclick="setSearchDate(0)"/>오늘</button>
                    <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType2" onclick="setSearchDate(1)"/>어제</button>
                    <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType3" onclick="setSearchDate(2)"/>일주일</button>
                    <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType4" onclick="setSearchDate(3)"/>지난달</button>
                    <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType5" onclick="setSearchDate(4)"/>1개월</button>
                    <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType6" onclick="setSearchDate(5)"/>3개월</button>
                  </div>
                  <!--/input-group-->
                </div>
                <html><hr color="#E6E6E6" width=100%></html>
              </div>
              <!-- 검색 -->
              <div class="input-group">
                <div class="col-lg-6">
                  <button class="btn btn-lg mr-2 btn btn-xs" type="reset" style="display: none"  onclick="changes1Step(value)"><i class="fa fa-refresh"></i></button>
                </div>
                <button class="btn btn-primary btn btn-xs" style="text-align:center;" type="submit" onclick="search()" id="searchButton">검색</button>
              </div><br>



              <div class="row mb-2 mt-4">
                <div class="col-lg-9 text-left"><p>total:<?echo $cnt;?></p></div>
                <div class="">
                  <select class="form-control form-control-sm" id="order" name='order' onchange="javascript: ascdesc()">
                    <option <?if($_GET['order'] == "asc"){echo "selected";}?> value="asc" selected>오름차순</option>
                    <option <?if($_GET['order'] == "desc"){echo "selected";}?> value="desc">내림차순</option>
                  </select></div>
                  <div class="col-lg-1">
                    <select class="form-control form-control-sm" id="list" name='list'>
                      <option value="10" <?if($_GET['list'] == 10){echo "selected";}else if($_GET['list'] == null){echo "selected";}?>>10</option>
                      <option value="20" <?if($_GET['list'] == 20){echo "selected";}?>>20</option>
                      <option value="30" <?if($_GET['list'] == 30){echo "selected";}?>>30</option>
                    </select></div>
                  </form>
                  <div class="form-group text-right">
                    <a href="api/statsReg/stats_pv_excel.php?date_from=<?echo $date_from;?>&date_to=<?echo $date_to;?>&selectID=<?echo $_GET['selectID'];?>&space_id=<?echo $_GET['space_id'];?>&order=<?echo $_GET['order']?>">
                      <button type="button" class="btn btn-xs text-right" id="searchButton"><i class="fa fa-download"></i>데이터 저장</button>
                    </a>
                  </div>

                  <div class="single-table col-lg-12">
                    <div class="table-responsive"><br>
                      <table class="table table-bordered progress-table text-center">
                        <thead class="text-uppercase">
                          <tr>
                            <th scope="col">순위</th>
                            <th scope="col">구분</th>
                            <th scope="col">공간</th>
                            <th scope="col">컨텐츠</th>
                            <th scope="col">사용 횟수</th>
                            <!-- <th scope="col">비율</th> -->
                            <th scope="col">평균체류시간</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?
                          $sql = "
                          SELECT a.구분, a.pos_id, a.공간, a.컨텐츠, SUM(a.사용횟수) AS '사용횟수', a.avg_dif
                          FROM
                          (
                          	SELECT 'U+tv' AS '구분', a.pos_id, '-' AS '공간', a.DESC AS '컨텐츠', COUNT(a.pos_id) AS '사용횟수', b.avg_dif
                          	FROM did_pos_code
                          	INNER JOIN
                          	(
                          		SELECT pos_id, b.desc
                          		FROM
                          		(
                          			SELECT pos_id, space_id
                          			FROM did_log_type_3
                          			WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
                          			AND space_id NOT IN('s000001', 's000002', 's000003', 's000004')
                          		)a LEFT JOIN
                          		(
                          			SELECT did_bookmark_config.DESC, target_id
                          			FROM did_bookmark_config
                          		)b ON a.space_id = b.target_id
                          	)a ON did_pos_code.pos_code = a.pos_id
                          	LEFT JOIN
                          	(
                          		SELECT *, SEC_TO_TIME(AVG(TIME_TO_SEC(E.diff))) AS avg_dif
                                FROM
                                (
                                    SELECT A.pos_id, A.device_id , @var ,CASE WHEN @user = device_id THEN 0 ELSE  @var := 0 END,
                                    TIMEDIFF( STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s') , @var) AS diff,
                                    @user := device_id,@var := STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')
                                    FROM
                                    (
                                      SELECT *
                          	         FROM did_log_type_3
                          	         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                          	         AND space_id NOT IN('s000001', 's000002', 's000003', 's000004')
                          	         GROUP BY device_id , TIMESTAMP
                          	         ORDER BY device_id,TIMESTAMP
                                    )AS A,(SELECT @var := 0, @user := '' )AS t
                                  )AS E
                                  WHERE E.diff IS NOT NULL AND E.diff < '00:15:00'
                                  GROUP BY E.pos_id
                          	)b ON a.pos_id = b.pos_id
                          	GROUP BY 공간, 컨텐츠
                          	UNION ALL
                          	SELECT 'U+IoT' AS '구분', a.pos_id, a.DESC AS '공간', '-' AS '컨텐츠', COUNT(a.pos_id) AS '사용횟수', b.avg_dif
                          	FROM did_pos_code
                          	INNER JOIN
                          	(
                          		SELECT pos_id, b.desc
                          		FROM
                          		(
                          			SELECT pos_id, space_id
                          			FROM did_log_type_3
                          			WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
                          			$searchSpace1
                          		)a LEFT JOIN
                          		(
                          			SELECT did_space_config.DESC, space_id
                          			FROM did_space_config
                          		)b ON a.space_id = b.space_id
                          	)a ON did_pos_code.pos_code = a.pos_id
                          	LEFT JOIN
                          	(
                          		SELECT *, SEC_TO_TIME(AVG(TIME_TO_SEC(E.diff))) AS avg_dif
                                FROM
                                (
                                    SELECT A.pos_id, A.device_id , @var ,CASE WHEN @user = device_id THEN 0 ELSE  @var := 0 END,
                                    TIMEDIFF( STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s') , @var) AS diff,
                                    @user := device_id,@var := STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')
                                    FROM
                                    (
                                      SELECT *
                          	         FROM did_log_type_3
                          	         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                     $searchSpace1
                          	         GROUP BY device_id , TIMESTAMP
                          	         ORDER BY device_id,TIMESTAMP
                                    )AS A,(SELECT @var := 0, @user := '' )AS t
                                  )AS E
                                  WHERE E.diff IS NOT NULL AND E.diff < '00:15:00'
                                  GROUP BY E.pos_id
                          	)b ON a.pos_id = b.pos_id
                          	GROUP BY 공간, 컨텐츠
                          	UNION ALL
                          	SELECT 'U+IoT' AS '구분', a.pos_id, a.DESC AS '공간', IFNULL(a.contents,'-') AS '컨텐츠', COUNT(a.pos_id) AS '사용횟수', b.avg_dif
                          	FROM did_pos_code
                          	INNER JOIN
                          	(
                          		SELECT pos_id, b.DESC, IFNULL(c.TEXT,d.TEXT) AS 'contents'
                          		FROM
                          		(
                          			SELECT pos_id, page_id, space_id, target_id
                          			FROM did_log_type_4
                          			WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
                                $searchSpace2
                          		)a
                          		LEFT JOIN
                          		(
                          			SELECT did_space_config.DESC, space_id
                          			FROM did_space_config
                          		)b ON a.space_id = b.space_id
                          		LEFT JOIN
                          		(
                          			SELECT did_usecase_config.TEXT, target_id, usescene_id
                          			FROM did_usecase_config
                          		)c ON a.space_id = c.target_id AND a.target_id = c.usescene_id
                          		LEFT JOIN
                              (
                                SELECT did_product_config.TEXT, product_id
                			          FROM did_product_config
                           		)d ON a.target_id = d.product_id
                          	)a ON did_pos_code.pos_code = a.pos_id
                          	LEFT JOIN
                          	(
                          		SELECT *, SEC_TO_TIME(AVG(TIME_TO_SEC(E.diff))) AS avg_dif
                                FROM
                                (
                                    SELECT A.pos_id, A.device_id , @var ,CASE WHEN @user = device_id THEN 0 ELSE  @var := 0 END,
                                    TIMEDIFF( STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s') , @var) AS diff,
                                    @user := device_id,@var := STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')
                                    FROM
                                    (
                                      SELECT *
                          	         FROM did_log_type_4
                          	         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                     $searchSpace2
                          	         GROUP BY device_id , TIMESTAMP
                          	         ORDER BY device_id,TIMESTAMP
                                    )AS A,(SELECT @var := 0, @user := '' )AS t
                                  )AS E
                                  WHERE E.diff IS NOT NULL AND E.diff < '00:15:00'
                                  GROUP BY E.pos_id
                          	)b ON a.pos_id = b.pos_id
                          	GROUP BY 공간, 컨텐츠
                          )a
                          $searchCate
                          GROUP BY 공간, 컨텐츠
                          ORDER BY 사용횟수 $order, pos_id, 공간, 컨텐츠, avg_dif
                          limit $limit, $list
                          ";
                          $conn->DBQ($sql);
                          $conn->DBE();

                          while($row=$conn->DBF()){
                            ?>
                            <tr>
                              <td data-title="순위" scope="row"><?php if($order == 'desc') {echo $i+1;} else if($order == 'asc') {echo $i-1;}?></td>
                              <td data-title="구분" scope="row"><?php echo $row['구분']; ?></td>
                              <td data-title="공간"><?php echo $row['공간'];?></td>
                              <td data-title="컨텐츠"><?php echo $row['컨텐츠'];?></td>
                              <td data-title="사용 횟수"><?php echo number_format($row['사용횟수']); ?></td>
                              <td data-title="평균체류시간">
                                <?php
                                if($row['avg_dif'] == null){
                                  echo '00분 01초';
                                } else {
                                echo substr($row['avg_dif'],3,2).'분 '.substr($row['avg_dif'],6,2).'초';
                                } ?>
                              </td>
                            </tr>
                            <? if($order == 'desc') { $i++; } else if($order == 'asc') { $i--; } }?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <!--/single-table-->
                  </div>
                  <!--/cardbody--><br>
                  <div class="text-center">
                    <ul class="pagination" style="justify-content: center;">
                      <?echo $paging; ?>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <!--/row-->
          </div>

          <!--/container-->
        </div>

        <!-- main content area end -->
        <?$layout->footer($footer);?>
      </div>
      <!-- main wrapper end -->
      <?$layout->JsFile("
      ");?>
      <?$layout->js($js);?>
      <!-- 검색 기본값 선택 스크립트 -->
      <script>
      var order = "<?php echo $order; ?>";
      var list = "<?php echo $list; ?>";




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



      </script>
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
      });





      </script>
    </body>
    </html>
