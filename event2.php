<?php
include 'layout/layout.php';
include 'api/dbconn.php';
include 'api/common.php';

$conn = new DBC();
$conn->DBI();

$layout = new Layout;


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

function battleDate(){
  var date_from = new Date(document.getElementById('date_from2').value);
  date_from = getFormatDate(date_from);
  // if(date_from=="2019-06-06" || date_from=="2019-06-07"){
  //   document.getElementById('battleFrom').value="33";
  // }
  if(date_from=='2019-06-10' ||  date_from=='2019-06-11' ||  date_from=='2019-06-12' ||  date_from=='2019-06-13' ||  date_from=='2019-06-14' ||  date_from=='2019-06-15' ||  date_from=='2019-06-16'){
    // document.getElementById('date_from2').value="2019-06-10";
    document.getElementById('battleFrom').value="1";
  }else if(date_from=='2019-06-17' ||  date_from=='2019-06-18' ||  date_from=='2019-06-19' ||  date_from=='2019-06-20' ||  date_from=='2019-06-21' ||  date_from=='2019-06-22' ||  date_from=='2019-06-23'){
    // document.getElementById('date_from2').value="2019-06-17";
    document.getElementById('battleFrom').value="2";
  }else if(date_from=='2019-06-24' ||  date_from=='2019-06-25' ||  date_from=='2019-06-26' ||  date_from=='2019-06-27' ||  date_from=='2019-06-28' ||  date_from=='2019-06-29' ||  date_from=='2019-06-30'){
    // document.getElementById('date_from2').value="2019-06-24";
    document.getElementById('battleFrom').value="3";
  }else if(date_from=='2019-07-01' ||  date_from=='2019-07-02' ||  date_from=='2019-07-03' ||  date_from=='2019-07-04' ||  date_from=='2019-07-05' ||  date_from=='2019-07-06' ||  date_from=='2019-07-07'){
    // document.getElementById('date_from2').value="2019-07-01";
    document.getElementById('battleFrom').value="4";
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
    <?$layout->header($header);?>
      <!-- page title area end -->
      <div class="main-content-inner">
        <div class="container">
          <div class="row">
            <div class="col-lg-6"><h5>담당배틀 관리 </h5></div>
            <div class="col-lg-6" style="text-align: right;"><small> Main > 담당배틀 관리 </small></div>
            <style>
            form{border:1px solid #E6E6E6;}
            hr{margin:1px;}
            </style>
            <html><hr color="black" width=100%></html>
            <div class="card col-lg-12 mt-3">
              <div class="card-body">
                <form action="api/v1/event_excel.php" method="GET" class="col-lg-12" id="submit_form" name='form'>
                  <div class="input-group">
                    <html><hr color="#E6E6E6" class="mt-2" width=100%></html>

                    <span style="" name="span" id="span" class="input-group form-control2 form-control-sm col-lg-1">누적기간</span>
                    <!-- <input data-toggle="datepicker" type="text" class="form-control form-control-sm col-lg-1" id="date_from" name="date_from" readonly=""
                    value="<?//echo $date_from;?>">
                    <div class="input-group-prepend">
                      <div class="input-group-text form-control form-control-sm">~</div>
                    </div> -->
                    <input data-toggle="datepicker" type="text" class="form-control form-control-sm col-lg-1 mr-5" id="date_to" name="date_to" readonly=""
                    value="<?echo $date_to;?>">

                    <span style="" name="span" id="span" class="input-group form-control2 form-control-sm col-lg-1 ml-5">배틀기간</span>
                    <select name="battleFrom" id="battleFrom" class="form-control2 form-control-sm col-lg-3 ml-5">
                      <?for($i=1; $i<54; $i++){?>
                        <option id="bDate<?echo $i;?>" value="<?echo date("Y-m-d", strtotime("+". $i-1 ." week",  strtotime("2018-12-31")));?>"><?php echo "(".$i."주차) ".date("Y-m-d", strtotime("+". $i-1 ." week",  strtotime("2018-12-31")))." ~ ".date("Y-m-d", strtotime("+". $i-1 ." week",  strtotime("2019-01-06"))); ?></option>
                      <?}?>
                    </select>

                    <input type="hidden" value="">
                    <input type="text" name="battleTo" id="battleTo" readonly="" class="form-control form-control-sm col-lg-2 mr-5"
                    value="2019-01-21 ~ 2019-01-27">
                    <script>
                      function getFormatDate(date){
                        var year = date.getFullYear();                                 //yyyy
                        var month = (1 + date.getMonth());                     //M
                        month = month >= 10 ? month : '0' + month;     // month 두자리로 저장
                        var day = date.getDate();                                        //d
                        day = day >= 10 ? day : '0' + day;                            //day 두자리로 저장
                        return  year + '-' + month + '-' + day;
                      }

                      $("#battleFrom").change(function(){
                        var tempDate1 = new Date($("#battleFrom option:selected").val());
                        var tempDate2 = new Date($("#battleFrom option:selected").val());

                        new Date(tempDate1.setDate(tempDate1.getDate()+21));
                        new Date(tempDate2.setDate(tempDate2.getDate()+27));

                        tempDate1 = getFormatDate(tempDate1);
                        tempDate2 = getFormatDate(tempDate2);

                        var text = tempDate1 + " ~ " + tempDate2;
                        $("#battleTo").val(text);
                      });
                    </script>
                    <!-- <input data-toggle="datepicker" type="text" class="form-control form-control-sm col-lg-2" id="date_from2" name="date_from2" readonly=""
                    value="2019-06-10" onchange="battleDate()">
                    <input type="hidden" id="battleFrom" name="battleFrom" value="1">
                    <div class="input-group-prepend">
                      <div class="input-group-text form-control form-control-sm">~</div>
                    </div>
                    <input type="text" class="form-control form-control-sm col-lg-2 mr-5" id="date_to2" name="date_to2" readonly=""
                    value="2019-07-07">
                    <input type="hidden" id="battleTo" name="battleTo" value="4"> -->

                    <button type="button" id="RawButton" class="btn btn-primary ml-5 btn-xs">데이터 저장</button>

                  </div>
                  <!-- /input-group -->
                  <html><hr color="#E6E6E6" width=100%></html>
                  <!-- /form-group -->
                </form>
              </div>
            </div>
          </div>
          <!-- /row -->
        </div>
        <!-- /container -->
      </div>
      <!-- main content area end -->
      <?$layout->footer($footer);?>
  </div>
  <!-- main wrapper end -->
  <?$layout->JsFile("");?>
  <?$layout->js($js);?>
  <script>
  $(function(){
    $('[data-toggle = "datepicker"]').datepicker({
      autoHide: true,
      zIndex: 2048,
      language: 'ko-KR',

      // startDate: '2019-06-10',
      // endDate: '2019-07-07',
    });
  });

  $(function(){
    $("#RawButton").click(function(){
      $("#submit_form").submit();
    });
  });
  </script>
</body>

</html>
