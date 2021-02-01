<?php
include 'layout/layout.php';
include 'api/dbconn.php';

$conn = new DBC();
$conn->DBI();

$layout = new Layout;

if(isset($_GET['no'])){
  $no = $_GET['no'];
}

$sql = "select * from did_member where pos_code = '".$no."'";
$conn->DBQ($sql);
$conn->DBE();
$row = $conn->DBF();
?>
<!doctype html>
<html class="no-js" lang="kr">
<?$layout->CssJsFile('<link href="assets/css/table-responsive.css" rel="stylesheet">');?>
<?$layout->head($head);?>
<script>
function setConfirm(obj){
  switch (obj) {
    case 1:
    var con_modify = confirm("수정 하시겠습니까?");
    if(con_modify == true){
      document.getElementById('submit_form').submit();
    } else if(con_modify == false){
      return false;
    }
    break;

    default:
    var con_delete = confirm("정말 삭제 하시겠습니까?");
    if(con_delete == true){
      document.getElementById('delete_no').value = obj;
      document.getElementById('delete_form').submit();
    } else if(con_delete == false){
      return false;
    }
    break;
  }
}
</script>

<style>
  form {
    border:1px solid #E6E6E6;
  }
   hr {
     margin:1px;
   }
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
    <?$layout->header($header);?>
      <!-- page title area end -->
      <div class="main-content-inner">
        <div class="container">
          <div class="row">
            <div class="col-lg-6"><h5>계정관리 </h5></div>
            <div class="col-lg-6" style="text-align: right;"><small> Main > 계정관리 </small></div>
            <html><hr color="black" width=100%></html>

            <div class="col-lg-12 mt-2">
              <form action="api/memberReg/delete.php" method="POST" id="delete_form">
                <input type="hidden" id="delete_no" name="delete_no" value="">
              </form>
              <form action="api/noticeReg/insert.php" method="POST" id="submit_form" enctype="multipart/form-data">
                <input type="hidden" name="no" value="<?php echo $no; ?>">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-12 mt-3 mb-3">
                        <h5><strong><font color="blue">기초관리</font></strong></h5>
                      </div>
                      <html><hr color="" width=99%></html>
                      <div class="col-lg-2 mt-3">
                        <span style='font-weight: 550;'>등급 설정</span>
                      </div>
                      <div class="col-lg-3 mt-3">
                        <div class="custom-control custom-radio custom-control-inline ml-3">
                          <input type="radio" id="rate0" name="rate" class="custom-control-input" value="A"
                          <?if($no != null){if($row['rate'] == 'A'){echo "checked";}}else{echo "checked";}?>>
                          <label class="custom-control-label" for="rate0">A</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="rate1" name="rate" class="custom-control-input" value="B"
                          <?if($no != null){if($row['rate'] == 'B'){echo "checked";}}?>>
                          <label class="custom-control-label" for="rate1">B</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio" id="rate2" name="rate" class="custom-control-input" value="C"
                          <?if($no != null){if($row['rate'] == 'C'){echo "checked";}}?>>
                          <label class="custom-control-label" for="rate2">C</label>
                        </div>
                      </div>

                      <div class="col-lg-2 mt-3">
                        <span style='font-weight: 550;'>비밀번호 재설정</span>
                      </div>
                      <div class="col-lg-3 mt-3">
                        <input type="password" class="form-control form-control-sm" name="pw" value="" style="background-color: #E9ECEF;">
                      </div>
                    </div>

                    <div class="row mt-5">
                      <div class="col-lg-12 text-center">
                        <button type="button" class="btn btn-flat btn-light btn-md mr-2" onclick="history.back(-1)">취소</button>
                        <button type="button" class="btn btn-flat btn-secondary btn-md mr-2" onclick="setConfirm(1)">수정</button>
                      </div>
                    </div>
                  </div>
                  <!-- /card-body -->
                </div>
                <!-- /card -->
              </form>
            </div>
            <!-- /col-lg-12 mt-2 -->
          </div>
          <!-- /row -->
        </div>
        <!-- /container -->
      </div>
      <!-- main content area end -->
      <?$layout->footer($footer);?>
  </div>
  <!-- main wrapper end -->
  <?$layout->JsFile("
  ");?>
  <?$layout->js($js);?>
  <script>
  $(function() {
    $('[data-toggle = "datepicker"]').datepicker({
      autoHide: true,
      zIndex: 2048,
      language: 'ko-KR'
    });
  });

  </script>
</body>

</html>
