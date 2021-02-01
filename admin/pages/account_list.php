<!doctype html>
<html lang="en">
<?php include "../sessions/db.php";
include "../sessions/access_manager.php";
?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>계정현황</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
  <link href="../assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/libs/css/style.css">
  <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
  <script src="../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>

  <script type="text/javascript">

  function on_search(){
    var sortbox = document.getElementById("sort");
    var searchbox= document.getElementById("search");
    alert('선택된 옵션 value 값=' + sortbox.options[sortbox.selectedIndex].value);     // 옵션 value 값
    alert('선택된 옵션 value 값=' + searchbox.value);
  }

  function maxrow_change(){
    var row=$('#max_row').val();
    location.href="account_list.php?mc=<?=$_GET['mc']?>&sc=<?=$_GET['sc']?>&page=<?=$_GET['page']?>&row="+row+"&by=<?=$_GET['by']?>&search=<?=$_GET['search']?>";
  }


  function change_root(idx){
    root_num=$("#root"+idx).children("option:selected").val();
    if(<?=$_SESSION['user_idx']?> != idx){
      $.ajax({
        type: "POST"
        ,url: "../sessions/change_root.php"
        ,data: {root:root_num,idx:idx}
        ,success:function(data){
          alert("변경되었습니다.");
          location.reload();
        }
        ,error:function(data){
          alert("서버오류");
        }
      });
    }else{
      alert("본인계정은 수정할 수 없습니다.");
    }
  }

  $(function () {
    $("select[name=by]").change(function(){
      var by = $('select[name=by] option:selected').val();
      location.href="account_list.php?mc=<?=$_GET['mc']?>&sc=<?=$_GET['sc']?>&page=1&row=<?=$_GET['row']?>&by="+by+"&search=<?=$_GET['search']?>";
    });
  })
  //로딩
  $(function () {
    $('select[name=by] option[value="<?=$_GET['by']?>"]').prop("selected",true);
  })

  </script>
</head>
<body>
  <?php
  $sc=$_GET['sc']; // 소분류
  $page=$_GET['page']; //페이지 번호
  $max_row=$_GET['row']; //표시할 열 총개수
  ?>

  <div class="dashboard-main-wrapper">
    <!-- navbar -->
    <?php
    include "../layout/header.php";
    ?>
    <!-- end navbar -->
    <!-- left sidebar -->
    <?php
    include "../layout/sidebar.php";
    ?>
    <!-- end left sidebar -->
    <div class="dashboard-wrapper">
      <div class="container-fluid  dashboard-content">
        <!-- pageheader -->
        <?php
        include "../layout/pageheader.php";
        ?>
        <!-- end pageheader -->
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <div style="padding-bottom: 40px;">
                    <span>표시 개수 :</span>
                    <select id="max_row" class="form-control-sm" onchange="maxrow_change()" ><?
                    if ($max_row==5) {?>
                      <option value="5" selected>5개</option>
                      <option value="10">10개</option><?
                    }else {?>
                      <option value="5">5개</option>
                      <option value="10" selected>10개</option><?
                    }
                    ?>
                  </select>
                  <div style="float:right;">
                    <form style="float:right; display:hidden"  method="GET" action="account_list.php">
                      <input name="mc" type="text" style="display:none" value="<?=$_GET['mc']?>">
                      <input name="sc" type="text" style="display:none" value="<?=$_GET['sc']?>">
                      <input name="page" type="text" style="display:none" value="1">
                      <input name="row" type="text" style="display:none" value="<?=$_GET['row']?>">
                      <input name="by" type="text" style="display:none" value="<?=$_GET['by']?>">
                      <label style="float:left; padding:8px">ID :</label>
                      <input style="float:left; width:130px; margin-right:8px" class="form-control" name="search" type="text" placeholder="Search..." value="<?=$_GET['search']?>">
                      <input type="submit" style="padding-top: 6px; padding-bottom:4px" class="btn btn-primary" value="검색" >
                    </form>
                    <div>
                      <label style="float:left; padding:8px">권한 : </label>
                      <?
                      if($_GET['sc']=="승인대기"){?>
                        <select class="form-control-sm" style=" margin-top:4px; margin-bottom:8px; width:100px" name="by">
                          <option value="0">미승인</option>
                        </select>
                      <? }else if($_GET['sc']=="계정현황"){?>
                        <select class="form-control-sm" style=" margin-top:4px; margin-bottom:8px; width:100px" name="by">
                          <option value="4">전체</option>
                          <option value="1">일반</option>
                          <option value="2">부 관리자</option>
                          <option value="3">관리자</option>
                        </select>
                      <? }
                      if($_GET['sc']=="승인대기"){?>
                        <a href="account_list.php?mc=계정관리&sc=승인대기&page=1&row=5"><button style="float:right; margin:8px"type="button"class="btn btn-secondary"><i class="fas fa-undo-alt"></i></button></a>
                      <? }else{?>
                        <a href="account_list.php?mc=계정관리&sc=계정현황&page=1&row=5"><button style="float:right; margin:8px"type="button"class="btn btn-secondary"><i class="fas fa-undo-alt"></i></button></a>
                      <? }?>
                    </div>
                  </div>
                </div>
                <table class="table table-striped table-bordered first">
                  <thead>
                    <tr>
                      <th>번호</th>
                      <th>회원번호</th>
                      <th>ID</th>
                      <th>권한</th>
                      <th>생성일</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $row_start=$page*$max_row-$max_row; //DB로 부터 시작할 번호
                    $count = $row_start+1;

                    $a="";
                    if($_GET['by'] != ""){
                      if($_GET['by'] != 4){
                        $a.=" and root =".$_GET['by'];
                      }
                    }
                    if(isset($_GET['search'])){
                      $a.=" and id like '%".$_GET['search']."%'";
                    }

                    $db = new DBC;
                    if ($sc=="계정현황") {
                      $query= "SELECT idx,id,root,sign_date FROM member where root<>0$a ORDER BY idx desc LIMIT $row_start, $max_row ";
                    }else {
                      $query= "SELECT idx,id,root,sign_date FROM member WHERE root='0'$a ORDER BY idx desc LIMIT $row_start, $max_row";
                    }
                    $db->DBI();
                    $db->DBQ($query);
                    $db->DBE();
                    while ($list=$db->DBF()) {?>
                      <tr>
                        <th><?=$count++?></th>
                        <th><?=$list['idx']?></th>
                        <th><?=$list['id']?></th>
                        <th>
                          <div>
                            <select class="form-control-sm" id="root<?=$list['idx']?>" >
                              <?
                              for ($i=0; $i < 4; $i++) {
                                if($i==1){
                                  $root = "일반";
                                }else if($i==2){
                                  $root = "부 관리자";
                                }else if($i==3){
                                  $root = "관리자";
                                }else{
                                  $root = "미승인";
                                }
                                if ($i==$list['root']) {?>
                                  <option value="<?=$i?>" selected><?=$root?></option><?
                                }else {?>
                                  <option value="<?=$i?>"><?=$root?></option><?
                                }
                              }?>
                            </select>
                            <button type="button" style="padding: 8px ;padding-top: 6px; padding-bottom:4px" class="btn btn-primary" onclick="change_root(<?=$list['idx']?>)">수정</button>
                          </div>
                        </th>
                        <th><?=$list['sign_date']?></th>
                      </tr>
                    <?}?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- end basic table  -->
        <!-- ============================================================== -->
      </div>

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

          if ($sc=="계정현황") {
            $query= "SELECT idx FROM member where root<>0$a";
          }else {
            $query= "SELECT idx FROM member WHERE root='0'$a";
          }
          $db->DBI();
          $db->DBQ($query);
          $db->DBE();
          $fin_num=ceil(ceil($db->resultRow()/$max_row)/5);
          $row_list_start=ceil($page/5);
          if ($page/5>1) {?>
            <li class="page-item"><a class="page-link" href="account_list.php?mc=<?=$_GET['mc']?>&sc=<?=$_GET['sc']?>&page=<?=$row_list_start*5-9?>&row=<?=$max_row?>&by=<?=$_GET['by']?>&search=<?=$_GET['search']?>" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a>
            </li><?
          }
          for ($i=$row_list_start*5-4; ($i<=$row_list_start*5)&&($i<=ceil($db->resultRow()/$max_row)); $i++) {
            if ($page==$i) {?>
              <li class="page-item active"><a class="page-link"><?=$i?></a></li><?
            }else {?>
              <li class="page-item"><a class="page-link" href="account_list.php?mc=<?=$_GET['mc']?>&sc=<?=$_GET['sc']?>&page=<?=$i?>&row=<?=$max_row?>&by=<?=$_GET['by']?>&search=<?=$_GET['search']?>"><?=$i?></a></li><?
            }
          }
          if ($row_list_start!=$fin_num) {?>
            <li class="page-item"><a class="page-link" href="account_list.php?mc=<?=$_GET['mc']?>&sc=<?=$_GET['sc']?>&page=<?=$row_list_start*5+1?>&row=<?=$max_row?>&by=<?=$_GET['by']?>&search=<?=$_GET['search']?>" aria-label="Next"><span aria-hidden="true">&raquo;</span>
              <span class="sr-only">Next</span></a></li><?
            }?>
          </ul>
        </nav>

      </div>
      <!-- footer -->
      <?php
      include "../layout/footer.php";
      ?>
      <!-- end footer -->
    </div>
  </div>
        <!-- Optional JavaScript -->

</body>
</html>
