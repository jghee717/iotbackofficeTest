<!doctype html>
<?php
include "../sessions/access_all.php";
include "../sessions/db.php";

date_default_timezone_set('Asia/Seoul');   // 현재시간을 서울 기준으로
?>
<html lang="ko">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>세션상세</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
  <link href="../assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/libs/css/style.css">
  <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
  <script src="../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
  <script src="../assets/vendor/slimscroll/jquery.slimscroll.js"></script>
  <script src="../assets/libs/js/main-js.js"></script>

  <link  href="../assets/datepicker/datepicker.css" rel="stylesheet">
  <script src="../assets/datepicker/datepicker.js"></script>
  <script src="../assets/datepicker/datepicker.ko-KR.js"></script>
  <script src="../assets/datepicker/date.js"></script>
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
      location.href="./session_detail.php?mc=통계관리&sc=세션상세&start="+start+"&end="+end;
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

  //증감율 양수일경우 파란색
  $(function(){
    //체류시간 증감율
    var a = $("input[name=session_time]").val();
    if(a>=0){
      $(".session_time").css("color","blue");
    }
    //PV 증감율
    var a = $("input[name=session_pv]").val();
    if(a>=0){
      $(".session_pv").css("color","blue");
    }
    //세션수 증감율
    var a = $("input[name=session]").val();
    if(a>=0){
      $(".session").css("color","blue");
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
                  <div style="text-align:center">
                    <label class="btn btn-primary" id="today">오늘</label>
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
            <!----------------------------------------- 테이블 End ------------------------------------------------->
          </div>
          <div class="row">
            <!----------------------------------------- 차트 ---------------------------------------------------->
            <!-- 일 -->
            <div class="col-xl-20 col-lg-12 col-md-12 col-sm-12 col-12">
              <div class="card">
                <div class="card-body">
                  <h3>세션수</h3>
                  <table class="table table-striped table-bordered first" style="text-align:center">
                    <thead>
                      <tr>
                        <th width="20%">(전체 기간) 총 세션수</th>
                        <th width="20%">(선택 기간) 총 세션수</th>
                        <th width="20%">(전체 기간) 평균 세션수</th>
                        <th width="20%">(선택 기간) 평균 세션수</th>
                        <th width="20%">증감율</th>
                      </tr>
                    </thead>
                    <tbody style="font-size:20px">
                      <?php
                      $db = new DBC;
                      $db -> DBI();
                      $query = "SELECT count(distinct connect_cookie) as total from web_log";
                      $db -> DBQ($query);
                      $db -> DBE();
                      $total = $db -> DBF();

                      if(isset($_GET['start']) && isset($_GET['end'])){
                        $start = $_GET['start'];
                        $end = $_GET['end'];
                      }else{
                        $start = date("Y-m-d",strtotime("today", time()));
                        $end = date("Y-m-d",strtotime("today", time()));
                      }
                      $a = " where DATE_FORMAT(loadtime,'%Y-%m-%d') between '$start' and '$end'";
                      $query2 = "SELECT count(distinct connect_cookie) as total from web_log$a";
                      $db -> DBQ($query2);
                      $db -> DBE();
                      $since = $db -> DBF();?>
                      <tr>
                        <td><?=$total['total']?></td>
                        <td><?=$since['total']?></td>
                        <?
                        //두 날짜의 차이 계산
                        $query2 = "SELECT loadtime from web_log order by idx asc limit 0,1";
                        $db -> DBQ($query2);
                        $db -> DBE();
                        $time = $db-> DBF();
                        $first = date_format(date_create($time['loadtime']),'Y-m-d');?>
                        <td>
                          <?
                          $last = date("Y-m-d",strtotime("today", time()));
                          $c = strtotime($last)-strtotime($first);
                          $d = $c/24/60/60 +1;
                          if($total['total'] == "0"){
                            $all=0;
                          }else{
                            $all = $total['total']/$d;
                          }
                          echo(round($all,1));?></td>
                        <td>
                          <?
                          if(isset($_GET['start']) && isset($_GET['end'])){
                            $start = $_GET['start'];
                            $end = $_GET['end'];
                          }else{
                            $start = date("Y-m-d",strtotime("today", time()));
                            $end = date("Y-m-d",strtotime("today", time()));
                          }
                          if(strtotime($first)-strtotime($start) >=0){
                            $c = strtotime($end)-strtotime($first);
                          }else{
                            $c = strtotime($end)-strtotime($start);
                          }
                          $d = $c/24/60/60 +1;
                          if($since['total'] == "0"){
                            $final=0;
                          }else{
                            $final = $since['total']/$d;
                          }
                          echo(round($final,1));?></td>
                        <td class="session" style="color:red"><?=round(($final-$all)/$all*100)?>%</td>
                        <input type="hidden" name="session" value="<?=round(($final-$all)/$all*100)?>">
                      </tr>
                    </tbody>
                  </table>
                  <h3 style="padding-top:20px">세션당 평균 PV</h3>
                  <table class="table table-striped table-bordered first" style="text-align:center">
                    <thead>
                      <tr>
                        <th width="30%">(전체 기간) 세션당 평균 PV</th>
                        <th width="30%">(선택 기간) 세션당 평균 PV</th>
                        <th width="30%">증감율</th>
                      </tr>
                    </thead>
                    <tbody style="font-size:20px">
                      <?php
                      $db = new DBC;
                      $db -> DBI();
                      $query = "SELECT ROUND(count(loadpage)/(select count(distinct connect_cookie) from web_log),1) as total from web_log";
                      $db -> DBQ($query);
                      $db -> DBE();
                      $total = $db -> DBF();

                      if(isset($_GET['start']) && isset($_GET['end'])){
                        $start = $_GET['start'];
                        $end = $_GET['end'];
                      }else{
                        $start = date("Y-m-d",strtotime("today", time()));
                        $end = date("Y-m-d",strtotime("today", time()));
                      }
                      $a = " where DATE_FORMAT(loadtime,'%Y-%m-%d') between '$start' and '$end'";
                      $query2 = "SELECT ROUND(count(loadpage)/(select count(distinct connect_cookie) from web_log$a),1) as total from web_log$a";
                      $db -> DBQ($query2);
                      $db -> DBE();
                      $since = $db -> DBF();
                      $db -> DBO();
                      if($since['total']==""){
                        $since['total']=0;
                      }
                      if($total['total']==""){
                        $total['total']=0;
                      }?>

                      <tr>
                        <td><?=$total['total']?></td>
                        <td><?=$since['total']?></td>
                        <td class="session_pv" style="color:red"><?=round(($since['total']-$total['total'])/$total['total']*100)?>%</td>
                        <input type="hidden" name="session_pv" value="<?=round(($since['total']-$total['total'])/$total['total']*100)?>">
                      </tr>
                    </tbody>
                  </table>
                  <h3 style="padding-top:20px">세션당 평균 체류시간</h3>
                  <table class="table table-striped table-bordered first" style="text-align:center">
                    <thead>
                      <tr>
                        <th width="30%">(전체 기간) 세션당 평균 체류시간</th>
                        <th width="30%">(선택 기간) 세션당 평균 체류시간</th>
                        <th width="30%">증감율</th>
                      </tr>
                    </thead>
                    <tbody style="font-size:20px">
                      <?php
                      $db = new DBC;
                      $db -> DBI();
                      $query = "SELECT ROUND(sum(residtime)/(select count(distinct connect_cookie) from web_log where residtime is not null),0) as total from web_log";
                      $db -> DBQ($query);
                      $db -> DBE();
                      $total = $db -> DBF();

                      if(isset($_GET['start']) && isset($_GET['end'])){
                        $start = $_GET['start'];
                        $end = $_GET['end'];
                      }else{
                        $start = date("Y-m-d",strtotime("today", time()));
                        $end = date("Y-m-d",strtotime("today", time()));
                      }
                      $a = " where DATE_FORMAT(loadtime,'%Y-%m-%d') between '$start' and '$end'";
                      $b = " and DATE_FORMAT(loadtime,'%Y-%m-%d') between '$start' and '$end'";
                      $query2 = "SELECT ROUND(sum(residtime)/(select count(distinct connect_cookie) from web_log where residtime is not null$b),0) as total from web_log$a";
                      $db -> DBQ($query2);
                      $db -> DBE();
                      $since = $db -> DBF();
                      $db -> DBO();?>
                      <tr>
                        <td><?
                        if(isset($total['total'])){
                          if($total['total']>="60"){
                            echo(floor($total['total']/60)."분 ".($total['total']%60)."초");
                          }else{
                            echo($total['total']."초");
                          }
                        }else{
                          echo"0초";
                        }
                        ?></td>
                        <td>
                          <?
                          if(isset($since['total'])){
                            if($since['total']>="60"){
                              echo(floor($since['total']/60)."분 ".($since['total']%60)."초");
                            }else{
                              echo($since['total']."초");
                            }
                          }else{
                            echo"0초";
                          }
                          ?></td>
                        <td class="session_time" style="color:red"><?=round(($since['total']-$total['total'])/$total['total']*100)?>%</td>
                        <input type="hidden" name="session_time" value="<?=round(($since['total']-$total['total'])/$total['total']*100)?>">
                      </tr>
                    </tbody>
                  </table>
                  <button class="btn btn-secondary" id="excel" onclick="" style="float:right; margin-top:10px;">excel 다운</button>
                </div>
              </div>
            </div>
            <!----------------------------------------- 테이블 End ------------------------------------------------->
          </div>
        <!-- ============================================================== -->
        <!-- end footer -->
        <!-- ============================================================== -->
        </div>
        <?php
        include "../layout/footer.php";
        ?>
      </div>
    </div>
  </div>

</body>
</html>
