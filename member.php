<?php
include 'layout/layout.php';
include 'api/dbconn.php';
include 'api/pageClass.php';
include 'api/common.php';

$conn = new DBC();
$conn->DBI();

$layout = new Layout;

if($_GET['order'] == null or $_GET['order'] == 'asc') {
  $order = ' order by pos_code asc';
} else if($_GET['order'] == 'desc') {
  $order = ' order by pos_code desc';
}

if($_GET['search_content'] == null) {
  $searchContent = ' ';
} else if($_GET['search_content'] != null) {
  $searchContent = " where (pos_code like '%".$_GET['search_content']."%' or rate like '%".$_GET['search_content']."%')";
}


// 페이징
$query = "select * from did_member".$searchContent.$order;
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

if(isset($_GET['search_content']) or isset($_GET['order']) or isset($_GET['list'])) {
  $page->setUrl("search_content".$_GET['search_content']."&order=".$_GET['order']."&list=".$_GET['list']);
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
<!doctype html>
<html class="no-js" lang="kr">
<?$layout->CssJsFile('<link href="assets/css/table-responsive.css" rel="stylesheet">');?>
<?$layout->head($head);?>
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
          <div class="row mt-4">
            <div class="col-lg-6"><h5>계정관리 </h5></div>
            <div class="col-lg-6" style="text-align: right;"><small> Main > 계정관리 </small></div>
            <html><hr color="black" width=100%></html>
            <div class="card col-lg-12 mt-3">
              <form action="<?$_SERVER['PHP_SELF']?>" method="GET" class="col-lg-12" name='form'>
                <div class="card-body">
  								<html><hr color="#E6E6E6" width=100%></html>
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
                  <div class="input-group">
  									<div class="col-lg-6">
  										<button style="display:none;" class="btn btn-lg mr-2 btn btn-xs" type="reset" value="" onclick="changes1Step(value)"><i class="fa fa-refresh"></i></button>
                    </div>
  									<button class="btn btn-primary btn btn-xs" style="text-align:center;" type="submit" id="searchButton">검색</button>
  								</div><br>
                  <div class="row mt-4 mb-2">
                    <div class="col-lg-10 text-left">
                      <p>total: <?echo $cnt;?>  </p>
                    </div>
                    <div class="">
  										<select class="form-control form-control-sm" id="order" name='order'>
  											<option <?if($_GET['order'] == "asc"){echo "selected";}?> value="asc" selected>오름차순</option>
  											<option <?if($_GET['order'] == "desc"){echo "selected";}?> value="desc">내림차순</option>
  										</select>
  									</div>
                    <div class="col-lg-1 text-right">
                      <select class="form-control form-control-sm" name='list'>
  											<option value="10" <?if($_GET['list'] == 10){echo "selected";}else if($_GET['list'] == null){echo "selected";}?>>10</option>
  											<option value="20" <?if($_GET['list'] == 20){echo "selected";}?>>20</option>
                        <option value="30" <?if($_GET['list'] == 30){echo "selected";}?>>30</option>
  										</select>
                    </div>

                    <div class="col-lg-12 mt-2">
                      <div class="single-table">
                        <div class="table-responsive">
                          <table class="table table-bordered text-center">
                            <thead class="text-uppercase">
                              <tr>
                                <th scope="col">NO.</th>
                                <th scope="col">아이디</th>
                                <th scope="col">등급</th>
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
                              $sql = "select * from did_member $searchContent $order limit $limit, $list";
                              $conn->DBQ($sql);
                              $conn->DBE();

                              while($row=$conn->DBF()){
                              ?>
                              <tr>
                                <td><?php echo $i+1;?></td>
                                <td><a href="member_form.php?no=<?echo $row['pos_code'];?>"><?php echo $row['pos_code']; ?></a></td>
                                <td><?php if($row['rate'] == null){echo '-';}else{echo $row['rate'];} ?></td>
                              </tr>
                              <?$i++;}?>
                            </tbody>
                          </table>
                        </div>
                        <!-- /table-responsive -->
                      </div>
                      <!-- /single-table -->
                    </div>
                    <!-- /col-lg-12 mt-2 -->
                  </div>
                  <ul class="pagination" style="justify-content: center;">
                    <?echo $paging; ?>
                  </ul>
                </div>
                <!-- /card-body -->
              </form>
            </div>
            <!-- /card -->
          </div>
          <!-- /row mt-4 -->
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
      language: 'ko-KR'
    });
  });
  </script>
</body>

</html>
