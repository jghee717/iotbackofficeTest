<?php
include 'layout/layout.php';
include 'api/dbconn.php';
// include 'api/dashReg/sql.php';
// include 'api/dashReg/sql2.php';
include 'api/common.php';
include 'api/dashReg/explode.php';

$conn = new DBC();
$conn->DBI();
$layout = new Layout;

?>
<!doctype html>
<html class="no-js" lang="kr">
<?$layout->CssJsFile('<link href="assets/css/table-responsive.css" rel="stylesheet">');?>
<?$layout->head($head);?>
<style>
  form{border:1px solid #E6E6E6;}
  hr{margin:1px;}
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
          <div class="row mt-2">

            <div class="input-group">
             <html><hr color="#E6E6E6" width=100%></html>
              <span style="margin-left:15px;" name="span" id="span" class="input-group-text form-control form-control-sm col-lg-1" >공지사항</span>
              <div class="col-lg-4">
                <?
                $sql = "
                SELECT *
                FROM did_notice
                WHERE end_day >= '".$date_from."' AND start_day <= '".$date_to."' AND expose = '노출' ORDER BY idx DESC LIMIT 1
                ";
                $conn->DBQ($sql);
                $conn->DBE();
                $notice = $conn->DBF();
                ?>

                <?if($notice[0] == null){?>
                <p>최신 공지사항 없음</p>
                <?}else{?>
                <p><a href="notice_form.php?no=<?echo $notice['idx'];?>"><?echo $notice['title'];?></a></p>
                <?}?>
              </div>
              <div class="col-lg-4">
                <?if($notice[0] == null){}else{?>
                  <p><?echo $notice['start_day'].' ~ '.$notice['end_day'];?> │</p>
                <?}?>
              </div>
              <div class="col-lg-3 text-right">
                <a href="notice.php"><button type="button" class="btn btn-flat btn-outline-secondary btn-xs">+MORE</button></a>
              </div>
              <html><hr color="#E6E6E6" width=100%></html>
            </div>

            <div class="col-lg-8 mt-3">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="row mb-2">
                        <div class="col-lg-4">
                          <p style='font-weight: 600;'>기간별 운영 데이터</p>
                        </div>
                        <div class="col-lg-6">
                          <p><?php echo $date_from; ?> ~ <?php echo $date_to; ?> (7일) 데이터 기준</p>
                        </div>
                        <div class="col-lg-2 text-right">
                          <a href="stats_period.php"><button type="button" class="btn btn-flat btn-outline-secondary btn-xs">+MORE</button></a>
                        </div>

                        <div class="col-lg-6 mt-4">
                          <div class="card">
                            <div class="card-body">
                              <p style='font-weight: 550;'>설치매장현황</p><br>
                              <div class="row mt">

                                <div class="col-lg-4 text-left">
                                  <h4><font color="blue"><?php echo number_format($regist_store[0]); ?></font></h4>
                                </div>
                                <div class="col-lg-4 text-left">
                                  <h4><font color="black"><?php echo number_format($regist_store[0]+$regist_store[2]); ?></font></h4>
                                </div>
                                <div class="col-lg-4 text-right">
                                  <h4><font color="orange"><?php echo number_format(($regist_store[0]/($regist_store[0]+$regist_store[2]))*100,2); ?>%</font></h4>
                                </div>

                                <div class="col-lg-4 text-left">
                                  <p style="font-weight:700;">설치</p>
                                </div>
                                <div class="col-lg-4 text-left">
                                  <p style="font-weight:700;">전체</p>
                                </div>
                              </div>
                              <!-- /row mt -->
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-6 mt-4">
                          <div class="card">
                            <div class="card-body">
                              <p style='font-weight: 550;'>등록현황</p><br>
                              <div class="row">
                                <div class="col-lg-4 text-left">
                                  <h4><font color="blue"><?php echo number_format($regist_store[0]); ?></font></h4>
                                </div>

                                <div class="col-lg-4 text-left">
                                  <h4><font color="red"><?php echo number_format($regist_store[2]); ?></font></h4>
                                </div>

                                <div class="col-lg-4 text-left">
                                  <h4><font color="black"><?php echo ''; ?></font></h4>
                                </div>

                                <div class="col-lg-4 text-left">
                                  <p style="font-weight:700;">인증매장</p>
                                </div>

                                <div class="col-lg-4 text-left">
                                  <p style="font-weight:700;">미설치매장</p>
                                </div>

                                <div class="col-lg-4 text-left">
                                  <p style="font-weight:700;"><?php echo ''; ?></p>
                                </div>
                              </div>
                              <!-- /row -->

                            </div>
                          </div>
                        </div>

                        <div class="col-lg-3 mt-3">
                          <div class="card">
                            <div class="card-body">
                              <p style='font-weight: 550;'>실행 매장 수</p><br>
                              <h1> <?php echo number_format($exe_new_store[0]); ?></h1>
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-3 mt-3">
                          <div class="card">
                            <div class="card-body">
                              <p style='font-weight: 550;'>신규 등록 매장 수</p><br>
                              <h1> <?php echo number_format($exe_new_store[1]); ?></h1>
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-3 mt-3">
                          <div class="card">
                            <div class="card-body">
                              <p style='font-weight: 550;'>실행 비중</p><br>
                              <h1><font color="blue">
                                <?php echo $exe_per_pro[0]; ?>
                              </font></h1>
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-3 mt-3">
                          <div class="card">
                            <div class="card-body">
                              <p style='font-weight: 550;'>지난주 대비 실행률</p><br>
                              <h1><font color="blue">
                              <?php echo $exe_per_pro[1]; ?>
                            </font></h1>
                            </div>
                          </div>
                        </div>

                      </div>
                      <!-- /row mb-2 -->
                    </div>
                    <!-- /col-lg-12 -->

                    <div class="col-lg-12 mt-3">
                      <p style='font-weight: 600;'>앱 이용건수</p>
                      <div class="single-table">
                        <div class="table-responsive">
                          <!-- <table class="table table-bordered text-center">
                            <thead class="text-uppercase">
                              <tr>
                                <th rowspan="2">구분</th>
                                <th colspan="2">홈 -> 공간 이동 수</th>
                                <th colspan="2">전체 터치 수</th>
                              </tr>
                              <tr>
                                <th><?//echo substr($date_from,5,5). ' ~ ' .substr($date_to,5,5);?></th>
                                <th><?//echo substr($date_from,6,1);?>월 누적</th>
                                <th><?//echo substr($date_from,5,5). ' ~ ' .substr($date_to,5,5);?></th>
                                <th><?//echo substr($date_from,6,1);?>월 누적</th>
                              </tr>
                            </thead>

                            <tbody>
                              <tr>
                                <td><strong>전체(테스트 포함)</strong></td>
                                <td><?php// echo number_format($app_use[0]); ?></td>
                                <td><?php// echo number_format($app_use[1]); ?></td>
                                <td><?php// echo number_format($app_use[2]); ?></td>
                                <td><?php// echo number_format($app_use[3]); ?></td>
                              </tr>
                              <tr>
                                <td><strong>앱 설치 P코드 전체 매장 (파일럿 포함)</strong></td>
                                <td><?php// echo number_format($app_use[4]); ?></td>
                                <td><?php// echo number_format($app_use[5]); ?></td>
                                <td><?php// echo number_format($app_use[6]); ?></td>
                                <td><?php// echo number_format($app_use[7]); ?></td>
                              </tr>
                              <tr>
                                <td><strong>P코드 파일럿 매장</strong></td>
                                <td><?php// echo number_format($app_use[8]); ?></td>
                                <td><?php// echo number_format($app_use[9]); ?></td>
                                <td><?php// echo number_format($app_use[10]); ?></td>
                                <td><?php// echo number_format($app_use[11]); ?></td>
                              </tr>
                            </tbody>
                          </table> -->
                          <!-- /table table-bordered text-center -->

                          <table class="table table-bordered text-center">
                            <thead>
                              <tr>
                                <th rowspan="2" colspan="2">구분</th>
                                <th colspan="3">전체</th>
                                <th colspan="3">U+tv 체험하기</th>
                                <th colspan="3">U+IoT 체험하기</th>
                              </tr>
                              <tr>
                                <th>지난달</th>
                                <th>이번달(누적)</th>
                                <th>이번주</th>
                                <th>지난달</th>
                                <th>이번달(누적)</th>
                                <th>이번주</th>
                                <th>지난달</th>
                                <th>이번달(누적)</th>
                                <th>이번주</th>
                              </tr>
                            </thead>

                            <tbody>
                              <tr>
                                <td rowspan="2">UV</td>
                                <td>전체</td>
                                <td data-title="전체/지난달/전체"><?php echo number_format($app_use[3]+$app_use[6]); ?></td>
                                <td data-title="전체/이번달/전체"><?php echo number_format($app_use[4]+$app_use[7]); ?></td>
                                <td data-title="전체/이번주/전체"><?php echo number_format($app_use[5]+$app_use[8]); ?></td>
                                <td data-title="tv/지난달/전체"><?php echo number_format($app_use[3]); ?></td>
                                <td data-title="tv/이번달/전체"><?php echo number_format($app_use[4]); ?></td>
                                <td data-title="tv/이번주/전체"><?php echo number_format($app_use[5]); ?></td>
                                <td data-title="iot/지난달/전체"><?php echo number_format($app_use[6]); ?></td>
                                <td data-title="iot/이번달/전체"><?php echo number_format($app_use[7]); ?></td>
                                <td data-title="iot/이번주/전체"><?php echo number_format($app_use[8]); ?></td>
                              </tr>
                              <tr>
                                <td>P코드점</td>
                                <td data-title="전체/지난달/P코드점"><?php echo number_format($app_use[12]+$app_use[15]); ?></td>
                                <td data-title="전체/이번달/P코드점"><?php echo number_format($app_use[13]+$app_use[16]); ?></td>
                                <td data-title="전체/이번주/P코드점"><?php echo number_format($app_use[14]+$app_use[17]); ?></td>
                                <td data-title="tv/지난달/P코드점"><?php echo number_format($app_use[12]); ?></td>
                                <td data-title="tv/이번달/P코드점"><?php echo number_format($app_use[13]); ?></td>
                                <td data-title="tv/이번주/P코드점"><?php echo number_format($app_use[14]); ?></td>
                                <td data-title="iot/지난달/P코드점"><?php echo number_format($app_use[15]); ?></td>
                                <td data-title="iot/이번달/P코드점"><?php echo number_format($app_use[16]); ?></td>
                                <td data-title="iot/이번주/P코드점"><?php echo number_format($app_use[17]); ?></td>
                              </tr>
                              <tr>
                                <td rowspan="2">PV</td>
                                <td>전체</td>
                                <td data-title="전체/지난달/전체"><?php echo number_format($app_use[21]+$app_use[24]); ?></td>
                                <td data-title="전체/이번달/전체"><?php echo number_format($app_use[22]+$app_use[25]); ?></td>
                                <td data-title="전체/이번주/전체"><?php echo number_format($app_use[23]+$app_use[26]); ?></td>
                                <td data-title="tv/지난달/전체"><?php echo number_format($app_use[21]); ?></td>
                                <td data-title="tv/이번달/전체"><?php echo number_format($app_use[22]); ?></td>
                                <td data-title="tv/이번주/전체"><?php echo number_format($app_use[23]); ?></td>
                                <td data-title="iot/지난달/전체"><?php echo number_format($app_use[24]); ?></td>
                                <td data-title="iot/이번달/전체"><?php echo number_format($app_use[25]); ?></td>
                                <td data-title="iot/이번주/전체"><?php echo number_format($app_use[26]); ?></td>
                              </tr>

                              <tr>
                                <td>P코드점</td>
                                <td data-title="전체/지난달/P코드점"><?php echo number_format($app_use[30]+$app_use[33]); ?></td>
                                <td data-title="전체/이번달/P코드점"><?php echo number_format($app_use[31]+$app_use[34]); ?></td>
                                <td data-title="전체/이번주/P코드점"><?php echo number_format($app_use[32]+$app_use[35]); ?></td>
                                <td data-title="tv/지난달/P코드점"><?php echo number_format($app_use[30]); ?></td>
                                <td data-title="tv/이번달/P코드점"><?php echo number_format($app_use[31]); ?></td>
                                <td data-title="tv/이번주/P코드점"><?php echo number_format($app_use[32]); ?></td>
                                <td data-title="iot/지난달/P코드점"><?php echo number_format($app_use[33]); ?></td>
                                <td data-title="iot/이번달/P코드점"><?php echo number_format($app_use[34]); ?></td>
                                <td data-title="iot/이번주/P코드점"><?php echo number_format($app_use[35]); ?></td>
                              </tr>
                            </tbody>
                          </table>
                          <!-- /table table-bordered text-center -->
                        </div>
                        <!-- /table-responsive -->
                      </div>
                      <!-- /single-table -->
                    </div>
                    <!-- /col-lg-12 -->
                  </div>
                  <!-- /row -->


                </div>
                <!-- /card-body -->
              </div>
              <!-- /card -->
            </div>
            <!-- /col-lg-8 mt-2 -->

            <!-- 전체 PV 이용 현황 -->
            <div class="col-lg-4 mt-3">
              <div class="card">
                <div class="card-body">
                  <div class="row mb-3">
                    <div class="col-lg-7">
                      <h4 class="header-title">전체 PV 이용 현황</h4>
                    </div>
                    <!-- 버튼 그룹 -->
                    <div class="col-lg-5 text-right">
                      <div class="btn-group mb-xl-3" role="group">
                        <button type="button" id="pv_day" class="btn btn-xs btn-primary">일</button>
                        <button type="button" id="pv_week" class="btn btn-xs btn-primary">주</button>
                        <button type="button" id="pv_month" class="btn btn-xs btn-primary">월</button>
                      </div>
                    </div>
                  </div>
                  <!-- /row mb-3 -->
                  <?if(($pv_doughnut[0] + $pv_doughnut[1]) == 0){?>
                    <div id="no-data-1" style="display: block;"><p style="text-align:center;">데이터가 없습니다</p></div>
                  <?}else{?>
                    <canvas id="pie-1" height="233" style='display: block;'></canvas>
                  <?}?>

                  <?if(($pv_doughnut[2] + $pv_doughnut[3]) == 0){?>
                    <div id="no-data-2" style="display: none;"><p style="text-align:center;">데이터가 없습니다</p></div>
                  <?}else{?>
                    <canvas id="pie-2" height="233" style='display: none;'></canvas>
                  <?}?>

                  <?if(($pv_doughnut[4] + $pv_doughnut[5]) == 0){?>
                    <div id="no-data-3" style="display: none;"><p style="text-align:center;">데이터가 없습니다</p></div>
                  <?}else{?>
                    <canvas id="pie-3" height="233" style='display: none;'></canvas>
                  <?}?>
                </div>
                <!-- /card-body -->

                <div class="card-body">
                  <div class="row mb-3">
                    <div class="col-lg-7">
                      <h4 class="header-title">전체 UV 현황</h4>
                    </div>
                    <!-- 버튼 그룹 -->
                    <div class="col-lg-5 text-right">
                      <div class="btn-group mb-xl-3" role="group">
                        <button type="button" id="uv_day" class="btn btn-xs btn-primary">일</button>
                        <button type="button" id="uv_week" class="btn btn-xs btn-primary">주</button>
                        <button type="button" id="uv_month" class="btn btn-xs btn-primary">월</button>
                      </div>
                    </div>
                  </div>
                  <!-- /row mb-3 -->

                  <?if(($uv_doughnut[0] + $uv_doughnut[1]) == 0){?>
                    <div id="no-data-4" style="display: block;"><p style="text-align:center;">데이터가 없습니다</p></div>
                  <?}else{?>
                    <canvas id="pie-4" height="233" style='display: block;'></canvas>
                  <?}?>

                  <?if(($uv_doughnut[2] + $uv_doughnut[3]) == 0){?>
                    <div id="no-data-5" style="display: none;"><p style="text-align:center;">데이터가 없습니다</p></div>
                  <?}else{?>
                    <canvas id="pie-5" height="233" style='display: none;'></canvas>
                  <?}?>

                  <?if(($uv_doughnut[4] + $uv_doughnut[5]) == 0){?>
                    <div id="no-data-6" style="display: none;"><p style="text-align:center;">데이터가 없습니다</p></div>
                  <?}else{?>
                    <canvas id="pie-6" height="233" style='display: none;'></canvas>
                  <?}?>
                </div>
                <!-- /card-body -->
              </div>
              <!-- /card -->
            </div>
            <!-- /col-lg-4 mt-2 -->

            <!-- 라인 차트 -->
            <div class="col-lg-12 mt-3">
              <div class="card">
                <div class="card-body">
                  <div class="row mb-3">
                    <div class="col-lg-2">
                      <h4 class="header-title">기간별 PV/UV 추이</h4>
                    </div>
                    <!-- 버튼 그룹 -->
                    <div class="col-lg-10 text-left">
                      <div class="btn-group mb-xl-3" role="group">
                        <button type="button" id="pu_day" class="btn btn-xs btn-primary">일</button>
                        <button type="button" id="pu_week" class="btn btn-xs btn-primary">주</button>
                        <button type="button" id="pu_month" class="btn btn-xs btn-primary">월</button>
                      </div>
                    </div>
                  </div>
                  <!-- /row mb-3 -->
                  <div id="pu_line">
                    <canvas id="line-1" height="80" style='display: block;'></canvas>
                    <canvas id="line-2" height="80" style='display: none;'></canvas>
                    <canvas id="line-3" height="80" style='display: none;'></canvas>
                  </div>
                  <div class="mt-4 ml-3">
                    <p style="font-size:12px;">주간 데이터는 한 지점으로부터 6일 이후의 지점까지의 데이터를 합산한 수치임</p>
                    <p style="font-size:12px;">ex) <?echo substr(date("m-d", strtotime("".$date_from." -35 days")),0,2);?>월 <?echo substr(date("m-d", strtotime("".$date_from." -35 days")),3,2);?>일에
                      표시되는 수치는 <?echo substr(date("m-d", strtotime("".$date_from." -35 days")),0,2);?>월 <?echo substr(date("m-d", strtotime("".$date_from." -35 days")),3,2);?>일부터
                      <?echo substr(date("m-d", strtotime("".$date_from." -29 days")),0,2);?>월 <?echo substr(date("m-d", strtotime("".$date_from." -29 days")),3,2);?>일까지의 합산값</p>
                  </div>
                </div>
                <!-- /card-body -->
              </div>
              <!-- /card -->
            </div>
            <!-- /col-lg-12 mt-3 -->

            <!-- Updates version table -->
            <div class="col-lg-12 mt-3">
              <div class="card">
                <div class="card-body">
                  <h4 class="header-title">Application Updates Version</h4>
                  <section id="no-more-tables">
                    <table class="table table-bordered text-center">
                      <thead class="text-uppercase">
                        <tr style="text-align:center;">
                          <th class="numeric">구분</th>
                          <th class="numeric">전체기기</th>
                          <th class="numeric">최신버전</th>
                          <th class="numeric">최신버전 사용수</th>
                          <th class="numeric">최신버전 비율</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr style="text-align:center;">
                          <td class="numeric" data-title="구분">App</td>
                          <td class="numeric" data-title="전체기기"><?php echo number_format($app_contents[0]); ?></td>
                          <td class="numeric" data-title="최신버전"><?php echo $app_contents[1]; ?></td>
                          <td class="numeric" data-title="최신버전 사용수"><?php echo number_format($app_contents[2]); ?></td>
                          <td class="numeric" data-title="최신버전 비율"><?php echo $app_contents[3]; ?>
                          </td>
                        </tr>
                        <tr style="text-align:center;">
                          <td class="numeric" data-title="구분">컨텐츠</td>
                          <td class="numeric" data-title="전체기기"><?php echo number_format($app_contents[4]); ?></td>
                          <td class="numeric" data-title="최신버전"><?php echo $app_contents[5]; ?></td>
                          <td class="numeric" data-title="최신버전 사용수"><?php echo number_format($app_contents[6]); ?></td>
                          <td class="numeric" data-title="최신버전 비율"><?php echo $app_contents[7]?></td>
                        </tr>
                      </tbody>
                    </table>
                  </section>
                </div>
              </div>
            </div>
            <!-- /col-lg-12 mt-3 -->

          </div>
          <!-- /row mt-2-->
        </div>
        <!-- /container -->
      </div>
      <!-- main content area end -->
      <?$layout->footer($footer);?>
  </div>
  <!-- main wrapper end -->
  <?$layout->JsFile("
  <script src='api/dashReg/logic.js?ver=0.1'></script>
  ");?>
  <?$layout->js($js);?>
  <script>
  /*-------------- PV Pie chart 일 ------------*/
  if ($('#pie-1').length) {
      var ctx = document.getElementById("pie-1").getContext('2d');
      var chart = new Chart(ctx, {
          // The type of chart we want to create
          type: 'doughnut',
          // The data for our dataset
          data: {
              labels: ["어제", "오늘"],
              datasets: [{
                  backgroundColor: [
                      "#DF3A01",
                      "#0431B4",
                  ],
                  borderColor: '#fff',
                  data: [<?echo $pv_doughnut[0];?>, <?echo $pv_doughnut[1];?>],
              }]
          },
          // Configuration options go here
          options: {
              responsive: true,
              legend: {
                  display: true
              },
              animation: {
                  easing: "easeInOutBack"
              }
          }
      });
  }
  /*-------------- PV Pie chart 일 ------------*/

  /*-------------- PV Pie chart 주 ------------*/
  if ($('#pie-2').length) {
      var ctx = document.getElementById("pie-2").getContext('2d');
      var chart = new Chart(ctx, {
          // The type of chart we want to create
          type: 'doughnut',
          // The data for our dataset
          data: {
              labels: ["저번주", "이번주"],
              datasets: [{
                  backgroundColor: [
                      "#DF3A01",
                      "#0431B4",
                  ],
                  borderColor: '#fff',
                  data: [<?echo $pv_doughnut[2];?>, <?echo $pv_doughnut[3];?>],
              }]
          },
          // Configuration options go here
          options: {
              legend: {
                  display: true
              },
              animation: {
                  easing: "easeInOutBack"
              }
          }
      });
  }
  /*-------------- PV Pie chart 주 ------------*/

  /*-------------- PV Pie chart 월 ------------*/
  if ($('#pie-3').length) {
      var ctx = document.getElementById("pie-3").getContext('2d');
      var chart = new Chart(ctx, {
          // The type of chart we want to create
          type: 'doughnut',
          // The data for our dataset
          data: {
              labels: ["저번달", "이번달"],
              datasets: [{
                  backgroundColor: [
                      "#DF3A01",
                      "#0431B4",
                  ],
                  borderColor: '#fff',
                  data: [<?echo $pv_doughnut[4];?>, <?echo $pv_doughnut[5];?>],
              }]
          },
          // Configuration options go here
          options: {
              legend: {
                  display: true
              },
              animation: {
                  easing: "easeInOutBack"
              }
          }
      });
  }
  /*-------------- PV Pie chart 월 ------------*/

  /*-------------- UV Pie chart 일 ------------*/
  if ($('#pie-4').length) {
      var ctx = document.getElementById("pie-4").getContext('2d');
      var chart = new Chart(ctx, {
          // The type of chart we want to create
          type: 'doughnut',
          // The data for our dataset
          data: {
              labels: ["어제", "오늘"],
              datasets: [{
                  backgroundColor: [
                      "#9A2EFE",
                      "#01DF3A",
                  ],
                  borderColor: '#fff',
                  data: [<?echo $uv_doughnut[0];?>, <?echo $uv_doughnut[1];?>],
              }]
          },
          // Configuration options go here
          options: {
              responsive: true,
              legend: {
                  display: true
              },
              animation: {
                  easing: "easeInOutBack"
              }
          }
      });
  }
  /*-------------- UV Pie chart 일 ------------*/

  /*-------------- UV Pie chart 주 ------------*/
  if ($('#pie-5').length) {
      var ctx = document.getElementById("pie-5").getContext('2d');
      var chart = new Chart(ctx, {
          // The type of chart we want to create
          type: 'doughnut',
          // The data for our dataset
          data: {
              labels: ["저번주", "이번주"],
              datasets: [{
                  backgroundColor: [
                      "#9A2EFE",
                      "#01DF3A",
                  ],
                  borderColor: '#fff',
                  data: [<?echo $uv_doughnut[2];?>, <?echo $uv_doughnut[3];?>],
              }]
          },
          // Configuration options go here
          options: {
              legend: {
                  display: true
              },
              animation: {
                  easing: "easeInOutBack"
              }
          }
      });
  }
  /*-------------- UV Pie chart 주 ------------*/

  /*-------------- UV Pie chart 월 ------------*/
  if ($('#pie-6').length) {
      var ctx = document.getElementById("pie-6").getContext('2d');
      var chart = new Chart(ctx, {
          // The type of chart we want to create
          type: 'doughnut',
          // The data for our dataset
          data: {
              labels: ["저번달", "이번달"],
              datasets: [{
                  backgroundColor: [
                      "#9A2EFE",
                      "#01DF3A",
                  ],
                  borderColor: '#fff',
                  data: [<?echo $uv_doughnut[4];?>, <?echo $uv_doughnut[5];?>],
              }]
          },
          // Configuration options go here
          options: {
              legend: {
                  display: true
              },
              animation: {
                  easing: "easeInOutBack"
              }
          }
      });
  }
  /*-------------- UV Pie chart 월 ------------*/

  /*-------------- line-chart 일 START ------------*/
  if ($('#line-1').length) {
    var ctx = document.getElementById('line-1').getContext('2d');
    var chart = new Chart(ctx, {
      // The type of chart we want to create
      type: 'line',
      // The data for our dataset
      data: {
          labels: ['<?echo date("Y-m-d", strtotime("-11 days"));?>','<?echo date("Y-m-d", strtotime("-10 days"));?>','<?echo date("Y-m-d", strtotime("-9 days"));?>',
          '<?echo date("Y-m-d", strtotime("-8 days"));?>','<?echo date("Y-m-d", strtotime("-7 days"));?>','<?echo date("Y-m-d", strtotime("-6 days"));?>',
          '<?echo date("Y-m-d", strtotime("-5 days"));?>','<?echo date("Y-m-d", strtotime("-4 days"));?>','<?echo date("Y-m-d", strtotime("-3 days"));?>',
          '<?echo date("Y-m-d", strtotime("-2 days"));?>','<?echo date("Y-m-d", strtotime("-1 days"));?>','<?echo date("Y-m-d");?>'],
          datasets: [{
              label: 'UV',
              backgroundColor: 'rgba(0,0,204,0.2)',
              borderColor: 'rgb(0,0,204)',
              data: [<?echo $uv_line_day[11];?>, <?echo $uv_line_day[10];?>, <?echo $uv_line_day[9];?>,
                <?echo $uv_line_day[8];?>, <?echo $uv_line_day[7];?>, <?echo $uv_line_day[6];?>,
                <?echo $uv_line_day[5];?>, <?echo $uv_line_day[4];?>, <?echo $uv_line_day[3];?>,
                <?echo $uv_line_day[2];?>, <?echo $uv_line_day[1];?>, <?echo $uv_line_day[0];?>]
          },{
              label: 'PV',
              backgroundColor: 'rgba(255,102,51,0.2)',
              borderColor: 'rgb(255,102,51)',
              data: [<?echo $pv_line_day[11];?>, <?echo $pv_line_day[10];?>, <?echo $pv_line_day[9];?>,
                <?echo $pv_line_day[8];?>, <?echo $pv_line_day[7];?>, <?echo $pv_line_day[6];?>,
                <?echo $pv_line_day[5];?>, <?echo $pv_line_day[4];?>, <?echo $pv_line_day[3];?>,
                <?echo $pv_line_day[2];?>, <?echo $pv_line_day[1];?>, <?echo $pv_line_day[0];?>]
          }]
      },

      // Configuration options go here
      options: {}
    });
  }
  /*-------------- line-chart 일 END ------------*/

  /*-------------- line-chart 주 START ------------*/
  if ($('#line-2').length) {
    var ctx = document.getElementById('line-2').getContext('2d');
    var chart = new Chart(ctx, {
      // The type of chart we want to create
      type: 'line',
      // The data for our dataset
      data: {
          labels: ['<?echo date("Y-m-d", strtotime("".$date_from." -77 days"));?>','<?echo date("Y-m-d", strtotime("".$date_from." -70 days"));?>','<?echo date("Y-m-d", strtotime("".$date_from." -63 days"));?>',
          '<?echo date("Y-m-d", strtotime("".$date_from." -56 days"));?>','<?echo date("Y-m-d", strtotime("".$date_from." -49 days"));?>','<?echo date("Y-m-d", strtotime("".$date_from." -42 days"));?>',
          '<?echo date("Y-m-d", strtotime("".$date_from." -35 days"));?>','<?echo date("Y-m-d", strtotime("".$date_from." -28 days"));?>','<?echo date("Y-m-d", strtotime("".$date_from." -21 days"));?>',
          '<?echo date("Y-m-d", strtotime("".$date_from." -14 days"));?>','<?echo date("Y-m-d", strtotime("".$date_from." -7 days"));?>','<?echo date("Y-m-d", strtotime("".$date_from." -0 days"));?>'],
          datasets: [{
              label: 'UV',
              backgroundColor: 'rgba(0,0,204,0.2)',
              borderColor: 'rgb(0,0,204)',
              data: [<?echo $uv_line_week[11];?>, <?echo $uv_line_week[10];?>, <?echo $uv_line_week[9];?>,
                <?echo $uv_line_week[8];?>, <?echo $uv_line_week[7];?>, <?echo $uv_line_week[6];?>,
                <?echo $uv_line_week[5];?>, <?echo $uv_line_week[4];?>, <?echo $uv_line_week[3];?>,
                <?echo $uv_line_week[2];?>, <?echo $uv_line_week[1];?>, <?echo $uv_line_week[0];?>]
          },{
              label: 'PV',
              backgroundColor: 'rgba(255,102,51,0.2)',
              borderColor: 'rgb(255,102,51)',
              data: [<?echo $pv_line_week[11];?>, <?echo $pv_line_week[10];?>, <?echo $pv_line_week[9];?>,
                <?echo $pv_line_week[8];?>, <?echo $pv_line_week[7];?>, <?echo $pv_line_week[6];?>,
                <?echo $pv_line_week[5];?>, <?echo $pv_line_week[4];?>, <?echo $pv_line_week[3];?>,
                <?echo $pv_line_week[2];?>, <?echo $pv_line_week[1];?>, <?echo $pv_line_week[0];?>]
          }]
      },

      // Configuration options go here
      options: {}
    });
  }
  /*-------------- line-chart 주 END ------------*/

  /*-------------- line-chart 월 START ------------*/
  if ($('#line-3').length) {
    var ctx = document.getElementById('line-3').getContext('2d');
    var chart = new Chart(ctx, {
      // The type of chart we want to create
      type: 'line',
      // The data for our dataset
      data: {
          labels: ['<?echo date("Y-m", strtotime("-330 days"));?>','<?echo date("Y-m", strtotime("-300 days"));?>','<?echo date("Y-m", strtotime("-270 days"));?>',
          '<?echo date("Y-m", strtotime("-240 days"));?>','<?echo date("Y-m", strtotime("-210 days"));?>','<?echo date("Y-m", strtotime("-180 days"));?>',
          '<?echo date("Y-m", strtotime("-150 days"));?>','<?echo date("Y-m", strtotime("-120 days"));?>','<?echo date("Y-m", strtotime("-90 days"));?>',
          '<?echo date("Y-m", strtotime("-60 days"));?>','<?echo date("Y-m", strtotime("-30 days"));?>','<?echo date("Y-m");?>'],
          datasets: [{
              label: 'UV',
              backgroundColor: 'rgba(0,0,204,0.2)',
              borderColor: 'rgb(0,0,204)',
              data: [<?echo $uv_line_month[11];?>, <?echo $uv_line_month[10];?>, <?echo $uv_line_month[9];?>,
                <?echo $uv_line_month[8];?>, <?echo $uv_line_month[7];?>, <?echo $uv_line_month[6];?>,
                <?echo $uv_line_month[5];?>, <?echo $uv_line_month[4];?>, <?echo $uv_line_month[3];?>,
                <?echo $uv_line_month[2];?>, <?echo $uv_line_month[1];?>, <?echo $uv_line_month[0];?>]
          },{
              label: 'PV',
              backgroundColor: 'rgba(255,102,51,0.2)',
              borderColor: 'rgb(255,102,51)',
              data: [<?echo $pv_line_month[11];?>, <?echo $pv_line_month[10];?>, <?echo $pv_line_month[9];?>,
                <?echo $pv_line_month[8];?>, <?echo $pv_line_month[7];?>, <?echo $pv_line_month[6];?>,
                <?echo $pv_line_month[5];?>, <?echo $pv_line_month[4];?>, <?echo $pv_line_month[3];?>,
                <?echo $pv_line_month[2];?>, <?echo $pv_line_month[1];?>, <?echo $pv_line_month[0];?>]
          }]
      },

      // Configuration options go here
      options: {}
    });
  }
  /*-------------- line-chart 월 END ------------*/
  </script>
</body>

</html>
