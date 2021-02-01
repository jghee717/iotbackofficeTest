
$(function() {
  var $input = $("#InputPrice");

  $input.on('keyup', function() {
    // 입력 값 알아내기
    var _this = this;
    numberFormat(_this)
  })

});



// 콤마 찍기
function comma(str) {
  str = String(str);
  return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

// 콤마 풀기
function uncomma(str) {
  str = String(str);
  return str.replace(/[^\d]+/g, '');
}

function numberFormat(obj) {
  obj.value = comma(uncomma(obj.value));
}
