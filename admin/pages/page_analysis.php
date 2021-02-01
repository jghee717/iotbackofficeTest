<!doctype html>
<html lang="ko">
<?php
include "../sessions/db.php";
//include "../sessions/access_all.php";
?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>계정현황</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
  <link href="../assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/libs/css/style.css">
  <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
  <link rel="stylesheet" type="text/css" href="../assets/vendor/datatables/css/dataTables.bootstrap4.css">
  <link rel="stylesheet" type="text/css" href="../assets/vendor/datatables/css/buttons.bootstrap4.css">
  <link rel="stylesheet" type="text/css" href="../assets/vendor/datatables/css/select.bootstrap4.css">
  <link rel="stylesheet" type="text/css" href="../assets/vendor/datatables/css/fixedHeader.bootstrap4.css">

  <link rel="stylesheet" href="../assets/vendor/bootstrap-select/css/bootstrap-select.css">
  <link  href="../assets/datepicker/datepicker.css" rel="stylesheet">
</head>

<body>
  <?php
  $sc=$_GET['sc']; // 소분류
  $page=$_GET['page']; //페이지 번호
  $max_row=$_GET['row']; //표시할 열 총개수
  $startd=$_GET['start']; //표시할 열 총개수
  $endd=$_GET['end']; //표시할 열 총개수
  $sort=$_GET['sortby'];
  if($startd==''&&$endd==''){
    $startd = date("Y-m-d",strtotime("last Thursday"));
    $endd = date("Y-m-d",strtotime("Wednesday"));
  }
  ?>
  <!-------------------------------------------------------모달 -->
  <div class="modal fade" id="UPModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:70rem" role="document">
      <div class="modal-content" >
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">로컬에서 이미지 추가하기</h5>
          <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </a>
        </div>
        <div class="modal-body">
          <div style="float:right" class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-primary">
              <input type="radio" name="options" id="opt_day">일
            </label>
            <label class="btn btn-primary active">
              <input type="radio" name="options" id="opt_week"> 주
            </label>
            <label class="btn btn-primary">
              <input type="radio" name="options" id="opt_month"> 월
            </label>
          </div>
          <div>
            <canvas id="canvas"></canvas>
          </div>
        </div>
        <div class="modal-footer">
          <button onclick="addData()" id="addData">Add Data</button>
          <input type="button" class="btn btn-secondary" data-dismiss="modal" value="닫기">
        </div>
      </div>
    </div>
  </div>
  <!-------------------------------------------------------모달 END-->

  <div class="dashboard-main-wrapper">
    <!-- navbar -->
    <?php
    include "../layout/header.php";
    ?>
    <!-- end navbar -->
    <!-- left sidebar -->
    <?php
    include "../layout/sidebar.php";
    ?>
    <!-- end left sidebar -->
    <div class="dashboard-wrapper">
      <div class="container-fluid  dashboard-content">
        <!-- pageheader -->
        <?php
        include "../layout/pageheader.php";
        ?>
        <!-- end pageheader -->
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
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
                  <input style="width:150px; display:inline; margin-right:40px; background-color:white; text-align:center"type="text" class="form-control" id="datepicker1" readonly/>
                  <label style="margin-right:40px">~</label>
                  <input style="width:150px; display:inline; margin-right:10px; background-color:white; text-align:center"type="text" class="form-control" id="datepicker2" readonly/>
                  <button type="button" class="btn btn-info" id="search">검색</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
              <div class="card-header">
                <div style="font-size:20px;text-align:center">'<?=$startd?>'부터 '<?=$endd?>'까지의 결과
                  <select id="sort" class="form-control form-control-sm" style="float:right;width:20%" onchange="sort_change()"><?
                  switch ($_GET['sortby']) {
                    case 'PV':?>
                    <option value="PV" selected>PV순</option>
                    <option value="UV">UV순</option>
                    <option value="AV">평균체류시간순</option>
                    <option value="EX">종료횟수</option><?
                    break;
                    case 'UV':?>
                    <option value="PV">PV순</option>
                    <option value="UV" selected>UV순</option>
                    <option value="AV">평균체류시간순</option>
                    <option value="EX">종료횟수</option><?
                    break;
                    case 'AV':?>
                    <option value="PV">PV순</option>
                    <option value="UV">UV순</option>
                    <option value="AV" selected>평균체류시간순</option>
                    <option value="EX">종료횟수</option><?
                    break;
                    case 'EX':?>
                    <option value="PV">PV순</option>
                    <option value="UV">UV순</option>
                    <option value="AV">평균체류시간순</option>
                    <option value="EX" selected>종료횟수</option><?
                    break;
                    default:?>
                    <option value="PV" selected>PV순</option>
                    <option value="UV">UV순</option>
                    <option value="AV">평균체류시간순</option>
                    <option value="EX">종료횟수</option><?
                    break;
                  }?>
                </select>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped table-bordered first">
                  <thead>
                    <tr style="text-align:center">
                      <th>순위</th>
                      <th>페이지</th>
                      <th>PV<!div class="fas fa-angle-down"></div></th>
                      <th>UV</th>
                      <th>평균 체류시간</th>
                      <th>종료 횟수</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    $db = new DBC;
                    $pv="SELECT loadpage,(SELECT menu FROM menu_list WHERE menu_num=W.loadpage) as page,COUNT(*) as PV,COUNT(DISTINCT user_cooky) as UV,AVG(residtime) as AV,IFNULL(COUNT(*)-COUNT(residtime),0) as EX FROM web_log W";
                    $pv.=" WHERE DATE(loadtime) BETWEEN '$startd' AND '$endd'";
                    $pv.=" GROUP BY loadpage";
                    if($sort=='') {
                      $pv.=" ORDER BY PV desc";
                    }else {
                      $pv.=" ORDER BY ".$_GET['sortby']." desc";
                    }
                    $db->DBI();
                    $db->DBQ($pv);
                    $db->DBE();
                    $i=1;
                    while ($list=$db->DBF()) {?>
                      <tr>
                        <th style="text-align:center"><?=$i?></th>
                        <?
                        if ($list['page']=="") {?>
                          <th style="text-align:center"><a href="" onclick="addData('서비스전체보기')" data-toggle="modal" data-target="#UPModal" title="상세보기"><font color="blue">서비스 전체보기</a></font></th><?
                        }else {?>
                          <th style="text-align:center"><a href="" onclick="addData('<?=$list['page']?>',<?=$i?>)" data-toggle="modal" data-target="#UPModal"  title="상세보기"><font color="blue"><?=$list['page']?></a></font></th><?
                        }
                        ?>
                        <th style="text-align:right"><?=$list['PV']?>회</th>
                        <input type="hidden" name="pv" value="<?=$list['PV']?>">
                        <th style="text-align:right"><?=$list['UV']?>회</th>
                        <input type="hidden" name="uv" value="<?=$list['UV']?>">
                        <th style="text-align:right"><?=round($list['AV'],1)?>초</th>
                        <th style="text-align:right"><?=$list['EX']?>회</th>
                      </tr><?
                      $i++;
                    }?>

                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer">
              <form class="" action="../sessions/pageExcle.php" method="post">
                <input type="text" name="query" value="<?=$pv?>" style="display:none">
                <input type="text" name="startd" value="<?=$startd?>" style="display:none">
                <input type="text" name="endd" value="<?=$endd?>" style="display:none">
                <input type="text" name="sort" value="<?=$sort?>" style="display:none">
                <input style="float:right" class="btn btn-download" type="submit" name="" value="Excel 저장">
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- footer -->
    <?php
    include "../layout/footer.php";
    ?>
    <!-- end footer -->

  </div>
</div>
<!-- Optional JavaScript -->

<script src="../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
<script src="../assets/vendor/slimscroll/jquery.slimscroll.js"></script>
<script src="../assets/vendor/multi-select/js/jquery.multi-select.js"></script>
<script src="../assets/libs/js/main-js.js"></script>
<script src="../../../../../cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="../../../../../cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="../assets/vendor/datatables/js/buttons.bootstrap4.min.js"></script>
<script src="../assets/vendor/datatables/js/data-table.js"></script>

<script src="../assets/vendor/bootstrap-select/js/bootstrap-select.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="../Chart/samples/utils.js"></script>
<script src="../datepicker/date.js"></script>
<script>

// $(function(){
//   $("#").click(function(){
//     $.ajax({
//       type: 'post' ,
//       url: '' ,
//       dataType : 'html' ,
//       success: function(data) {
//         $("").html(data);
//       }
//     });
//   })
// });

// $(function(){
//   $('#opt_day').click(function () {
//     var day = $('#opt_day').val();
//     location.href="?show="+day;
//   });
//   $('#opt_week').click(function () {
//     var week = $('#opt_week').val();
//     location.href="?show="+week;
//   });
//   $('#opt_month').click(function () {
//     var month = $('#opt_month').val();
//     location.href="?show="+month;
//   });
// });
//
var value = $('input[name=pv]').val();
var value2 = $('input[name=uv]').val();

var config = {
  type: 'line',
  data: {
    labels: [],
    datasets: [{
      label: 'PV',
      fill: false,
      backgroundColor: window.chartColors.red,
      borderColor: window.chartColors.red,
      data: []
    }, {
      label: 'UV',
      fill: false,
      backgroundColor: window.chartColors.blue,
      borderColor: window.chartColors.blue,
      data: []
    }]
  },

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
        }
      }]
    }
  }
};

window.onload = function() {
  var ctx = document.getElementById('canvas').getContext('2d');
  window.myLine = new Chart(ctx, config);
};

function addData(title,ik){
  for (var i = 0; i < 7; i++) {
    removedada();
  }
  var today = new Date();
  var dd = today.getDate()-7;
  var mm = today.getMonth()+1; //January is 0!
  var yyyy = today.getFullYear();

  for (var i = 0; i < 7; i++) {
    today =yyyy+'/'+mm+'/'+dd;
    adddada(today,i+ik,i+ik+5);
    dd+=1;
  }
  $('#exampleModalLabel').html(title+" 상세");
}

function adddada(labal,pv,uv){
  if (config.data.datasets.length > 0) {
    config.data.labels.push(labal);
    var i=0;
    config.data.datasets.forEach(function(dataset) {
      if (i==0) {
        dataset.data.push(value);
      }else {
        dataset.data.push(value2);
      }
      i++;
    });

    window.myLine.update();
  }
}

function removedada(){
  config.data.labels.splice(-1, 1); // remove the label first

  config.data.datasets.forEach(function(dataset) {
    dataset.data.pop();
  });

  window.myLine.update();
}


function download(qu){//쿼리 보내기
  location.href="http://ccitc.dothome.co.kr/admin/sessions/downExcle.php";
}

function sort_change(){
  var sort=$('#sort').val();
  location.href="page_analysis.php?mc=<?=$_GET['mc']?>&sc=<?=$_GET['sc']?>&sortby="+sort+"&start=<?=$_GET['start']?>&end=<?=$_GET['end']?>";
}
</script>

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
    location.href="./page_analysis.php?mc=통계관리&sc=페이지분석&sortby=<?=$_GET['sortby']?>&start="+start+"&end="+end;
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
    $('#datepicker1').datepicker('setDate','<?=date("Y-m-d",strtotime("last Thursday"))?>');
    $('#datepicker2').datepicker('setDate','<?=date("Y-m-d",strtotime("Wednesday"))?>');
  }
});
</script>
</body>

</html>
