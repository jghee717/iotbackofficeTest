$(document).ready(function(e){

  // PV / UV 현황 (라인)
  $("#pu_day").click(function(){
    $("#line-2").css("display","none");
    $("#line-3").css("display","none");
    $("#line-1").css("display","block");
  });

  $("#pu_week").click(function(){
    $("#line-1").css("display","none");
    $("#line-3").css("display","none");
    $("#line-2").css("display","block");
  });

  $("#pu_month").click(function(){
    $("#line-1").css("display","none");
    $("#line-2").css("display","none");
    $("#line-3").css("display","block");
  });

  /******************* 도넛 ****************/
  // 전체 PV 이용 현황 (도넛)
  $("#pv_day").click(function(){
    $("#pie-2").css("display","none");
    $("#pie-3").css("display","none");
    $("#pie-1").css("display","block");

    $("#no-data-2").css("display","none");
    $("#no-data-3").css("display","none");
    $("#no-data-1").css("display","block");
  });

  $("#pv_week").click(function(){
    $("#pie-1").css("display","none");
    $("#pie-3").css("display","none");
    $("#pie-2").css("display","block");

    $("#no-data-1").css("display","none");
    $("#no-data-3").css("display","none");
    $("#no-data-2").css("display","block");
  });

  $("#pv_month").click(function(){
    $("#pie-1").css("display","none");
    $("#pie-2").css("display","none");
    $("#pie-3").css("display","block");

    $("#no-data-1").css("display","none");
    $("#no-data-2").css("display","none");
    $("#no-data-3").css("display","block");
  });

  // 전체 UV 이용 현황 (도넛)
  $("#uv_day").click(function(){
    $("#pie-5").css("display","none");
    $("#pie-6").css("display","none");
    $("#pie-4").css("display","block");

    $("#no-data-5").css("display","none");
    $("#no-data-6").css("display","none");
    $("#no-data-4").css("display","block");
  });

  $("#uv_week").click(function(){
    $("#pie-4").css("display","none");
    $("#pie-6").css("display","none");
    $("#pie-5").css("display","block");

    $("#no-data-4").css("display","none");
    $("#no-data-6").css("display","none");
    $("#no-data-5").css("display","block");
  });

  $("#uv_month").click(function(){
    $("#pie-4").css("display","none");
    $("#pie-5").css("display","none");
    $("#pie-6").css("display","block");

    $("#no-data-4").css("display","none");
    $("#no-data-5").css("display","none");
    $("#no-data-6").css("display","block");
  });
});
