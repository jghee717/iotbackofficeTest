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
    <script src="../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="../assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="../assets/libs/js/main-js.js"></script>
    <link rel="stylesheet" href="../assets/vendor/multi-select/css/multi-select.css">
    <script src="../assets/vendor/multi-select/js/jquery.multi-select.js"></script>
    <script type="text/javascript">

    // 분류- 카테고리선택에 따른 메뉴목록 보여주기
    $(function () {
      $('select[name=category]').change(function(){
        var category = $('select[name=category] option:selected').text();
        $.ajax({
          type : 'POST',
          dataType : 'html',
          url : '../sessions/load_menu.php',
          data : {category:category},
          success: function(data){
            $("select[name=menu] option").remove();
            $("select[name=menu]").append(data);
          }
        });
      })
    })

    // 컨텐츠 종류 선택에 따른 해당 div 보여주기
   $(function () {
     function video(){
       $.ajax({
         type : 'POST',
         dataType : 'html',
         url : '../sessions/load_video_form.php',
         success: function(data){
           $("#content").empty();
           $("#content").append(data);
         }
       });
     }

     function image(){
       $.ajax({
         type : 'POST',
         dataType : 'html',
         url : '../sessions/load_image_form.php',
         success: function(data){
           $("#content").empty();
           $("#content").append(data);
         }
       });
     }

     var source = $('input[name=content_source]:checked').val();
     if(source==1){
       video();
     }else{
       image();
     }

     $('input[name=content_source]').click(function(){
       var source = $('input[name=content_source]:checked').val();
       if(source==1){
         video();
       }else{
         image();
       }
     })

     $('select[name=category]').change(function(){
       if($('select[name=category] option:selected').html() == "IoT"){
         $('#note').hide();
         $("#title").hide();
         $("#youtube").hide();
         $('input[name=content_source]:eq(1)').prop("checked",true);
         image();
       }else{
         $('#note').show();
         $("#title").show();
         $("#youtube").show();
         $('input[name=content_source]:eq(0)').prop("checked",true);
         video()
       }
     });

     $('button[type=submit]').click(function(){
       if($('select[name=category] option:selected').html() == "IoT"){
         if(!$("select[class=send_image] option:selected").val()){
           alert("이미지를 선택해주세요.");
           return false;
         }
       }else{
         if($("input[name=title]").val()==""){
           $("input[name=title]").focus();
           alert("제목을 입력해주세요.");
           return false;
         }
         if($("textarea[name=note]").val()==""){
           $("textarea[name=note]").focus();
           alert("내용을 입력해주세요.");
           return false;
         }
         if($('input[name=content_source]:checked').val()==1){
           if($("input[name=name]").val()=="" || $("input[name=idx]").val()==""){
             alert("유튜브 영상을 선택해주세요.");
             return false;
           }
         }else if($('input[name=content_source]:checked').val()==2){
           if(!$("select[class=send_image] option:selected").val()){
             alert("이미지를 선택해주세요.");
             return false;
           }
         }
       }
     })
   });

// 엔터 입력폼 막기
    $(function () {
      $('input[type="text"]').keydown(function() {
        if (event.keyCode === 13) {
            event.preventDefault();
        }
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
                  <h5 class="card-header">게시글 작성</h5>
                  <div class="card-body">
                      <form id="validationform" action="../sessions/board_write_ok.php" method="post" data-parsley-validate="" novalidate="">
                        <?php
                        include "../sessions/db.php";
                        $db = new DBC;
                        $db->DBI();
                        $sql= "SELECT distinct category FROM menu_list where state <> 4 ";
                        $db->DBQ($sql);
                        $db->DBE();
                        ?>
                          <div class="form-group row">
                              <label class="col-12 col-sm-3 col-form-label text-sm-right">분류</label>
                              <div class="col-12 col-sm-8 col-lg-6">
                                <div class="custom-control-inline">
                                  <label style="margin:0; padding-top:7px; margin-right:1vw">카테고리</label>
                                  <select class="form-control form-control-sm" name="category" style="margin-right:2vw;width:120px">
                                    <?
                                    while($cate=$db->DBF()){?>
                                    <option value="<?=$cate['category']?>"><?=$cate['category']?></option>
                                  <? }?>
                                  </select>
                                </div>
                                <div class="custom-control-inline">
                                  <label style="margin:0; padding-top:7px; margin-right:1vw">메뉴명</label>
                                  <select class="form-control form-control-sm" name="menu" style="width:200px">
                                    <?
                                    $sql2="SELECT menu_num, menu FROM menu_list where category='IPTV'" and state <> 4 ;
                                    $db->DBQ($sql2);
                                    $db->DBE();
                                    while($menu=$db->DBF()){?>
                                    <option value="<?=$menu['menu_num']?>"><?=$menu['menu']?></option>
                                  <? }?>
                                  </select>
                                </div>
                              </div>
                          </div>
                          <div class="form-group row"  id="title">
                              <label class="col-12 col-sm-3 col-form-label text-sm-right">제목</label>
                              <div class="col-12 col-sm-8 col-lg-6">
                                  <input type="text" name="title" required="" placeholder="제목" maxlength="15"class="form-control">
                              </div>
                          </div>
                          <div class="form-group row" id="note">
                              <label class="col-12 col-sm-3 col-form-label text-sm-right">내용</label>
                              <div class="col-12 col-sm-8 col-lg-6">
                                  <textarea required="" name="note" class="form-control"></textarea>
                              </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-12 col-sm-3 col-form-label text-sm-right">컨텐츠</label>
                            <div class="col-12 col-sm-8 col-lg-6">
                              <label class="custom-control custom-radio custom-control-inline" id="youtube">
                                  <input type="radio" value="1" name="content_source" checked="" class="custom-control-input"><span class="custom-control-label">유튜브</span>
                              </label>
                              <label class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" value="2" name="content_source" class="custom-control-input"><span class="custom-control-label">이미지</span>
                              </label>
                            </div>
                          </div>
                          <div id="content" >
                          </div>
                          <div class="form-group row text-right">
                              <div class="col col-sm-10 col-lg-9 offset-sm-1 offset-lg-0">
                                  <button type="submit" class="btn btn-space btn-primary">글쓰기</button>
                                  <a href="board_list.php?mc=게시글관리&sc=게시글%20목록"><button type="button" class="btn btn-space btn-secondary">목록 보기</button></a>
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
</body>

</html>
