<!doctype html>
<?php
include "../sessions/db.php";
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
  <script src="../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
  <script src="../assets/vendor/slimscroll/jquery.slimscroll.js"></script>
  <script src="../assets/libs/js/main-js.js"></script>
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
                <?php
                $sc=$_GET['sc']; // 소분류
                $page=$_GET['page']; //페이지 번호
                $max_row=12; //표시할 열 총개수
                if ($sc!="영상관리") {?>
                  <button class="btn btn-primary" data-toggle="modal" data-target="#UPModal">새로 업로드</button>

                  <!--모달 -->
                  <div class="modal fade" id="UPModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">로컬에서 이미지 추가하기</h5>
                          <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </a>
                        </div>
                        <div class="modal-body">
                          <input multiple="multiple" type="file" id="input_img" name="filename[]" accept=".gif, .jpg, .png, .jpeg, .bmp">
                          <div class="preview">

                          </div>
                        </div>
                        <div class="modal-footer">
                          <input type="button" class="btn btn-primary" data-dismiss="modal" value="추가하기" onclick="submitAction();">
                          <input type="button" class="btn btn-secondary" data-dismiss="modal" value="닫기">
                        </div>
                      </div>
                    </div>
                  </div><?
                }else {?>

                  <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal1">새로 업로드</button>
                  <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">새로운 영상 추가하기</h5>
                          <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </a>
                        </div>
                        <div class="modal-body">
                          <div class="form-group row" >
                            <label class="col-12 col-sm-3 col-form-label text-sm-right" id="video_name">유튜브 Source</label>
                            <div class="col-12 col-sm-8 col-lg-6" >
                              <input type="text" name=url placeholder="유튜브 영상 URL or 영상 ID값을 입력하세요." class="form-control">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-12 col-sm-3 col-form-label text-sm-right">유튜브 영상 확인</label>
                            <div class="col-12 col-sm-8 col-lg-6" id="video"><!-- 영상 출력 -->
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <input type="button" class="btn btn-primary" data-dismiss="modal" value="추가하기" onclick="visubmitAction();">
                          <input type="button" class="btn btn-secondary" data-dismiss="modal" value="닫기">
                        </div>
                      </div>
                    </div>
                  </div><?
                }
                ?>
                <form style="float:right;display: flex"  enctype="multipart/form-data" method="GET" action="contents_search.php">
                  <input name="mc" type="text" style="display:none" value="<?=$_GET['mc']?>">
                  <input name="sc" type="text" style="display:none" value="<?=$_GET['sc']?>">
                  <input name="page" type="text" style="display:none" value="<?=$_GET['page']?>">
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
                      $row_start=$page*$max_row-$max_row; //DB로 부터 시작할 번호
                      $count = $row_start+1;
                      $db = new DBC;
                      if ($sc=="영상관리") {
                        $query= "SELECT idx,con_id,(SELECT id FROM member WHERE member.idx=C.user) as id,upload_date FROM contents C WHERE res_type=1 ORDER BY idx desc limit $row_start,12";
                      }else {
                        $query= "SELECT idx,con_id,(SELECT id FROM member WHERE member.idx=C.user) as id,upload_date FROM contents C WHERE res_type=2 ORDER BY idx desc limit $row_start,12";
                      }
                      $db->DBI();
                      $db->DBQ($query);
                      $db->DBE();
                      while ($list=$db->DBF()) {?>
                        <div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12 "><?
                        $usage="";
                        if ($sc=="영상관리") { //영상일때 띄울목록
                          $usage_db=new DBC;
                          $usage_query="SELECT idx FROM board_list WHERE b_state <> 4 AND con_source=1 AND content1={$list['idx']}";
                          $usage_db->DBI();
                          $usage_db->DBQ($usage_query);
                          $usage_db->DBE();
                          while ($usage_list=$usage_db->DBF()) {
                            $usage=$usage.$usage_list['idx']."|";
                          }?>
                          <a href="" class="img-item" onclick="vimodalshow(<?=$list['idx']?>,'<?=$list['con_id']?>','<?=$list['id']?>','<?=$list['upload_date']?>','<?=$usage?>')" data-toggle="modal" data-target="#viModal">
                            <img src="https://img.youtube.com/vi/<?=$list['con_id']?>/0.jpg"  class="img-thumbnail mr-0" alt="Responsive image">
                            <span><?=$list['con_id']?></span>
                          </a><?

                        }else {//이미지일때 띄울목록
                          $usage_db=new DBC;
                          $usage_query="SELECT * FROM board_list WHERE b_state <> 4 AND con_source=2";
                          $usage_db->DBI();
                          $usage_db->DBQ($usage_query);
                          $usage_db->DBE();
                          while ($usage_list=$usage_db->DBF()) {
                            for ($i=1; $i <= $usage_list['content_count']; $i++) {
                              if ($usage_list['content'.$i]==$list['idx']) {

                                $usage=$usage.$usage_list['idx']."|";
                              }
                            }

                          }
                          ?>

                          <a href="" class="img-item" onclick="imgmodalshow(<?=$list['idx']?>,'<?=$list['con_id']?>','<?=$list['id']?>','<?=$list['upload_date']?>','<?=$usage?>')" data-toggle="modal" data-target="#imgModal">
                            <img src="../../io/images/<?=$list['con_id']?>"  class="img-thumbnail mr-0" alt="Responsive image">
                            <span><?=$list['con_id']?></span>
                          </a>
                          <!-- Modal -->
                          <?
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
      <nav aria-label="Page navigation example" style="">
        <ul class="pagination" style="justify-content: center">
          <?
          $db = new DBC;

          $a="";
          if($_GET['by'] != ""){
            if($_GET['by'] != 4){
              $a.=" and root =".$_GET['by'];
            }
          }
          if(isset($_GET['search'])){
            $a.=" and id like '%".$_GET['search']."%'";
          }

          if ($sc=="영상관리") {
            $query= "SELECT * FROM contents where res_type=1";
          }else {
            $query= "SELECT * FROM contents WHERE res_type=2";
          }
          $db->DBI();
          $db->DBQ($query);
          $db->DBE();
          $fin_num=ceil(ceil($db->resultRow()/$max_row)/5);
          $row_list_start=ceil($page/5);
          if ($page/5>1) {?>
            <li class="page-item"><a class="page-link" href="contents.php?mc=<?=$_GET['mc']?>&sc=<?=$_GET['sc']?>&page=<?=$row_list_start*5-9?>&search=<?=$_GET['search']?>" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a>
            </li><?
          }
          for ($i=$row_list_start*5-4; ($i<=$row_list_start*5)&&($i<=ceil($db->resultRow()/$max_row)); $i++) {
            if ($page==$i) {?>
              <li class="page-item active"><a class="page-link"><?=$i?></a></li><?
            }else {?>
              <li class="page-item"><a class="page-link" href="contents.php?mc=<?=$_GET['mc']?>&sc=<?=$_GET['sc']?>&page=<?=$i?>&search=<?=$_GET['search']?>"><?=$i?></a></li><?
            }
          }
          if ($row_list_start!=$fin_num) {?>
            <li class="page-item"><a class="page-link" href="contents.php?mc=<?=$_GET['mc']?>&sc=<?=$_GET['sc']?>&page=<?=$row_list_start*5+1?>&search=<?=$_GET['search']?>" aria-label="Next"><span aria-hidden="true">&raquo;</span>
              <span class="sr-only">Next</span></a></li><?
            }?>
          </ul>
        </nav>

    </div>
    <?php
    include "../layout/footer.php";
    ?>
    <!-- ============================================================== -->
    <!-- end footer -->
    <!-- ============================================================== -->
  </div>
</div>
<!-- 이미지 모달 -->
<div class="modal fade" id="imgModal" tabindex="-1" role="dialog" aria-labelledby="imgModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form class="modal-content" enctype="multipart/form-data" method="POST" action="../sessions/change_name.php">
      <div class="modal-header">
        <h5 class="modal-title" id="imgModalLabel">상세정보</h5>
        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </a>
      </div>
      <div class="modal-body">
        <img id="imim" style="width:100%" src="" alt="" >
        <input id="imttyp"name="type" value="2" style="display:none">
        <input id="iidx"name="idx" value="<?=$list['idx']?>" style="display:none">
        <input id="ifull" value="<?=$list['con_id']?>" style="display:none">
        <div>이미지이름:
          <?
          $file_ext = substr( strrchr($list['con_id'],"."),1);
          $type = explode(".", $list['con_id']);
          $file_ext = $type[count($type)-2];

          ?>
          <input id="iorgin"name="orgin_name" value="<?=$file_ext?>" style="display:none">
          <input id="iname" name="name" type="text" value="<?=$file_ext?>" disabled required><?= $type[count($type)-1]?>
          <input name="extension" value="<?= $type[count($type)-1]?>" style="display:none">
          <a href="#"  id="ichg_" onclick="imgedit_name()" style="color:red">수정하기</a>
        </div>
        <div id="iupuser"></div>
        <div id="iupload"></div>
      </div>
      <div  class="modal-footer" >
        <div id="iuseuse"></div>
        <button id="idelbutton" type="button" onclick="deleteimgcontent()" class="btn btn-primary">삭제</button>
        <div id="icon_" style="display:none">
          <button type="submit" class="btn btn-primary">변경 저장</button>
          <a href="#" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">저장하지 않고 닫기</a>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- 이미지 모달끝 -->
<!-- 비디오 모달 -->
<div class="modal fade" id="viModal" tabindex="-1" role="dialog" aria-labelledby="viModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form class="modal-content" enctype="multipart/form-data" method="POST" action="../sessions/change_name.php">
      <div class="modal-header">
        <h5 class="modal-title" id="viModalLabel">상세정보</h5>
        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </a>
      </div>
      <div class="modal-body">
        <iframe id="vidi" src="https://www.youtube.com/embed/<?=$list['con_id']?>?version=3&vq=hd1080" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

        <input id="full" value="" style="display:none">
        <input id="ttyp" value="1" style="display:none">
        <input id="idx" name="idx" value="" style="display:none">
        <div>
          <span id="title">영상 ID : </span>
          <input name="orgin_name" value="<?=$file_ext?>" style="display:none">
          <input id="name" name="name" type="text" value="<?=$list['con_id']?>" disabled required>
          <input name="extension" value="<?= $type[count($type)-1]?>" style="display:none">
          <a href="#"  id="chg_" onclick="viedit_name()" style="color:red">수정하기</a>
        </div>

        <div id="upuser"></div>
        <div id="update"></div>
      </div>
      <div  class="modal-footer" >
        <div id="viuseuse"></div>
        <button id="videlbutton" type="button" onclick="deletevicontent()" class="btn btn-primary">삭제</button>
        <div id="con_">
          <button type="submit" class="btn btn-primary">변경 저장</button>
          <a href="#" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">저장하지 않고 닫기</a>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- 비디오 모달끝 -->
<!-- ============================================================== -->
<!-- end main wrapper -->
<!-- ============================================================== -->
<!-- Optional JavaScript -->
<script type="text/javascript">
function vimodalshow(idx,con_id,id,update,usage){
  $("#videlbutton").attr("style","display:none");
  $("#viuseuse").html("");
  $("#name").attr("disabled","disabled");//이름창 입력막기
  $("#chg_").removeAttr("style");//수정하기 보이게
  $("#con_").attr("style","display:none");//저장버튼 안보이게
  if (usage=="") {
    $("#videlbutton").removeAttr("style");

  }else {
    $("#viuseuse").html("사용중인 영상은 삭제할수 없습니다.<br>게시글 번호: "+usage);
  }
  $("#vidi").attr("src","https://www.youtube.com/embed/"+con_id+"?version=3&vq=hd1080");//영상 띄우기
  $("#idx").val(idx);//영상IDX
  $("#upuser").html("올린 사람 : "+id);//유저ID
  $("#update").html("올린 날짜"+update);//올린날짜
  $("#title").val(con_id);//컨텐츠 이름값 지정
  $("#name").val(con_id);
  $("#full").val(con_id);
}

function imgmodalshow(idx,con_id,id,update,usage){
  $("#idelbutton").attr("style","display:none");
  $("#iuseuse").html("");
  $("#iname").attr("disabled","disabled");
  $("#ichg_").removeAttr("style");
  $("#icon_").attr("style","display:none");
  if (usage=="") {
    $("#idelbutton").removeAttr("style");

  }else {
    $("#iuseuse").html("사용중인 이미지는 삭제할수 없습니다.<br>게시글 번호: "+usage);
  }
  $("#imim").attr("src","../../io/images/"+con_id);
  $("#iidx").val(idx);
  $("#iupuser").html("올린 사람 : "+id);
  $("#iupload").html("올린 날짜"+update);
  $("#iorgin").val(con_id);
  $("#ititle").val(con_id);
  $("#iname").val(con_id);
  $("#ifull").val(con_id);
}

function deleteimgcontent(){
  $.post( "../sessions/delete_contents.php", {idx:$("#iidx").val(), name:$("#ifull").val(), con:$("#imttyp").val() })
  .done(function( data ) {

    alert(data);
    location.reload();
  });
}

function deletevicontent(){
  $.post( "../sessions/delete_contents.php", {idx:$("#idx").val(), name:$("#full").val(), con:$("#ttyp").val() })
  .done(function( data ) {

    alert(data);
    location.reload();
  });
}

function viedit_name(){
  if ($("#name").attr("disabled")=='disabled') {
    $("#name").removeAttr("disabled");
    $("#name").val("");
    $("#chg_").attr("style","display:none");
    $("#con_").attr("style","");
    $("#title").html("영상URL : ");
  }
}

function imgedit_name(){
  if ($("#iname").attr("disabled")=='disabled') {
    $("#iname").removeAttr("disabled");
    $("#iname").val("");
    $("#ichg_").attr("style","display:none");
    $("#icon_").attr("style","");
  }
}

$(function () {
  // 컨텐츠가 비디오 일시 입력된 영상아이디 추출에 대한 정규식


  function youtubeId(url) {
    var tag = "";
    if(url.length==11){
      tag = url;
      return tag;
    }else{
      if(url) {
        var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
        var matchs = url.match(regExp);
        if (matchs) {
          tag += matchs[7];
        }
        return tag;
      }
    }
  }
  // 컨텐츠가 비디오 일시 입력,커서이동된 영상아이디에 대한 영상을 보여줌
  var timer;
  $('input[name=url]').on("keyup",function(){
    if (timer) {
      clearTimeout(timer);
    }
    timer = setTimeout(function() {
      var id = youtubeId($('input[name=url]').val());
      $.ajax({
        type : 'POST',
        dataType : 'html',
        url : '../sessions/load_video.php',
        data : {video_id:id},
        success: function(data){
          // $('#video_name').html("영상 ID");
          $('input[name=url]').val(id);
          if($('input[name=url]').val()==""){
            // $('#video_name').html("영상 URL");
            $("#video").empty();
            $("#video").html("영상 URL or ID값을 <br> 확인해주세요");
            $("#video").css({"color":"red","font-size":"20px"});
          }else{
            $("#video").empty();
            $("#video").append(data);
          }
        }
      });
    }, 600);
  })
});

function visubmitAction() {
  var video = $('input[name=url]').val();
  if(video.length == "11"){
    $.ajax({
      type : 'POST',
      dataType : 'html',
      url : '../sessions/save_video.php',
      data : {video:video},
      success: function(data){
        alert(data);
        location.reload();
      }
    });
  }else{
    alert("영상 URL or ID값을 다시 확인해주세요")
  }
}





var sel_files = [];

function readURL(e) {
  sel_files=[];
  $(".preview").empty();

  var files = e.files;
  var filesArr = Array.prototype.slice.call(files);
  var index = 0;
  filesArr.forEach(function(f){
    if(!f.type.match("image.*")) {
      alert("이미지 확장자만 가능합니다.");
      $(e).val("");
      exit();
    }
    var extension = f.name.substring(f.name.lastIndexOf(".")+1);
    var ck = extension.toLowerCase();
    if(ck!="bmp" && ck!="jpeg" && ck!="jpg" && ck!="png" && ck!="gif"){
      alert("bmp, jpg, jpeg, png, gif 형식의 이미지만 가능합니다.");
      $(e).val("");
      exit();
    }

    var name_ck = f.name.substring(0,f.name.lastIndexOf("."));
    var big = extension.toUpperCase();
    $.ajax({
      type : 'POST',
      dataType : 'html',
      url : '../sessions/check_image.php',
      data : {name:name_ck, big:big, small:ck},
      success: function(data){
        if(data== "YES"){
          alert("같은 이름을 가진 파일이 이미 등록되어있습니다. \n수정후 다시 시도해주세요");
          $(e).val("");
          $(".preview").empty();
          exit();
        }
      }
    });

    sel_files.push(f);
    var reader = new FileReader();
    reader.onload = function(e){
      var html = "<a href=\"javascript:void(0);\" onclick=\"deleteImageAction("+index+")\" id=\"img_id_"+index+"\"><img style='padding:5px; max-width:40%' src=\"" + e.target.result + "\" data-file='"+f.name+"' class='selProductFile' title='Click to remove'></a>";

      // var img_html = "<img style='padding:5px; max-width:40%'src=\""+e.target.result+"\"/>";
      $('.preview').append(html);
      index++;
    }
    reader.readAsDataURL(f);
  });
}

function deleteImageAction(index) {
  sel_files.splice(index, 1);

  var img_id = "#img_id_"+index;
  $(img_id).remove();
  $("#input_img").val("");
}

function submitAction() {
  var data = new FormData();

  for(var i=0, len=sel_files.length; i<len; i++) {
    var name = "image_"+i;
    data.append(name, sel_files[i]);
  }
  data.append("image_count", sel_files.length);


  var xhr = new XMLHttpRequest();
  xhr.onload = function(e) {
    if(this.status == 200) {
      alert(e.currentTarget.responseText);
      location.reload();
    }
  }
  xhr.open("POST","../sessions/save_image.php");
  xhr.send(data);
}

$(function () {
  $("#input_img").change(function() {
    return readURL(this);
  });
});
</script>

</body>

</html>
