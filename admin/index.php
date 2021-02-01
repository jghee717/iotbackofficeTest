<!doctype html>

<html lang="ko">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>로그인</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="./assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/libs/css/style.css">
    <link rel="stylesheet" href="./assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <style>
    html,
    body {
        height: 100%;
    }

    body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
    }
    </style>
</head>

<body>
  <?php
  session_start();
  if(isset($_SESSION['user_id'])){
    header("Location:http://iotdidsystem.cafe24.com:8080/admin/pages/dashboard.php");
  }
  ?>
    <!-- ============================================================== -->
    <!-- login page  -->
    <!-- ============================================================== -->
    <div class="splash-container">
        <div class="card ">
            <div class="card-header text-center"><a href="./index.php"><img class="logo-img" src="./assets/images/logo_ucrm.png" style="width:200px;height:90px" alt="logo"></a><span class="splash-description"></span></div>
            <div class="card-body">
                <form enctype="multipart/form-data" method="POST" id="basicform" action="./sessions/login_check.php" data-parsley-validate="">
                  <div class="form-group">
                    <input id="inputUserName" type="text" name="ID" data-parsley-trigger="change" required="" placeholder="Enter user name" autocomplete="off" class="form-control">
                  </div>
                  <div class="form-group">
                    <input id="inputPassword" type="password" name="PW" placeholder="Password" required="" class="form-control">
                  </div>
                    <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input  id="idSaveCheck"class="custom-control-input" type="checkbox"><span class="custom-control-label">아이디 저장</span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">로그인</button>
                </form>
            </div>
            <div class="card-footer bg-white p-0">
                <div width="50%" class="card-footer-item card-footer-item-bordered">
                    <a href="./pages/sign-up.html" class="footer-link">계정 생성</a>
                </div>
                <div class="card-footer-item card-footer-item-bordered">
                    <a href="" onclick="alert('관리자에게 문의하세요.')" class="footer-link">비밀번호 찾기</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- end login page  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="./assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="./assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="./assets/vendor/parsley/parsley.js"></script>
    <script src="./assets/libs/js/main-js.js"></script>
    <script src="../io/js/cooky.js"></script>
    <script>
    $('#form').parsley();
    </script>
    <script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
    </script>
    <script>
    $(document).ready(function(){

        // 저장된 쿠키값을 가져와서 ID 칸에 넣어준다. 없으면 공백으로 들어감.
        var key = getCookie("_ID");
        $("#inputUserName").val(key);

        if($("#inputUserName").val() != ""){ // 그 전에 ID를 저장해서 처음 페이지 로딩 시, 입력 칸에 저장된 ID가 표시된 상태라면,
            $("#idSaveCheck").attr("checked", true); // ID 저장하기를 체크 상태로 두기.
        }

        $("#idSaveCheck").change(function(){ // 체크박스에 변화가 있다면,
            if($("#idSaveCheck").is(":checked")){ // ID 저장하기 체크했을 때,
                setCookie("_ID", $("#inputUserName").val(), 7); // 7일 동안 쿠키 보관
            }else{ // ID 저장하기 체크 해제 시,
                deleteCookie("_ID");
            }
        });

        // ID 저장하기를 체크한 상태에서 ID를 입력하는 경우, 이럴 때도 쿠키 저장.
        $("#inputUserName").keyup(function(){ // ID 입력 칸에 ID를 입력할 때,
            if($("#idSaveCheck").is(":checked")){ // ID 저장하기를 체크한 상태라면,
                setCookie("_ID", $("#inputUserName").val(), 7); // 7일 동안 쿠키 보관
            }
        });
    });
    </script>
</body>

</html>
