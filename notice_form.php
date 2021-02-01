<?php
include 'layout/layout.php';
include 'api/dbconn.php';

$conn = new DBC();
$conn->DBI();

$layout = new Layout;

if(isset($_GET['no'])){
  $no = $_GET['no'];
}

$sql = "select * from did_notice where idx = '".$no."'";
$conn->DBQ($sql);
$conn->DBE();
$row = $conn->DBF();

$sql = "select rate from did_member where pos_code = '".$_SESSION['id']."'";
$conn->DBQ($sql);
$conn->DBE();
$row2 = $conn->DBF();



 if($row2[0] == 'B') {
   ?>
   <script type="text/javascript">alert("접근 권한이 없습니다")
   window.history.back(-1); </script>
   <?
 } else {
?>
<!doctype html>
<html class="no-js" lang="kr">
<?$layout->CssJsFile('<link href="assets/css/table-responsive.css" rel="stylesheet">');?>
<?$layout->head($head);?>
<script>

  function setContent(num){
    switch (num) {
      case 0:
      document.getElementById('notice_content').innerHTML = '내용';
      document.getElementById('notice_textarea').removeAttribute('style');
      document.getElementById('content_text').setAttribute('required', 'true');

      document.getElementById('notice_file').setAttribute('style', 'display: none;');
      document.getElementById('content_image').removeAttribute('required');
      document.getElementById('content_image').value = "";
      break;

      case 1:
      document.getElementById('notice_content').innerHTML = '이미지 업로드';
      document.getElementById('notice_file').removeAttribute('style');
      document.getElementById('content_image').setAttribute('required', 'true');

      document.getElementById('notice_textarea').setAttribute('style', 'display: none;');
      document.getElementById('content_text').removeAttribute('required');
      document.getElementById('content_text').value = "";
      break;
    }
  }

  function setConfirm(obj){
    switch (obj) {
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

  //중복 파일
  function LoadMenu()
{
	<?
		//이미 저장된 파일이 있는지 확인한다
		$previe_menu_filename = "./upload_files/" . $project_code . "/" . $project_code . "_menu.html";
		$bIsMenuFileExist = file_exists($previe_menu_filename );
		if ( $bIsMenuFileExist ){ ?>
			vPreview_slide_filename = "./upload_files/<?echo $project_code;?>/<?echo $project_code;?>_menu.html";
			$.ajax({
				url : vPreview_slide_filename,
				dataType: 'html'
			}).done(function(data) {
				document.getElementById("menu_main").innerHTML = data;
			})
			<?}
		else{
			?>
			//alert("저장된 메뉴파일이 없습니다");
			<?}?>
}


</script>
<!--이미지 미리보기-->
<script src="http://madalla.kr/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $("#content_image").on('change', function(){
                readURL(this);
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                    $('#img').attr('src', e.target.result);
                }
              reader.readAsDataURL(input.files[0]);
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
            <div class="col-lg-6"><h5>공지사항 </h5></div>
            <div class="col-lg-6" style="text-align: right;"><small> Main > 공지사항 </small></div>
            <html><hr color="black" width=100%></html>

            <div class="col-lg-12 mt-2">
              <form action="api/noticeReg/delete.php" method="POST" id="delete_form">
                <input type="hidden" id="delete_no" name="delete_no" value="">
              </form>
              <form action="api/noticeReg/insert.php" method="POST" id="submit_form" enctype="multipart/form-data">
                <?if($no != null){?>
                <input type="hidden" name="idx" value="<?php echo $no; ?>">
                <input type="hidden" name="compare" value="수정">
                <?} else {?>
                <input type="hidden" name="compare" value="등록">
                <?}?>
                <div class="card">
                  <div class="card-body">
                    <table class="table table-bordered text-left">
                      <tr>
                        <th width="150px">노출여부</th>
                        <td>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="expose0" name="expose" class="custom-control-input" value="노출"
                            <?if($no != null){if($row['expose'] == '노출'){echo "checked";}}else{echo "checked";}?>>
                            <label class="custom-control-label" for="expose0">노출</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="expose1" name="expose" class="custom-control-input" value="미노출"
                            <?if($no != null){if($row['expose'] == '미노출'){echo "checked";}}?>>
                            <label class="custom-control-label" for="expose1">미노출</label>
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <th width="150px">노출기간</th>
                        <td>
                          <div class="row">
                            <input data-toggle="datepicker" type="text" readonly="" autocomplete="off" class="form-control form-control-sm col-lg-2 ml-3 mr-3" id="date_from" name="date_from"
                            value="<?if($no != null){echo $row['start_day'];}?>">
                            <p> ~ </p>
                            <input data-toggle="datepicker" type="text" readonly="" autocomplete="off" class="form-control form-control-sm col-lg-2 ml-3" id="date_to" name="date_to"
                            value="<?if($no != null){echo $row['end_day'];}?>">
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <th width="150px">링크</th>
                        <td>
                          <input type="text" placeholder="http:// 또는 https:// 를 포함한 페이지 주소를 입력해주세요."
                          class="form-control form-control-sm col-lg-7" id="link" name="link" required="" style="background-color: #E9ECEF;"
                          value="<?if($no != null){echo $row['link'];}?>">
                        </td>
                      </tr>

                      <tr>
                        <th width="150px">공지타입</th>
                        <td>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="notice_text" name="notice_type" class="custom-control-input" value="텍스트" onclick="setContent(0)"
                            <?if($no != null){if($row['type'] == '텍스트'){echo "checked";}}else{echo "checked";}?>>
                            <label class="custom-control-label" for="notice_text">텍스트</label>
                          </div>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="notice_image" name="notice_type" class="custom-control-input" value="이미지" onclick="setContent(1)"
                            <?if($no != null){if($row['type'] == '이미지'){echo "checked";}}?>>
                            <label class="custom-control-label" for="notice_image">이미지</label>
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <th width="150px">제목</th>
                        <td>
                          <input type="text" class="form-control form-control-sm col-lg-12" required="" id="title" name="title" style="background-color: #E9ECEF;"
                          value="<?if($no != null){echo $row['title'];}?>">
                        </td>
                      </tr>
                      <tr>
                        <th width="150px" id="notice_content">
                          <?if($no != null){if($row['type'] == '텍스트'){echo "내용";}else{echo "이미지 업로드";}}else{echo "내용";}?>
                        </th>

                        <!-- textarea -->
                        <td id="notice_textarea" <?if($no != null){if($row['type'] == '이미지'){echo "style='display:none;'";}}?>>
                          <textarea type="text" class="form-control form-control-sm col-lg-12" id="content_text" name="content_text" required="" style="background-color: #E9ECEF;"><?if($no != null){echo $row['content'];}?></textarea>
                        </td>

                        <!-- image -->
                        <td id="notice_file" <?if($no != null){if($row['type'] == '텍스트'){echo "style='display:none;'";}}else{echo "style='display:none;'";}?>>
                          <input type="file" id="content_image" name="content_image" size=100 accept=".png, .jpg, .bmp, .gif"
                          value="<?if($no != null){echo $row['image'];}?>">
                          <div class="fileupload-new thumbnail mt-2" style="">
                            <img id="img" src="http://<?php echo $row['image']; ?>" alt=""  style="width: 80%; height: auto;" />
                          </div>
                        </td>
                      </tr>
                    </table>

                    <?
                    $sql = "select * from did_notice where idx = '".$no."'";
                    $conn->DBQ($sql);
                    $conn->DBE();
                    $aa = $conn->DBF();
                    ?>

                    <div class="row mt-5">
                      <div class="col-lg-12 text-center">
                        <button type="button" class="btn btn-flat btn-light btn-md mr-2" onclick="history.back(-1)">취소</button>
                        <?if($no != null){?>
                        <button type="button" class="btn btn-flat btn-secondary btn-md mr-2" id="btnMod">수정</button>
                        <?}else{?>
                        <button type="button" class="btn btn-flat btn-secondary btn-md mr-2" id="btnIns">등록</button>
                        <?} if($no != null){?>
                        <button type="button" onclick="setConfirm(<?echo $no;?>)" class="btn btn-flat btn-dark btn-md mr-2">삭제</button>
                        <?}?>
                      </div>
                    </div>
                  </div>
                  <!-- /card-body -->
                </div>
                <!-- /card -->
              </form>
            </div>
            <!-- /col-lg-12 mt-2 -->

            <div class="modal fade show" id="ModalLong">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-body" id="Mbody">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="dismissModal">확인</button>
                  </div>
                </div>
              </div>
            </div>
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
      language: 'ko-KR',
      startDate: '1980-01-01',
      endDate: '2020-12-31'
    });
    $("#date_to").datepicker('setDate', document.getElementById('date_to').value);
    $("#date_from").datepicker('setDate', document.getElementById('date_from').value);
  });
  $('[data-toggle = "datepicker"]').click(function() {
    $('[data-toggle = "datepicker"]').datepicker({
      autoHide: true,
      zIndex: 2048,
      language: 'ko-KR',
      startDate: '1980-01-01',
      endDate: '2020-12-31'
    });
    $("#date_from").datepicker('setEndDate', $("#date_to").datepicker('getDate', true));
    $("#date_to").datepicker('setStartDate', $("#date_from").datepicker('getDate', true));
  });

  $("#btnIns").click(function(){
    var title = $("#title").val();
    var pattern = /select|union|insert|update|delete|drop|[\'\"|#|\/\*|\*\/|\\\|\;]/gi;
    if(title == '' || title.length < 1 || pattern.test(title) != false){
      alert('제목을 입력해주세요!');
      $("#title").focus();
      return false;
    } else {
      $.ajax({
        url: './api/noticeReg/checkDate.php',
        type: 'POST',
        data: { date_from: $("#date_from").val(), date_to: $("#date_to").val() },
        dataType: 'JSON',
        success	: function(result)
         {
           if(result.cnt != 0){
             if($("#expose1").is(":checked") == true){
               var con_insert = confirm("등록 하시겠습니까?");
               if(con_insert == true){
                 $("#submit_form").submit();
               } else if(con_insert == false){
                 return false;
               }
              }else{
                $("#Mbody").html(result.data);
                $("#ModalLong").show();
              }
           }else{
             var con_insert = confirm("등록 하시겠습니까?");
             if(con_insert == true){
               $("#submit_form").submit();
             } else if(con_insert == false){
               return false;
             }
           }
  			 }
      });
    }
  });

  $("#btnMod").click(function(){
    var title = $("#title").val();
    var pattern = /select|union|insert|update|delete|drop|[\'\"|#|\/\*|\*\/|\\\|\;]/gi;
    if(title == '' || title.length < 1 || pattern.test(title) != false){
      alert('제목을 입력해주세요!');
      $("#title").focus();
      return false;
    } else {
      $.ajax({
        url: './api/noticeReg/checkDate.php',
        type: 'POST',
        data: { no: '<?echo $_GET['no'];?>', date_from: $("#date_from").val(), date_to: $("#date_to").val() },
        dataType: 'JSON',
        success	: function(result)
         {
           if(result.cnt != 0){
             if($("#expose1").is(":checked") == true){
               var con_modify = confirm("수정 하시겠습니까?");
               if(con_modify == true){
                 $("#submit_form").submit();
               } else if(con_modify == false){
                 return false;
               }
              }else{
                $("#Mbody").html(result.data);
                $("#ModalLong").show();
              }
           }else{
             var con_modify = confirm("수정 하시겠습니까?");
             if(con_modify == true){
               $("#submit_form").submit();
             } else if(con_modify == false){
               return false;
             }
           }
  			 }
      });
    }
  });

  $("#dismissModal").click(function(){
    $("#ModalLong").hide();
  })
  </script>
</body>

</html>
<?}?>
