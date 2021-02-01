<?php
include 'layout/layout.php';
include 'api/dbconn.php';
include 'api/common.php';

$conn = new DBC();
$conn->DBI();

$layout = new Layout;

$sql = "
SELECT
          A.channel,
          COUNT(A.pos_code) AS '매장수',
          B.exe_cnt AS '설치수',
          B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율' ,
          C.touch_cnt / COUNT(A.pos_code) * 100  AS '사용율' ,
          C.touch_cnt AS '실행수',
          D.start_cnt  AS '사용횟수',
          ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'


        FROM did_pos_code AS A
        LEFT JOIN
        (

      SELECT
        did_pos_code.channel
        ,COUNT(did_pos_code.pos_code) AS 'exe_cnt'
       FROM did_pos_code
      LEFT JOIN
      (
        SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
        FROM did_log_type_1
        WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
        GROUP BY pos_id
      )AS A
      ON
       did_pos_code.pos_code = A.pos_id
       WHERE A.pos_id IS NOT NULL
       GROUP BY channel

        )AS B
        ON
        A.channel = B.channel
        LEFT JOIN
        (
          SELECT
                      did_pos_code.channel
                      ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                     FROM did_pos_code
                    LEFT JOIN
                    (
                    SELECT pos_id
                       FROM did_log_type_3
                    WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                     AND page_id = 'p900005'
                    GROUP BY pos_id
                        UNION
                      SELECT pos_id
                      FROM did_log_type_4
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                      GROUP BY pos_id
                    )AS A
                    ON
                     did_pos_code.pos_code = A.pos_id
                     WHERE A.pos_id IS NOT NULL
                     GROUP BY CHANNEL

        )AS C
        ON
        A.channel = C.channel
        LEFT JOIN
        (

      SELECT
            B.channel
            , B.pos_code
         ,   SUM(B.start_cnt)  AS 'start_cnt'
      FROM
      (
         SELECT
             did_pos_code.channel,
             did_pos_code.pos_code
             ,SUM(A.cnt) AS 'start_cnt'
            FROM did_pos_code
           LEFT JOIN
           (
            SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) ,  COUNT(pos_id) AS `cnt`
               FROM did_log_type_3
            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
             AND page_id = 'p900005'
            GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
               UNION ALL
            SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
               FROM did_log_type_4
            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
            GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
           )AS A
           ON
            did_pos_code.pos_code = A.pos_id
            WHERE A.pos_id IS NOT NULL
            GROUP BY did_pos_code.pos_code
      )AS B
      GROUP BY B.channel

        )AS D
        ON
        A.channel = D.channel
        WHERE A.channel IS NOT NULL
        GROUP BY A.channel
        ORDER BY A.channel
";
$conn->DBQ($sql);
$conn->DBE();
$resCnt = $conn->resultRow();

$store = 0;
$install = 0;
$execute = 0;
$use = 0;
$peruse = 0;

$row = $conn->DBP();

for($i=0; $i<$resCnt; $i++){
  $store += $row[$i]['매장수'];
}
for($i=0; $i<$resCnt; $i++){
  $install += $row[$i]['설치수'];
}
for($i=0; $i<$resCnt; $i++){
  $execute += $row[$i]['실행수'];
}
for($i=0; $i<$resCnt; $i++){
  $use += $row[$i]['사용횟수'];
}
for($i=0; $i<$resCnt; $i++){
  $peruse += $row[$i]['평균사용률'];
}
?>
<!doctype html>
<html class="no-js" lang="kr">
<?$layout->CssJsFile('<link href="assets/css/table-responsive.css" rel="stylesheet">');?>
<?$layout->head($head);?>
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
<?php $curDate1 = date("Y-m-d",strtotime("-6 days"));?>
<?php $curDate2 = date("Y-m-d",strtotime("-1 months"));?>
<?php $curDate3 = date("Y-m-d",strtotime("-3 months"));?>
<?php $curDate4 = date("Y-m-d",strtotime("-6 months"));?>

function setSearchDate(num){
  switch(num){
    case 0:
    document.getElementById('date_from').value = <?php echo json_encode($curDate); ?>;
    document.getElementById('date_to').value = <?php echo json_encode($curDate); ?>;
    break;

    case 1:
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

    case 2:
    document.getElementById('date_from').value = <?php echo json_encode($curDate2); ?>;
    document.getElementById('date_to').value = <?php echo json_encode($curDate); ?>;
    break;

    case 3:
    document.getElementById('date_from').value = <?php echo json_encode($curDate3); ?>;
    document.getElementById('date_to').value = <?php echo json_encode($curDate); ?>;
    break;

    case 4:
    document.getElementById('date_from').value = <?php echo json_encode($curDate4); ?>;
    document.getElementById('date_to').value = <?php echo json_encode($curDate); ?>;
    break;

    default: return 0;
    break;
  }
}

function compare(num)
{
  document.getElementById('compare').value = num;
}

function getFormatDate(date){
  var year = date.getFullYear();                                 //yyyy
  var month = (1 + date.getMonth());                     //M
  month = month >= 10 ? month : '0' + month;     // month 두자리로 저장
  var day = date.getDate();                                        //d
  day = day >= 10 ? day : '0' + day;                            //day 두자리로 저장
  return  year + '-' + month + '-' + day;
}

function changeValue() {
  var ago = new Date();
  var today = new Date();
  new Date(ago.setDate(ago.getDate()-6));

  ago = getFormatDate(ago);
  today = getFormatDate(today);

  document.getElementById('date_from').value = ago;
  document.getElementById('date_to').value = today;
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

    <!-- category -->
    <div class="main-content-inner">
      <div class="container">
        <div class="row">

          <div class="col-lg-6"><h5>조직별 통계 </h5></div>
          <div class="col-lg-6" style="text-align: right;"><small> Main > 조직별 통계 </small></div>
          <style>
          form{border:1px solid #E6E6E6;}
          hr{margin:1px;}
          </style>
          <html><hr color="black" width=100%></html>
          <div class="card col-lg-12 mt-3">
            <div class="card-body">
              <form action="#" method="get" class="col-lg-12" name='form'>

                <!-- 기간 -->
                <html><hr color="#E6E6E6" class="mt-2" width=100%></html>
                <div class="form-group">
                  <div class="col-lg-12">
                    <div class="input-group">
                      <span class="input-group form-control2 form-control-sm col-lg-1" style="text-align:center;">기간</span>
                      <input data-toggle="datepicker" type="text" class="form-control form-control-sm col-lg-1" style="margin-left:20px" id="date_from" name="date_from" value="<?echo $date_from;?>" readonly="readonly">
                      <div class="input-group-prepend">
                        <div class="input-group-text form-control form-control-sm">~</div>
                      </div>
                      <input data-toggle="datepicker" type="text" class="form-control form-control-sm col-lg-1" id="date_to" name="date_to" value="<?echo $date_to;?>" readonly="readonly">
                      <button type="button" style="margin-left:10px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType1" value="" onclick="setSearchDate(0)"/>오늘</button>
                      <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType2" onclick="setSearchDate(1)"/>일주일</button>
                      <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType3" onclick="setSearchDate(2)"/>1개월</button>
                      <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType4" onclick="setSearchDate(3)"/>3개월</button>
                      <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" id="dateType5" onclick="setSearchDate(4)"/>6개월</button>
                    </div>
                  </div>
                  <html><hr color="#E6E6E6" width=100%></html>
                </div>

                <!-- 검색 -->
                <div class="input-group">
                  <div class="col-lg-6">
                    <button style="display:none;" class="btn btn-lg mr-2 btn btn-xs" type="reset" value="" onclick="changeValue()"><i class="fa fa-refresh"></i></button>
                  </div>
                  <button class="btn btn-primary btn btn-xs" style="text-align:center;" type="submit" id="searchButton">검색</button>
                </div><br>
              </form>

              <!--/formgroup-->

              <!-- table -->
              <div class="input-group mt-5">
                <div class="col-lg-2"><strong><h5>전체 운영 현황<h5></strong>
                </div>
                <div class="col-lg-10 text-right">
                  <a href="api/statsReg/stats_store_excel.php?date_from=<?echo $date_from;?>&date_to=<?echo $date_to;?>">
                  <button class="btn btn-xs" type="button"><i class="fa fa-download"></i>데이터 저장</button></a>
                </div>
                <!-- <div class="col-lg-3 text-right"></div> -->
              </div>
                  <div class="col-lg-12 mt-1">
                    <div class="single-table">
                      <div class="table-responsive">
                        <table class="table table-bordered text-center">
                          <thead class="text-uppercase">
                            <tr style="text-align: center;">
                              <th scope="col">NO.</th>
                              <th scope="col">영업담당</th>
                              <th scope="col">매장수</th>
                              <th scope="col">설치수</th>
                              <th scope="col">설치율</th>
                              <th scope="col">실행수</th>
                              <th scope="col">실행율</th>
                              <th scope="col">총 사용횟수</th>
                              <th scope="col">매장당 평균 사용횟수</th>
                              <th scope="col">상세보기</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?
                            $i=0;

                            for($j=0; $j<$resCnt; $j++){
                            ?>
                            <tr style="text-align: center;">
                              <td scope="row"><?echo $i+1;?></td>
                              <td><?php if($row[$j]['channel'] == '홈/미디어'){echo '스마트홈';}else{echo $row[$j]['channel'];} ?></td>
                              <td><?php if($row[$j]['매장수'] == null){echo '-';}else{echo number_format($row[$j]['매장수']);} ?></td>
                              <td><?php if($row[$j]['설치수'] == null){echo '-';}else{echo number_format($row[$j]['설치수']);}?></td>
                              <td><?php if($row[$j]['설치율'] == null){echo '-';}else{echo number_format($row[$j]['설치율'],2).'%';} ?></td>
                              <td><?php if($row[$j]['실행수'] == null){echo '-';}else{echo number_format($row[$j]['실행수']);} ?></td>
                              <td><?php if(number_format(($row[$j]['실행수']/$row[$j]['설치수'])*100,2) == nan){echo '-';}else{echo number_format(($row[$j]['실행수']/$row[$j]['설치수'])*100,2).'%';} ?></td>
                              <td><?php if($row[$j]['사용횟수'] == null){echo '-';}else{echo number_format($row[$j]['사용횟수']);} ?></td>
                              <td><?php if($row[$j]['평균사용률'] == null){echo '-';}else{echo $row[$j]['평균사용률'];} ?></td>
                              <td><button type="button" id="modal_btn<?echo $i;?>" onclick="compare(<?echo $i;?>)" class="btn btn-primary btn btn-xs" data-toggle="modal" data-target="#store_Modal<?echo $i;?>">보기</button></td>
                            </tr>
                            <?$i++;}?>
                          </tbody>
                          <tfoot>
                            <tr style="font-weight:bold;">
                              <td colspan="2">합계</td>
                              <td><?php echo number_format($store); ?></td>
                              <td><?php echo number_format($install); ?></td>
                              <td><?php if(number_format(($install/$store)*100,1) == nan){echo '-';}else{echo number_format(($install/$store)*100,2).'%';}?></td>
                              <td><?php echo number_format($execute); ?></td>
                              <td><?php if(number_format(($execute/$install)*100,1) == nan){echo '-';}else{echo number_format(($execute/$install)*100,2).'%';} ?></td>
                              <td><?php echo number_format($use); ?></td>
                              <td><?php echo number_format(($peruse/$resCnt),2); ?></td>
                              <td></td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                  <!-- /col-lg-12 -->

                  <div class="input-group">
                    <div class="col-lg-6"></div>
                    <div style="text-align:right" class="col-lg-6">
                      <br>
                      <!--엑셀-->
                    </div>
                  </div>
                  </div>
                  <!-- /card-body -->
                </div>
                <!-- /<div class="card col-lg-12"> -->
              </div>
            </div>
          </div>
          <!-- main content area end -->



          <!-- Modal-->
          <?for($i=0; $i<$resCnt; $i++){

            switch ($i){
              case 0:
              $sales = '강남';
              break;

              case 1:
              $sales = '강동';
              break;

              case 2:
              $sales = '강북';
              break;

              case 3:
              $sales = '동부';
              break;

              case 4:
              $sales = '서부';
              break;

              case 5:
              $sales = '홈/미디어';
              break;
            }
            ?>
           <div class="modal fade bd-example-modal-lg" id="store_Modal<?echo $i;?>" role="dialog" aria-labelledby="myModalLabel_com" aria-hidden="true">
             <div class="modal-dialog modal-lg">
               <div class="modal-content">
                 <div class="modal-header">
                   <h5 class="modal-title">현장지원팀 운영 현황</h5>
                   <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                 </div>

                 <div class="modal-body">
                   <div class="row">
                     <div class="col-lg-12 text-right mb-1"><a href="/api/statsReg/stats_store_detail_total.php?channel=<?echo $sales;?>&bg_code=<?php echo $row['bg_code'];?>&date_from=<?echo $date_from;?>&date_to=<?echo $date_to;?>">
                       <button class="btn btn-xs" type="button"><i class="fa fa-download"></i>데이터 저장</button></a>
                     </div>

                     <div class="col-lg-12" id="category_table<?echo $i;?>">
                       <table class="table table-striped table-advance table-hover">
                         <thead class="cf" style='background-color: #FEFEFE'>
                           <tr style="font-size: 11px; text-align: center;">
                             <th class="numeric">NO</th>
                             <th class="numeric">영업담당</th>
                             <th class="numeric">지원팀</th>
                             <th class="numeric">매장수</th>
                             <th class="numeric">설치수</th>
                             <th class="numeric">설치율</th>
                             <th class="numeric">실행수</th>
                             <th class="numeric">실행율</th>
                             <th class="numeric">총 사용횟수</th>
                             <th class="numeric">매장당 평균 사용횟수</th>
                             <th class="numeric"></th>
                           </tr>
                         </thead>
                         <tbody>
                           <?
                           $j = 0;

                           $store2 = 0;
                           $install2 = 0;
                           $execute2 = 0;
                           $use2 = 0;

                            // $sql = "
                            // SELECT A.channel, A.bg_code, COUNT(A.pos_code) AS '매장수', B.exe_cnt AS '설치수'
                            // , B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율', C.touch_cnt / COUNT(A.pos_code) * 100 AS '사용율'
                            // , C.touch_cnt AS '실행수', D.start_cnt AS '사용횟수', ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'
                            // FROM did_pos_code AS A
                            // LEFT JOIN
                            // (
                            // 	SELECT did_pos_code.CHANNEL, did_pos_code.bg_code, COUNT(did_pos_code.pos_code) AS 'exe_cnt'
                            // 	FROM did_pos_code
                            // 	LEFT JOIN (
                            // 	SELECT pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                            // 	FROM did_log_type_1
                            // 	WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                            // 	GROUP BY pos_id) AS A ON did_pos_code.pos_code = A.pos_id
                            // 	WHERE A.pos_id IS NOT NULL
                            // 	GROUP BY did_pos_code.bg_code
                            // ) AS B ON A.bg_code = B.bg_code
                            // LEFT JOIN (
                            // SELECT did_pos_code.channel, did_pos_code.bg_code, COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                            // FROM did_pos_code
                            // LEFT JOIN (
                            // SELECT pos_id
                            // FROM did_log_type_3
                            // WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."' AND page_id = 'p900005'
                            // GROUP BY pos_id UNION
                            // SELECT pos_id
                            // FROM did_log_type_4
                            // WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                            // GROUP BY pos_id) AS A ON did_pos_code.pos_code = A.pos_id
                            // WHERE A.pos_id IS NOT NULL
                            // GROUP BY bg_code) AS C ON A.bg_code  = C.bg_code
                            // LEFT JOIN (
                            // SELECT B.CHANNEL,B.bg_code, B.pos_code, SUM(B.start_cnt) AS 'start_cnt'
                            // FROM (
                            // SELECT did_pos_code.channel, did_pos_code.bg_code, did_pos_code.pos_code, SUM(A.cnt) AS 'start_cnt'
                            // FROM did_pos_code
                            // LEFT JOIN (
                            // SELECT pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')), COUNT(pos_id) AS `cnt`
                            // FROM did_log_type_3
                            // WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."' AND page_id = 'p900005'
                            // GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) UNION all
                            // SELECT pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')), COUNT(pos_id) AS `cnt`
                            // FROM did_log_type_4
                            // WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                            // GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))) AS A ON did_pos_code.pos_code = A.pos_id
                            // WHERE A.pos_id IS NOT NULL
                            // GROUP BY did_pos_code.pos_code) AS B
                            //
                            // GROUP BY B.bg_code) AS D ON A.bg_code  = D.bg_code
                            // WHERE A.CHANNEL IS NOT null
                            // GROUP BY A.bg_code
                            // HAVING A.CHANNEL = '".$sales."'
                            // ";

                            $sql = "
                            SELECT
                                      A.channel,
                                      A.bg_code,
                                      COUNT(A.pos_code) AS '매장수',
                                      B.exe_cnt AS '설치수',
                                      B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율' ,
                                      C.touch_cnt / COUNT(A.pos_code) * 100  AS '사용율' ,
                                      C.touch_cnt AS '실행수',
                                      D.start_cnt  AS '사용횟수',
                                      ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'
                            FROM did_pos_code AS A
                            LEFT JOIN
                            (
                            	SELECT did_pos_code.channel
                                      ,COUNT(did_pos_code.pos_code) AS 'exe_cnt'
                                      ,did_pos_code.bg_code
                               FROM did_pos_code
                               LEFT JOIN
                               (
                                  SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                  FROM did_log_type_1
                                  WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                                  GROUP BY pos_id
                               )AS A
                               ON did_pos_code.pos_code = A.pos_id
                               WHERE A.pos_id IS NOT NULL AND CHANNEL = '".$sales."'
                               GROUP BY bg_code
                            )AS B ON A.bg_code = B.bg_code
                            LEFT JOIN
                            (
                               SELECT did_pos_code.channel
                                      ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                                      ,did_pos_code.bg_code
                               FROM did_pos_code
                               LEFT JOIN
                               (
                                  SELECT pos_id
                                  FROM did_log_type_3
                                  WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                  AND page_id = 'p900005'
                                  GROUP BY pos_id
                                  UNION
                                  SELECT pos_id
                                  FROM did_log_type_4
                                  WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                  GROUP BY pos_id
                               )AS A ON did_pos_code.pos_code = A.pos_id
                               WHERE A.pos_id IS NOT NULL AND CHANNEL = '".$sales."'
                               GROUP BY bg_code
                            )AS C ON A.bg_code = C.bg_code
                            LEFT JOIN
                            (
                               SELECT B.channel
                                      ,B.pos_code
                                      ,SUM(B.start_cnt)  AS 'start_cnt'
                                      ,B.bg_code
                               FROM
                               (
                                  SELECT did_pos_code.channel
                            		      ,did_pos_code.pos_code
                                        ,SUM(A.cnt) AS 'start_cnt'
                                        ,did_pos_code.bg_code
                                  FROM did_pos_code
                                  LEFT JOIN
                                  (
                                     SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) ,  COUNT(pos_id) AS `cnt`
                                     FROM did_log_type_3
                                     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                     AND page_id = 'p900005'
                                     GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                     UNION ALL
                                     SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                                     FROM did_log_type_4
                                     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                     GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                  )AS A ON did_pos_code.pos_code = A.pos_id
                                  WHERE A.pos_id IS NOT NULL AND CHANNEL = '".$sales."'
                                  GROUP BY did_pos_code.bg_code
                               )AS B
                               GROUP BY B.bg_code
                            )AS D ON A.bg_code = D.bg_code
                            WHERE A.channel IS NOT NULL AND A.CHANNEL != '' AND A.CHANNEL = '".$sales."'
                            GROUP BY A.bg_code
                            ORDER BY A.bg_code
                            ";
                            $conn->DBQ($sql);
                            $conn->DBE();
                            while($row=$conn->DBF()){
                           ?>
                           <tr style="text-align: center;">
                             <td class="numeric" data-title="NO"><?echo $j+1;?></td>
                             <td class="numeric" data-title="영업담당"><?if($row['channel'] == '홈/미디어'){echo '스마트홈';}else{echo $row['channel'];}?></td>
                             <td class="numeric" data-title="지원팀"><?echo $row['bg_code'];?></td>
                             <td class="numeric" data-title="매장수"><?if($row['매장수']==null){echo '-';}else{echo $row['매장수'];} $store2 += $row['매장수'];?></td>
                             <td class="numeric" data-title="APP 설치수"><?if($row['설치수']==null){echo '-';}else{echo $row['설치수'];} $install2 += $row['설치수'];?></td>
                             <td class="numeric" data-title="APP 설치율"><?if($row['설치율']==null){echo '-';}else{echo number_format($row['설치율'],2).'%';}?></td>
                             <td class="numeric" data-title="APP 실행수"><?if($row['실행수']==null){echo '-';}else{echo $row['실행수'];} $execute2 += $row['실행수'];?></td>
                             <td class="numeric" data-title="APP 실행율">
                               <?if(number_format(($row['실행수']/$row['설치수'])*100,2) == nan){echo '-';}else{echo number_format(($row['실행수']/$row['설치수'])*100,2).'%';}?>
                             </td>
                             <td class="numeric" data-title="총 사용횟수"><?if($row['사용횟수']==null){echo '-';}else{echo $row['사용횟수'];} $use2 += $row['사용율'];?></td>
                             <td class="numeric" data-title="매장당 평균 사용횟수"><?if($row['평균사용률']==null){echo '-';}else{echo $row['평균사용률'];} ?></td>
                             <td class="numeric"><a href="/api/statsReg/stats_store_detail.php?channel=<?echo $row['channel'];?>&bg_code=<?php echo $row['bg_code'];?>&date_from=<?echo $date_from;?>&date_to=<?echo $date_to;?>">
                               <button type="button" class="btn btn-primary btn btn-xs">저장</button></a>
                             </td>
                           </tr>
                           <?$j++;}?>
                         </tbody>
                       </table>
                     </div>
                     <!-- /col-lg-12 -->

                     <div class="input-group">
                       <div class="col-lg-6 text-left">
                         <small>설치율: <?echo number_format(($install2/$store2)*100,2);?>% / 실행율: <?echo number_format(($execute2/$install2)*100,2);?>%</small>
                       </div>
                       <div class="col-lg-6 text-right"><span style="color:grey"><small>기간: <?echo $date_from. ' ~ ' .$date_to;?>
                         </small></span>
                       </div>
                     </div>
                   </div>
                   <!-- /row -->
                 </div>
                 <!-- modal-body -->
               </div>
             </div>
           </div>
           <?}?>

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
            endDate: '2020-12-31'

          });

          $("#date_from").datepicker('setEndDate', $("#date_to").datepicker('getDate', true));
          $("#date_to").datepicker('setStartDate', $("#date_from").datepicker('getDate', true));

        });
        $('[data-toggle = "datepicker"]').click(function() {
          $('[data-toggle = "datepicker"]').datepicker({
            autoHide: true,
            zIndex: 2048,
            language: 'ko-KR',
            startDate: '1980-01-01',
            endDate: '2020-12-31'

          });
          $("#date_from").datepicker('setEndDate', document.getElementById('date_to').value);
          $("#date_to").datepicker('setStartDate', document.getElementById('date_from').value);
          $("#date_from").datepicker('setDate', document.getElementById('date_from').value);
          $("#date_to").datepicker('setDate', document.getElementById('date_to').value);

        });
        </script>
      </body>
    </html>
