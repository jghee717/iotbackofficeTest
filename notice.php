<?php
include 'layout/layout.php';
include 'api/dbconn.php';
include 'api/pageClass.php';
include 'api/common.php';

$conn = new DBC();
$conn->DBI();
$layout = new Layout;

$sql = "select rate from did_member where pos_code = '".$_SESSION['id']."'";
$conn->DBQ($sql);
$conn->DBE();
$row = $conn->DBF();

switch ($row[0]) {
  case 'B':
  ?>
  <script type="text/javascript">alert("접근 권한이 없습니다")
  window.history.back(-1); </script>
  <?
  break;

  default:


$list = $_GET['list'];

// 다중 검색 조건

$searchDate = " and end_day >= '".$date_from."' AND start_day <= '".$date_to."' ";

switch($_GET['expose']){
  case 전체:
  $searchExpose = " expose IN('노출','미노출') ";
  break;

  case 노출:
  $searchExpose = " expose = '노출'";
  break;

  case 미노출:
  $searchExpose = " expose = '미노출'";
  break;

  default:
  $searchExpose = " expose IN('노출','미노출')";
  break;
}
if($_GET['search_content'] == null){
  $searchContent = '';
} else {
  $searchContent = " and title like '%".$_GET['search_content']."%' ";
}

if($_GET['order'] == null or $_GET['order'] == 'asc') {
  $order = ' order by idx asc';
} else if($_GET['order'] == 'desc') {
  $order = ' order by idx desc';
}

// 페이징
$query = "SELECT * FROM did_notice WHERE". $searchExpose.$searchContent.$searchDate.$order;
$conn->DBQ($query);
$conn->DBE(); //쿼리 실행
$cnt = $conn->resultRow();

$total_row = $cnt;		// db에 저장된 게시물의 레코드 총 갯수 값. 현재 값은 테스트를 위한 값
if($_GET['list'] == null) {
  $list = 10;							// 화면에 보여질 게시물 갯수
} else {
  $list = $_GET['list'];  // 화면에 보여질 게시물 갯수
}
$block = 5;							// 화면에 보여질 블럭 단위 값[1]~[5]
$page = new paging($_GET['page'], $list, $block, $total_row);

if(isset($_GET['expose']) or isset($_GET['date_from']) or isset($_GET['date_to']) or isset($_GET['search_content']) or isset($_GET['list'])) {
  $page->setUrl("expose=".$_GET['expose']."&date_from=".$_GET['date_from']."&date_to=".$_GET['date_to']."&search_content=".$_GET['search_content']."&list=".$_GET['list']);
}

$limit = $page->getVar("limit");	// 가져올 레코드의 시작점을 구하기 위해 값을 가져온다. 내부로직에 의해 계산된 값

$page->setDisplay("prev_btn", "<"); // [이전]버튼을 [prev] text로 변경
$page->setDisplay("next_btn", ">"); // 이와 같이 버튼을 이미지로 바꿀수 있음
$page->setDisplay("end_btn", ">>");
$page->setDisplay("start_btn", "<<");
$page->setDisplay("class","page-item");
$page->setDisplay("full");
$paging = $page->showPage();

?>
<script type="text/javascript">
//인젝션 정규식
function nospecialKey()
{
  var re = /select|union|insert|update|delete|drop|[\'\"|#|\/\*|\*\/|\\\|\;]/gi;
  var input=$("#search_content").val();
  if(re.test(input) != false)
  {
    alert("입력 불가능한 문자가 있습니다.");
    $("#search_content").focus();
    return false;
  }
}
</script>
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
  <?$time = mktime(0,0,0, date("m"), 1, date("Y"));
  $prev_month = strtotime("-1 month", $time); ?>
  function setSearchDate(num){
    switch(num) {
      case 0:
      document.getElementById('date_from').value = <?php echo json_encode(date('Y-m-d')); ?>;
      document.getElementById('date_to').value = <?php echo json_encode(date('Y-m-d')); ?>;
      break;

      case 1:
      document.getElementById('date_from').value = <?php echo json_encode(date("Y-m-d",strtotime("-1 days"))); ?>;
      document.getElementById('date_to').value = <?php echo json_encode(date("Y-m-d",strtotime("-1 days"))); ?>;
      break;

      case 2:
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

      case 3:
      document.getElementById('date_from').value = <?php echo json_encode(date("Y-m-01", $prev_month)); ?>;
      document.getElementById('date_to').value = <?php echo json_encode(date("Y-m-t", $prev_month)); ?>;
      break;

      case 4:
      document.getElementById('date_from').value = <?php echo json_encode(date("Y-m-d",strtotime("-1 months"))); ?>;
      document.getElementById('date_to').value = <?php echo json_encode(date('Y-m-d')); ?>;
      break;

      case 5:
      document.getElementById('date_from').value = <?php echo json_encode(date("Y-m-d",strtotime("-3 months"))); ?>;
      document.getElementById('date_to').value = <?php echo json_encode(date('Y-m-d')); ?>;
      break;
    }
  }

  function setConfirm(obj){
    var con_delete = confirm("정말 삭제 하시겠습니까?");
    if(con_delete == true){
      document.getElementById('delete_no').value = obj;
      document.getElementById('delete_form').submit();
    } else if(con_delete == false){
      return false;
    }
  }

  function chkValue(){
    var pattern = /[~!@#$%^&*()_+|<>?:{}]/;
    if(pattern.test(document.getElementById('search_content').value)){
      alert('한글 / 영문 / 숫자만 입력 가능합니다.');
      document.getElementById('search_content').value = "";
      document.getElementById('search_content').focus();
      return false;
    } else {
      document.getElementById('submit_form').submit();
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
    <?$layout->header($header);?><br>
      <!-- page title area end -->
      <div class="main-content-inner">
        <div class="container">
          <div class="row">
            <div class="col-lg-6"><h5>공지사항 등록 </h5></div>
            <div class="col-lg-6" style="text-align: right;"><small> Main > 공지사항 등록 </small></div>
            <style>
            form{border:1px solid #E6E6E6;}
            hr{margin:1px;}
            </style>
            <html><hr color="black" width=100%></html>
            <div class="card col-lg-12 mt-3">
              <div class="card-body">
              <form action="api/noticeReg/delete.php" method="POST" id="delete_form">
                <input type="hidden" id="delete_no" name="delete_no" value="">
              </form>
              <form action="<?$_SERVER['PHP_SELF']?>" method="GET" class="col-lg-12" id="submit_form" name='form' onchange="return nospecialKey()">
                  <div class="input-group">
                    <html><hr color="#E6E6E6" class="mt-2" width=100%></html>
                    <span style="margin-left:15px; boackground-color: #FFFFFF;" name="span" id="span" class="form-control2 form-control-sm col-lg-1 color-white" >노출여부</span>
                    <div class="col-lg-3">
                      <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio"
                          <?if($_GET['expose'] == "전체" or $_GET['expose'] == null){echo "checked";}?> id="expose0" name="expose" class="custom-control-input" value="전체">
                          <label class="custom-control-label" for="expose0">전체</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio"
                          <?if($_GET['expose'] == "노출"){echo "checked";}?> id="expose1" name="expose" class="custom-control-input" value="노출">
                          <label class="custom-control-label" for="expose1">노출</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline">
                          <input type="radio"
                          <?if($_GET['expose'] == "미노출"){echo "checked";}?> id="expose2" name="expose" class="custom-control-input" value="미노출">
                          <label class="custom-control-label" for="expose2">미노출</label>
                      </div>
  									</div>
                    <span style="margin-left:8px;" name="span" id="span" class="form-control2 form-control-sm col-lg-2 color-white" >노출기간</span>
                    <input data-toggle="datepicker" type="text" class="form-control form-control-sm col-lg-1 ml-2" id="date_from" name="date_from" readonly=""
                    value="<?echo $date_from;?>">
                    <div class="input-group-prepend">
                      <div class="input-group-text form-control form-control-sm">~</div>
                    </div>
                    <input data-toggle="datepicker" type="text" class="form-control form-control-sm col-lg-2" id="date_to" name="date_to" readonly=""
                    value="<?echo $date_to;?>">
                    <button type="button" style="margin-left:10px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" value="" onclick="setSearchDate(0)"/>오늘</button>
                    <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" onclick="setSearchDate(1)"/>어제</button>
                    <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" onclick="setSearchDate(2)"/>일주일</button>
                    <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" onclick="setSearchDate(3)"/>지난달</button>
                    <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" onclick="setSearchDate(4)"/>1개월</button>
                    <button type="button" style="margin-left:5px" class="btn btn-flat btn-outline-dark btn btn-xs" name="dateType" onclick="setSearchDate(5)"/>3개월</button>
                  </div>
                  <!-- /input-group -->
                  <html><hr color="#E6E6E6" width=100%></html>
  								<html><hr color="#E6E6E6" class="mt-2" width=100%></html>

                  <div class="form-group">
                    <div class="col-lg-12">
                      <div class="input-group">
                        <span class="input-group form-control2 form-control-sm col-lg-1">검색어</span>
                        <input type="text" class="form-control form-control-sm col-lg-3 ml-3" id="search_content" name="search_content" style="background-color: #E9ECEF"
                        value="<?if($_GET['search_content'] != null){echo $_GET['search_content'];}?>">
                      </div>
                      <!-- /input-group -->
                    </div>
                    <!-- /col-lg-12 -->
                    <html><hr color="#E6E6E6" width=100%></html>
                  </div>
                  <!-- /form-group -->
                  <!-- 검색 -->
  								<div class="input-group">
  									<div class="col-lg-6">
  										<button style="display:none;" class="btn btn-lg mr-2 btn btn-xs" type="reset" value="" onclick="changes1Step(value)"><i class="fa fa-refresh"></i></button>
                    </div>
  									<button class="btn btn-primary btn btn-xs" style="text-align:center;" type="button" onclick="chkValue()" id="searchButton">검색</button>
  								</div><br>

                <div class="row mt-4 mb-2">
                  <div class="col-lg-11 text-left">
                    <p>total: <?echo $cnt;?>  </p>
                  </div>
                  <div class="col-lg-1 text-right">
                    <select class="form-control form-control-sm" name='list'>
											<option value="10" <?if($_GET['list'] == 10){echo "selected";}else if($_GET['list'] == null){echo "selected";}?>>10</option>
											<option value="20" <?if($_GET['list'] == 20){echo "selected";}?>>20</option>
                      <option value="30" <?if($_GET['list'] == 30){echo "selected";}?>>30</option>
										</select>
                  </div>
                  <!-- /col-lg-3 text-right -->

                  <div class="col-lg-12 mt-2">
                    <div class="single-table">
                      <div class="table-responsive">
                        <table class="table table-bordered text-center">
                          <thead class="text-uppercase">
                            <tr>
                              <th scope="col">NO.</th>
                              <th scope="col">제목</th>
                              <th scope="col">시작일</th>
                              <th scope="col">종료일</th>
                              <th scope="col">노출여부</th>
                              <th scope="col">등록일</th>
                              <th scope="col">관리</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?
                            if($_GET['page'] == null){
                              $i = 0;
                            } else if($_GET['list'] == 10 && $_GET['page'] != null){
                              $i = $_GET['list'] * ($_GET['page'] - 1);
                            } else if($_GET['list'] == 20 && $_GET['page'] != null) {
                              $i = $_GET['list'] * ($_GET['page'] - 1);
                            } else if($_GET['list'] == 30 && $_GET['page'] != null) {
                              $i = $_GET['list'] * ($_GET['page'] - 1 );
                            }
                            $sql = "SELECT * FROM did_notice WHERE $searchExpose $searchContent $searchDate $order limit $limit, $list";
                            $conn->DBQ($sql);
                            $conn->DBE();
                            while($row=$conn->DBF()){
                            ?>
                            <tr>
                              <td><?php echo $i+1; ?></td>
                              <td><?php echo $row['title']; ?></td>
                              <td><?php echo $row['start_day']; ?></td>
                              <td><?php echo $row['end_day']; ?></td>
                              <td><?php echo $row['expose']; ?></td>
                              <td><?php echo $row['date']; ?></td>
                              <td>
                                <a href="notice_form.php?no=<?echo $row['idx'];?>"><button type="button" class="btn btn-flat btn-outline-secondary btn-xs">수정</button></a>
                                <button type="button" class="btn btn-flat btn-outline-secondary btn-xs" onclick="setConfirm(<?echo $row['idx'];?>)">삭제</button>
                              </td>
                            </tr>
                            <?$i++;}?>
                          </tbody>
                        </table>
                        <!-- /table -->
                      </div>
                      <!-- /table-responsive -->
                    </div>
                    <!-- single-table -->

                    <div class="col-lg-12 mt-5 text-right">
                      <a href="notice_form.php"><button type="button" class="btn btn-flat btn-secondary">등록</button></a>
                    </div>

                  </div>
                </div>
                </form>
                <!-- row mt-4 mb-2 -->
                <br>
                <div class="text-center">
                  <ul class="pagination" style="justify-content: center;">
                    <?echo $paging; ?>
                  </ul>
                </div>
              </div>
              <!-- /card-body -->
          </div>
          <!-- /card -->
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
  $(function() {
    $('[data-toggle = "datepicker"]').datepicker({
      autoHide: true,
      zIndex: 2048,
      language: 'ko-KR',
      startDate: '1980-01-01',
      endDate: '2020-12-31',

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
      endDate: '2020-12-31',

    });

    $("#date_from").datepicker('setEndDate', document.getElementById('date_to').value);
    $("#date_to").datepicker('setStartDate', document.getElementById('date_from').value);
    $("#date_from").datepicker('setDate', document.getElementById('date_from').value);
    $("#date_to").datepicker('setDate', document.getElementById('date_to').value);

  });

  </script>
</body>

</html>
<?break; }?>
