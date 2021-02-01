<!doctype html>

<?php
include "../sessions/access_all.php";
?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>게시글 등록</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
  <link href="../assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/libs/css/style.css">
  <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
  <script src="../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
  <script src="../assets/vendor/slimscroll/jquery.slimscroll.js"></script>
  <script src="../assets/libs/js/main-js.js"></script>
  <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
  <script type="text/javascript">

  $(function () {
    $('#return').click(function() {
      history.back();
    })
  });

  // bxslider -> 이미지 슬라이드
  $(document).ready(function(){
    var main = $('.bxslider').bxSlider({
      auto: false,	//자동으로 슬라이드
      controls : true,	//좌우 화살표
      autoControls: false,	//stop,play
      pager:true,	//페이징
      infiniteLoop:false,
      hideControlOnEnd:true,
      adaptiveHeight: true
    })
  });

  </script>
</head>

<body>
  <?php
  $sc=$_GET['sc'];
  $page=$_GET['page'];
  ?>
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
    <?php
    include "../layout/sidebar.php";
    ?>
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- end left sidebar -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- wrapper  -->
    <!-- ============================================================== -->
    <div class="dashboard-wrapper">
      <div class="container-fluid  dashboard-content">
        <!-- ============================================================== -->
        <!-- pageheader -->
        <!-- ============================================================== -->
        <?php
        include "../layout/pageheader.php";
        ?>
        <div class="row">
          <!-- ============================================================== -->
          <!-- valifation types -->
          <!-- ============================================================== -->
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
              <h5 class="card-header">게시글 상세</h5>
              <div class="card-body">
                <form id="validationform" action="./board_modify.php" method="GET" data-parsley-validate="" novalidate="">
                  <?php
                  $mc=$_GET['mc'];
                  $post_idx=$_GET['idx'];
                  include "../sessions/db.php";

                  $db = new DBC;
                  $db->DBI();
                  $sql= "SELECT *,category,menu FROM board_list Natural join menu_list WHERE idx='$post_idx'";
                  $db->DBQ($sql);
                  $db->DBE();
                  $cate=$db->DBF();
                  $note = urldecode($cate['note']);
                  $note = str_replace("\n","<br>",$note);?>
                  <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right">분류</label>
                    <div class="col-12 col-sm-8 col-lg-6">
                      <div class="custom-control-inline">
                        <label style="margin:0; padding-top:7px; margin-right:1vw">카테고리</label>
                        <div class="form-control form-control-sm" style="margin-right:2vw;width:120px"><?=$cate['category']?></div>
                      </div>
                      <div class="custom-control-inline">
                        <label style="margin:0; padding-top:7px; margin-right:1vw">메뉴명</label>
                        <div class="form-control form-control-sm" style="width:200px"><?=$cate['menu']?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?
                  if($cate['category'] != "IoT"){?>
                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right">제목</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <div class="form-control"><?=urldecode($cate['title'])?></div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-12 col-sm-3 col-form-label text-sm-right">내용</label>
                      <div class="col-12 col-sm-8 col-lg-6">
                        <div class="form-control"><?=$note?></div>
                      </div>
                    </div>
                  <? }?>
                  <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right">컨텐츠</label>
                    <div class="col-12 col-sm-8 col-lg-6">
                      <div class="custom-control-inline">
                        <div class="form-control form-control-sm" style="width:200px"><? if($cate['con_source']==1){echo"유튜브";}else{echo"이미지";}?></div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-12 col-sm-3 col-form-label text-sm-right"></label>
                    <div class="col-12 col-sm-8 col-lg-6"><?php
                    if ($cate['con_source']==1) {
                      $c_db = new DBC;
                      $c_db->DBI();
                      $c_sql= "SELECT con_id FROM contents WHERE idx={$cate['content1']} and res_type <> 4";
                      $c_db->DBQ($c_sql);
                      $c_db->DBE();
                      $con=$c_db->DBF();?>
                      <a class="img-item">
                        <iframe width="80%" height="300px" src="https://www.youtube.com/embed/<?=$con['con_id']?>?version=3&vq=hd1080" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <span><?=$list['con_id']?></span>
                      </a><?
                    }else if($cate['con_source'] ==2){?>
                      <div id="image">
                        <div class="bxslider">
                          <?
                          for ($i=1; $i <= $cate['content_count']; $i++) {
                            $c_num=$cate['content'.$i];
                            $c_db = new DBC;
                            $c_query= "SELECT * FROM contents where idx=$c_num and res_type <>4";
                            $c_db->DBI();
                            $c_db->DBQ($c_query);
                            $c_db->DBE();
                            $c_name= $c_db->DBF();
                            ?>
                            <div><img style="margin:auto;" src="../../io/images/<?=$c_name['con_id']?>"/></div>
                            <?$c_db->DBO();
                          }?>
                        </div>
                      </div><?
                    }?>
                    </div>
                  </div>
                  <input style="display:none" name="mc" value="<?=$mc?>">
                  <input style="display:none" name="sc" value="게시글수정">
                  <input style="display:none" name="idx" value="<?=$post_idx?>">
                  <div class="form-group row text-right">
                    <div class="col col-sm-10 col-lg-9 offset-sm-1 offset-lg-0">
                      <button type="submit" class="btn btn-space btn-primary">수정하기</button>
                      <button type="button" id="return"class="btn btn-space btn-secondary">목록 보기</button></a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- ============================================================== -->
          <!-- end valifation types -->
          <!-- ============================================================== -->
        </div>
      </div>
      <!-- ============================================================== -->
      <!-- footer -->
      <!-- ============================================================== -->
      <?php
      include "../layout/footer.php";
      ?>
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script>
    $('#form').parsley();
    </script>
  </body>

  </html>
