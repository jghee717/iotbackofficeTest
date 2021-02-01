<?php
include 'layout/layout.php';

$layout = new Layout;
?>

<title>행운의 777 이벤트</title>
<?$layout->CssJsFile('');?>
<?$layout->head($head);?>
<link rel="stylesheet" href="assets/css/pages/event_winner.css">
<link rel="stylesheet" href="assets/lib/HoldOn/HoldOn.min.css">

<script type="text/javascript">
  let today = "<?= date("Y/m/d"); ?>"
</script>

<body>
  <div id="preloader">
    <div class="loader"></div>
  </div>
<!-- preloader area end -->

<div class="horizontal-main-wrapper">
  <div class="main-content-inner">
    <div class="container">
      <div class="row mt-2">
        <div class="card col-lg-12">
          <div class="card-body">
            <a href="event_winner.php"><h1><strong class="badge badge-pill">행운의 777 이벤트 안내</strong></h1></a>
            <div class="row">
              <div class="col-lg-12">
                <img class="img-fluid mr-4 mt-2" src="assets/images/event/190603_6_event.png" width="100%">
              </div>
            </div>
            <br>
            <!-- <p><strong>당첨자 명단</strong>　 ※일 당첨자를 취합하여 주 1회 입력하신 CTN으로 기프티콘이 발송됩니다.</p><br> -->
            <!-- 날자 버튼-->
            <!-- <div class="col-lg-12 row">
              <div class="badge badge-pill center-btn-group">
                <div class="input-group">
                  <div>
                      <input type="submit" id="weekPrev" class="btn btn-rounded btn-light btn-xs date-move-btn" value="<<">
                  </div>
                  <div>
                      <input type="submit" id="dayPrev" class="btn btn-rounded btn-light btn-xs date-move-btn" value="<">
                  </div>
                  <div class="mt-2 t-today-text">
                    <h6>
                      <a class="t-today">
                      </a>
                    </h6>
                  </div>
                  <div>
                      <input type="button" id="dayNext" class="btn btn-rounded btn-light btn-xs date-move-btn" value=">">
                  </div>
                  <div>
                      <input type="button" id="weekNext" class="btn btn-rounded btn-light btn-xs date-move-btn" value=">>">
                  </div>
                </div>
              </div>
            </div> -->

            <!--테이블 정보-->
            <!-- <div class="col_lg_12">
              <section class="mt-5" id="no-more-tables">
                <table id="winnerTable" class="table table-bordered text-center">
                  <thead class="text-uppercase">
                    <tr style="text-align:center; background-color: #8F93C9; color: #FFFFFF;">
                      <th class="numeric">NO.</th>
                      <th class="numeric">당첨일시</th>
                      <th class="numeric">매장코드</th>
                      <th class="numeric">직영점명</th>
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </section>
            </div> -->

            <!-- /col-lg-12 mt-5 -->
            <br><p><strong>배틀 우승팀 명단</strong>　 ※6월 1~4주차 담당별 설치율, 실행율의 총점을 취합하여 각 담당자에게 치킨 20마리 기프티콘이 발송됩니다.</p><br>

            <div class="col-lg-12 row">
              <div class="badge badge-pill week_wrapper" style="background-color: #8F93C9; margin: auto; text-align:center;">
                <div class="input-group">
                  <div id="prevWrapper">
                      <input type="submit" id="weekPrevBattle" class="btn btn-rounded btn-light btn-xs date-move-btn" value="<">
                  </div>
                  <div class="mt-2"  style="color: #FFFFFF; letter-spacing :3px;">
                    <h6><a id="weekData">1주차</a></h6>
                  </div>
                  <div id="nextWrapper">
                      <input type="button" id="weekNextBattle" class="btn btn-rounded btn-light btn-xs date-move-btn" value=">">
                  </div>
                </div>
              </div>
            </div>

            <!-- 테이블 -->
            <div class="col_lg_12">
              <section class="mt-5" id="battleGroupTable">
                <table class="table table-bordered text-center" id="table">
                  <thead class="text-uppercase">
                    <tr style="text-align:center; background-color: #8F93C9; color: #FFFFFF;">
                      <th class="numeric" rowspan="2">배틀그룹</th>
                      <th class="numeric" rowspan="2">담당명</th>
                      <th class="numeric" colspan="5" rowspan="1">획득점수</th>
                      <th class="numeric" rowspan="2">배틀결과</th>
                    <tr style="text-align:center; background-color: #8F93C9; color: #FFFFFF;">
                      <th class="numeric">설치율</th>
                      <!-- <th class="numeric">환산점수</br>(50점)</th> -->
                      <th class="numeric">실행율</th>
                      <th class="numeric">환산점수</br>(30점)</th>
                      <th class="numeric">매장당 평균 사용횟수</th>
                      <th class="numeric">환산점수</br>(70점)</th>
                      <!-- <th class="numeric">총합</th> -->
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </section>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?$layout->JsFile('');?>
<?$layout->js($js);?>
<script src="assets/js/pages/event_winner.js" charset="utf-8"></script>
<script src="assets/lib/HoldOn/HoldOn.min.js" charset="utf-8"></script>

</body>
</html>
