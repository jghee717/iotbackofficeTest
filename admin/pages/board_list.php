<!doctype html>

<?php
include "../sessions/access_all.php";
?>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>게시글 목록</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="../assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/libs/css/style.css">
    <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <script src="../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="../assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="../assets/vendor/parsley/parsley.js"></script>
    <script src="../assets/libs/js/main-js.js"></script>
    <style media="screen">
      .link {
        color:rgba(36, 120, 247, 0.7);
        text-decoration: underline;
      }
    </style>
    <script type="text/javascript">

    //상태수정
    function change_stat(idx){
      if(<?=$_SESSION['user_root']?> == 2 || <?=$_SESSION['user_root']?> == 3){
        var activ_ch=$("#activ"+idx).children("option:selected").val();
        var menu_num=$("#menu_num"+idx).val();
        $.ajax({
          type: "POST"
          ,url: "../sessions/change_stat.php"
          ,data: {activ:activ_ch, idx:idx, num:menu_num}
          ,success:function(data){
            if(data){
              var ask = confirm(data);
              if(ask){
                $.ajax({
                  type: "POST"
                  ,url: "../sessions/change_stat_strong.php"
                  ,data: {activ:activ_ch, idx:idx, num:menu_num}
                  ,success:function(data){
                    alert("변경되었습니다.");
                    location.reload();
                  }
                  ,error:function(data){
                    alert("서버오류");
                  }
                });
              }
              location.reload();
            }else{
              alert("변경되었습니다.");
              location.reload();
            }
          }
          ,error:function(data){
            alert("서버오류");
          }
        });
      }else {
        alert("권한이 없습니다.");
      }
    }

    //삭제
    $(function(){
      $("#delete").click(function(){
        if(<?=$_SESSION['user_root']?> == 2 || <?=$_SESSION['user_root']?> == 3){
          var count = $("input[name=checkbox]:checked").length;
          if(count == 0){
            alert("삭제하실 글을 선택해주세요.");
          }else{
            var a = confirm("정말로 삭제하시겠습니까?");
            if(a == true){
              var checkbox = [];
              $("input[name=checkbox]:checked").each(function(i){
                checkbox.push($(this).val());
              });
              $.ajax({
                type: "POST"
                ,url: "../sessions/delete_list.php"
                ,data: {checkbox:checkbox}
                ,success:function(data){
                  alert(data);
                  location.reload();
                }
                ,error:function(data){
                  alert("서버오류");
                }
              });
            }
          }
        }else {
          alert("권한이 없습니다.");
        }
      });
    });

    // 정렬
    $(function(){
      var get_sort = <? if(isset($_GET['sort'])){echo$_GET['sort'];}else{echo("1");}?>;
      if(get_sort == 1){
        $('select[name=sort] option:eq(0)').attr("selected",true);
      }else if(get_sort == 2){
        $('select[name=sort] option:eq(1)').attr("selected",true);
      }else if(get_sort == 3){
        $('select[name=sort] option:eq(2)').attr("selected",true);
      }
      $('select[name=sort]').change(function(){
        var sort = $('select[name=sort] option:selected').val();
        var category = "<? if(isset($_GET['category'])){echo$_GET['category'];}else{echo("");}?>";
        var menu = "<? if(isset($_GET['menu'])){echo$_GET['menu'];}else{echo("");}?>";
        var contents = <? if(isset($_GET['contents'])){echo$_GET['contents'];}else{echo("0");}?>;
        var state = <? if(isset($_GET['state'])){echo$_GET['state'];}else{echo("0");}?>;
        var search_category = "<? if(isset($_GET['search_category'])){echo$_GET['search_category'];}else{echo("title");}?>";
        var search = "<? if(isset($_GET['search'])){echo$_GET['search'];}else{echo("");}?>";
        location.href = 'board_list.php?mc=게시글관리&sc=게시글%20목록&sort='+sort+'&category='+category+'&menu='+menu+'&contents='+contents+'&state='+state+'&search_category='+search_category+'&search='+search;
      });
    });

    //카테고리에 따른 메뉴명 보여주기
    $(function () {
      $('select[name=category]').change(function(){
        var category = $('select[name=category] option:selected').text();
        $.ajax({
          type : 'POST',
          dataType : 'html',
          url : '../sessions/load_menu.php',
          data : {category:category},
          success: function(data){
            $("select[name=menu] option").remove();
            $("select[name=menu]").append("<option value=''>------</option>");
            $("select[name=menu]").append(data);
          }
        });
      })
    })

    //검색초기화
    $(function () {
      $('#reset').click(function(){
        $("select[name=category] option:eq(0)").prop("selected",true);
        $("select[name=menu] option").remove();
        $("select[name=menu]").append("<option value=''>------</option>");
        $("input[name=contents]:eq(0)").prop("checked",true);
        $("input[name=state]:eq(0)").prop("checked",true);
        $("select[name=search_category] option:eq(0)").prop("selected",true);
        $("input[name=search]").val("");
      })
    })

    //검색
    $(function () {
      $('#search').click(function(){
        var sort = <? if(isset($_GET['sort'])){echo$_GET['sort'];}else{echo("1");}?>;
        var category = $("select[name=category] option:selected").val();
        var menu = $("select[name=menu] option:selected").val();
        var contents = $("input[name=contents]:checked").val();
        var state = $("input[name=state]:checked").val();
        var search_category = $("select[name=search_category] option:selected").val();
        var search = $("input[name=search]").val();
        location.href = 'board_list.php?mc=게시글관리&sc=게시글%20목록&sort='+sort+'&category='+category+'&menu='+menu+'&contents='+contents+'&state='+state+'&search_category='+search_category+'&search='+search;
      })
    })

    //검색 로딩
    $(function () {
      var category = "<? if(isset($_GET['category'])){echo$_GET['category'];}else{echo("");}?>";
      var menu = "<? if(isset($_GET['menu'])){echo$_GET['menu'];}else{echo("");}?>";
      var contents = <? if(isset($_GET['contents'])){echo$_GET['contents'];}else{echo("0");}?>;
      var state = <? if(isset($_GET['state'])){echo$_GET['state'];}else{echo("0");}?>;
      var search_category = "<? if(isset($_GET['search_category'])){echo$_GET['search_category'];}else{echo("title");}?>";
      var search = "<? if(isset($_GET['search'])){echo$_GET['search'];}else{echo("");}?>";
      $("input[name=contents][value="+contents+"]").prop("checked",true);
      $("input[name=state][value="+state+"]").prop("checked",true);
      $("select[name=search_category] option[value="+search_category+"]").prop("selected",true);
      $("input[name=search]").val(search);
    })

    $(function () {
      $('select[name=category]').change(function(){
        if($('select[name=category] option:checked').text() == "IoT"){
          $('input[name=contents][value=2]').prop("checked",true);
        }else{
          $('input[name=contents][value=0]').prop("checked",true);
        }
      })
    })
    </script>
</head>

<body>
  <?php
  $sc=$_GET['sc'];
  $page=$_GET['page'];
  ?>
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
    <?php
    include "../layout/sidebar.php";
    ?>
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- end left sidebar -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- wrapper  -->
    <!-- ============================================================== -->
    <div class="dashboard-wrapper">
      <div class="container-fluid  dashboard-content">
        <!-- ============================================================== -->
        <!-- pageheader -->
        <!-- ============================================================== -->
        <?php
        include "../layout/pageheader.php";
        ?>
        <div class="row">
        <!-- ============================================================== -->
        <!-- valifation types -->
        <!-- ============================================================== -->

          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
              <div class="card-body">
                <div style="padding-bottom:10px">
                  <strong>검색 설정</strong>
                </div>
                <div class="search">
                  <div class="type" style="padding:8px;">
                    <label style="float:left; width:25%;text-align:center;">분류</label>
                    <select class="form-control form-control-sm" name="category" style="float:left; margin-right:20px; width:120px">
                      <option value="">------</option>
                      <?
                      include "../sessions/db.php";
                      $db = new DBC;
                      $db->DBI();
                      $sql= "SELECT distinct category FROM menu_list where state <> 4 ";
                      $db->DBQ($sql);
                      $db->DBE();
                      while($cate=$db->DBF()){
                        if($cate['category'] == $_GET['category']){?>
                          <option value="<?=$cate['category']?>"selected><?=$cate['category']?></option>
                      <? }else{?>
                          <option value="<?=$cate['category']?>"><?=$cate['category']?></option>
                      <? }?>
                    <? }?>
                    </select>
                    <select class="form-control form-control-sm" name="menu" style="width:120px">
                      <option value="">------</option>
                      <?
                      $sql2="SELECT menu_num, menu FROM menu_list where category='{$_GET['category']}' and state <> 4 ";
                      $db->DBQ($sql2);
                      $db->DBE();
                      while($menu=$db->DBF()){
                        if($menu['menu_num'] == $_GET['menu']){?>
                          <option value="<?=$menu['menu_num']?>" selected><?=$menu['menu']?></option>
                      <? }else{?>
                          <option value="<?=$menu['menu_num']?>"><?=$menu['menu']?></option>
                      <? }?>
                    <? }?>
                    </select>
                  </div>
                  <div class="contents" style="padding:8px;">
                    <label style="float:left; width:25%; text-align:center;">컨텐츠 타입</label>
                    <input type="radio" name="contents" value="0" checked>모두
                    <input style="margin-left:10%" type="radio" name="contents" value="1">유튜브
                    <input style="margin-left:10%" type="radio" name="contents" value="2">이미지
                  </div>
                  <div class="state" style="padding:8px;">
                    <label style="float:left; width:25%; text-align:center;">상태</label>
                    <input type="radio" name="state" value="0" checked>모두
                    <input style="margin-left:10%" type="radio" name="state" value="1">미발행
                    <input style="margin-left:10%" type="radio" name="state" value="2">발행
                  </div>
                  <div class="search_bar" style="padding:8px; overflow:hidden">
                    <label style="float:left; width:25%; text-align:center;">검색</label>
                    <select class="form-control form-control-sm" style="float:left; width:70px" name="search_category">
                      <option value="title">제목</option>
                      <option value="user">작성자</option>
                    </select>
                    <input class="form-control" style="float:left; margin-left:20px; width:250px" type="text" name="search" placeholder="Search...">
                  </div>
                  <button style="float:right; margin:8px" type="button" id="reset" class="btn btn-info"><i class="fas fa-undo-alt"></i></button>
                  <button style="float:right; margin:8px"type="button" id="search" class="btn btn-primary">검색</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered first" id="table" style="text-align:center">
                    <thead>
                      <tr>
                        <th width="5%">삭제</th>
                        <th width="5%">번호</th>
                        <th width="10%">카테고리</th>
                        <th width="10%">메뉴</th>
                        <th width="25%">제목</th>
                        <th width="10%">컨텐츠</th>
                        <th width="10%">상태</th>
                        <th width="8%">작성자</th>
                        <th width="15%">작성시간</th>
                      </tr>
                    </thead>
                    <tbody>
                      <div style="padding-bottom:10px">
                        <strong>게시글 목록</strong>
                      </div>
                      <div style="float:right; margin-bottom: 5px;">
                        <label style="float:left; margin-right:10px">sort by :</label>
                        <select class="form-control form-control-sm" name="sort" style="width:100px;">
                          <option value="1">최신순</option>
                          <option value="2">분류순</option>
                          <option value="3">상태순</option>
                        </select>
                        <a href="board_list.php?mc=게시글관리&sc=게시글%20목록"><button style="float:right; margin:8px"type="button"class="btn btn-secondary"><i class="fas fa-undo-alt"></i></button></a>
                      </div>
                      <?
                      if(!isset($_GET['page'])){
                        $page = 1;
                      }else {
                        $page = $_GET['page'];
                      } //페이지 번호
                      $max_row=10;
                      $row_start=$page*$max_row-$max_row;

                      $a="";
                      if($_GET['category'] !=""){
                        $a .= " and category='{$_GET['category']}'";
                      }
                      if($_GET['menu'] !=""){
                        $a .= " and menu_num='{$_GET['menu']}'";
                      }
                      if($_GET['contents'] != "" && $_GET['contents'] != "0"){
                        $a .= " and con_source='{$_GET['contents']}'";
                      }
                      if($_GET['state'] != "" && $_GET['state'] != "0"){
                        $a .= " and b_state='{$_GET['state']}'";
                      }
                      if(isset($_GET['search'])){
                        if($_GET['search_category']=="title"){
                          $title = urlencode($_GET['search']);
                          $a .= " and title like '%{$title}%'";
                        }else{
                          $a .= " and (select id from member where member.idx=c.user) like '%{$_GET['search']}%'";
                        }
                      }

                      if($_GET['sort']==2){
                        $query = "SELECT idx,category,menu,title,menu_num,(select id from member where member.idx=c.user) as id, con_source, b_state, datetime FROM board_list c natural join menu_list where b_state <> 4$a ORDER BY category desc, menu asc,idx desc limit $row_start,$max_row ";
                      }else if($_GET['sort']==3){
                        $query = "SELECT idx,category,menu,title,menu_num,(select id from member where member.idx=c.user) as id, con_source, b_state, datetime FROM board_list c natural join menu_list where b_state <> 4$a ORDER BY b_state desc, category desc, menu asc, idx desc limit $row_start,$max_row";
                      }else{
                        $query = "SELECT idx,category,menu,title,menu_num,(select id from member where member.idx=c.user) as id, con_source, b_state, datetime FROM board_list c natural join menu_list where b_state <> 4$a ORDER BY idx desc limit $row_start,$max_row ";
                      }

                      $db->DBQ($query);
                      $db->DBE();
                      $count = $row_start;
                      while($list=$db->DBF()){
                        $count++; ?>
                        <!-- 발행/미발행용 폼태그 -->
                        <input type="hidden" id="menu_num<?=$list['idx']?>" value="<?=$list['menu_num']?>">
                        <tr>
                          <td><input type="checkbox" name="checkbox" value="<?=$list['idx']?>"></td>
                          <td><a href="board_detail.php?mc=게시글관리&sc=게시글%20상세&idx=<?=$list['idx']?>"><?=$count?></a></td>
                          <td><?=$list['category']?></td>
                          <td><?=$list['menu']?></td>
                          <td><a class="link" href="board_detail.php?mc=게시글관리&sc=게시글%20상세&idx=<?=$list['idx']?>"><?=urldecode($list['title'])?></a></td>
                          <td>
                          <?
                            if($list['con_source'] == '1'){echo("유튜브");} else{echo"이미지";}
                          ?>
                          </td>
                          <td>
                            <select class="form-control form-control-sm" style="width:70px; float:left;" id="activ<?=$list['idx']?>">
                              <?php if($list['b_state'] == '1'){?>
                                <option value="1" selected>미발행</option>
                                <option value="2">발행</option>
                              <?php }else{?>
                                <option value="1">미발행</option>
                                <option value="2" selected>발행</option>
                              <? }?>
                            </select>
                            <button type="button" style="padding:5px" class="btn btn-primary" onclick="change_stat(<?=$list['idx']?>)">수정</button>
                          </td>
                          <td><?=$list['id']?></td>
                          <td><?=$list['datetime']?></td>
                        </tr>
                      <? }?>
                    </tbody>
                  </table>
                  <button class="btn btn-secondary" id="delete" style="float:right; margin-top:10px;">선택 삭제</button>
                  <a href="board_write.php?mc=게시글관리&sc=게시글쓰기" class="btn btn-primary" style="float:right; margin-top:10px; margin-right:10px">글쓰기</a>
                </div>
              </div>
            </div>
            <nav aria-label="Page navigation example" style="">
              <ul class="pagination" style="justify-content: center">
                <?
                $a="";
                if($_GET['category'] !=""){
                  $a .= " and category='{$_GET['category']}'";
                }
                if($_GET['menu'] !=""){
                  $a .= " and menu_num='{$_GET['menu']}'";
                }
                if($_GET['contents'] != "" && $_GET['contents'] != "0"){
                  $a .= " and con_source='{$_GET['contents']}'";
                }
                if($_GET['state'] != "" && $_GET['state'] != "0"){
                  $a .= " and b_state='{$_GET['state']}'";
                }
                if(isset($_GET['search'])){
                  if($_GET['search_category']=="title"){
                    $a .= " and title like '%{$_GET['search']}%'";
                  }else{
                    $a .= " and (select id from member where member.idx=c.user) like '%{$_GET['search']}%'";
                  }
                }

                $query= "SELECT * FROM board_list c natural join menu_list where b_state <> 4$a";
                $db->DBQ($query);
                $db->DBE();
                $row = $db->resultRow();
                $db-> DBO();

                if($row == "0"){
                  $row = 1;
                }

                $fin_num=ceil(ceil($row/$max_row/5));
                $row_list_start=ceil($page/5);
                $sort = $_GET['sort'];
                $contents = $_GET['contents'];
                $state= $_GET['state'];
                if($sort==""){
                  $sort=1;
                }
                if($contents==""){
                  $contents=0;
                }
                if ($state=="") {
                  $state = 0;
                }
                if ($page/5>1) {?>
                  <li class="page-item"><a class="page-link" href="board_list.php?mc=<?=$_GET['mc']?>&sc=<?=$_GET['sc']?>&page=<?=$row_list_start*5-9?>&sort=<?=$sort?>&category=<?=$_GET['category']?>&menu=<?=$_GET['menu']?>&contents=<?=$contents?>&state=<?=$state?>&search_category=<?=$_GET['search_category']?>&search=<?=$_GET['search']?>" aria-label="Previous">
                    <span aria-hidden="ture">&laquo;</span><span class="sr-only">Previous</span></a>
                  </li><?
                }
                for ($i=$row_list_start*5-4; ($i<=$row_list_start*5)&&($i<=ceil($row/$max_row)); $i++) {
                  if ($page==$i) {?>
                    <li class="page-item active"><a class="page-link"><?=$i?></a></li><?
                  }else {?>
                    <li class="page-item"><a class="page-link" href="board_list.php?mc=<?=$_GET['mc']?>&sc=<?=$_GET['sc']?>&page=<?=$i?>&sort=<?=$sort?>&category=<?=$_GET['category']?>&menu=<?=$_GET['menu']?>&contents=<?=$contents?>&state=<?=$state?>&search_category=<?=$_GET['search_category']?>&search=<?=$_GET['search']?>"><?=$i?></a></li>
                  <?}
                }
                if($row_list_start!=$fin_num) {?>
                  <li class="page-item"><a class="page-link" href="board_list.php?mc=<?=$_GET['mc']?>&sc=<?=$_GET['sc']?>&page=<?=$row_list_start*5+1?>&sort=<?=$sort?>&category=<?=$_GET['category']?>&menu=<?=$_GET['menu']?>&contents=<?=$contents?>&state=<?=$state?>&search_category=<?=$_GET['search_category']?>&search=<?=$_GET['search']?>" aria-label="Next"><span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span></a></li>
                <?}?>
              </ul>
            </nav>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- end valifation types -->
        <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- footer -->
      <!-- ============================================================== -->
      <?php
      include "../layout/footer.php";
      ?>
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- end main wrapper -->
  <!-- ============================================================== -->
  <!-- Optional JavaScript -->
</body>

</html>
