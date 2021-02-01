<?php
include "../sessions/db.php";
include "../sessions/access_all.php";
$db = new DBC;
$db->DBI();
?>
<!doctype html>
<html lang="ko">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>소개글 관리</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
  <link href="../assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/libs/css/style.css">
  <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
  <script src="../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
  <script src="../assets/vendor/slimscroll/jquery.slimscroll.js"></script>
  <script src="../assets/libs/js/main-js.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

  <link  href="../datepicker/datepicker.css" rel="stylesheet">
  <script src="../datepicker/datepicker.js"></script>
  <script src="../datepicker/datepicker.ko-KR.js"></script>
  <script src="../datepicker/date.js"></script>
  <script type="text/javascript">

  $(function(){
    $('#datepicker1').datepicker({
      language: 'ko-KR',
      format: 'yyyy-mm-dd',
      startDate: "2018-01-01",
      endDate: "today",
      weekStart:0,
      autoHide:true
    });
  });

  $(function(){
    $('#datepicker2').datepicker({
      language: 'ko-KR',
      format: 'yyyy-mm-dd',
      startDate: "2018-01-01",
      endDate: "today",
      weekStart:0,
      autoHide:true
    });
  });

  //클릭
  $(function(){
    //오늘 클릭
    $('#today').click(function(){
      $(".active").removeClass("active");
      $(this).addClass("active");
      $('#datepicker1').datepicker('setDate','<?=date("Y-m-d",strtotime("today", time()))?>');
      $('#datepicker2').datepicker('setDate','<?=date("Y-m-d",strtotime("today", time()))?>');
    });
    //어제 클릭
    $('#yesterday').click(function(){
      $(".active").removeClass("active");
      $(this).addClass("active");
      $('#datepicker1').datepicker('setDate','<?=date("Y-m-d",strtotime("yesterday", time()))?>');
      $('#datepicker2').datepicker('setDate','<?=date("Y-m-d",strtotime("yesterday", time()))?>');
    });
    //지난주 클릭
    $('#lastweek').click(function(){
      $(".active").removeClass("active");
      $(this).addClass("active");
      $('#datepicker1').datepicker('setDate','<?=date("Y-m-d",strtotime("yesterday-7day", time()))?>');
      $('#datepicker2').datepicker('setDate','<?=date("Y-m-d",strtotime("yesterday", time()))?>');
    });
    //최근 30일 클릭
    $('#month').click(function(){
      $(".active").removeClass("active");
      $(this).addClass("active");
      $('#datepicker1').datepicker('setDate','<?=date("Y-m-d",strtotime("yesterday-30day", time()))?>');
      $('#datepicker2').datepicker('setDate','<?=date("Y-m-d",strtotime("yesterday", time()))?>');
    });
    //지난달 클릭
    $('#last_month').click(function(){
      $(".active").removeClass("active");
      $(this).addClass("active");
      $('#datepicker1').datepicker('setDate','<?=date("Y-m-01",strtotime("last month", time()))?>');
      $('#datepicker2').datepicker('setDate','<?=date("Y-m-t",strtotime("last month", time()))?>');
    });
    //이번달 클릭
    $('#this_month').click(function(){
      $(".active").removeClass("active");
      $(this).addClass("active");
      $('#datepicker1').datepicker('setDate','<?=date("Y-m-01",strtotime("0 month", time()))?>');
      $('#datepicker2').datepicker('setDate','<?=date("Y-m-d",strtotime("today", time()))?>');
    });

    //검색 클릭
    $('#search').click(function(){
      var start = $('#datepicker1').val();
      var end = $('#datepicker2').val();
      location.href="./page_session.php?mc=세션현황&sc=세션수&start="+start+"&end="+end;
    });

    //데이트 피커 클릭
    $('#datepicker1').click(function(){
      $(".active").removeClass("active");
    });
    $('#datepicker2').click(function(){
      $(".active").removeClass("active");
    });
  });
  //로딩
  $(function(){
    $(".active").removeClass("active");
    var start = "<?=$_GET['start']?>";
    var end = "<?=$_GET['end']?>";
    $(this).addClass("active");
    if(start && end){
      $('#datepicker1').datepicker('setDate',start);
      $('#datepicker2').datepicker('setDate',end);
    }else{
      $('#datepicker1').datepicker('setDate','<?=date("Y-m-d",strtotime("today", time()))?>');
      $('#datepicker2').datepicker('setDate','<?=date("Y-m-d",strtotime("today", time()))?>');
    }
  });
  </script>
</head>
<body>
  <!-- ============================================================== -->
  <!-- main wrapper -->
  <!-- ============================================================== -->
  <div class="dashboard-main-wrapper">
    <!-- ============================================================== -->
    <!-- navbar -->
    <!-- ============================================================== -->
    <?php
    include "../layout/header.php";
    ?>
    <!-- ============================================================== -->
    <!-- end navbar -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- left sidebar -->
    <!-- ============================================================== -->
    <?php
    include "../layout/sidebar.php";
    ?>
    <!-- ============================================================== -->
    <!-- end left sidebar -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- wrapper  -->
    <!-- ============================================================== -->
    <div class="dashboard-wrapper">
      <div class="container-fluid dashboard-content">
        <!-- ============================================================== -->
        <!-- pageheader -->
        <!-- ============================================================== -->
        <?php
        include "../layout/pageheader.php";
        ?>
        <!-- ============================================================== -->
        <!-- end pageheader -->
        <!-- ============================================================== -->
        <div class="dashboard-short-list">
          <!-- ============================================================== -->
          <!-- ============================================================== -->
          <div class="row">
            <!----------------------------------------- 차트 ---------------------------------------------------->
            <!-- 일 -->
            <div class="col-xl-20 col-lg-12 col-md-12 col-sm-12 col-12">
              <div class="card">
                <div class="card-body">
                  <div style="float:right" class="btn-group btn-group-toggle" data-toggle="buttons">
                    <!-- <label class="btn btn-primary">
                      <input type="radio" name="day" id="opt_day">일
                    </label>
                    <label class="btn btn-primary active">
                      <input type="radio" name="week" id="opt_week" checked>주
                    </label>
                    <label class="btn btn-primary">
                      <input type="radio" name="month" id="opt_month">월
                    </label> -->
                    <button class="btn btn-primary" type="radio" name="day" id="opt_day"  >일</button>
                    <button class="btn btn-primary" type="radio" name="week" id="opt_week">주</button>
                    <button class="btn btn-primary" type="radio" name="month" id="opt_month">월</button>
                  </div>
                  <canvas id="myChart" height="100"></canvas>
                </div>
                <?
                $sql ="SELECT DATE_FORMAT(loadtime,'%Y-%m-%d') as loadtime, COUNT(DISTINCT user_cooky) as cookie FROM web_log $a GROUP BY DATE_FORMAT(loadtime,'%Y-%m-%d') ORDER BY DATE_FORMAT(loadtime,'%Y-%m-%d') desc";
                $db->DBQ($sql);
                $db->DBE();
                $session = $db->DBF();

                $i = 1;
                $date_day_num1 = "SELECT COUNT(DISTINCT user_cooky) as day FROM web_log WHERE loadtime between subdate(now(), interval $i DAY) and now()";
                $date_week_num1 = "SELECT COUNT(DISTINCT user_cooky) as day FROM web_log WHERE loadtime between subdate(now(), interval $i WEEK) and now()";
                $date_month_num1 = "SELECT COUNT(DISTINCT user_cooky) as day FROM web_log WHERE loadtime between subdate(now(), interval $i MONTH) and now()";
                ?>
                <script type="text/javascript">
                $(document).ready(function(){
                  //일별 chart js
                  $('#opt_day').click(function () {
                      var ctx = document.getElementById('myChart').getContext('2d');
                      var data = [];

                      var chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                          labels: ['<?=date("Y-m-d",strtotime("-6 day" ))?>', '<?=date("Y-m-d",strtotime("-5 day" ))?>', '<?=date("Y-m-d",strtotime("-4 day" ))?>', '<?=date("Y-m-d",strtotime("-3 day" ))?>', '<?=date("Y-m-d",strtotime("-2 day" ))?>', '<?=date("Y-m-d",strtotime("-1 day" ))?>', '<?=date("Y-m-d",strtotime("now" ))?>'], //x축
                          datasets: [{
                            label: '세션수(일)',
                            borderColor: 'rgb(255, 99, 132)',
                            data: data //y축
                          }]
                        },
                        // Configuration options go here
                        options: {
                          scales: { //X,Y축 옵션
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true  //Y축의 값이 0부터 시작
                                }
                            }]
                          }
                        }
                      });
                    });
                    //주별 chart js
                    $('#opt_week').click(function () {
                        var ctx = document.getElementById('myChart').getContext('2d');
                        var chart = new Chart(ctx, {
                          type: 'line',
                          data: {
                            labels: ['<?=date("Y-m-d",strtotime("-6 week"))?>', '<?=date("Y-m-d",strtotime("-5 week" ))?>', '<?=date("Y-m-d",strtotime("-4 week" ))?>', '<?=date("Y-m-d",strtotime("-3 week" ))?>', '<?=date("Y-m-d",strtotime("-2 week" ))?>', '<?=date("Y-m-d",strtotime("-1 week" ))?>', '<?=date("Y-m-d",strtotime("now" ))?>'], //x축
                            datasets: [{
                              label: '세션수(주)',
                              borderColor: 'rgb(255, 99, 132)',
                              data: [<?=$session[cookie]?>,<?=$session[cookie]?>,<?=$session[cookie]?>,<?=$session[cookie]?>,<?=$session[cookie]?>,<?=$session[cookie]?>,<?=$session[cookie]?>] //y축
                            }]
                          },
                          // Configuration options go here
                          options: {
                            scales: { //X,Y축 옵션
                              yAxes: [{
                                  ticks: {
                                      beginAtZero:true  //Y축의 값이 0부터 시작
                                  }
                              }]
                            }
                          }
                        });
                      });
                      //월별 chart js
                      $('#opt_month').click(function () {
                        var ctx = document.getElementById('myChart').getContext('2d');
                        var chart = new Chart(ctx, {
                          type: 'line',
                          data: {
                            labels: ['<?=date("Y-m-d",strtotime("-6 month" ))?>', '<?=date("Y-m-d",strtotime("-5 month" ))?>', '<?=date("Y-m-d",strtotime("-4 month" ))?>', '<?=date("Y-m-d",strtotime("-3 month" ))?>', '<?=date("Y-m-d",strtotime("-2 month" ))?>', '<?=date("Y-m-d",strtotime("-1 month" ))?>', '<?=date("Y-m-d",strtotime("now" ))?>'], //x축
                            datasets: [{
                              label: '세션수(최근6개월)',
                              borderColor: 'rgb(255, 99, 132)',
                              data: [
                                <?=$session[cookie]?>,
                                <?=$session[cookie]?>,
                                <?=$session[cookie]?>,
                                <?=$session[cookie]?>,
                                <?=$session[cookie]?>,
                                <?=$session[cookie]?>,
                                <?=$session[cookie]?>] //y축
                            }]
                          },
                          // Configuration options go here
                          options: {
                            scales: { //X,Y축 옵션
                              yAxes: [{
                                  ticks: {
                                      beginAtZero:true  //Y축의 값이 0부터 시작
                                  }
                              }]
                            }
                          }
                        });
                      });
                  });

                </script>
            </div>
            <!----------------------------------------- 차트 End(차트 일단 죽임) -------------------------------------------->
            <!------------------------------------- datepicker -->
            <div class="col-xl-20 col-lg-12 col-md-12 col-sm-12 col-12">
              <div class="card">
                <div class="card-body">
                  <div style="text-align:center">
                    <label class="btn btn-primary active" id="today">오늘</label>
                    <label class="btn btn-primary" id="yesterday">어제</label>
                    <label class="btn btn-primary" id="lastweek">최근 7일</label>
                    <label class="btn btn-primary" id="month">최근 30일</label>
                    <label class="btn btn-primary" id="last_month">지난 달</label>
                    <label class="btn btn-primary" id="this_month">이번 달</label>
                  </div>
                  <div style="text-align:center; padding:15px">
                    <label style="margin-right:10px">시작일 :</label>
                    <input style="width:150px; display:inline; margin-right:50px; background-color:white"type="text" class="form-control" id="datepicker1" readonly/>
                    <label style="margin-right:50px">~</label>
                    <label style="margin-right:10px">종료일 :</label>
                    <input style="width:150px; display:inline; margin-right:10px; background-color:white"type="text" class="form-control" id="datepicker2" readonly/>
                    <button type="button" class="btn btn-info" id="search">검색</button>
                  </div>
                </div>
              </div>
            </div>
            <!------------------------------------- datepicker End-->
            <!----------------------------------------- 테이블 -------------------------------------------->
            <!-- <? echo "$a[C]" ?> -->
            <div class="col-xl-20 col-lg-12 col-md-12 col-sm-12 col-12">
              <div class="card">
                <h5 class="card-header">세션수</h5>
                <div class="card-body">
                  <table class="table" style="text-align:center">
                    <thead>
                      <tr>
                        <th scope="col">날짜</th>
                        <th scope="col">세션수</th>
                        <th scope="col">전일대비</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $db = new DBC;

                      if(isset($_GET['start']) && isset($_GET['end'])){
                        $start = $_GET['start'];
                        $end = $_GET['end'];
                      }else{
                        $start = date("Y-m-d",strtotime("today", time()));
                        $end = date("Y-m-d",strtotime("today", time()));
                      }

                      $a = " where DATE_FORMAT(loadtime,'%Y-%m-%d') between '$start' and '$end'";
                      $b = " and DATE_FORMAT(loadtime,'%Y-%m-%d') between '$start' and '$end'";

                      $sql1="SELECT DATE_FORMAT(loadtime,'%Y-%m-%d') as loadtime, COUNT(DISTINCT user_cooky) as cookie FROM web_log $a GROUP BY DATE_FORMAT(loadtime,'%Y-%m-%d') ORDER BY DATE_FORMAT(loadtime,'%Y-%m-%d') desc";
                      $db->DBI();
                      $db->DBQ($sql1);
                      $db->DBE();

                      while($list=$db->DBF()){?>
                      <!-- 날짜 -->
                      <td><?=$list['loadtime']?></td>
                      <!-- 당일 세션수 -->
                      <td><?=$list['cookie']?></td>
                       <!-- 전일 대비 세션수 증가/감소 -->
                       <!-- 당일 세션수 - 전날 세션수 = 양수 -> 증가 / 음수 -> 감소 -->
                      <td><??></td>
                    </tbody>
                    <?}?>
                  </table>
                  <button class="btn btn-secondary" id="excel" onclick="" style="float:right; margin-top:10px;">excel 다운</button>
                </div>
              </div>
            </div>
            <!----------------------------------------- 테이블 End ------------------------------------------------->
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- end footer -->
        <!-- ============================================================== -->
      </div>
      <?php
      include "../layout/footer.php";
      ?>
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper -->
    <!-- ============================================================== -->
  </div>

</body>
</html>
