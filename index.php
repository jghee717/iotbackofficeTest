<?php
include 'api/dbconn.php';

$conn = new DBC();
$conn->DBI();

?>
<!doctype html>
<html class="no-js" lang="kr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>IoT BackOffice</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/png" href="assets/images/icon/favicon.ico">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/css/themify-icons.css">
  <link rel="stylesheet" href="assets/css/metisMenu.css">
  <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
  <link rel="stylesheet" href="assets/css/slicknav.min.css">
  <!-- amchart css -->
  <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
  <!-- others css -->
  <link rel="stylesheet" href="assets/css/typography.css">
  <link rel="stylesheet" href="assets/css/default-css.css">
  <link rel="stylesheet" href="assets/css/styles.css?ver=1.2">
  <link rel="stylesheet" href="assets/css/responsive.css">
  <link rel="stylesheet" href="assets/datepicker/datepicker.css?ver=1">
  <!-- modernizr css -->
  <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>

  <script>
  function check_id(obj)
  {
    var string = obj.value;
    var pattern = /[ㄱ-힣]|[ \[\]{}()<>?|`~!@#$%^&*=,.;:\"'\\]/g;
    if(pattern.test(string)){
      obj.value =  string.replace(pattern,"");
    }
  }
  </script>
</head>
</style>
<body>
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->
      <div class="container-fluid p-0">
        <div class="row">

          <div class="col-lg-7 text-center" style='background-color: #585858'>
            <br><br><br><br><br><br><br><br><br><br><br>
            <h1><font color="white">스마트 홈 체험</font></h1><br>
            <h2><font color="white">Administrator</font></h2>
          </div>
          <!-- /col-lg-6 -->

          <div class="col-lg-5">
              <br><br><br><br><br><br><br>
              <div class="card">
                <div class="card-body text-center">
                  <h4>LOGIN</h4>
                </div>
              </div>
              <!-- /card -->

              <form action="api/dashReg/login.php" method="post">
                <div class="login-form-body">
                  <div class="form-gp">
                    <label for="admin-id">아이디를 입력하여 주세요</label>
                    <input type="text" id="admin-id" name="admin-id" value="" onchange="check_id(this);" onkeyup="check_id(this);">
                  </div>
                  <div class="form-gp">
                    <label for="admin-pass">비밀번호를 입력하여 주세요</label>
                    <input type="password" id="admin-pass" name="admin-pass" value="">
                  </div>
                  <div class="submit-btn-area">
                    <button type="submit">로그인 <i class="ti-arrow-right"></i></button>
                  </div>
                </div>
              </form>
                <!-- /login-form-body -->
                <br><br><br><br><br><br><br>

          </div>
          <!-- /col-lg-6 -->
        </div>
        <!-- /row -->
        <!-- footer area start-->
        <footer>
            <div class="footer-area">
                <p>© Copyright 2019 lnc. All right reserved.</p>
            </div>
        </footer>
        <!-- footer area end-->
      </div>
      <!-- /container -->

      <!-- jquery latest version -->
      <script src="assets/js/vendor/jquery-2.2.4.min.js"></script>
      <!-- bootstrap 4 js -->
      <script src="assets/js/popper.min.js"></script>
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/js/owl.carousel.min.js"></script>
      <script src="assets/js/metisMenu.min.js"></script>
      <script src="assets/js/jquery.slimscroll.min.js"></script>
      <script src="assets/js/jquery.slicknav.min.js"></script>

      <!-- start chart js -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
      <!-- start highcharts js -->
      <script src="https://code.highcharts.com/highcharts.js"></script>
      <script src="https://code.highcharts.com/modules/exporting.js"></script>
      <script src="https://code.highcharts.com/modules/export-data.js"></script>

      <!-- start amcharts -->
      <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
      <script src="https://www.amcharts.com/lib/3/ammap.js"></script>
      <script src="https://www.amcharts.com/lib/3/maps/js/worldLow.js"></script>
      <script src="https://www.amcharts.com/lib/3/serial.js"></script>
      <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
      <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

      <!-- start zingchart js -->
      <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
      <script>
      zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
      ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "ee6b7db5b51705a13dc2339db3edaf6d"];
      </script>

      <!-- others plugins -->
      <script src="assets/js/plugins.js"></script>
      <script src="assets/js/scripts.js"></script>
      <!-- datepicker js -->
      <script src="assets/datepicker/datepicker.js?ver=1"></script>
      <script src="assets/datepicker/datepicker.ko-KR.js?ver=1"></script>
</body>

</html>
