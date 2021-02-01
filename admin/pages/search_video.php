<!DOCTYPE HTML>
<html lang="ko">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

  <title> youtube api test </title>
  <style>
  p.box { border:1px solid #000; }
  </style>

</head>

<body>
  <div style="border: 1px solid;margin:1px">
    <form name="form1" method="post" onSubmit="return false;">
      <input type="text" id="search_box"><button onClick="fnGetList();">가져오기</button>
    </form>
  </div>

  <div style="border: 1px solid;margin:1px" id="get_view"></div>
  <div id="nav_view"></div>
  <script src="http://code.jquery.com/jquery-latest.min.js"></script>
  <script>
  function fnGetList(sGetToken){
    var $getval = $("#search_box").val();
    if($getval==""){
      alert("검색어를 입력하세요.");
      $("#search_box").focus();
      return;
    }
    $("#get_view").empty();
    $("#nav_view").empty()


    var sTargetUrl = "https://www.googleapis.com/youtube/v3/search?part=snippet&order=relevance&type=video"
    + "&q="+ encodeURIComponent($getval) +"&key=AIzaSyCmw3zqaTXmWum84d9idOCtu1gx2NyEB9U";
    if(sGetToken){
      sTargetUrl += "&pageToken="+sGetToken;
    }
    $.ajax({
      type: "POST",
      url: sTargetUrl,
      dataType: "jsonp",
      success: function(jdata) {
        console.log(jdata);

        $(jdata.items).each(function(i){
          //console.log(this.snippet.channelId);
          $("#get_view").append("<div style='border: 1px solid;margin: 1px;height: 90px;padding: 1px;'><img align='left' src='"+this.snippet.thumbnails.default.url+"'><span><div>제 목 : "+this.snippet.title+"</div><div>영상ID : "+this.id.videoId+"</div></span></div><br style='disaply:none' clear=''>");
        }).promise().done(function(){
          if(jdata.prevPageToken){
            $("#nav_view").append("<a href='javascript:fnGetList(\""+jdata.prevPageToken+"\");'><이전페이지></a>");
          }
          if(jdata.nextPageToken){
            $("#nav_view").append("<a href='javascript:fnGetList(\""+jdata.nextPageToken+"\");'><다음페이지></a>");
          }
        });
      },
      error:function(xhr, textStatus) {
        console.log(xhr.responseText);
        alert("지금은 시스템 사정으로 인하여 요청하신 작업이 이루어지지 않았습니다.\n잠시후 다시 이용하세요.[2]");
        return;
      }
    });
  }
  </script>
</body>
</html>
