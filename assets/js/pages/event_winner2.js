/**
   @Author dykim
   @Date 2019.05.13
   @Description 777 이벤트 당첨자 페이지 JS
*/

let weekN = 0;

$(document).ready(function(){
  if(weekNumberByMonth(today).weekNo - 2 == 1){
    $("#nextWrapper").hide();
    $("#prevWrapper").hide();
  }else{
    $("#nextWrapper").hide();
  }

  getDateWinner();

  getBattleGroup(weekNumberByMonth(today).weekNo - 2);
});

$(".t-today").html(today);

$("#weekPrev").click(function(){
    var d = new Date(today);
    today = d.setDate(d.getDate() - 7);
    var k = new Date(today);

    var year = k.getFullYear().toString();
    var month = oneStrDate(k, true);
    var day = oneStrDate(k, false);
    $(".t-today").html( year + "/" + month + "/" + day);

    getDateWinner( year + "/" + month + "/" + day);
});

$("#dayPrev").click(function(){
  var d = new Date(today);
  today = d.setDate(d.getDate() - 1);
  var k = new Date(today);

  var year = k.getFullYear().toString();
  var month = oneStrDate(k, true);
  var day = oneStrDate(k, false);
  $(".t-today").html( year + "/" + month + "/" + day);

  getDateWinner( year + "/" + month + "/" + day);
})

$("#weekNext").click(function(){
    var d = new Date(today);
    today = d.setDate(d.getDate() + 7);
    var k = new Date(today);

    var year = k.getFullYear().toString();
    var month = oneStrDate(k, true);
    var day = oneStrDate(k, false);
    $(".t-today").html( year + "/" + month + "/" + day);

    getDateWinner( year + "/" + month + "/" + day);
});

$("#dayNext").click(function(){
  var d = new Date(today);
  today = d.setDate(d.getDate() + 1);
  var k = new Date(today);

  var year = k.getFullYear().toString();
  var month = oneStrDate(k, true);
  var day = oneStrDate(k, false);
  $(".t-today").html( year + "/" + month + "/" + day);

  getDateWinner( year + "/" + month + "/" + day);
})

$("#weekPrevBattle").click(function(){
    $("#nextWrapper").show();
    if(weekN <= 1){
      $("#prevWrapper").hide();
      return false;
    }
    weekN--;
    getBattleGroup(weekN);
})


$("#weekNextBattle").click(function(){
    if(weekN > 6){
      return false;
    }
    weekN++;
    if(weekN  ==  weekNumberByMonth(today).weekNo - 1){
      $("#nextWrapper").hide();
    }
    getBattleGroup(weekN);
})





// function getDateWinner(){
//     var k = new Date(today);
//
//     var year = k.getFullYear().toString();
//     var month = oneStrDate(k, true);
//     var day = oneStrDate(k, false);
//
//     $.ajax({
//         url : "/api/v1/event2.php"
//       , data : {
//         date :  year + "-" + month + "-" + day
//       }
//       , dataType : "JSON"
//       , type : "POST"
//       , async : true
//       , beforeSend : function(){
//       }
//       , success : function(d){
//            var str = "";
//
//            if(d.length == 0){
//              str = "<tr style=\"text-align:center;\"><td colspan=\"4\">당첨자 정보가 없습니다.</td></tr>";
//            }else{
//              d.forEach(function(item, idx){
//                str += "<tr>"
//                        +"<td>"+Number(idx + 1)+"</td>"
//                        +"<td>"+item.TIMESTAMP+"</td>"
//                        +"<td>"+item.pos_code+"</td>"
//                        +"<td>"+item.pos_name+"</td>"
//                        +"</tr>"
//              })
//            }
//
//           $("#winnerTable tbody").html(str);
//       }
//     })
// }


function getBattleGroup(a){
  var weekno = weekNumberByMonth(today);      //현재 주차
  if(a != undefined){
    var sDate = getWeekDays(a)[0];    // 시작
    var eDate = getWeekDays(a)[6];   // 종료
    weekN = a;
  }else{
    var sDate = getWeekDays(weekno.weekNo)[0];    // 시작
    var eDate = getWeekDays(weekno.weekNo)[6];   // 종료
    weekN = weekno.weekNo;
  }

  $.ajax({
      url : "/api/v1/event2.php"
    , data : {
        sDate : sDate
      , eDate : eDate
    }
    , dataType : "JSON"
    , type : "GET"
    , async : true
    , beforeSend : function(){
      HoldOn.open({
           theme:"sk-circle"
      });
    }
    , success : function(d){
         var str = "";

         if(a != undefined){
           console.log(a);
           $("#weekData").html(a+"주차");
         }else{
           $("#weekData").html(weekno.weekNo+"주차");
         }


            d.forEach(function(item, idx){
              var chan = (item.channel != "홈/미디어" ) ? ( item.channel + "영업담당") : "스마트홈부문";
              if(idx % 2 == 0){
                str += "<tr class=\"r"+item.chan_group+"\" style=\"text-align:center;\">"
                        +"<td class=\"numeric\" data-title=\"배틀그룹\" rowspan=\"2\" style=\"text-align:center;\">"+item.chan_group+"</td>"
                        +"<td class=\"numeric\" data-title=\"담당명\">"+ chan +"</td>"
                        +"<td class=\"numeric\" data-title=\"설치율\">"+item.execPer+"%</td>"
                        +"<td class=\"numeric\">"+item.exexVal+"점</td>"
                        +"<td class=\"numeric\" data-title=\"사용율\">"+item.res1+"%</td>"
                        +"<td class=\"numeric\">"+item.useVal+"점</td>"
                        +"<td class=\"numeric\" data-title=\"사용횟수\">"+item.res2+"회</td>"
                        +"<td class=\"numeric\">"+item.useCntVal+"점</td>"
                        +"<td class=\"numeric result-data\" data-title=\"총합\">"+item.result+"</td>"
                        +"<td class=\"numeric result\" data-title=\"배틀결과\">-</td>"
                        +"</tr>"
              }else {
                str += "<tr class=\"r"+item.chan_group+"\" style=\"text-align:center;\">"
                        +"<td class=\"numeric\" data-title=\"담당명\">"+chan+"</td>"
                        +"<td class=\"numeric\" data-title=\"설치율\">"+item.execPer+"%</td>"
                        +"<td class=\"numeric\">"+item.exexVal+"점</td>"
                        +"<td class=\"numeric\" data-title=\"사용율\">"+item.res1+"%</td>"
                        +"<td class=\"numeric\">"+item.useVal+"점</td>"
                        +"<td class=\"numeric\" data-title=\"사용횟수\">"+item.res2+"회</td>"
                        +"<td class=\"numeric\">"+item.useCntVal+"점</td>"
                        +"<td class=\"numeric result-data\" data-title=\"총합\">"+item.result+"</td>"
                        +"<td class=\"numeric result\" data-title=\"배틀결과\">-</td>"
                        +"</tr>"
              }
            });


        $("#battleGroupTable tbody").html(str);

        var r1, r2, r3;      // 기준 값
        $(".r1").each(function(a){
            if(r1 == null)
        		  r1 = $(this).find('.result-data').html();
            else{
              if(r1 > $(this).find('.result-data').html()){
                $(".r1 .result").eq(0).html("치킨 20마리")
              }else{
                $(".r1 .result").eq(1).html("치킨 20마리")
              }
            }
        });

        // 귀찮...
        $(".r2").each(function(a){
            if(r2 == null)
              r2 = $(this).find('.result-data').html();
            else{
              if(r2 > $(this).find('.result-data').html()){
                $(".r2 .result").eq(0).html("치킨 20마리")
              }else{
                $(".r2 .result").eq(1).html("치킨 20마리")
              }
            }
        });

        // 귀찮...
        $(".r3").each(function(a){
            if(r3 == null)
              r3 = $(this).find('.result-data').html();
            else{
              if(r3 > $(this).find('.result-data').html()){
                $(".r3 .result").eq(0).html("치킨 20마리")
              }else{
                $(".r3 .result").eq(1).html("치킨 20마리")
              }
            }
        });

        HoldOn.close();
    }
  })
}



/**
   @param a : date
   @param b : month  ( true, false )
   @brief 월  1자리 수 앞단 0 붙이기
*/

function oneStrDate(a , b){
  if(b)
    return (a.getMonth() + 1).toString().length < 2 ? "0" + (a.getMonth() + 1).toString() : (a.getMonth() + 1);
  else
    return a.getDate().toString().length < 2 ? "0" + a.getDate().toString() : a.getDate().toString();
}


/**
  @param a : 주차 기입  (ex : a = 1)
  @brief 5월 만 이벤트라 5월만 계산 일단.
*/
function getWeekDays(a){
  var currentDay = new Date('2019-06-01');
  currentDay = new Date(currentDay.setDate(currentDay.getDate() + 7 * a));

  var theYear = currentDay.getFullYear();
  var theMonth = currentDay.getMonth();
  var theDate  = currentDay.getDate();
  var theDayOfWeek = currentDay.getDay();

  var thisWeek = [];

  for(var i=0; i<7; i++) {
    var resultDay = new Date(theYear, theMonth, theDate + (i - theDayOfWeek));
    var yyyy = resultDay.getFullYear();
    var mm = Number(resultDay.getMonth()) + 1;
    var dd = resultDay.getDate() + 1;

    mm = String(mm).length === 1 ? '0' + mm : mm;
    dd = String(dd).length === 1 ? '0' + dd : dd;

    thisWeek[i] = yyyy + '-' + mm + '-' + dd;
  }

  return thisWeek;
}


/**
  @Author dykim
  @brief 오늘 날짜 기준 현재 주차 구하기
*/

function weekNumberByMonth(dateFormat) {
  const inputDate = new Date(dateFormat);

  // 인풋의 년, 월
  let year = inputDate.getFullYear();
  let month = inputDate.getMonth() + 1;

  // 목요일 기준 주차 구하기
  const weekNumberByThurFnc = (paramDate) => {

    const year = paramDate.getFullYear();
    const month = paramDate.getMonth();
    const date = paramDate.getDate();

    // 인풋한 달의 첫 날과 마지막 날의 요일
    const firstDate = new Date(year, month, 1);
    const lastDate = new Date(year, month+1, 0);
    const firstDayOfWeek = firstDate.getDay() === 0 ? 7 : firstDate.getDay();
    const lastDayOfweek = lastDate.getDay();

    // 인풋한 달의 마지막 일
    const lastDay = lastDate.getDate();

    // 첫 날의 요일이 금, 토, 일요일 이라면 true
    const firstWeekCheck = firstDayOfWeek === 5 || firstDayOfWeek === 6 || firstDayOfWeek === 7;
    // 마지막 날의 요일이 월, 화, 수라면 true
    const lastWeekCheck = lastDayOfweek === 1 || lastDayOfweek === 2 || lastDayOfweek === 3;

    // 해당 달이 총 몇주까지 있는지
    const lastWeekNo = Math.ceil((firstDayOfWeek - 1 + lastDay) / 7);

    // 날짜 기준으로 몇주차 인지
    let weekNo = Math.ceil((firstDayOfWeek - 1 + date) / 7);

    // 인풋한 날짜가 첫 주에 있고 첫 날이 월, 화, 수로 시작한다면 'prev'(전달 마지막 주)
    if(weekNo === 1 && firstWeekCheck) weekNo = 'prev';
    // 인풋한 날짜가 마지막 주에 있고 마지막 날이 월, 화, 수로 끝난다면 'next'(다음달 첫 주)
    else if(weekNo === lastWeekNo && lastWeekCheck) weekNo = 'next';
    // 인풋한 날짜의 첫 주는 아니지만 첫날이 월, 화 수로 시작하면 -1;
    else if(firstWeekCheck) weekNo = weekNo -1;

    return weekNo;
  };

  // 목요일 기준의 주차
  let weekNo = weekNumberByThurFnc(inputDate);

  // 이전달의 마지막 주차일 떄
  if(weekNo === 'prev') {
    // 이전 달의 마지막날
    const afterDate = new Date(year, month-1, 0);
    year = month === 1 ? year - 1 : year;
    month = month === 1 ? 12 : month - 1;
    weekNo = weekNumberByThurFnc(afterDate);
  }
  // 다음달의 첫 주차일 때
  if(weekNo === 'next') {
    year = month === 12 ? year + 1 : year;
    month = month === 12 ? 1 : month + 1;
    weekNo = 1;
  }

  return {year, month, weekNo};
}
