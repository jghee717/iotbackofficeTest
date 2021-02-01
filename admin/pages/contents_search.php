<!doctype html>
<?
include "../sessions/access_all_manager.php";
?>
<html lang="ko">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Concept - Bootstrap 4 Admin Dashboard Template</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
  <link href="../assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
  <link rel="stylesheet" href="../assets/libs/css/style.css">
</head>

<style media="screen">
.card-body ul{
  list-style-type : none;
  margin: auto;
  padding: inherit;
}
.img {
  width: 100%;
}

.img-list {
  width: 300px;
  padding: 20px;
}

.img-item {
  border-radius: 3px;
  line-height: 32px;
  text-align: center;
  padding: 12px 7px 4px;
  display: block;
  border: 1px solid transparent;
  color: #3d405c;
  font-size: 12px;
}

.img-item img {
  width: 100%;
}

.img-item:hover {
  background-color: #fff;
  border: 1px solid #e6e6f2;
}

.img-item span {
  display: block;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
</style>
<body>

  <?php
  include "../sessions/db.php";
  $sc=$_GET['sc']; // 소분류
  $page=$_GET['page']; //페이지 번호
  $max_row=$_GET['row']; //표시할 열 총개수
  $stardby=$_GET['by']; //표시할 열 총개수
  $searchby=$_GET['search']; //표시할 열 총개수
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
    <!-- ============================================================== -->
    <?php
    include "../layout/sidebar.php";
    ?>
    <!-- ============================================================== -->
    <!-- end left sidebar -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- wrapper  -->
    <!-- ============================================================== -->
    <div class="dashboard-wrapper">
      <div class="container-fluid dashboard-content ">
        <!-- ============================================================== -->
        <!-- pageheader  -->
        <!-- ============================================================== -->
        <?php
        include "../layout/pageheader.php";
        ?>
        <h4>"<?=$stardby?>" 가 "<?=$searchby?>" 를 포함하는 계정 검색 결과-----<a href="contents.php?mc=리소스관리&sc=<?=$sc?>&page=1&row=5">돌아가기</a></h4>
        <!-- ============================================================== -->
        <!-- end pageheader  -->
        <!-- ============================================================== -->
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <!-- ============================================================== -->
            <!-- headings  -->
            <!-- ============================================================== -->
            <div class="card" id="headings">
              <div class="card-header">
                <form style="float:right;display: flex"  enctype="multipart/form-data" method="GET" action="contents_search.php">
                  <input name="mc" type="text" style="display:none" value="<?=$_GET['mc']?>">
                  <input name="sc" type="text" style="display:none" value="<?=$_GET['sc']?>">
                  <select name="by">
                    <option value="id">제목/ID</option>
                  </select>
                  <input class="form-control" name="search" type="text" placeholder="Search.." required>
                  <input type="submit" class="btn btn-primary" value="검색" >
                </form>
                </div>
              <div class="card-body">
                <ul class="img_ul">
                  <li class="">
                    <div class="row">
                      <?
                      $db = new DBC;
                      if ($sc=="영상관리") {
                        $query= "SELECT idx,con_id,(SELECT id FROM member WHERE member.idx=C.user) as id,upload_date FROM contents C WHERE res_type=1 AND con_$stardby like '%$searchby%' ORDER BY idx desc LIMIT 0, 12";
                      }else {
                        $query= "SELECT idx,con_id,(SELECT id FROM member WHERE member.idx=C.user) as id,upload_date FROM contents C WHERE res_type=2 AND con_$stardby like '%$searchby%' ORDER BY idx desc LIMIT 0, 12";
                      }
                      $db->DBI();
                      $db->DBQ($query);
                      $db->DBE();
                      while ($list=$db->DBF()) {?>
                        <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 "><?
                        if ($sc=="영상관리") {?>
                          <a href="" class="img-item" data-toggle="modal" data-target="#<?=$list['idx']?>Modal"><img src="https://img.youtube.com/vi/<?=$list['con_id']?>/0.jpg"  class="img-thumbnail mr-0" alt="Responsive image"> <span><?=$list['con_id']?></span></a>
                          <!-- Modal -->
                          <div class="modal fade" id="<?=$list['idx']?>Modal" tabindex="-1" role="dialog" aria-labelledby="<?=$list['idx']?>ModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <form class="modal-content" enctype="multipart/form-data" method="POST" action="../sessions/change_name.php">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="<?=$list['idx']?>ModalLabel">상세정보</h5>
                                  <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </a>
                                </div>
                                <div class="modal-body">
                                  <iframe src="https://www.youtube.com/embed/<?=$list['con_id']?>?version=3&vq=hd1080" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                                  <input name="idx" value="<?=$list['idx']?>" style="display:none">
                                  <div>
                                    <span id="<?=$list['idx']?>title">영상 ID : </span>>
                                    <input name="orgin_name" value="<?=$file_ext?>" style="display:none">
                                    <input id="name_<?=$list['idx']?>" name="name" type="text" value="<?=$list['con_id']?>" disabled>
                                    <input name="extension" value="<?= $type[count($type)-1]?>" style="display:none">
                                    <a href="#"  id="chg_<?=$list['idx']?>" onclick="edit_name(<?=$list['idx']?>)" style="color:red">수정하기</a>
                                  </div>


                                  <div>올린사람 : <?=$list['id']?></div>
                                  <div>업로드 날짜 : <?=$list['upload_date']?></div>
                                </div>
                                <div id="con_<?=$list['idx']?>" class="modal-footer" style="display:none">
                                  <button type="submit" class="btn btn-primary">변경 저장</button>
                                  <a href="#" class="btn btn-secondary" data-dismiss="modal">저장하지 않고 닫기</a>
                                </div>
                              </form>
                            </div>
                          </div><?
                        }else {?>

                          <a href="" class="img-item" data-toggle="modal" data-target="#<?=$list['idx']?>Modal"><img src="../../io/images/<?=$list['con_id']?>"  class="img-thumbnail mr-0" alt="Responsive image"> <span><?=$list['con_id']?></span></a>
                          <!-- Modal -->
                          <div class="modal fade" id="<?=$list['idx']?>Modal" tabindex="-1" role="dialog" aria-labelledby="<?=$list['idx']?>ModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <form class="modal-content" enctype="multipart/form-data" method="POST" action="../sessions/change_name.php">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="<?=$list['idx']?>ModalLabel">상세정보</h5>
                                  <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </a>
                                </div>
                                <div class="modal-body">
                                  <img style="width:100%" src="../../io/images/<?=$list['con_id']?>" alt="" >
                                  <input name="idx" value="<?=$list['idx']?>" style="display:none">
                                  <div>이미지이름:
                                    <?
                                    $file_ext = substr( strrchr($list['con_id'],"."),1);
                                    $type = explode(".", $list['con_id']);
                                    $file_ext = $type[count($type)-2];

                                    ?>
                                    <input name="orgin_name" value="<?=$file_ext?>" style="display:none">
                                    <input id="name_<?=$list['idx']?>" name="name" type="text" value="<?=$file_ext?>" disabled>.<?= $type[count($type)-1]?>
                                    <input name="extension" value="<?= $type[count($type)-1]?>" style="display:none">
                                    <a href="#"  id="chg_<?=$list['idx']?>" onclick="edit_name(<?=$list['idx']?>)" style="color:red">수정하기</a>
                                  </div>
                                  <div>올린사람: <?=$list['id']?></div>
                                  <div>업로드 날짜: <?=$list['upload_date']?></div>
                                </div>
                                <div id="con_<?=$list['idx']?>" class="modal-footer" style="display:none">
                                  <button type="submit" class="btn btn-primary">변경 저장</button>
                                  <a href="#" class="btn btn-secondary" data-dismiss="modal">저장하지 않고 닫기</a>
                                </div>
                              </form>
                            </div>
                          </div><?
                        }
                        ?>

                      </div><?
                    }?>
                  </div>
                </li>
              </ul>

            </div>
          </div>
          <!-- ============================================================== -->
          <!-- end headings  -->
          <!-- ============================================================== -->
        </div>
      </div>
      <!-- ============================================================== -->
      <!-- footer -->
      <!-- ============================================================== -->
      <?php
      include "../layout/footer.php";
      ?>
      <!-- ============================================================== -->
      <!-- end footer -->
      <!-- ============================================================== -->
    </div>
  </div>
</div>
<!-- ============================================================== -->
<!-- end main wrapper -->
<!-- ============================================================== -->
<!-- Optional JavaScript -->
<script>
function edit_name(idx){
  if ($("#name_"+idx).attr("disabled")=='disabled') {
    //alert($("#name_"+idx).removeattr("disabled"));
    $("#name_"+idx).removeAttr("disabled");
    $("#name_"+idx).val("");
    $("#chg_"+idx).attr("style","display:none");
    $("#con_"+idx).attr("style","");
    $("#"+idx+"title").html("영상URL : ");
  }
}

</script>

<script type="text/javascript">
var sel_files = [];

function fileUploadAction() {
  console.log("fileUploadAction");
  $("#input_imgs").trigger('click');
}


function imgimg(e){
  //alert($(this).val());
  // 이미지 정보들을 초기화
  sel_files = [];
  $(".imgs_wrap").empty();

  var files = e.target.files;
  var filesArr = Array.prototype.slice.call(files);

  var index = 0;
  filesArr.forEach(function(f) {
    if(!f.type.match("image.*")) {
      alert("확장자는 이미지 확장자만 가능합니다.");
      return;
    }
    sel_files.push(f);

    var reader = new FileReader();
    reader.onload = function(e) {
      var html = "<a  href=\"javascript:void(0);\" onclick=\"deleteImageAction("+index+")\" id=\"img_id_"+index+"\"><img style=\"width:40%\" src=\"" + e.target.result + "\" data-file='"+f.name+"' class='selProductFile' title='Click to remove'></a>";
      $(".imgs_wrap").append(html);
      index++;
    }
    reader.readAsDataURL(f);
  });
}


</script>
<script type="text/javascript">

function deleteImageAction(index) {
  console.log("index : "+index);
  sel_files.splice(index, 1);

  var img_id = "#img_id_"+index;
  $(img_id).remove();

  console.log(sel_files);
}
</script>

<script src="../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
<script src="../assets/vendor/slimscroll/jquery.slimscroll.js"></script>
<script src="../assets/libs/js/main-js.js"></script>
</body>

</html>
