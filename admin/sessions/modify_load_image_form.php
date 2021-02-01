<?php
include "./db.php";
$db = new DBC;
$db->DBI();

$query= "SELECT * FROM board_list where idx='{$_POST['idx']}' and b_state<>4";
$db->DBQ($query);
$db->DBE();
$result=$db->DBF();
?>
<script type="text/javascript">

function load_image(){
  var search = $('input[name=search_image]').val();
  if($("input[name=query]").val()){
    var arr = new Array();
    $("input[name=query]").each(function(idx){
      arr.push($("input[name=query]:eq(" + idx + ")").val());
    });
  }
  $.ajax({
    type : 'POST',
    dataType : 'html',
    url : '../sessions/load_image.php',
    data : {search:search, except:arr},
    success: function(data){
      $("#show_image").empty();
      $("#show_image").append(data);
    }
  });
}

//이미지 검색
$(function () {

  function reset_image(){
    $('input[name=search_image]').val("");
    var search = "";
    if($("input[name=query]").val()){
      var arr = new Array();
      $("input[name=query]").each(function(idx){
        arr.push($("input[name=query]:eq(" + idx + ")").val());
      });
    }
    $.ajax({
      type : 'POST',
      dataType : 'html',
      url : '../sessions/load_image.php',
      data : {search:search, except:arr},
      success: function(data){
        $("#show_image").empty();
        $("#show_image").append(data);
      }
    });
  }

  $('#search_image').click(function(){
    load_image();
  });

  $('#search_reset').click(function(){
    reset_image();
  });

  $('#reset').click(function() {
    $('#choice_image').empty();
    $('.send_image').empty();
    $('input[name=query]').remove();
    reset_image();
  });
});

//이미지 순서 정하기
var count = <?=$result['content_count']?>;

$(function(){
  for(var i=0; i<count; i++){
    var a= parseInt("1") + parseInt(i);
    var b = $("#hide"+a).val();
    $('#'+b).hide();
  }
});

function img_list(num, name){
  count +=1;
  $('#choice_image').append("<div id="+count+" style='padding-top:5px; overflow:hidden;'><img src='../../io/images/"+name+"' alt='"+name+"' style='width:20%;height:80px;float:left;padding-left:10px; padding-right:10px';><div style='width:60%; padding-top:30px;float:left'>"+name+"</div><button type='button'style='margin-top:22px' value="+count+" onClick='remove(this)'>&times</button></div>");
  $('.send_image').append("<option id="+count+" value="+num+" selected>"+name+"</option");
  $('#'+num).addClass(""+count+"");
  $('#'+num).hide();
  $('.search').append("<input type='hidden' id='"+count+"' name='query' value='"+name+"'>");
}

function remove(val){
  $('option[id='+$(val).val()+']').remove();
  $('#choice_image div[id='+$(val).val()+']').remove();
  $('.'+$(val).val()).show();
  $('input[id='+$(val).val()+']').remove();
  load_image();
}

// 엔터 입력폼 막기
$(function () {
  $('input[type="text"]').keydown(function() {
    if (event.keyCode === 13) {
        event.preventDefault();
    }
  })
});

// 이미지추가
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
  xhr.open("POST","../sessions/save_image.php");
  xhr.onload = function(e) {
    if($("#input_img").val()== ""){
      alert("선택된 이미지가 없습니다.");
    }else{
      if(this.status == 200) {
        alert(e.currentTarget.responseText);
        $('#input_img').val("");
        $(".preview").empty();
        sel_files=[];

        function reset_image(){
          $('input[name=search_image]').val("");
          var search = "";
          if($("input[name=query]").val()){
            var arr = new Array();
            $("input[name=query]").each(function(idx){
              arr.push($("input[name=query]:eq(" + idx + ")").val());
            });
          }
          $('input[name=search_image]').val("");
          $.ajax({
            type : 'POST',
            dataType : 'html',
            url : '../sessions/load_image.php',
            data : {search:search, except:arr},
            success: function(data){
              $("#show_image").empty();
              $("#show_image").append(data);
            }
          });
        }
        reset_image();
      }
    }
  }

  xhr.send(data);

}

$("#input_img").change(function() {
  return readURL(this);
});

$(function(){
  $(".modal #close").click(function(){
    $(".preview").empty();
    $("#input_img").val("");
  })
})

</script>
<div class="form-group row" >
  <label class="col-12 col-sm-3 col-form-label text-sm-right">이미지 선택</label>
  <div class="col-12 col-sm-8 col-lg-6">
    <div style="overflow:hidden">
      <div style="float:left; padding-top:30px">
        이미지 목록
      </div>
      <input type="button" style="float:right" class="btn btn-light" data-toggle="modal" data-target="#exampleModal1" value="로컬에서 이미지 추가하기">
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">로컬에서 이미지 추가하기</h5>
                    <a href="#" class="close" id="close" data-dismiss="modal" aria-label="Close">
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
                  <input type="button" class="btn btn-secondary" id="close" data-dismiss="modal" value="닫기">
                </div>
            </div>
        </div>
    </div>
    <div class="" style="border:1px solid black; margin-top: 10px">
      <div id="show_image" style="overflow-y:scroll;height:300px; padding-top:10px">
        <!-- 이미지 목록 -->
        <?php
        $sql= "SELECT idx, con_id FROM contents where res_type=2 order by idx desc";
        $db->DBQ($sql);
        $db->DBE();
        while($img=$db->DBF()){?>
          <div class="list" style="width:33%; float:left; height:150px; text-align:center" id="<?=$img['idx']?>">
            <img src="../../io/images/<?=$img['con_id']?>" alt="<?=$img['con_id']?>" style="width:90%;height:100px; cursor:pointer" onClick="img_list('<?=$img['idx']?>','<?=$img['con_id']?>')">
            <div class="" style="height:20px; overflow:hidden"><?=$img['con_id']?></div>
          </div>
        <?php } ?>
      </div>
      <div class='search' style="width:100%; text-align:center; border-top:1px solid gray; border-bottom:1px solid gray; padding:10px">
        <label class="text-sm-right">이미지 검색</label>
        <input type="text" name="search_image" class="form-control" style="display: inline; margin-left:5px; width:60%">
        <button style="padding-top:5px; padding-bottom:5px" id="search_image" type="button" class="btn btn-outline-info">검색</button>
        <i style="padding:10px; cursor:pointer" class="fas fa-undo-alt" id="search_reset"></i>
        <?for($i=1; $i<$result['content_count']+1; $i++){
          $query1 = "SELECT con_id,idx FROM contents where idx='{$result['content'.$i]}' and res_type <>4";
          $db->DBQ($query1);
          $db->DBE();
          $image=$db->DBF();?>
          <input type="hidden" id="<?=$i?>" name="query" value="<?=$image['con_id']?>">
          <input type="hidden" id="hide<?=$i?>" value="<?=$image['idx']?>">
        <? }?>
      </div>
      <div style="width:100%; text-align:center; border-bottom:1px solid gray; padding:10px">
        ↓ 선택된 이미지 ↓ (위에서부터 1순위)
      </div>
      <select class="send_image" name="send_image[]" multiple style="display:none"><!-- 선택된이미지 저장 -->
        <?for($i=1; $i<$result['content_count']+1; $i++){
          $query1 = "SELECT con_id,idx FROM contents where idx='{$result['content'.$i]}' and res_type <>4";
          $db->DBQ($query1);
          $db->DBE();
          $image=$db->DBF();?>
          <div id="<?=$i?>" style="padding-top:5px; overflow:hidden;">
            <option id="<?=$i?>" value="<?=$image['idx']?>" selected=""><?=$image['con_id']?></option>
          </div>
        <? }?>
      </select>
      <div id="choice_image" style="overflow-y:scroll;height:300px; text-align:center"><!-- 선택된 이미지 목록 -->
        <?
        for($i=1; $i<$result['content_count']+1; $i++){
          $query1 = "SELECT con_id FROM contents where idx='{$result['content'.$i]}' and res_type <>4";
          $db->DBQ($query1);
          $db->DBE();
          $image=$db->DBF();?>
          <div id="<?=$i?>" style="padding-top:5px; overflow:hidden;">
            <img src="../../io/images/<?=$image['con_id']?>" alt="<?=$image['con_id']?>" style="width:20%;height:80px;float:left;padding-left:10px; padding-right:10px">
            <div style="width:60%; padding-top:30px;float:left"><?=$image['con_id']?></div>
            <button type="button" style="margin-top:22px" value="<?=$i?>" onclick="remove(this)">×</button>
          </div>
        <? }?>
      </div>
      <button type="button" id="reset" style="float:right"class="btn btn-secondary">초기화</button>
    </div>
  </div>
</div>
