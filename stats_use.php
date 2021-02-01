<?php
include 'layout/layout.php';
include 'api/dbconn.php';
include 'api/pageClass.php';
include 'api/common.php';
// include 'api/popupReg/testTable.php';

$conn = new DBC();
$conn->DBI();

$layout = new Layout;
$searchSql;
$order = $_GET['order'];
$list2 = $_GET['list'];

if($_GET['order'] == null){
  $order = '';
} else {
  $order = $_GET['order'];
}

if($_GET['list'] == null){
  $list = '10';
} else {
  $list = $_GET['list'];
}

//컬럼추가시 order by 재정렬


$searchDate = " and b.TIMESTAMP >= '" .$date_from. "' and a.TIMESTAMP <= '" .$date_to. " 23:59:59' ";
if($date_from == $date_to){
  $searchDate = "and b.TIMESTAMP like '".str_replace('-', '', $date_from)."%'";}
  else {
    $searchDate = " and b.TIMESTAMP between '".str_replace('-', '', $date_from)."' and '".str_replace('-', '', $date_to)."'";
  }
switch ($_GET['date']) {
  case -1:
  $today = $_GET['today'];
  $today = date('Y-m-d', strtotime("$today -1 days"));
  break;

  case 1:
  $today = $_GET['today'];
  $today = date('Y-m-d', strtotime("$today 1 days"));
  break;

  case -7:
  $curDate2 = $_GET['today'];
  $curDate2 = date('Y-m-d', strtotime("$today -1 week"));
  break;

  case 7:
  $today = $_GET['today'];
  $today = date('Y-m-d', strtotime("$today 1 week"));
  break;

}
?>

<!--datepicker period-->


<!doctype html>
<html class="no-js" lang="kr">
<?$layout->CssJsFile('<link href="assets/css/table-responsive.css" rel="stylesheet">');?>
<?$layout->head($head);?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.2.5/css/fixedColumns.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
<!--serach date script-->
<?
$today = date("Y-m-d");
// $temp = date('w', strtotime($today));
//
// switch ($temp) {
//   // 일요일
//   case 0:
//   $date_from = date("Y-m-d", strtotime("-6 days",  strtotime($today)));
//   $date_to = date("Y-m-d", strtotime("+0 days",  strtotime($today)));
//   break;
//
//   // 월요일
//   case 1:
//   $date_from = date("Y-m-d", strtotime("-0 days",  strtotime($today)));
//   $date_to = date("Y-m-d", strtotime("+6 days",  strtotime($today)));
//   break;
//
//   // 화요일
//   case 2:
//   $date_from = date("Y-m-d", strtotime("-1 days",  strtotime($today)));
//   $date_to = date("Y-m-d", strtotime("+5 days",  strtotime($today)));
//   break;
//
//   // 수요일
//   case 3:
//   $date_from = date("Y-m-d", strtotime("-2 days",  strtotime($today)));
//   $date_to = date("Y-m-d", strtotime("+4 days",  strtotime($today)));
//   break;
//
//   // 목요일
//   case 4:
//   $date_from = date("Y-m-d", strtotime("-3 days",  strtotime($today)));
//   $date_to = date("Y-m-d", strtotime("+3 days",  strtotime($today)));
//   break;
//
//   // 금요일
//   case 5:
//   $date_from = date("Y-m-d", strtotime("-4 days",  strtotime($today)));
//   $date_to = date("Y-m-d", strtotime("+2 days",  strtotime($today)));
//   break;
//
//   // 토요일
//   case 6:
//   $date_from = date("Y-m-d", strtotime("-5 days",  strtotime($today)));
//   $date_to = date("Y-m-d", strtotime("+1 days",  strtotime($today)));
//   break;
// }

?>

<script>
function getFormatDate(date){
  var year = date.getFullYear();                                 //yyyy
  var month = (1 + date.getMonth());                     //M
  month = month >= 10 ? month : '0' + month;     // month 두자리로 저장
  var day = date.getDate();                                        //d
  day = day >= 10 ? day : '0' + day;                            //day 두자리로 저장
  return  year + '-' + month + '-' + day;
}

function setSearchDate(num){
  switch(num){
    case 0:
    document.getElementById('date_from').value = <?php echo json_encode(date('Y-m-d')); ?>;
    document.getElementById('date_to').value = <?php echo json_encode(date('Y-m-d')); ?>;
    document.getElementById('dateSubmit').submit();
    break;

    case 1:
    document.getElementById('date_from').value = <?php echo json_encode(date("Y-m-d",strtotime("-1 days"))); ?>;
    document.getElementById('date_to').value = <?php echo json_encode(date("Y-m-d",strtotime("-1 days"))); ?>;
    document.getElementById('dateSubmit').submit();
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
    document.getElementById('dateSubmit').submit();
    break;

    case 3:
    document.getElementById('date_from').value = <?php echo json_encode(date("Y-m-01", strtotime("-1 month", mktime(0,0,0, date("m"), 1, date("Y"))))); ?>;
    document.getElementById('date_to').value = <?php echo json_encode(date("Y-m-t", strtotime("-1 month", mktime(0,0,0, date("m"), 1, date("Y"))))); ?>;
    document.getElementById('dateSubmit').submit();
    break;

    case 4:
    document.getElementById('date_from').value = <?php echo json_encode(date("Y-m-d",strtotime("-1 months"))); ?>;
    document.getElementById('date_to').value = <?php echo json_encode(date('Y-m-d')); ?>;
    document.getElementById('dateSubmit').submit();
    break;

    case 5:
    document.getElementById('date_from').value = <?php echo json_encode(date("Y-m-d",strtotime("-3 months"))); ?>;
    document.getElementById('date_to').value = <?php echo json_encode(date('Y-m-d')); ?>;
    document.getElementById('dateSubmit').submit();
    break;
  }
}

var date_set1 = 0;
var date_set2 = 0;

function searchdate(num)
{
  switch (num) {
    case 0:
    date_set1 = 1;
    break;

    case 1:
    date_set2 = 2;
    break;
  }

  if((date_set1 + date_set2) == 3) {
    document.getElementById('dateSubmit').submit();
  }
}
</script>
<!--/search date script-->
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
      <form method="get" action="<?=$_SERVER['PHP_SELF']?>" id="dateSubmit">
      <div class="container">
        <div class="row">
          <div class="col-lg-6"><h5>가입혜택(견적) 사용이력 </h5></div>
          <div class="col-lg-6" style="text-align: right;"><small> Main > 가입혜택(견적) 사용이력 </small></div>
          <!--기간-->
          <html><hr color="black" width=100%></html>
          <div class="card col-lg-12 mt-3">
            <div class="card-body">
              <style>
              hr{margin:1px;}
              </style>
              <div class="form-group">
                <html><hr color="#E6E6E6" width=100%></html>
                <div class="col-lg-12">
                  <div class="input-group">
                    <span class="input-group form-control2 form-control-sm col-lg-1">기간</span>
                    <input data-toggle="datepicker" type="text" class="form-control form-control-sm col-lg-1" style="margin-left:20px" id="date_from" name="date_from" onchange="searchdate(0)" readonly="readonly" value="<?echo $date_from?>">
                    <div class="input-group-prepend">
                      <div class="input-group form-control form-control-sm" readonly="">~</div>
                    </div>
                    <input data-toggle="datepicker" type="text" class="form-control form-control-sm col-lg-1" id="date_to" name="date_to" onchange="searchdate(1)" readonly="readonly" value="<?echo $date_to?>">
                    <button type="button" style="margin-left:10px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType1" value="" onclick="setSearchDate(0)"/>오늘</button>
                    <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType2" onclick="setSearchDate(1)"/>어제</button>
                    <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="weekk" id="weekk" value ="<?echo $_GET['weekk']?>" onclick="setSearchDate(2)" >일주일</button>
                    <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType4" onclick="setSearchDate(3)"/>지난달</button>
                    <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType5" onclick="setSearchDate(4)"/>1개월</button>
                    <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType6" onclick="setSearchDate(5)"/>3개월</button>
                  </div>
                </div>
                <html><hr color="#E6E6E6" width=100%></html>
              </div>
              <!--/form-group-->
              <div style="text-align:right"><img src="img/gigi5.png" alt=""></div>
              <div class="single-table">
                <div class="table-responsive">
                  <table class="table table-bordered text-center col-lg-12" id="table1" name="table1">
                    <thead class="text-uppercase" style="font-size:12px">
                      <tr>
                        <td></td>
                        <td>AI리모컨</td>
                        <td>스위치</td>
                        <td width="8%">멀티탭</td>
                        <td>열림알리미</td>
                        <td>숙면등</td>
                        <td>숙면알리미</td>
                        <td>CCTV</td>
                        <td>가스잠그미</td>
                        <td>플러그</td>
                        <td>공기질알리미</td>
                        <td>간편버튼</td>
                        <td>전기료알리미</td>
                      </tr>
                    </thead>

                    <tbody>
                      <!--추가총합-->
                      <?
                      $sql = "
                      SELECT COUNT(target_id),
                      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000001' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'AI리모컨 추가',
                      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000002' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '스위치 추가',
                      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000003' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '멀티탭 추가',
                      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000004' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '열림알리미 추가',
                      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000005' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면등 추가',
                      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000006' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면알리미 추가',
                      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000007' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'CCTV 추가',
                      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000008' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '가스잠그미 추가',
                      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000009' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '플러그 추가',
                      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000010' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '공기질알리미 추가',
                      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000011' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '간편버튼 추가',
                      (SELECT COUNT(target_id) FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code WHERE target_id='p000012' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '전기료알리미 추가'
                      FROM did_log_type_5 inner JOIN did_pos_code ON did_log_type_5.pos_id = did_pos_code.pos_code
                      WHERE target_id != 'id000001' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
                      ";
                      $conn->DBQ($sql);
                      $conn->DBE();
                      $row=$conn->DBP();
                      ?>
                      <style>
                      font {
                        cursor: pointer;
                      }
                      </style>
                      <tr>
                        <td name="view12" id="view12" width=10%><font color="blue">추가총합</td></font>
                        <td name="view0" id="view0"><font color="blue"><?php if($row[0][1]==null){echo '0';}else{echo $row[0][1];} ?></font></td>
                        <td name="view1" id="view1"><font color="blue"><?php if($row[0][2]==null){echo '0';}else{echo $row[0][2];} ?></font></td>
                        <td name="view2" id="view2"><font color="blue"><?php if($row[0][3]==null){echo '0';}else{echo $row[0][3];} ?></font></td>
                        <td name="view3" id="view3"><font color="blue"><?php if($row[0][4]==null){echo '0';}else{echo $row[0][4];} ?></font></td>
                        <td name="view4" id="view4"><font color="blue"><?php if($row[0][5]==null){echo '0';}else{echo $row[0][5];} ?></font></td>
                        <td name="view5" id="view5"><font color="blue"><?php if($row[0][6]==null){echo '0';}else{echo $row[0][6];} ?></font></td>
                        <td name="view6" id="view6"><font color="blue"><?php if($row[0][7]==null){echo '0';}else{echo $row[0][7];} ?></font></td>
                        <td name="view7" id="view7"><font color="blue"><?php if($row[0][8]==null){echo '0';}else{echo $row[0][8];} ?></font></td>
                        <td name="view8" id="view8"><font color="blue"><?php if($row[0][9]==null){echo '0';}else{echo $row[0][9];} ?></font></td>
                        <td name="view9" id="view9"><font color="blue"><?php if($row[0][10]==null){echo '0';}else{echo $row[0][10];} ?></font></td>
                        <td name="view10" id="view10"><font color="blue"><?php if($row[0][11]==null){echo '0';}else{echo $row[0][11];} ?></font></td>
                        <td name="view11" id="view11"><font color="blue"><?php if($row[0][12]==null){echo '0';}else{echo $row[0][12];} ?></font></td>
                      </tr>
                      <!--삭제총합-->
                      <?
                      $sql = "
                      SELECT COUNT(target_id),
                      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000001' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'AI리모컨 삭제',
                      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000002' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '스위치 삭제',
                      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000003' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '멀티탭 삭제',
                      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000004' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '열림알리미 삭제',
                      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000005' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면등 삭제',
                      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000006' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면알리미 삭제',
                      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000007' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'CCTV 삭제',
                      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000008' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '가스잠그미 삭제',
                      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000009' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '플러그 삭제',
                      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000010' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '공기질알리미 삭제',
                      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000011' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '간편버튼 삭제',
                      (SELECT COUNT(target_id) FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code WHERE target_id='p000012' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '전기료알리미 삭제'
                      FROM did_log_type_6 inner JOIN did_pos_code ON did_log_type_6.pos_id = did_pos_code.pos_code
                      WHERE target_id != 'id000001' and DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
                      ";
                      $conn->DBQ($sql);
                      $conn->DBE();
                      $row2=$conn->DBP();
                      ?>
                      <tr>
                        <td name="unview12" id="unview12" width=10%><font color="blue">삭제총합</td></font>
                        <td name="unview0" id="unview0"><font color="blue"><?php if($row2[0][1]==null){echo '0';}else{echo $row2[0][1];} ?></font></td>
                        <td name="unview1" id="unview1"><font color="blue"><?php if($row2[0][2]==null){echo '0';}else{echo $row2[0][2];} ?></font></td>
                        <td name="unview2" id="unview2"><font color="blue"><?php if($row2[0][3]==null){echo '0';}else{echo $row2[0][3];} ?></font></td>
                        <td name="unview3" id="unview3"><font color="blue"><?php if($row2[0][4]==null){echo '0';}else{echo $row2[0][4];} ?></font></td>
                        <td name="unview4" id="unview4"><font color="blue"><?php if($row2[0][5]==null){echo '0';}else{echo $row2[0][5];} ?></font></td>
                        <td name="unview5" id="unview5"><font color="blue"><?php if($row2[0][6]==null){echo '0';}else{echo $row2[0][6];} ?></font></td>
                        <td name="unview6" id="unview6"><font color="blue"><?php if($row2[0][7]==null){echo '0';}else{echo $row2[0][7];} ?></font></td>
                        <td name="unview7" id="unview7"><font color="blue"><?php if($row2[0][8]==null){echo '0';}else{echo $row2[0][8];} ?></font></td>
                        <td name="unview8" id="unview8"><font color="blue"><?php if($row2[0][9]==null){echo '0';}else{echo $row2[0][9];} ?></font></td>
                        <td name="unview9" id="unview9"><font color="blue"><?php if($row2[0][10]==null){echo '0';}else{echo $row2[0][10];} ?></font></td>
                        <td name="unview10" id="unview10"><font color="blue"><?php if($row2[0][11]==null){echo '0';}else{echo $row2[0][11];} ?></font></td>
                        <td name="unview11" id="unview11"><font color="blue"><?php if($row2[0][12]==null){echo '0';}else{echo $row2[0][12];} ?></font></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- table=responsive -->
              </div><br>
              <!-- /single-table -->
              <div class="col-lg-11"></div>
              <div class="form-group text-right">
                <a target="_blank" href="api/statsReg/stats_use_excel.php?&date_from=<?echo $date_from;?>&date_to=<?echo $date_to;?>&order=<?echo $order;?>&list=<?echo $list;?>">
                  <button type="button" target="_blank" onclick="windows.open('api/statsReg/stats_use_excel.php?&date_from=<?echo $date_from;?>&date_to=<?echo $date_to;?>&order=<?echo $order;?>&list=<?echo $list;?>');" class="btn btn-xs text-right"
                    id="searchButton"><i class="fa fa-download"></i>데이터 저장</button></a>
                </div>

              <!--테이블 스크롤 및 크기고정 스타일-->
              <style>
              table {
                width: 100%;
              	margin: 0 auto;
              	clear: both;
              	border-collapse: collapse !important;
              	border-spacing: 0;
              }
              table td{
                height : 40px !important;
              }
              div.dataTables_wrapper div.dataTables_length select{
                width:60px;
              }
              div.dataTables_wrapper{
                margin: 0 auto;
              }
              .dataTables_paginate {
                  float: left  !important;
                  margin-left:530px !important;
              }
              table.dataTable thead .sorting,
              table.dataTable thead .sorting_asc,
              table.dataTable thead .sorting_desc {
                  background : none;
              }
              .dataTables_wrapper .dataTables_paginate .paginate_button {
              padding : 0px;
              margin-left: 0px;
              border: 0px;
              }
              .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                  border: 0px;
              }

              th { white-space: nowrap; }
              </style>
              <div class="single-table ">
                    <table class="data-tables row-border order-column text-center" id="sample" name="table1">
                      <!-- 전체 추가테이블-->
                      <thead>
                        <tr style ="font-size:15px">
                          <th>No</th>
                          <th>영업담당</th>
                          <th>지원팀</th>
                          <th>운영자명</th>
                          <th>매장명</th>
                          <th>매장코드</th>
                          <th>AI리모컨 추가</th>
                          <th>AI리모컨 삭제</th>
                          <th>스위치 추가</th>
                          <th>스위치 삭제</th>
                          <th>멀티탭 추가</th>
                          <th>멀티탭 삭제</th>
                          <th>열림알리미 추가</th>
                          <th>열림알리미 삭제</th>
                          <th>숙면등 추가</th>
                          <th>숙면등 삭제</th>
                          <th>숙면알리미 추가</th>
                          <th>숙면알리미 삭제</th>
                          <th>CCTV 추가</th>
                          <th>CCTV 삭제</th>
                          <th>가스잠그미 추가</th>
                          <th>가스잠그미 삭제</th>
                          <th>플러그 추가</th>
                          <th>플러그 삭제</th>
                          <th>공기질알리미 추가</th>
                          <th>공기질알리미 삭제</th>
                          <th>간편버튼 추가</th>
                          <th>간편버튼 삭제</th>
                          <th>전기료알리미 추가</th>
                          <th>전기료알리미 삭제</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?
                         $i=0;
                         $sql = "
                         SELECT pos.CHANNEL AS '영업담당', pos.bg_code AS '지원팀' , pos.agency_name AS '운영자명', pos.pos_name AS '매장명',
                                 a.pos_id AS '매장코드', pos.pos_address AS '매장주소',
                                 (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000001' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'AI리모컨추가',
                                 (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000001' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'AI리모컨삭제',
                                 (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000002' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '스위치추가',
                                 (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000002' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '스위치삭제',
                                 (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000003' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '멀티탭추가',
                                 (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000003' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '멀티탭삭제',
                                 (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000004' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '열림알리미추가',
                                 (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000004' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '열림알리미삭제',
                                 (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000005' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면등추가',
                                 (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000005' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면등삭제',
                                 (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000006' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면알리미추가',
                                 (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000006' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '숙면알리미삭제',
                                 (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000007' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'CCTV추가',
                                 (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000007' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') 'CCTV삭제',
                                 (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000008' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '가스잠그미추가',
                                 (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000008' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '가스잠그미삭제',
                                 (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000009' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '플러그추가',
                                 (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000009' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '플러그삭제',
                                 (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000010' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '공기질알리미추가',
                                 (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000010' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '공기질알리미삭제',
                                 (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000011' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '간편버튼추가',
                                 (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000011' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '간편버튼삭제',
                                 (SELECT COUNT(target_id) FROM did_log_type_5 WHERE target_id = 'p000012' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '전기료알리미추가',
                                 (SELECT COUNT(target_id) FROM did_log_type_6 WHERE target_id = 'p000012' AND pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."') '전기료알리미삭제',
                                 (
                                 SELECT COUNT(target_id)
                                 FROM (
                                 SELECT *
                                 FROM did_log_type_5 UNION ALL
                                 SELECT *
                                 FROM did_log_type_6)b
                                 WHERE b.pos_id = a.pos_id AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
                                 )AS '총합'
                         FROM(
                         SELECT * FROM did_log_type_5
                         UNION ALL
                         SELECT * FROM did_log_type_6
                         )a
                         RIGHT JOIN did_pos_code pos ON a.pos_id = pos.pos_code
                         WHERE a.target_id != 'id000001' AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
                         GROUP BY a.pos_id ORDER BY 총합 desc, 매장코드
                         ";
                         $conn->DBQ($sql);
                         $conn->DBE();
                         $resCnt = $conn->resultRow();
                         while($row3=$conn->DBF()){
                         ?>
                         <tr name="all_item[]" style="font-size:13px">
                           <td name="no"><?php echo $i+1; ?></td>
                           <td name="no"><?php if($row3['영업담당'] == '홈/미디어'){echo '스마트홈';}else{echo $row3['영업담당'];} ?></td>
                           <td name="no"><?php echo $row3['지원팀']; ?></td>
                           <td name="no"><?php echo $row3['운영자명']; ?></td>
                           <td name="no"><?php echo $row3['매장명']; ?></td>
                           <td name="no"><?php echo $row3['매장코드']; ?></td>
                           <td name="no" class="remote_add"><?php echo $row3['AI리모컨추가']; ?></td>
                           <td name="no" class="remote_del"><?php echo $row3['AI리모컨삭제']; ?></td>
                           <td name="no" class="switch_add"><?php echo $row3['스위치추가']; ?></td>
                           <td name="no" class="switch_del"><?php echo $row3['스위치삭제']; ?></td>
                           <td name="no" name="no" class="multi_add"><?php echo $row3['멀티탭추가']; ?></td>
                           <td name="no" class="multi_del"><?php echo $row3['멀티탭삭제']; ?></td>
                           <td name="no" class="open_add"><?php echo $row3['열림알리미추가']; ?></td>
                           <td name="no" class="open_del"><?php echo $row3['열림알리미삭제']; ?></td>
                           <td name="no" class="light_add"><?php echo $row3['숙면등추가']; ?></td>
                           <td name="no" class="light_del"><?php echo $row3['숙면등삭제']; ?></td>
                           <td name="no" class="sleep_add"><?php echo $row3['숙면알리미추가']; ?></td>
                           <td name="no" class="sleep_del"><?php echo $row3['숙면알리미삭제']; ?></td>
                           <td name="no" class="cctv_add"><?php echo $row3['CCTV추가']; ?></td>
                           <td name="no" class="cctv_del"><?php echo $row3['CCTV삭제']; ?></td>
                           <td name="no" class="gas_add"><?php echo $row3['가스잠그미추가']; ?></td>
                           <td name="no" class="gas_del"><?php echo $row3['가스잠그미삭제']; ?></td>
                           <td name="no" class="plug_add"><?php echo $row3['플러그추가']; ?></td>
                           <td name="no" class="plug_del"><?php echo $row3['플러그삭제']; ?></td>
                           <td name="no" class="air_add"><?php echo $row3['공기질알리미추가']; ?></td>
                           <td name="no" class="air_del"><?php echo $row3['공기질알리미삭제']; ?></td>
                           <td name="no" class="button_add"><?php echo $row3['간편버튼추가']; ?></td>
                           <td name="no" class="button_del"><?php echo $row3['간편버튼삭제']; ?></td>
                           <td name="no" class="elec_add"><?php echo $row3['전기료알리미추가']; ?></td>
                           <td name="no" class="elec_del"><?php echo $row3['전기료알리미삭제']; ?></td>
                         </tr>
                         <?$i++;}?>
                      </tbody>
                      <!-- /전체 추가테이블-->
                    </table>
              </div>
            </div>
          </div>
        </div>
        <!-- /row -->
      </div>
      <!-- /container -->
      </form>
    </div>
    <!-- main-content-inner -->
  </div>
  <!-- /horizontal-main-wrapper -->
  <?$layout->footer($footer);?>
  <!-- main wrapper end -->

  <?$layout->js($js);?>
  <!-- Start datatable js -->
  <?include 'api/statsReg/use_datatablescript.php';?>

</body>
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
<!-- <script>
  $(document).ready(function(){
    $.ajax({
      url: 'api/statsReg/use_sipa.php',
      type: 'GET',
      dataType: 'JSON',
      data: { date_from: $("#date_from").val(), date_to: $("#date_to").val() },
      success : function(result)
      {
        $("#view0").html(result.data);
      }
    });
  }); -->
  </script>
</html>
