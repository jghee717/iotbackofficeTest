<?php
include 'layout/layout.php';
// include 'api/common.php';
include 'api/dbconn.php';

$y = 0;

if($_GET['date_from'] == null and $_GET['date_to'] == null){
  $date_from = '2019-06-10';
  $date_to = '2019-06-16';
}else{
  $date_from = $_GET['date_from'];
  $date_to = $_GET['date_to'];
}

$conn = new DBC();
$conn->DBI();

$layout = new Layout;
?>

<title>행운의 777 이벤트</title>
<?$layout->CssJsFile('
<link href="assets/css/pages/event_winner.css" rel="stylesheet">
<link href="assets/lib/HoldOn/HoldOn.min.css" rel="stylesheet">');?>
<?$layout->head($head);?>

<script type="text/javascript">
  let today = "<?= date("Y/m/d"); ?>"
</script>

<body>
  <div id="preloader">
    <div class="loader"></div>
  </div>
<!-- preloader area end -->

<div class="horizontal-main-wrapper">
  <div class="main-content-inner">
    <div class="container">
      <div class="row mt-2">
        <div class="card col-lg-12">
          <div class="card-body">
            <a href="event_winner.php"><h1><strong class="badge badge-pill">행운의 777 이벤트 안내</strong></h1></a>
            <div class="row">
              <div class="col-lg-12">
                <img class="img-fluid mr-4 mt-2" src="assets/images/event/10000yearBronze.png" width="65%">
              </div>
            </div>


            <!-- /col-lg-12 mt-5 -->
            <br><p><strong>배틀 우승팀 명단</strong>　 ※6월 1~4주차 담당별 설치율, 실행율의 총점을 취합하여 각 담당자에게 치킨 20마리 기프티콘이 발송됩니다.</p><br>

            <div class="col-lg-12 row">
              <div class="badge badge-pill" style="background-color: #8F93C9; margin: auto; text-align:center;">
                <div class="input-group">
                  <div id="prevWrapper">
                    <?if($date_from == '2019-06-10'){}else{?>
                      <a href="event_winner2.php?date_from=<?echo date("Y-m-d", strtotime("-7 days",  strtotime($date_from)));?>&date_to=<?echo date("Y-m-d", strtotime("-7 days",  strtotime($date_to)));?>">
                        <input type="button" id="weekPrevBattle" class="btn btn-rounded btn-light btn-xs date-move-btn" value="<">
                      </a>
                    <?}?>
                  </div>
                  <div class="mt-2"  style="color: #FFFFFF; letter-spacing :3px;">
                    <h6>
                      <a id="weekData">
                        <?
                        switch ($date_from) {
                          case '2019-06-10':
                          echo '1주차';
                          break;

                          case '2019-06-17':
                          echo '2주차';
                          break;

                          case '2019-06-24':
                          echo '3주차';
                          break;

                          case '2019-07-01':
                          echo '4주차';
                          break;
                        }
                        ?>
                      </a>
                    </h6>
                  </div>
                  <div id="nextWrapper">
                    <?if($date_from == '2019-07-01'){}else{?>
                      <a href="event_winner2.php?date_from=<?echo date("Y-m-d", strtotime("+7 days",  strtotime($date_from)));?>&date_to=<?echo date("Y-m-d", strtotime("+7 days",  strtotime($date_to)));?>">
                        <input type="button" id="weekNextBattle" class="btn btn-rounded btn-light btn-xs date-move-btn" value=">">
                      </a>
                    <?}?>
                  </div>
                </div>
              </div>
            </div>

            <!-- 테이블 -->
            <div class="col_lg_12">
              <section class="mt-5" id="battleGroupTable">
                <table class="table table-bordered text-center" id="table">
                  <thead class="text-uppercase">
                    <tr style="text-align:center; background-color: #8F93C9; color: #FFFFFF;">
                      <th class="numeric">배틀그룹</th>
                      <th class="numeric">담당명</th>
                      <th class="numeric">설치율</th>
                      <th class="numeric">실행율</th>
                      <th class="numeric">환산점수(30점)</th>
                      <th class="numeric">매장당 평균 사용횟수</th>
                      <th class="numeric">환산점수(70점)</th>
                      <th class="numeric">배틀결과</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?
                    $j=0;
                    if(date('md') == '0610' or date('md') == '0611' or date('md') == '0612' or date('md') == '0613' or date('md') == '0614' or date('md') == '0615' or date('md') == '0616'){
                      $group_str = " CASE
                              WHEN install.channel IN ('강북','강남')  THEN '1'
                              WHEN install.channel IN ('강동', '동부') THEN '2'
                              WHEN install.channel IN ('서부','홈/미디어') THEN '3' END AS `chan_group`," ;
                    }else if(date('md') == '0617' or date('md') == '0618' or date('md') == '0619' or date('md') == '0620' or date('md') == '0621' or date('md') == '0622' or date('md') == '0623'){
                      $group_str = " CASE
                              WHEN install.channel IN ('동부','강남')  THEN '1'
                              WHEN install.channel IN ('강북', '서부') THEN '2'
                              WHEN install.channel IN ('강동','홈/미디어') THEN '3' END AS `chan_group`," ;
                    }else if(date('md') == '0624' or date('md') == '0625' or date('md') == '0626' or date('md') == '0627' or date('md') == '0628' or date('md') == '0629' or date('md') == '0630'){
                      $group_str = " CASE
                              WHEN install.channel IN ('강남','서부')  THEN '1'
                              WHEN install.channel IN ('동부', '홈/미디어') THEN '2'
                              WHEN install.channel IN ('강동','강북') THEN '3' END AS `chan_group`," ;
                    }else if(date('md') == '0701' or date('md') == '0702' or date('md') == '0703' or date('md') == '0704' or date('md') == '0705' or date('md') == '0706' or date('md') == '0707'){
                      $group_str = " CASE
                              WHEN install.channel IN ('강남','강동')  THEN '1'
                              WHEN install.channel IN ('서부', '동부') THEN '2'
                              WHEN install.channel IN ('강북','홈/미디어') THEN '3' END AS `chan_group`," ;
                    }

                    $sql = "
                    SELECT
                    ".$group_str."
                    install.CHANNEL,
                    exe.cnt AS '실행수',
                    a.cnt AS '매장수',
                    install.cnt AS '설치수',
                    ROUND((install.cnt / a.cnt)*100, 2) AS '설치율',
                    IFNULL(ROUND((exe.cnt / install.cnt)*100, 2),'-') AS '실행율',
                    IFNULL((ROUND(exe.cnt / install.cnt, 2)*30),'-') AS '환산30',
                    total_exe.start_cnt AS '총사용횟수',
                    IFNULL(ROUND(total_exe.start_cnt / install.cnt, 2),'-') '평균사용횟수',
                    IFNULL((ROUND(exe.cnt / install.cnt, 2) * 30) + (ROUND(total_exe.start_cnt / install.cnt, 2) *  1.7),'-') AS '환산70'
                    FROM
                    (
                      SELECT CHANNEL, COUNT(pos_code) AS 'cnt'
                      FROM did_pos_code
                      WHERE CHANNEL IS NOT null
                      GROUP BY CHANNEL
                    )a
                    LEFT JOIN
                    (
                      SELECT did_pos_code.CHANNEL, COUNT(did_pos_code.pos_code) AS 'cnt'
                       FROM did_pos_code
                       LEFT JOIN
                       (
                          SELECT pos_id
                          FROM did_log_type_1
                          WHERE DATE(TIMESTAMP) <= '".$date_to."'
                          GROUP BY pos_id
                       )AS A ON did_pos_code.pos_code = A.pos_id
                       WHERE A.pos_id IS NOT NULL AND did_pos_code.CHANNEL IS NOT NULL
                       GROUP BY channel
                    )install ON a.CHANNEL = install.channel
                    LEFT JOIN
                    (
                       SELECT did_pos_code.CHANNEL ,COUNT(did_pos_code.pos_code) AS 'cnt'
                       FROM did_pos_code
                       LEFT JOIN
                       (
                         SELECT pos_id
                         FROM did_log_type_3
                          WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
                          AND page_id = 'p900005'
                          GROUP BY pos_id
                          UNION
                          SELECT pos_id
                          FROM did_log_type_4
                          WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
                          GROUP BY pos_id
                       )AS A ON did_pos_code.pos_code = A.pos_id
                       WHERE A.pos_id IS NOT NULL AND did_pos_code.CHANNEL IS NOT NULL
                       GROUP BY CHANNEL
                    )exe ON install.CHANNEL = exe.CHANNEL
                    LEFT JOIN
                    (
                      SELECT B.CHANNEL, B.pos_code, SUM(B.start_cnt)  AS 'start_cnt'
                       FROM
                       (
                          SELECT did_pos_code.CHANNEL, did_pos_code.pos_code, SUM(A.cnt) AS 'start_cnt'
                          FROM did_pos_code
                          LEFT JOIN
                          (
                            SELECT pos_id, DATE(TIMESTAMP), COUNT(pos_id) AS `cnt`
                            FROM did_log_type_3
                            WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
                            AND page_id = 'p900005'
                            GROUP BY pos_id, DATE(TIMESTAMP)
                            UNION ALL
                            SELECT pos_id, DATE(TIMESTAMP), COUNT(pos_id)  AS `cnt`
                            FROM did_log_type_4
                            WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
                            GROUP BY pos_id, DATE(TIMESTAMP)
                          )AS A
                          ON did_pos_code.pos_code = A.pos_id WHERE A.pos_id IS NOT NULL GROUP BY did_pos_code.pos_code
                       )AS B
                       GROUP BY B.CHANNEL
                      )total_exe ON exe.channel = total_exe.CHANNEL
                      WHERE install.CHANNEL IS NOT NULL ANd install.CHANNEL != ''
                      ORDER BY chan_group
                    ";
                    $conn->DBQ($sql);
                    $conn->DBE();
                    while($row=$conn->DBF()){
                    ?>
                    <tr>
                      <?if($j==0 or $j==2 or $j==4){?>
                      <td rowspan="2"><?php echo $row['chan_group']; ?></td>
                      <?}?>
                      <td><?php echo $row['CHANNEL']; ?></td>
                      <td><?php echo $row['설치율'].'%'; ?></td>
                      <td><?php echo $row['실행율'].'%'; ?></td>
                      <td><?php echo $row['환산30']; ?></td>
                      <td><?php echo $row['평균사용횟수']; ?></td>
                      <td><?php echo $row['환산70']; ?></td>
                      <td></td>
                    </tr>
                    <?$j+=1;}?>
                  </tbody>
                </table>
              </section>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?$layout->JsFile('');?>
<?$layout->js($js);?>

</body>
</html>
