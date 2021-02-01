<?php
include 'layout/layout.php';
include 'api/dbconn.php';
include 'api/common.php';
// include 'api/statsReg/period_explode.php';

$conn = new DBC();
$conn->DBI();

if($_GET['date_set'] == null)
{
  $date = date("Y-m-d");
}
else
{
  $date = $_GET['date_set'];
}



$sql = "select * from pre_period where timestamp >= '".$date_from."' and timestamp <= '".$date_to."' ";
$conn->DBQ($sql);
$conn->DBE();
$row=$conn->DBF();

$install_table = explode('|',$row[1]);
$app_util = explode('|',$row[2]);
$app_use = explode('|',$row[3]);

$layout = new Layout;

?>

<!doctype html>
<html class="no-js" lang="kr">
<?$layout->CssJsFile('<link href="assets/css/table-responsive.css" rel="stylesheet">');?>
<?$layout->head($head);?>

<script>
<?php $curDate = date("Y-m-d",strtotime("-1 days"));?>
<?php $curDate1 = date("Y-m-d",strtotime("+1 days"));?>

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
    var date_from = new Date(document.getElementById('date_set').value);
    var date_to = new Date(document.getElementById('date_set').value);
    var tempDate = new Date(document.getElementById('date_set').value).getDay();

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

    // case 1:
    // var date_from = new Date(document.getElementById('date_set').value);
    // var date_to = new Date(document.getElementById('date_set').value);
    //
    // new Date(date_from.setDate(date_from.getDate()-1));
    // date_from = getFormatDate(date_from);
    // document.getElementById('date_set').value = date_from;
    //
    // var date_from = new Date(document.getElementById('date_set').value);
    // var date_to = new Date(document.getElementById('date_set').value);
    // var tempDate = new Date(document.getElementById('date_set').value).getDay();
    // switch (tempDate) {
    //   // 일요일
    //   case 0:
    //   new Date(date_from.setDate(date_from.getDate()-6));
    //   new Date(date_to.setDate(date_to.getDate()+0));
    //
    //   break;
    //
    //   // 월요일
    //   case 1:
    //   new Date(date_from.setDate(date_from.getDate()-0));
    //   new Date(date_to.setDate(date_to.getDate()+6));
    //   break;
    //
    //   // 화요일
    //   case 2:
    //   new Date(date_from.setDate(date_from.getDate()-1));
    //   new Date(date_to.setDate(date_to.getDate()+5));
    //   break;
    //
    //   // 수요일
    //   case 3:
    //   new Date(date_from.setDate(date_from.getDate()-2));
    //   new Date(date_to.setDate(date_to.getDate()+4));
    //   break;
    //
    //   // 목요일
    //   case 4:
    //   new Date(date_from.setDate(date_from.getDate()-3));
    //   new Date(date_to.setDate(date_to.getDate()+3));
    //   break;
    //
    //   // 금요일
    //   case 5:
    //   new Date(date_from.setDate(date_from.getDate()-4));
    //   new Date(date_to.setDate(date_to.getDate()+2));
    //   break;
    //
    //   // 토요일
    //   case 6:
    //   new Date(date_from.setDate(date_from.getDate()-1));
    //   new Date(date_to.setDate(date_to.getDate()+5));
    //   break;
    // }
    // date_from = getFormatDate(date_from);
    // date_to = getFormatDate(date_to);
    // document.getElementById('date_from').value = date_from;
    // document.getElementById('date_to').value = date_to;
    // break;
    //
    // case 2:
    // var date_from = new Date(document.getElementById('date_set').value);
    // var date_to = new Date(document.getElementById('date_set').value);
    //
    // new Date(date_from.setDate(date_from.getDate()+1));
    // date_to = getFormatDate(date_from);
    // document.getElementById('date_set').value = date_to;
    //
    // var date_from = new Date(document.getElementById('date_set').value);
    // var date_to = new Date(document.getElementById('date_set').value);
    // var tempDate = new Date(document.getElementById('date_set').value).getDay();
    // switch (tempDate) {
    //   // 일요일
    //   case 0:
    //   new Date(date_from.setDate(date_from.getDate()-6));
    //   new Date(date_to.setDate(date_to.getDate()+0));
    //   break;
    //
    //   // 월요일
    //   case 1:
    //   new Date(date_from.setDate(date_from.getDate()-0));
    //   new Date(date_to.setDate(date_to.getDate()+6));
    //   break;
    //
    //   // 화요일
    //   case 2:
    //   new Date(date_from.setDate(date_from.getDate()-1));
    //   new Date(date_to.setDate(date_to.getDate()+5));
    //   break;
    //
    //   // 수요일
    //   case 3:
    //   new Date(date_from.setDate(date_from.getDate()-2));
    //   new Date(date_to.setDate(date_to.getDate()+4));
    //   break;
    //
    //   // 목요일
    //   case 4:
    //   new Date(date_from.setDate(date_from.getDate()-3));
    //   new Date(date_to.setDate(date_to.getDate()+3));
    //   break;
    //
    //   // 금요일
    //   case 5:
    //   new Date(date_from.setDate(date_from.getDate()-4));
    //   new Date(date_to.setDate(date_to.getDate()+2));
    //   break;
    //
    //   // 토요일
    //   case 6:
    //   new Date(date_from.setDate(date_from.getDate()-5));
    //   new Date(date_to.setDate(date_to.getDate()+1));
    //   break;
    // }
    // date_from = getFormatDate(date_from);
    // date_to = getFormatDate(date_to);
    // document.getElementById('date_from').value = date_from;
    // document.getElementById('date_to').value = date_to;
    // break;
  }
}

</script>

<style>
form{border:1px solid #E6E6E6;}
hr{margin:1px;}
</style>
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


    <!-- 카테고리-->
    <div class="main-content-inner">
      <div class="container">
        <div class="row">
          <div class="col-lg-6"><h5>기간별 통계 </h5></div>
          <div class="col-lg-6" style="text-align: right;"><small> Main > 기간별 통계 </small></div>
          <style>
          form{border:1px solid #E6E6E6;}
          hr{margin:1px;}
          </style>
          <html><hr color="black" width=100%></html>

          <div class="card col-lg-12 mt-3">
            <div class="card-body">
              <form action="<?$_SERVER['PHP_SELF']?>" method="GET" id='date_form'>

                <!-- 기간 -->
                <div class="form-group">
                  <div class="col-lg-12">
                    <div class="input-group">
                      <span class="input-group form-control2 form-control-sm col-lg-1">기준일</span>
                      <input id="date_set" onchange="setSearchDate(0)" name="date_set" data-toggle="datepicker" type="text" class="col-lg-1 form-control form-control-sm" style="margin-left:5px" readonly="readonly"
                      value="<?if($_GET['date_set']==null){echo date("Y-m-d");} else if($_GET['date_set']!=null){echo $_GET['date_set'];}?>">
                      <input type="hidden" name="date_to" id="date_from" value="<?php echo $date_from; ?>">
                      <input type="hidden" name="date_to" id="date_to" value="<?php echo $date_to; ?>">
                      <!-- <input type="text" class="form-control form-control-sm col-lg-1 ml-2" id="date_from" name="date_from" readonly="" value="<?//echo $date_from;?>">
                      <div class="input-group-prepend">
                        <div class="input-group-text form-control form-control-sm">~</div>
                      </div>
                      <input type="text" class="form-control form-control-sm col-lg-1" id="date_to" name="date_to" readonly="" value="<?//echo $date_to;?>">
                      &nbsp; &nbsp;
                      <button type="button" class="previous btn btn-xs btn-outline-dark" style="margin-left:5px" name="dateType" id="dateType1" onclick="setSearchDate(1)">‹</button>
                      <button type="button" class="next btn btn-xs btn-outline-dark" name="dateType" name="dateType" id="dateType2" onclick="setSearchDate(2)">›</button>
                      <p style="margin-left:5px"><small>선택한 날짜부터 8주전까지의 결과가 보여집니다.</small></p> -->
                    </div>
                  </div>
                  <html><hr color="#E6E6E6" width=100%></html>
                </div>

                <!-- 검색 -->
                <div class="input-group">
                  <div class="col-lg-6">
                    <button style="display:none;" class="btn btn-lg mr-2 btn btn-xs" type="reset" value="" onclick="changes1Step(value)"><i class="fa fa-refresh"></i></button>
                  </div>
                  <button class="btn btn-primary btn btn-xs" style="text-align:center;" type="submit" id="searchButton">검색</button>
                </div><br>
              </form>

              <!--/formgroup-->

              <div class="input-group">
                <div class="col-lg-6"></div>
                <div style="text-align:right" class="col-lg-6"><br>
                  <div class="">
                    <a href="api/statsReg/stats_period_excel.php?date_from=<?echo $date_from;?>&date_to=<?echo $date_to;?>">
                    <button class="btn btn-xs" type="button" id="searchButton"><i class="fa fa-download"></i>데이터 저장</button></a>
                  </div>
                </div>
              </div>

              <?
              $date_from2 = str_replace('-', '/', $date_from);
              $date_to2 = str_replace('-', '/', $date_to);

              $first_day1 = date("Y-m-d", strtotime("-7 week",  strtotime($date_from)));
              $end_day1 = date("Y-m-d", strtotime("-7 week",  strtotime($date_to)));

              $first_day2 = date("Y/m/d", strtotime("-7 week",  strtotime($date_from2)));
              $end_day2 = date("Y/m/d", strtotime("-7 week",  strtotime($date_to2)));

              $first_month1 = date("Y-".substr($date_from,5,2)."-01");
              $end_month1 = date("Y-".substr($date_from,5,2)."-31");
              $first_month2 = date("Y/".substr($date_from2,5,2)."/01");
              $end_month2 = date("Y/".substr($date_from2,5,2)."/31");

              $fourWeek_first1 = $date_from;
              $fourWeek_end1 = $date_to;

              $fourWeek_first2 = str_replace('-', '/', $date_from);
              $fourWeek_end2 = str_replace('-','/', $date_to);
              ?>
              <!-- 설치 비율 -->
              <div class="input-group">
                <div class="col-lg-2"><p>1.설치 비율</p></div>
                <div class="col-lg-10 text-right"></div>
                <div class="col-lg-12"><small>POS 코드 전체 중 앱 설치 비율(전체 POS코드 중 P123456, A CODE를 제외한 코드 대상 앱 설치 POS코드 수)</small></div>

                <div class="col-lg-4">
                  <div class="single-table">
                    <div class="table-responsive">
                      <table class="table table-bordered text-center">
                        <tr>
                          <td>전체 수</td>
                          <td id="total_store">
                            <? echo number_format($install_table[0]); ?>
                            </td>
                        </tr>
                        <tr>
                          <td>설치 수</td>
                          <td id="installed_store">
                            <? echo number_format($install_table[1]); ?>
                          </td>
                        </tr>
                        <tr>
                          <td>설치 비율</td>
                          <td><font color="red" id="installed_percent">
                            <? echo $install_table[2]; ?>
                          </font>
                          </td>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="single-table">
                      <div class="table-responsive">
                        <table class="table table-bordered text-center">
                          <tr>
                            <td>앱 설치</td>
                            <td id="installed_store2">
                            <? echo number_format($install_table[3]) ?>
                            </td>
                          </tr>
                          <tr>
                            <td>총 앱 실행</td>
                            <td id="exec_app_total">
                              <? echo number_format($install_table[4]) ?>
                            </td>
                          </tr>
                          <tr>
                            <td>총 앱 실행 비율</td>
                            <td id="exec_app_total_percent">
                              <? echo $install_table[5]; ?>
                            </td>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <br>

                  <!-- 앱 이용률 -->
                  <div class="col-lg-2"><p>2.앱 이용률</p></div>
                  <div class="col-lg-10 text-right"></div>

                  <div class="col-lg-12">
                    <div class="single-table">
                      <div class="table-responsive">
                        <table class="table table-bordered text-center">
                          <thead class="text-uppercase">
                            <tr>
                              <th scope="col">기간</th>
                      				<th scope="col">기간별 총 실행 매장 수</th>
                      				<th scope="col">기간별 신규등록 매장 수</th>
                      				<th scope="col">기간별 실행 비중</th>
                      				<th scope="col">지난주 대비 실행률</th>
                            </tr>
                          </thead>
                          <tbody id="app_array">
                            <tr>
                              <td><? echo $app_util[0]; ?></td>
                              <td><? echo $app_util[1]; ?></td>
                              <td><? echo $app_util[2]; ?></td>
                              <td><? echo $app_util[3]; ?></td>
                              <td><font color="red"><? echo $app_util[4]; ?></font></td>
                            </tr>
                            <tr>
                              <td><? echo $app_util[5]; ?></td>
                              <td><? echo $app_util[6]; ?></td>
                              <td><? echo $app_util[7]; ?></td>
                              <td><? echo $app_util[8]; ?></td>
                              <td><font color="red"><? echo $app_util[9]; ?></font></td>
                            </tr>
                            <tr>
                              <td><? echo $app_util[10]; ?></td>
                              <td><? echo $app_util[11]; ?></td>
                              <td><? echo $app_util[12]; ?></td>
                              <td><? echo $app_util[13]; ?></td>
                              <td><font color="red"><? echo $app_util[14]; ?></font></td>
                            </tr>
                            <tr>
                              <td><? echo $app_util[15]; ?></td>
                              <td><? echo $app_util[16]; ?></td>
                              <td><? echo $app_util[17]; ?></td>
                              <td><? echo $app_util[18]; ?></td>
                              <td><font color="red"><? echo $app_util[19]; ?></font></td>
                            </tr>
                            <tr>
                              <td><? echo $app_util[20]; ?></td>
                              <td><? echo $app_util[21]; ?></td>
                              <td><? echo $app_util[22]; ?></td>
                              <td><? echo $app_util[23]; ?></td>
                              <td><font color="red"><? echo $app_util[24]; ?></font></td>
                            </tr>
                            <tr>
                              <td><? echo $app_util[25]; ?></td>
                              <td><? echo $app_util[26]; ?></td>
                              <td><? echo $app_util[27]; ?></td>
                              <td><? echo $app_util[28]; ?></td>
                              <td><font color="red"><? echo $app_util[29]; ?></font></td>
                            </tr>
                            <tr>
                              <td><? echo $app_util[30]; ?></td>
                              <td><? echo $app_util[31]; ?></td>
                              <td><? echo $app_util[32]; ?></td>
                              <td><? echo $app_util[33]; ?></td>
                              <td><font color="red"><? echo $app_util[34]; ?></font></td>
                            </tr>
                            <tr>
                              <td><? echo $app_util[35]; ?></td>
                              <td><? echo $app_util[36]; ?></td>
                              <td><? echo $app_util[37]; ?></td>
                              <td><? echo $app_util[38]; ?></td>
                              <td><font color="red"><? echo $app_util[39]; ?></font></td>
                            </tr>
                          </tbody>
                        </table>
                        <!-- /table -->
                      </div>
                      <!-- /table-responsive -->
                    </div>
                    <!-- single-table -->
                  </div><br>

                  <!-- 앱 이용건수 -->
                  <div class="col-lg-2"><p>3.앱 이용건수</p></div>
                  <div class="col-lg-10 text-right"></div>

                  <div class="col-lg-12 mt-3">
                    <div class="single-table">
                      <div class="table-responsive">
                        <table class="table table-bordered text-center">
                          <thead>
                            <tr>
                              <th rowspan="2" colspan="2">구분</th>
                              <th colspan="3">전체</th>
                              <th colspan="3">U+tv 체험하기</th>
                              <th colspan="3">U+IoT 체험하기</th>
                            </tr>
                            <tr>
                              <th>지난달</th>
                              <th>이번달(누적)</th>
                              <th>이번주</th>
                              <th>지난달</th>
                              <th>이번달(누적)</th>
                              <th>이번주</th>
                              <th>지난달</th>
                              <th>이번달(누적)</th>
                              <th>이번주</th>
                            </tr>
                          </thead>

                          <tbody>
                            <tr>
                              <td rowspan="2">UV</td>
                              <td>전체</td>
                              <td data-title="전체/지난달/전체"><?php echo number_format($app_use[3]+$app_use[6]); ?></td>
                              <td data-title="전체/이번달/전체"><?php echo number_format($app_use[4]+$app_use[7]); ?></td>
                              <td data-title="전체/이번주/전체"><?php echo number_format($app_use[5]+$app_use[8]); ?></td>
                              <td data-title="tv/지난달/전체"><?php echo number_format($app_use[3]); ?></td>
                              <td data-title="tv/이번달/전체"><?php echo number_format($app_use[4]); ?></td>
                              <td data-title="tv/이번주/전체"><?php echo number_format($app_use[5]); ?></td>
                              <td data-title="iot/지난달/전체"><?php echo number_format($app_use[6]); ?></td>
                              <td data-title="iot/이번달/전체"><?php echo number_format($app_use[7]); ?></td>
                              <td data-title="iot/이번주/전체"><?php echo number_format($app_use[8]); ?></td>
                            </tr>
                            <tr>
                              <td>P코드점</td>
                              <td data-title="전체/지난달/P코드점"><?php echo number_format($app_use[12]+$app_use[15]); ?></td>
                              <td data-title="전체/이번달/P코드점"><?php echo number_format($app_use[13]+$app_use[16]); ?></td>
                              <td data-title="전체/이번주/P코드점"><?php echo number_format($app_use[14]+$app_use[17]); ?></td>
                              <td data-title="tv/지난달/P코드점"><?php echo number_format($app_use[12]); ?></td>
                              <td data-title="tv/이번달/P코드점"><?php echo number_format($app_use[13]); ?></td>
                              <td data-title="tv/이번주/P코드점"><?php echo number_format($app_use[14]); ?></td>
                              <td data-title="iot/지난달/P코드점"><?php echo number_format($app_use[15]); ?></td>
                              <td data-title="iot/이번달/P코드점"><?php echo number_format($app_use[16]); ?></td>
                              <td data-title="iot/이번주/P코드점"><?php echo number_format($app_use[17]); ?></td>
                            </tr>
                            <tr>
                              <td rowspan="2">PV</td>
                              <td>전체</td>
                              <td data-title="전체/지난달/전체"><?php echo number_format($app_use[21]+$app_use[24]); ?></td>
                              <td data-title="전체/이번달/전체"><?php echo number_format($app_use[22]+$app_use[25]); ?></td>
                              <td data-title="전체/이번주/전체"><?php echo number_format($app_use[23]+$app_use[26]); ?></td>
                              <td data-title="tv/지난달/전체"><?php echo number_format($app_use[21]); ?></td>
                              <td data-title="tv/이번달/전체"><?php echo number_format($app_use[22]); ?></td>
                              <td data-title="tv/이번주/전체"><?php echo number_format($app_use[23]); ?></td>
                              <td data-title="iot/지난달/전체"><?php echo number_format($app_use[24]); ?></td>
                              <td data-title="iot/이번달/전체"><?php echo number_format($app_use[25]); ?></td>
                              <td data-title="iot/이번주/전체"><?php echo number_format($app_use[26]); ?></td>
                            </tr>
                            <tr>
                              <td>P코드점</td>
                              <td data-title="전체/지난달/P코드점"><?php echo number_format($app_use[30]+$app_use[33]); ?></td>
                              <td data-title="전체/이번달/P코드점"><?php echo number_format($app_use[31]+$app_use[34]); ?></td>
                              <td data-title="전체/이번주/P코드점"><?php echo number_format($app_use[32]+$app_use[35]); ?></td>
                              <td data-title="tv/지난달/P코드점"><?php echo number_format($app_use[30]); ?></td>
                              <td data-title="tv/이번달/P코드점"><?php echo number_format($app_use[31]); ?></td>
                              <td data-title="tv/이번주/P코드점"><?php echo number_format($app_use[32]); ?></td>
                              <td data-title="iot/지난달/P코드점"><?php echo number_format($app_use[33]); ?></td>
                              <td data-title="iot/이번달/P코드점"><?php echo number_format($app_use[34]); ?></td>
                              <td data-title="iot/이번주/P코드점"><?php echo number_format($app_use[35]); ?></td>
                            </tr>
                          </tbody>
                        </table>
                        <!-- /table table-bordered text-center -->
                      </div>
                      <!-- /table-responsive -->
                    </div>
                    <!-- /single-table -->
                  </div>
                  <!-- /col-lg-12 mt-3 -->

                </div>
                <!-- /card-body -->
              </div>
              <!--/<div class="row">-->
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
          startDate: '2019-01-03',
          endDate: '2020-01-01'
        });
      });
      </script>
    </body>
</html>
