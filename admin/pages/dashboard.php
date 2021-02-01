<?php
include "../sessions/db.php";
include "../sessions/access_all.php";

date_default_timezone_set('Asia/Seoul');   // 현재시간을 서울 기준으로
?>
<!doctype html>
<html lang="ko">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>dashboard</title>
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
  // 일/주/월 이동
  $(function(){
    $('#opt_day').click(function () {
      var day = $('#opt_day').val();
      location.href="?show="+day;
    });
    $('#opt_week').click(function () {
      var week = $('#opt_week').val();
      location.href="?show="+week;
    });
    $('#opt_month').click(function () {
      var month = $('#opt_month').val();
      location.href="?show="+month;
    });

    var show = '<?=$_GET['show']?>';
    if(show ==""){
      show = "day";
    }
    $('button').find('span').removeClass("active");
    $("button[name="+show+"]").addClass("active");

  });

  // 세션수 /PV /UV
  $(function(){
    var arr_session = new Array();
    $("input[name=session]").each(function(idx){
      arr_session.push($("input[name=session]:eq(" + idx + ")").val());
    });
    var arr_pv = new Array();
    $("input[name=pv]").each(function(idx){
      arr_pv.push($("input[name=pv]:eq(" + idx + ")").val());
    });
    var arr_uv = new Array();
    $("input[name=uv]").each(function(idx){
      arr_uv.push($("input[name=uv]:eq(" + idx + ")").val());
    });

    var date = new Array();
    $("input[name=date]").each(function(idx){
      date.push($("input[name=date]:eq(" + idx + ")").val());
    });

    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: date, //x축
        datasets: [{
          label: '세션수',
          backgroundColor: "rgba(89, 172, 203, 0.76)",
          data: arr_session //y축
        },{
          label: 'PV' , //기간동안의 모든 로드페이지 카운트 / 7  count(loadpage) / 7 as avgPV
          backgroundColor: "rgba(64, 246, 58, 0.38)",
          data: arr_pv //y축
        },{
          label: 'UV', //기간동안의  user_cooky갯수 를 distinct하여 표현 count(distinct user_cooky) / 7
          backgroundColor: "rgba(224, 97, 50, 0.72)",
          data: arr_uv //y축
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

  // 평균 체류시간
  $(function(){
    var arr_time = new Array();
    $("input[name=session_time]").each(function(idx){
      arr_time.push($("input[name=session_time]:eq(" + idx + ")").val());
    });

    var date = new Array();
    $("input[name=date]").each(function(idx){
      date.push($("input[name=date]:eq(" + idx + ")").val());
    });

    function minutesToHours(second) {
        var mintues = Math.floor(second/60);
        second = second % 60;
        return mintues+ "분" + second +"초";
    }

    var ctx = document.getElementById('myChart2').getContext('2d');
    var chart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: date, //x축
        datasets: [{
          label: '세션당 평균 체류시간',
          borderColor: "rgba(224, 97, 50, 0.72)",
          backgroundColor: "rgba(224, 97, 50, 0.72)",
          data: arr_time //y축
        }]
      },
      // Configuration options go here
      options: {
        responsive: true,
        title: {
          display: true,
          //text: 'Chart.js Line Chart'
        },
        tooltips: {
          mode: 'index',
          intersect: false,
        },
        hover: {
          mode: 'nearest',
          intersect: true
        },
        scales: {
          xAxes: [{
            display: true,
            scaleLabel: {
              display: true
            }
          }],
          yAxes: [{
            display: true,
            scaleLabel: {
              display: true
            },
            ticks: {
              userCallback: function(item) {
                return minutesToHours(item);
              },
              beginAtZero:true  //Y축의 값이 0부터 시작
            }
          }]
        }
      }
    });
  });

  // 세션당 평균 PV
  $(function(){
    var arr_session_pv = new Array();
    $("input[name=session_pv]").each(function(idx){
      arr_session_pv.push($("input[name=session_pv]:eq(" + idx + ")").val());
    });

    var date = new Array();
    $("input[name=date]").each(function(idx){
      date.push($("input[name=date]:eq(" + idx + ")").val());
    });

    var ctx = document.getElementById('myChart3').getContext('2d');
    var chart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: date, //x축
        datasets: [{
          label: '세션당 평균 PV',
          borderColor: "rgba(59, 224, 50, 0.72)",
          backgroundColor: "rgba(59, 224, 50, 0.72)",
          data: arr_session_pv //y축
        }]
      },
      // Configuration options go here
      options: {
        responsive: true,
        title: {
          display: true,
          //text: 'Chart.js Line Chart'
        },
        tooltips: {
          mode: 'index',
          intersect: false,
        },
        hover: {
          mode: 'nearest',
          intersect: true
        },
        scales: {
          xAxes: [{
            display: true,
            scaleLabel: {
              display: true
            }
          }],
          yAxes: [{
            display: true,
            scaleLabel: {
              display: true
            },
            ticks: {
              beginAtZero:true  //Y축의 값이 0부터 시작
            }
          }]
        }
      }
    });
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
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
              <h2 class="pageheader-title">전체 통계</h2>
              <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">전체 통계</li>
                    <li class="breadcrumb-item active" aria-current="page">전체 통계</li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <div class="dashboard-short-list">
          <div class="row">
            <div class="col-xl-20 col-lg-12 col-md-12 col-sm-12 col-12">
              <div style="padding-bottom:10px;" class="btn-group btn-group-toggle" data-toggle="buttons">
                <button class="btn btn-primary" type="radio" name="day" id="opt_day" value="day">일</button>
                <button class="btn btn-primary" type="radio" name="week" id="opt_week" value="week">주</button>
                <button class="btn btn-primary" type="radio" name="month" id="opt_month" value="month">월</button>
              </div>
              <div class="card">
                <div class="card-body">
                  <h3>세션수 / PV / UV</h3>
                  <canvas id="myChart" height="100"></canvas>
                  <?
                  $db = new DBC;
                  $db->DBI();
                  for($i=6; $i>=0; $i--){
                    if($_GET['show']=="week"){  //주 단위
                      if(date("w",time()) == "1"){ // 월요일
                        $start = date("Y-m-d",strtotime("this monday", strtotime("today -".$i."week")));
                        $end = date("Y-m-d",strtotime("this sunday", strtotime("today -".$i."week")));
                      }else{  //화~일요일
                        $start = date("Y-m-d",strtotime("last monday", strtotime("today -".$i."week")));
                        $end = date("Y-m-d",strtotime("this sunday", strtotime("today -".$i."week")));
                      }?>
                      <input type="hidden" name="date" value="<?=$start."(월) ~ ".$end."(일)"?>">
                    <?
                    }else if($_GET['show']=="month"){    //월 단위
                      $start = date("Y-m-01",strtotime("-".$i." month"));
                      $end = date("Y-m-t",strtotime("-".$i." month"));
                      $month = date("y년 n월",strtotime("-".$i." month"))
                      ?>
                      <input type="hidden" name="date" value="<?=$month?>">
                      <!-- <input type="hidden" name="date" value="<?=$start." ~ ".$end?>"> -->
                    <?
                    }else{  //일 단위
                      $start = date("Y-m-d",strtotime("today -".$i."day"));
                      $end = date("Y-m-d",strtotime("today -".$i."day"));
                      ?>
                      <input type="hidden" name="date" value="<?=$start?>">
                    <?
                    }

                    $a = " where DATE_FORMAT(loadtime,'%Y-%m-%d') between '$start' and '$end'";

                    $sql = "SELECT count(distinct connect_cookie) as session, count(loadpage) as pv, count(distinct user_cooky) as uv FROM web_log$a ";
                    $db->DBQ($sql);
                    $db->DBE();
                    $result = $db->DBF();?>
                    <input type="hidden" name="session" value="<?=$result['session']?>">
                    <input type="hidden" name="pv" value="<?=$result['pv']?>">
                    <input type="hidden" name="uv" value="<?=$result['uv']?>">
                  <? } $db->DBO();?>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <h3>세션당 평균 체류시간</h3>
                  <canvas id="myChart2" height="100"></canvas>
                  <?
                  $db = new DBC;
                  $db->DBI();

                  for($i=6; $i>=0; $i--){
                    if($_GET['show']=="week"){  //주 단위
                      if(date("w",time()) == "1"){ // 월
                        $start = date("Y-m-d",strtotime("this monday", strtotime("today -".$i."week")));
                        $end = date("Y-m-d",strtotime("this sunday", strtotime("today -".$i."week")));
                      }else{  //화~일
                        $start = date("Y-m-d",strtotime("last monday", strtotime("today -".$i."week")));
                        $end = date("Y-m-d",strtotime("this sunday", strtotime("today -".$i."week")));
                      }
                    }else if($_GET['show']=="month"){    //월 단위
                      $start = date("Y-m-01",strtotime("-".$i." month"));
                      $end = date("Y-m-t",strtotime("-".$i." month"));
                      $month = date("y년 n월",strtotime("-".$i." month"));
                    }else{  //일 단위
                      $start = date("Y-m-d",strtotime("today -".$i."day"));
                      $end = date("Y-m-d",strtotime("today -".$i."day"));
                    }
                    $a = " where DATE_FORMAT(loadtime,'%Y-%m-%d') between '$start' and '$end'";
                    $b = " and DATE_FORMAT(loadtime,'%Y-%m-%d') between '$start' and '$end'";

                    $sql = "SELECT ROUND(sum(residtime)/(select count(distinct connect_cookie) from web_log where residtime is not null$b),0) as total from web_log$a";
                    $db->DBQ($sql);
                    $db->DBE();
                    $result = $db->DBF();?>
                    <input type="hidden" name="session_time" value="<?=$result['total']?>">
                  <? } $db->DBO();?>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <h3>세션당 평균 PV</h3>
                  <canvas id="myChart3" height="100"></canvas>
                  <?
                  $db = new DBC;
                  $db->DBI();

                  for($i=6; $i>=0; $i--){
                    if($_GET['show']=="week"){  //주 단위
                      if(date("w",time()) == "1"){ // 월
                        $start = date("Y-m-d",strtotime("this monday", strtotime("today -".$i."week")));
                        $end = date("Y-m-d",strtotime("this sunday", strtotime("today -".$i."week")));
                      }else{  //화~일
                        $start = date("Y-m-d",strtotime("last monday", strtotime("today -".$i."week")));
                        $end = date("Y-m-d",strtotime("this sunday", strtotime("today -".$i."week")));
                      }
                    }else if($_GET['show']=="month"){    //월 단위
                      $start = date("Y-m-01",strtotime("-".$i." month"));
                      $end = date("Y-m-t",strtotime("-".$i." month"));
                      $month = date("y년 n월",strtotime("-".$i." month"));
                    }else{  //일 단위
                      $start = date("Y-m-d",strtotime("today -".$i."day"));
                      $end = date("Y-m-d",strtotime("today -".$i."day"));
                    }
                    $a = " where DATE_FORMAT(loadtime,'%Y-%m-%d') between '$start' and '$end'";
                    $b = " and DATE_FORMAT(loadtime,'%Y-%m-%d') between '$start' and '$end'";

                    $sql = "SELECT ROUND(count(loadpage)/(select count(distinct connect_cookie) from web_log$a),1) as total from web_log$a";
                    $db->DBQ($sql);
                    $db->DBE();
                    $result = $db->DBF();?>
                    <input type="hidden" name="session_pv" value="<?=$result['total']?>">
                  <? } $db->DBO();?>
                </div>
              </div>
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
