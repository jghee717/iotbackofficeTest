<?php
include "../sessions/db.php";
include "../sessions/access_all_manager.php";
$db = new DBC;
$db->DBI();
?>
<!doctype html>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>분류 관리</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
  <link href="../assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/libs/css/style.css">
  <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
  <script src="../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
  <script src="../assets/vendor/slimscroll/jquery.slimscroll.js"></script>
  <script src="../assets/libs/js/main-js.js"></script>
  <script src="../assets/vendor/shortable-nestable/jquery.nestable.js"></script>
</head>
<script type="text/javascript">
  function modify(category,name,num) {
    $("input[name=num]").val("");
    $("input[name=menu]").val("");
    var count = $("option").length;
    for(var i=0; i<count; i++){
      $("select[name=category] option:eq("+i+")").removeAttr("selected");
      if($("select[name=category] option:eq("+i+")").val() == category){
        $("select[name=category] option:eq("+i+")").attr("selected", "selected");
      }
    }
    $("input[name=num]").val(num);
    $("input[name=menu]").val(name);
  }

  function modify_category(category) {
    $("input[name=rename_category]").val("");
    $("input[name=search_category]").val("");
    $("input[name=rename_category]").val(category);
    $("input[name=search_category]").val(category);
  }

  function add_menu(category) {
    $("input[name=add_menu_category]").val("");
    $("input[name=add_menu_category]").val(category);
    $("#add_menu_content").empty();
    $("#add_menu_content").append('<div class="form-group row"><label class="col-12 col-sm-3 col-form-label text-sm-right">메뉴</label><div class="col-12 col-sm-8 col-lg-6"><input type="text" name="add_menu_name[]" required class="form-control"></div></div>')
  }

  function add_new(){
    $("input[name=new_category]").val("");
    $("#add_content").empty();
    $("#add_content").append('<div class="form-group row"><label class="col-12 col-sm-3 col-form-label text-sm-right">메뉴</label><div class="col-12 col-sm-8 col-lg-6"><input type="text" name="new_menu[]" required class="form-control"></div></div>')
  }

  function check(){
    var check = confirm("삭제하시겠습니까?");
    if(check){
      return true;
    }
    else{
      return false;
    }
  }

//새로운 카테고리 추가시
  $(function(){
    $("#add_more").click(function(){
      $("#add_content").append('<div class="form-group row"><label class="col-12 col-sm-3 col-form-label text-sm-right">메뉴</label><div class="col-12 col-sm-8 col-lg-6"><input type="text" name="new_menu[]" required class="form-control"></div></div>')
    });
    $("#minus").click(function(){
      var count = $("#add_content .row").length - 1;
      if(count == 0){
        alert("더이상 삭제할수 없습니다.")
      }else{
        $("#add_content .row:eq("+count+")").remove();
      }
    });
  });

  // 메뉴만 추가시
  $(function(){
    $("#add_menu_more").click(function(){
      $("#add_menu_content").append('<div class="form-group row"><label class="col-12 col-sm-3 col-form-label text-sm-right">메뉴</label><div class="col-12 col-sm-8 col-lg-6"><input type="text" name="add_menu_name[]" required class="form-control"></div></div>')
    });
    $("#menu_minus").click(function(){
      var count = $("#add_menu_content .row").length - 1;
      if(count == 0){
        alert("더이상 삭제할수 없습니다.")
      }else{
        $("#add_menu_content .row:eq("+count+")").remove();
      }
    });
  });
</script>

<body>
  <!-- ============================================================== -->
  <!-- main wrapper -->
  <!-- ============================================================== -->
  <div class="dashboard-main-wrapper">
    <!-- ============================================================== -->
    <!-- navbar -->
    <!-- ============================================================== -->
    <?php
    include "../layout/header.php";
    ?>
    <!-- ============================================================== -->
    <!-- end navbar -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- left sidebar -->
    <!-- ============================================================== -->
    <?php
    include "../layout/sidebar.php";
    ?>
    <!-- ============================================================== -->
    <!-- end left sidebar -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- wrapper  -->
    <!-- ============================================================== -->
    <div class="dashboard-wrapper">
      <div class="container-fluid dashboard-content">
        <!-- ============================================================== -->
        <!-- pageheader -->
        <!-- ============================================================== -->
        <?php
        include "../layout/pageheader.php";
        ?>
        <!-- ============================================================== -->
        <!-- end pageheader -->
        <!-- ============================================================== -->

        <div class="dashboard-short-list">
          <div class="row">
            <div class="col-xl-43 col-lg-12 col-md-12 col-sm-12 col-12">
              <div class="card">
                <div class="card-body" style="text-align:right" >
                  <label>새로운 카테고리와 메뉴명 추가하기</label>
                  <a data-toggle="modal" data-target="#add_category" onclick="add_new()"><button style="margin-left:2%"class="btn btn-primary">추가</button></a>
                </div>
              </div>
            </div>
            <?php
            $q = "SELECT distinct category from menu_list where state <> 4";
            $db->DBQ($q);
            $db->DBE();
            while($category=$db->DBF()){?>
              <div class="col-xl-43 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="card">
                  <div class="card-body">
                    <table style="text-align:center" class="table">
                      <form action="../sessions/delete_category.php" method="post">
                        <input type="hidden" name="delete_category" value="<?=$category['category']?>">
                        <thead class="card-title">
                          <th width="90%"><strong style="font-size:20px"><?=$category["category"]?></strong></th>
                          <th width="5%">
                            <a data-toggle="modal" data-target="#category_modify" onclick="modify_category('<?=$category["category"]?>')"><button class="btn btn-sm btn-outline-light">수정</button></a>
                          </th>
                          <th width="5%">
                            <button class="btn btn-sm btn-outline-light" onclick="return check()"><i class="far fa-trash-alt"></i></button>
                          </th>
                        </thead>
                      </form>
                      <tbody class="card-text">
                        <?php
                        $data = new DBC;
                        $data->DBI();
                        $sql = "SELECT category,menu,menu_num FROM menu_list where category='{$category['category']}' and state <> 4 ";
                        $data->DBQ($sql);
                        $data->DBE();
                        while($result=$data->DBF()){?>
                          <form  action="../sessions/delete_menu.php" method="post">
                            <input type="hidden" name="delete_idx" value="<?=$result['menu_num']?>">
                            <tr>
                              <td><?= $result["menu"]?></td>
                              <td><a data-toggle="modal" data-target="#exampleModal" onclick="modify('<?=$result['category']?>','<?=$result['menu']?>','<?=$result['menu_num']?>')"><button class="btn btn-sm btn-outline-light">수정</button></a></td>
                              <td><button class="btn btn-sm btn-outline-light" onclick="return check()"><i class="far fa-trash-alt"></i></button></td>
                            </tr>
                          </form>
                        <? } $data->DBO();?>
                      </tbody>
                    </table>
                    <a data-toggle="modal" data-target="#add_menu" onclick="add_menu('<?=$category["category"]?>')"><button style="float:right" class="btn btn-primary">추가</button></a>
                  </div>
                </div>
              </div>
            <? }?>
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <form class="" action="../sessions/category_list_ok.php" method="post">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">수정하기</h5>
                      <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </a>
                    </div>
                    <div class="modal-body">
                      <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right">카테고리</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                          <select name="category" class="form-control form-control-sm">
                            <?php
                            $query = "SELECT distinct category FROM menu_list where state <> 4 ";
                            $db->DBQ($query);
                            $db->DBE();
                            while($option=$db->DBF()){?>
                              <option value="<?=$option['category']?>"><?=$option['category']?></option>
                            <? }?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right">메뉴</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                          <input type="text" name="menu" value="" required class="form-control">
                          <input type="hidden" name="num" value="" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">수정</button>
                      <a href="#" class="btn btn-secondary" data-dismiss="modal">취소</a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="modal fade" id="category_modify" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <form class="" action="../sessions/modify_category.php" method="post">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">수정하기</h5>
                      <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </a>
                    </div>
                    <div class="modal-body">
                      <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right">카테고리</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                          <input type="text" name="rename_category"  required class="form-control">
                          <input type="hidden" name="search_category" value="" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">수정</button>
                      <a href="#" class="btn btn-secondary" data-dismiss="modal">취소</a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="modal fade" id="add_category" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <form class="" action="../sessions/add_category.php" method="post">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">새로 추가하기</h5>
                      <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </a>
                    </div>
                    <div class="modal-body">
                      <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right">카테고리</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                          <input type="text" name="new_category"  required class="form-control">
                        </div>
                      </div>
                      <div id="add_content">
                        <div class="form-group row">
                          <label class="col-12 col-sm-3 col-form-label text-sm-right">메뉴</label>
                          <div class="col-12 col-sm-8 col-lg-6">
                            <input type="text" name="new_menu[]" required class="form-control">
                          </div>
                        </div>
                      </div>
                      <div style="text-align:center; cursor:pointer">
                        <i class="fas fa-plus" id="add_more"></i>
                        <i style="margin-left:5%" class="fas fa-minus" id="minus"></i>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">추가</button>
                      <a href="#" class="btn btn-secondary"  data-dismiss="modal">취소</a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="modal fade" id="add_menu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <form class="" action="../sessions/add_menu.php" method="post">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">추가하기</h5>
                      <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </a>
                    </div>
                    <div class="modal-body">
                      <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right">카테고리</label>
                        <div class="col-12 col-sm-8 col-lg-6">
                          <input type="text" name="add_menu_category" readonly class="form-control">
                        </div>
                      </div>
                      <div id="add_menu_content">
                        <div class="form-group row">
                          <label class="col-12 col-sm-3 col-form-label text-sm-right">메뉴</label>
                          <div class="col-12 col-sm-8 col-lg-6">
                            <input type="text" name="add_menu_name[]" required class="form-control">
                          </div>
                        </div>
                      </div>
                      <div style="text-align:center; cursor:pointer">
                        <i class="fas fa-plus" id="add_menu_more"></i>
                        <i style="margin-left:5%" class="fas fa-minus" id="menu_minus"></i>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">추가</button>
                      <a href="#" class="btn btn-secondary" data-dismiss="modal">취소</a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          <!-- ============================================================== -->
          <!-- end nestable list  -->
          <!-- ============================================================== -->
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <?php
        include "../layout/footer.php";
        ?>
        <!-- ============================================================== -->
        <!-- end footer -->
        <!-- ============================================================== -->
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper -->
    <!-- ============================================================== -->

  </body>
  </html>
