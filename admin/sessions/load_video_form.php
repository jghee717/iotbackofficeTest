<style media="screen">
  .selected{
    background-color: rgb(176, 197, 232);
  }
</style>

<script type="text/javascript">
//비디오 추가
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

// 비디오 로딩
function load_video(){
  var search = $('input[name=search_image]').val();
  $.ajax({
    type : 'POST',
    dataType : 'html',
    url : '../sessions/load_video_list.php',
    data : {search:search},
    success: function(data){
      $("#show_video").empty();
      $("#show_video").append(data);
    }
  });
}
//비디오 검색
$(function () {
  function reset_image(){
    $('input[name=search_image]').val("");
    var search = "";
    $.ajax({
      type : 'POST',
      dataType : 'html',
      url : '../sessions/load_video_list.php',
      data : {search:search},
      success: function(data){
        $("#show_video").empty();
        $("#show_video").append(data);
      }
    });
  }

  $('#search_image').click(function(){
    load_video();
  });

  $('#search_reset').click(function(){
    reset_image();
  });

  $('#reset').click(function() {
    $("input[name=name]").val("");
    $("input[name=idx]").val("");
    reset_image();
  });
});

function select_video(idx,name){
  $("input[name=name]").val(name);
  $("input[name=idx]").val(idx);
  $("#show_video").find(".selected").removeClass("selected");
  $("#"+idx).addClass("selected");
}

function submitAction() {
  var video = $('input[name=url]').val();
  if(video.length == "11"){
    $.ajax({
      type : 'POST',
      dataType : 'html',
      url : '../sessions/save_video.php',
      data : {video:video},
      success: function(data){
        alert(data);
        $('input[name=url]').val("");
        $("#video").html("");
        load_video()
      }
    });
  }else{
    alert("영상 URL or ID값을 확인해주세요")
  }
}

// 엔터 입력폼 막기
    $(function () {
      $('input[type="text"]').keydown(function() {
        if (event.keyCode === 13) {
            event.preventDefault();
        }
      })
    });
// URL창 한글입력막기
$(function(){
	$('input[name=url]').on("blur keyup", function() {
		$(this).val( $(this).val().replace( /[ㄱ-힣]/g, '' ) );
	});
})

$(function(){
  $(".modal #close").click(function(){
    $('input[name=url]').val("");
    $("#video").html("");
  })
})

</script>
<div class="form-group row" >
  <label class="col-12 col-sm-3 col-form-label text-sm-right">유튜브 영상 선택</label>
  <div class="col-12 col-sm-8 col-lg-6">
    <div style="overflow:hidden">
      <div style="float:left; padding-top:30px">
        유튜브 영상 목록
      </div>
      <input type="button" style="float:right" class="btn btn-light" data-toggle="modal" data-target="#exampleModal1" value="새로운 유튜브 영상 추가하기">
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document" >
            <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">새로운 유튜브 영상 추가하기</h5>
                  <a href="#" class="close" id="close" data-dismiss="modal" aria-label="Close">
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
                <input type="button" class="btn btn-primary" data-dismiss="modal" value="추가하기" onclick="submitAction();">
                <input type="button" id="close" class="btn btn-secondary" data-dismiss="modal" value="닫기">
              </div>
            </div>
        </div>
    </div>
    <div class="" style="border:1px solid black; margin-top: 10px">
      <div id="show_video" style="overflow-y:scroll;height:300px">
        <!-- 동영상 목록 -->
        <?php
        include "./db.php";
        $db = new DBC;
        $db->DBI();
        $sql= "SELECT idx, con_id FROM contents where res_type=1 order by idx desc";
        $db->DBQ($sql);
        $db->DBE();
        while($video=$db->DBF()){?>
          <div class="list" style="width:33%; float:left; height:125px; text-align:center; padding-top:5px; margin-top:5px" id="<?=$video['idx']?>">
            <img src="https://img.youtube.com/vi/<?=$video['con_id']?>/0.jpg" alt="<?=$video['con_id']?>" style="width:90%;height:100px; cursor:pointer" onClick="select_video('<?=$video['idx']?>','<?=$video['con_id']?>')">
            <div class="" style="height:20px; overflow:hidden"><?=$video['con_id']?></div>
          </div>
        <?php } ?>
      </div>
      <div class='search' style="width:100%; text-align:center; border-top:1px solid gray; padding:10px">
        <label class="text-sm-right">영상 검색</label>
        <input type="text" name="search_image" class="form-control" style="display: inline; margin-left:5px; width:60%">
        <button style="padding-top:5px; padding-bottom:5px" id="search_image" type="button" class="btn btn-outline-info">검색</button>
        <i style="padding:10px; cursor:pointer" class="fas fa-undo-alt" id="search_reset"></i>
      </div>
      <div class='search' style="width:100%; text-align:center; border-top:1px solid gray; padding:10px">
        <label class="text-sm-right">선택된 영상ID</label>
        <input type="text" name="name" class="form-control" style="display: inline; margin-left:5px; width:60%; background-color:white" readonly>
        <input type="hidden" name="idx" readonly>
      </div>
      <button type="button" id="reset" style="float:right"class="btn btn-secondary" >초기화</button>
    </div>
  </div>
</div>
