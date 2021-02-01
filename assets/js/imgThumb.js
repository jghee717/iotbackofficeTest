function imgThumb() {

var image;

if(image !== null) {
  $("#preview *").remove();
  console.log(4);
}

var upload = document.querySelector('#upload');
var preview = document.querySelector('#preview');


upload.addEventListener('change',function (e) {

    var get_file = e.target.files;

    var image = document.createElement('img');

    /* FileReader 객체 생성 */
    var reader = new FileReader();

    /* reader 시작시 함수 구현 */
    reader.onload = (function (aImg) {
        console.log(1);

        return function (e) {
            console.log(3);
            /* base64 인코딩 된 스트링 데이터 */
            aImg.src = e.target.result;
            console.log(5);
        }
    })(image)

    if(get_file){
        /*
            get_file[0] 을 읽어서 read 행위가 종료되면 loadend 이벤트가 트리거 되고
            onload 에 설정했던 return 으로 넘어간다.
            이와 함게 base64 인코딩 된 스트링 데이터가 result 속성에 담겨진다.
        */
        reader.readAsDataURL(get_file[0]);
        console.log(2);
    }

    preview.appendChild(image);

})}
