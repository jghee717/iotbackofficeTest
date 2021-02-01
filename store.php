<?php
include 'layout/layout.php';
include 'api/dbconn.php';
include 'api/pageClass.php';
//include 'api/common.php';
include 'api/selectbox.php';
$conn = new DBC();
$conn->DBI();
$layout = new Layout;


//상태 인증매장 미등록매장 검색 쿼리
if($_GET['condition'] == 인증매장 && $_GET['condition1'] == 미등록매장 && $_GET['condition2'] == null){
  $condition = "";
}
if($_GET['condition'] == null && $_GET['condition1'] == 미등록매장 && $_GET['condition2'] == null){
  $condition = "and c.pos_code IS NULL";
}
if($_GET['condition'] == 인증매장 && $_GET['condition1'] == null && $_GET['condition2'] == null){
  $condition = "and c.pos_code IS not NULL";
}
if($_GET['condition'] == null && $_GET['condition1'] == null && $_GET['condition2'] == null){
  $condition = '';
}
if($_GET['condition2'] == 미설치매장 && $_GET['condition'] == null && $_GET['condition1'] == null){
  $condition ='';
}

//날짜
$date_from = date($_GET['date_from']);
$date_to = date($_GET['date_to']);


// 검색 쿼리
if($_GET['condition2'] == 미설치매장 && $_GET['condition'] == 인증매장 && $_GET['condition1'] == 미등록매장){
  if($date_from == $date_to && $date_from != null && $date_to != null){
    $searchDate = "and d.time like '".$date_from."%'";}
    else if ($date_from == null && $date_to == null) {
      $searchDate = "";
    }
    else {
      $searchDate = " and d.time between '".$date_from."' and '".$date_to."'";}
    }

    else if($_GET['condition2'] == 미설치매장 && $_GET['condition'] == 인증매장 && $_GET['condition1'] == null){
      if($date_from == $date_to && $date_from != null && $date_to != null){
        $searchDate = "and d.time like '".$date_from."%'";}
        else if ($date_from == null && $date_to == null) {
          $searchDate = "";
        }
        else {
          $searchDate = " and d.time between '".$date_from."' and '".$date_to."'";}
        }

        else if($_GET['condition2'] == 미설치매장 && $_GET['condition'] == null && $_GET['condition1'] == null){
          $searchDate ='';
        }

        else if($_GET['condition2'] == null && $_GET['condition'] == 인증매장 && $_GET['condition1'] == 미등록매장){
          if($date_from == $date_to && $date_from != null && $date_to != null){
            $searchDate = "and d.time like '".$date_from."%'";}
            else if ($date_from == null && $date_to == null) {
              $searchDate = "";
            }
            else {
              $searchDate = " and d.time between '".$date_from."' and '".$date_to."'";}
            }

            else if($date_from == $date_to && $date_from != null && $date_to != null){
              $searchDate = "and d.time like '".$date_from."%'";}
              else if ($date_from == null && $date_to == null) {
                $searchDate = "";
              }
              else {
                $searchDate = " and d.time between '".$date_from."' and '".$date_to."'";
              }

              $searchSql;
              if($_GET['channel'] != "" && $_GET['bg_code'] != "" && $_GET['mg_code'] != "" && $_GET['search'] != "" && $_GET['search_content'] != ""){
                $channel = $_GET['channel'];
                $bg_code = $_GET['bg_code'];
                $mg_code = $_GET['mg_code'];
                if($_GET['search'] == 전체){
                  $search = "c.agency_name like '%".$_GET['search_content']. "%' or c.agency_code like  '%".$_GET['search_content']. "%' or c.pos_name like '%".$_GET['search_content']. "%' or c.pos_code like   '%".$_GET['search_content']. "%' or d.pos_id like   '%".$_GET['search_content']. "%' or c.pos_address";
                }else if ($_GET['search'] == 'c.pos_code') {
                  $search = "c.pos_code like  '%".$_GET['search_content']. "%' or d.pos_id";
                }
                else{
                  $search = $_GET['search'];}
                  $search_content = $_GET['search_content'];
                  $sCase = 11;
                }
                else if($_GET['channel'] != "" && $_GET['bg_code'] != "" && $_GET['search'] != "" && $_GET['search_content'] != ""){
                  $channel = $_GET['channel'];
                  $bg_code = $_GET['bg_code'];
                  if($_GET['search'] == 전체){
                    $search = "c.agency_name like '%".$_GET['search_content']. "%' or c.agency_code like  '%".$_GET['search_content']. "%' or c.pos_name like '%".$_GET['search_content']. "%' or c.pos_code like   '%".$_GET['search_content']. "%' or d.pos_id like   '%".$_GET['search_content']. "%' or c.pos_address";
                  }else if ($_GET['search'] == 'c.pos_code') {
                    $search = "c.pos_code like  '%".$_GET['search_content']. "%' or d.pos_id";
                  }
                  else{
                    $search = $_GET['search'];}
                    $search_content = $_GET['search_content'];
                    $sCase = 10;
                  }
                  else if($_GET['channel'] != "" && $_GET['mg_code'] != "" && $_GET['search'] != "" && $_GET['search_content'] != ""){
                    $channel = $_GET['channel'];
                    $mg_code = $_GET['mg_code'];
                    if($_GET['search'] == 전체){
                      $search = "c.agency_name like '%".$_GET['search_content']. "%' or c.agency_code like  '%".$_GET['search_content']. "%' or c.pos_name like '%".$_GET['search_content']. "%' or c.pos_code like   '%".$_GET['search_content']. "%' or d.pos_id like   '%".$_GET['search_content']. "%' or c.pos_address";
                    }else if ($_GET['search'] == 'c.pos_code') {
                      $search = "c.pos_code like  '%".$_GET['search_content']. "%' or d.pos_id";
                    }
                    else{
                      $search = $_GET['search'];}
                      $search_content = $_GET['search_content'];
                      $sCase = 9;
                    }
                    else if($_GET['channel'] != "" && $_GET['bg_code'] != "" && $_GET['mg_code'] != ""){
                      $channel = $_GET['channel'];
                      $bg_code = $_GET['bg_code'];
                      $mg_code = $_GET['mg_code'];
                      $sCase = 8;
                    }
                    else if($_GET['channel'] != "" && $_GET['search'] !="" && $_GET['search_content'] != ""){
                      $channel = $_GET['channel'];
                      if($_GET['search'] == 전체){
                        $search = "c.agency_name like '%".$_GET['search_content']. "%' or c.agency_code like  '%".$_GET['search_content']. "%' or c.pos_name like '%".$_GET['search_content']. "%' or c.pos_code like   '%".$_GET['search_content']. "%' or d.pos_id like   '%".$_GET['search_content']. "%' or c.pos_address";
                      }else if ($_GET['search'] == 'c.pos_code') {
                        $search = "c.pos_code like  '%".$_GET['search_content']. "%' or d.pos_id";
                      }
                      else{
                        $search = $_GET['search'];}
                        $search_content = $_GET['search_content'];
                        $sCase = 7;
                      }
                      else if($_GET['mg_code'] != "" && $_GET['search'] !="" && $_GET['search_content'] != ""){
                        $mg_code = $_GET['mg_code'];
                        if($_GET['search'] == 전체){
                          $search = "c.agency_name like '%".$_GET['search_content']. "%' or c.agency_code like  '%".$_GET['search_content']. "%' or c.pos_name like '%".$_GET['search_content']. "%' or c.pos_code like   '%".$_GET['search_content']. "%' or d.pos_id like   '%".$_GET['search_content']. "%' or c.pos_address";
                        }else if ($_GET['search'] == 'c.pos_code') {
                          $search = "c.pos_code like  '%".$_GET['search_content']. "%' or d.pos_id";
                        }
                        else{
                          $search = $_GET['search'];}
                          $search_content = $_GET['search_content'];
                          $sCase = 6;
                        }
                        else if($_GET['search'] != "" && $_GET['search_content'] != ""){
                          if($_GET['search'] == 전체){
                            $search = "c.agency_name like '%".$_GET['search_content']. "%' or c.agency_code like  '%".$_GET['search_content']. "%' or c.pos_name like '%".$_GET['search_content']. "%' or c.pos_code like   '%".$_GET['search_content']. "%' or d.pos_id like   '%".$_GET['search_content']. "%' or c.pos_address";
                          }else if ($_GET['search'] == 'c.pos_code') {
                            $search = "c.pos_code like  '%".$_GET['search_content']. "%' or d.pos_id";
                          }
                          else {
                            $search = $_GET['search'];}
                            $search_content = $_GET['search_content'];
                            $sCase = 5;
                          }
                          else if($_GET['channel'] != "" && $_GET['bg_code'] != ""){
                            $channel = $_GET['channel'];
                            $bg_code = $_GET['bg_code'];
                            $sCase = 4;
                          }
                          else if($_GET['channel'] != "" && $_GET['mg_code'] != ""){
                            $channel = $_GET['channel'];
                            $mg_code = $_GET['mg_code'];
                            $sCase = 3;
                          }
                          else if($_GET['channel'] != ""){
                            $channel = $_GET['channel'];
                            $sCase = 2;
                          }
                          else if($_GET['mg_code'] != ""){
                            $mg_code = $_GET['mg_code'];
                            $sCase = 1;
                          }


                          switch($sCase)
                          {
                            case 11:
                            $searchSql = "  REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "' and c.bg_code = '" .$bg_code. "'  and c.mg_code = '" .$mg_code. "' and (".$search." like '%" .$search_content. "%')";
                            break;

                            case 10:
                            $searchSql = "  REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "' and c.bg_code = '" .$bg_code. "'and (".$search." like '%" .$search_content. "%')";
                            break;

                            case 9:
                            $searchSql = " REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "' and c.mg_code = '" .$mg_code. "' and (".$search." like '%" .$search_content. "%')";
                            break;

                            case 8:
                            $searchSql = "  REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "' and c.bg_code = '" .$bg_code. "'  and mg_code = '" .$mg_code. "'";
                            break;

                            case 7:
                            $searchSql = "  REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "' and (".$search." like '%" .$search_content. "%')";
                            break;

                            case 6:
                            $searchSql = "  c.mg_code = '" .$mg_code. "' and (".$search." like '%" .$search_content. "%')";
                            break;

                            case 5:
                            $searchSql = "  (".$search." like '%".$search_content."%')";
                            break;

                            case 4:
                            $searchSql = "  REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "' and c.bg_code = '" .$bg_code. "'";
                            break;

                            case 3:
                            $searchSql = "  REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "' and c.mg_code = '" .$mg_code. "'";
                            break;

                            case 2:
                            $searchSql = "  REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "'";
                            break;

                            case 1:
                            $searchSql = "  c.mg_code = '" .$mg_code. "'";
                            break;

                            default:
                            if($_GET['condition'] == 인증매장 && $_GET['condition1'] == 미등록매장 && $_GET['condition2'] == 미설치매장 ){
                              $searchSql = "(c.pos_code IS not NULL or c.pos_code IS NULL)";}

                              else if($_GET['condition'] == null && $_GET['condition1'] == 미등록매장 && $_GET['condition2'] == 미설치매장 ){
                                $searchSql = "(c.pos_code IS NULL OR c.pos_code IS NOT NULL)";
                              }
                              else if($_GET['condition'] == 인증매장 && $_GET['condition1'] == 미등록매장 && $_GET['condition2'] == null ){

                                $searchSql = "  (c.pos_code IS not NULL or c.pos_code IS NULL)";
                              }

                              else if($_GET['condition'] == null && $_GET['condition1'] == 미등록매장 && $_GET['condition2'] == null ){
                                $searchSql = " c.pos_code IS NULL";
                              }
                              else if($_GET['condition'] == null && $_GET['condition1'] == null && $_GET['condition2'] == 미설치매장 ){
                                $searchSql = "d.pos_id IS null";
                              }else {
                                $searchSql = " c.pos_code IS not NULL";
                              }

                              break;
                            }


                            //페이징
                            //인증매장 , 미등록매장, 미설치매장
                            if($_GET['condition2'] == 미설치매장 && $_GET['condition'] == 인증매장 && $_GET['condition1'] == 미등록매장){
                              $query ="SELECT * from
                              (select
                                c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                FROM
                                (
                                  SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                  FROM
                                  did_log_type_1 AS `pos_exec`
                                  WHERE
                                  pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                  GROUP BY pos_exec.pos_id
                                )AS `d`
                                left OUTER JOIN
                                did_pos_code AS `c`
                                ON d.pos_id = c.pos_code
                                where $searchSql $searchDate) DUMMY_ALIAS1
                                UNION
                                SELECT * from
                                (select
                                  c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                  c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                  FROM did_pos_code AS `c`
                                  left OUTER JOIN
                                  (
                                    SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                    FROM
                                    did_log_type_1 AS `pos_exec`
                                    WHERE
                                    pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                    GROUP BY pos_exec.pos_id
                                  )AS `d`
                                  ON d.pos_id = c.pos_code
                                  where d.pos_id IS NULL AND $searchSql) DUMMY_ALIAS2";}

                                  //인증매장, 미설치매장
                                  else if($_GET['condition2'] == 미설치매장 && $_GET['condition'] == 인증매장 && $_GET['condition1'] == null){
                                    $query = "SELECT * FROM
                                    (select
                                      c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                      c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                      FROM
                                      (
                                        SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                        FROM
                                        did_log_type_1 AS `pos_exec`
                                        WHERE
                                        pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                        GROUP BY pos_exec.pos_id
                                      )AS `d`
                                      left OUTER JOIN
                                      did_pos_code AS `c`
                                      ON d.pos_id = c.pos_code
                                      WHERE c.pos_code IS not NULL and $searchSql $searchDate
                                    ) DUMMY_ALIAS1
                                    UNION
                                    SELECT * FROM (SELECT
                                      c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                      c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                      FROM did_pos_code AS `c`
                                      left OUTER JOIN
                                      (
                                        SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                        FROM
                                        did_log_type_1 AS `pos_exec`
                                        WHERE
                                        pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                        GROUP BY pos_exec.pos_id
                                      )AS `d`
                                      ON d.pos_id = c.pos_code
                                      WHERE d.pos_id IS null and $searchSql
                                    ) DUMMY_ALIAS2
                                    order BY 등록일 desc, 매장코드 is null ASC ";}

                                    //미설치매장
                                    else if ($_GET['condition2'] == '미설치매장' && $_GET['condition'] == null && $_GET['condition1'] == null) {
                                      $query = "SELECT
                                      c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                      c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                      FROM did_pos_code AS `c`
                                      left OUTER JOIN
                                      (
                                        SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                        FROM
                                        did_log_type_1 AS `pos_exec`
                                        WHERE
                                        pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                        GROUP BY pos_exec.pos_id
                                      )AS `d`
                                      ON d.pos_id = c.pos_code
                                      WHERE $searchSql $condition $searchDate ";}

                                      //미설치매장, 미등록매장
                                      else if ($_GET['condition2'] == '미설치매장' && $_GET['condition'] == null && $_GET['condition1'] == 미등록매장) {
                                        $query = "SELECT * FROM (SELECT
                                          c.pos_code AS '매장코드',  d.pos_id AS '포스코드',REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                          c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                          FROM did_pos_code AS `c`
                                          left OUTER JOIN
                                          (
                                            SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                            FROM
                                            did_log_type_1 AS `pos_exec`
                                            WHERE
                                            pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                            GROUP BY pos_exec.pos_id
                                          )AS `d`
                                          ON d.pos_id = c.pos_code
                                          WHERE d.pos_id IS NULL and $searchSql
                                        ) DUMMY_ALIAS1
                                        UNION all
                                        SELECT * FROM(
                                          select
                                          c.pos_code AS '매장코드',  d.pos_id AS '포스코드',REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                          c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                          FROM
                                          (
                                            SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                            FROM
                                            did_log_type_1 AS `pos_exec`
                                            WHERE
                                            pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                            GROUP BY pos_exec.pos_id
                                          )AS `d`
                                          left OUTER JOIN
                                          did_pos_code AS `c`
                                          ON d.pos_id = c.pos_code
                                          WHERE c.pos_code IS null and $searchSql $searchDate) DUMMY_ALIAS2";}

                                          //인증매장 ,미등록매장
                                          else{
                                            $query = "SELECT
                                            c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                            c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                            FROM
                                            (
                                              SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                              FROM
                                              did_log_type_1 AS `pos_exec`
                                              WHERE
                                              pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                              GROUP BY pos_exec.pos_id
                                            )AS `d`
                                            left OUTER JOIN
                                            did_pos_code AS `c`
                                            ON d.pos_id = c.pos_code
                                            where  $searchSql $condition $searchDate";}

                                            $conn->DBQ($query);
                                            $conn->DBE(); //쿼리 실행
                                            $cnt = $conn->resultRow();
                                            $total_row = $cnt;		// db에 저장된 게시물의 레코드 총 갯수 값. 현재 값은 테스트를 위한 값
                                            if($_GET['list'] == null) {
                                              $list = 10;							// 화면에 보여질 게시물 갯수
                                            } else {
                                              $list = $_GET['list'];  // 화면에 보여질 게시물 갯수
                                            }$block = 5;							// 화면에 보여질 블럭 단위 값[1]~[5]
                                            $page = new paging($_GET['page'], $list, $block, $total_row);
                                            if(isset($channel) or isset($bg_code) or isset($mg_code) or isset($_GET['condition']) or isset($_GET['condition1']) or isset($_GET['condition2']) or isset($date_from) or isset($date_to) or isset($_GET['search']) or isset($_GET['search_content']) or isset($list)){
                                              $page->setUrl("channel=".$channel."&bg_code=".$bg_code."&mg_code=".$mg_code."&condition=".$_GET['condition']."&condition1=".$_GET['condition1']."&condition2=".$_GET['condition2'].
                                              "&date_from=".$date_from."&date_to=".$date_to."&search=".$_GET['search']."&search_content=".$_GET['search_content']."&list=".$list);
                                            }

                                            $limit = $page->getVar("limit");	// 가져올 레코드의 시작점을 구하기 위해 값을 가져온다. 내부로직에 의해 계산된 값

                                            $page->setDisplay("prev_btn", "<"); // [이전]버튼을 [prev] text로 변경
                                            $page->setDisplay("next_btn", ">"); // 이와 같이 버튼을 이미지로 바꿀수 있음
                                            $page->setDisplay("end_btn", ">>");
                                            $page->setDisplay("start_btn", "<<");
                                            $page->setDisplay("class","page-item");
                                            $page->setDisplay("full");
                                            $paging = $page->showPage();

                                            //datepicker period
                                            $curDate = date('Y-m-d');
                                            ?>

                                            <script type="text/javascript">
                                            //인젝션 정규식
                                            function nospecialKey()
                                            {
                                              var re = /select|union|insert|update|delete|drop|[\'\"|#|\/\*|\*\/|\\\|\;]/gi;
                                              var input=$("#search_content").val();
                                              if(re.test(input) != false)
                                              {
                                                alert("입력 불가능한 문자가 있습니다.");
                                                $("#search_content").focus();
                                                return false;
                                              }
                                            }
                                            </script>

                                            <!doctype html>
                                            <html class="no-js" lang="kr">
                                            <?$layout->CssJsFile('<link href="assets/css/table-responsive.css" rel="stylesheet">');?>
                                            <?$layout->head($head);?>

                                            <body class="body-bg">
                                              <!-- preloader area start -->
                                              <div id="preloader">
                                                <div class="loader"></div>
                                              </div>
                                              <!-- preloader area end -->

                                              <!-- main wrapper start -->
                                              <form name='form' class="col-lg-12" method="get" action="<?=$_SERVER['PHP_SELF']?>" onsubmit="return nospecialKey()">
                                                <div class="horizontal-main-wrapper">
                                                  <?$layout->mainHeader($mainHeader);?>
                                                  <?$layout->header($header);?><br>
                                                  <!-- page title area end -->
                                                  <div class="main-content-inner">
                                                    <div class="container">
                                                      <div class="row">
                                                        <div class="col-lg-6"><h5>매장관리</h5></div>
                                                        <div class="col-lg-6" style="text-align: right;"><small>  Main > 매장관리</small></div>
                                                        <style>
                                                        form{border:1px solid #E6E6E6;}
                                                        hr{margin:1px;}
                                                        </style>
                                                        <html><hr color="black" width=100%></html>
                                                        <div class="card col-lg-12 mt-3">
                                                          <div class="card-body">
                                                            <!-- form start -->
                                                            <div class="input-group">
                                                              <!-- 영업담당 -->
                                                              <html><hr color="#E6E6E6" class="mt-2" width=100%></html>
                                                              <span style="margin-left:15px; background-color: #FFFFFF;" name="span" id="span" class="input-group-text form-control2 form-control-sm col-lg-1" >영업담당</span>
                                                              <div class="col-lg-2">
                                                                <select style="background-color: #E9ECEF" name="channel" class="form-control form-control-sm" id="selectID">
                                                                  <option value="">선택</option>
                                                                  <?
                                                                  $query = "SELECT distinct(replace(CHANNEL,'홈/미디어','스마트홈')) as 'channel' FROM did_pos_code  where channel is not NULL and CHANNEL != ''";
                                                                  $conn->DBQ($query);
                                                                  $conn->DBE(); //쿼리 실행
                                                                  while ($option = $conn->DBF()) {  ?>
                                                                    <option <?if($_GET['channel'] == $option['channel']){echo "selected";}?> value="<?echo $option['channel'];?>"><?echo $option['channel'];?></option>
                                                                    <?}?>
                                                                  </select>
                                                                </div>
                                                                <!-- 지원팀 -->
                                                                <span style="margin-left:15px; background-color: #FFFFFF;" name="span" id="span" class="input-group-text form-control2 form-control-sm col-lg-1" >지원팀</span>
                                                                <div class="col-lg-2">
                                                                  <select style="background-color: #E9ECEF" name="bg_code" class="form-control form-control-sm" id="good">
                                                                    <option value="">전체</option>
                                                                    <?
                                                                    if ($_GET['channel'] != null){$query = "SELECT bg_code FROM did_pos_code where replace(CHANNEL,'홈/미디어','스마트홈') = '".$_GET['channel']."' GROUP BY bg_code";
                                                                      $conn->DBQ($query);
                                                                      $conn->DBE();
                                                                      while ($option1 = $conn->DBF()) {  ?>
                                                                        <option <?if($_GET['bg_code'] == $option1['bg_code']){echo "selected";}?> value="<?echo $option1['bg_code'];?>"><?echo $option1['bg_code'];?></option>
                                                                        <?} }?>
                                                                      </select>
                                                                    </div>
                                                                  </div>
                                                                  <html><hr color="#E6E6E6" width=100%></html>
                                                                  <!-- 투자유형 -->
                                                                  <div class="input-group">
                                                                    <html><hr color="#E6E6E6" class="mt-2" width=100%></html>
                                                                    <span style="margin-left:15px; background-color: #FFFFFF;" name="span" id="span" class="input-group-text form-control2 form-control-sm col-lg-1" >투자유형</span>
                                                                    <div class="col-lg-2">
                                                                      <select style="background-color: #E9ECEF" name="mg_code" class="form-control form-control-sm col-lg-12">
                                                                        <option value="">선택항목</option>
                                                                        <?
                                                                        $query = "SELECT mg_code FROM did_pos_code WHERE mg_code is not null AND mg_code != '' GROUP BY mg_code";
                                                                        $conn->DBQ($query);
                                                                        $conn->DBE(); //쿼리 실행
                                                                        while ($option = $conn->DBF()) {  ?>
                                                                          <option <?if($_GET['mg_code'] == $option['mg_code']){echo "selected";}?> value="<?echo $option['mg_code'];?>"><?echo $option['mg_code'];?></option>
                                                                          <?}?>
                                                                        </select>
                                                                      </div>
                                                                      <!-- 상태 -->
                                                                      <span style="margin-left:15px; background-color: #FFFFFF;" name="span" id="span" class="input-group-text form-control2 form-control-sm col-lg-1" >상태</span>
                                                                      <div class="col-lg-3">
                                                                        <div class="custom-control custom-checkbox custom-control-inline">
                                                                          <input name="condition" type="checkbox" <?if($_GET['condition'] == "인증매장"){echo "checked";}else if ($_GET['condition1'] == "미등록매장"
                                                                          and $_GET['condition'] != "인증매장") { }else if($_GET['condition2'] == "미설치매장" and $_GET['condition'] != 인증매장){}else if($_GET['condition'] == null) {echo "checked";}?> class="custom-control-input" id="customCheck5" value="인증매장">
                                                                          <label class="custom-control-label" for="customCheck5">인증매장</label>
                                                                        </div>
                                                                        <div class="custom-control custom-checkbox custom-control-inline">
                                                                          <input name="condition1" type="checkbox"  <?if($_GET['condition1'] == "미등록매장"){echo "checked";}?> class="custom-control-input" id="customCheck6" value="미등록매장">
                                                                          <label class="custom-control-label" for="customCheck6">미등록매장</label>
                                                                        </div>
                                                                        <div class="custom-control custom-checkbox custom-control-inline">
                                                                          <input name="condition2" type="checkbox"  <?if($_GET['condition2'] == "미설치매장"){echo "checked";}?> class="custom-control-input" id="customCheck7" value="미설치매장">
                                                                          <label class="custom-control-label" for="customCheck7">미설치매장</label>
                                                                        </div>
                                                                      </div>
                                                                      <!--등록일-->
                                                                      <span style="margin-left:15px; background-color: #FFFFFF;" name="span" id="span" class="input-group-text form-control2 form-control-sm col-lg-1" >등록일</span>
                                                                      <div class="col-lg-3">

                                                                        <div class="input-group">
                                                                          <input data-toggle="datepicker" type="text" class="form-control form-control-sm" id="date_from" name="date_from" value="<?echo $date_from;?>" readonly="readonly">
                                                                          <div class="input-group-prepend">
                                                                            <div class="form-control form-control-sm input-group-text">~</div>
                                                                          </div>
                                                                          <input data-toggle="datepicker" type="text" class="form-control form-control-sm" id="date_to" name="date_to" value="<?echo $date_to;?>" readonly="readonly">
                                                                        </div>
                                                                      </div>
                                                                    </div>
                                                                    <html><hr color="#E6E6E6" width=100%></html>
                                                                    <html><hr color="#E6E6E6" class="mt-2" width=100%></html>
                                                                    <!--검색 -->

                                                                    <div class="input-group">
                                                                      <span style="margin-left:15px; background-color: #FFFFFF;" name="span" id="span" class="input-group-text form-control2 form-control-sm col-lg-1" >검색어</span>
                                                                      <div class="col-lg-2">
                                                                        <select style="background-color: #E9ECEF" name="search" class="form-control form-control-sm col-lg-12">
                                                                          <option value="전체">전체</option>
                                                                          <option <?if($_GET['search'] == "c.agency_name"){echo "selected";}?> value="c.agency_name">운영자명</option>
                                                                          <option <?if($_GET['search'] == "c.agency_code"){echo "selected";}?> value="c.agency_code">운영자코드</option>
                                                                          <option <?if($_GET['search'] == "c.pos_name"){echo "selected";}?> value="c.pos_name">매장명</option>
                                                                          <option <?if($_GET['search'] == "c.pos_code"){echo "selected";}?> value="c.pos_code">매장코드</option>
                                                                          <option <?if($_GET['search'] == "c.pos_address"){echo "selected";}?> value="c.pos_address">매장주소</option>
                                                                        </select>
                                                                      </div>
                                                                      <div class="col-lg-4">
                                                                        <input  style="background-color: #E9ECEF" class="form-control form-control-sm" type="text" onchange="zero_store()" id="search_content" name="search_content"
                                                                        value='<?if($_GET['search_content'] != null){?><?echo $_GET['search_content'];}?>'>
                                                                      </div>
                                                                    </div>
                                                                    <html><hr color="#E6E6E6" width=100%></html>

                                                                    <!--리셋-->
                                                                    <div class="input-group mt-3">
                                                                      <div class="col-lg-5">
                                                                        <button class="btn btn-lg mr-2 btn btn-xs" style="display: none " type="reset" name="btn-reset" onclick="categoryChange(this)"><i class="fa fa-refresh"></i></button></div>
                                                                        <button class="btn btn-primary btn btn-xs" style="text-align:center;" type="submit" id="searchButton">검색</button>
                                                                      </div><br>

                                                                      <!-- 테이블 -->
                                                                      <div class="row mt-4">
                                                                        <div class="col-lg-10"><p>total:<?echo $cnt;?>  </p></div>
                                                                        <div class="col-lg-1" style="">
                                                                          <select class="form-control form-control-sm" id="list" name='list'>
                                                                            <option value="10" <?if($_GET['list'] == 10){echo "selected";}else if($_GET['list'] == null){echo "selected";}?>>10</option>
                                                                            <option value="20" <?if($_GET['list'] == 20){echo "selected";}?>>20</option>
                                                                            <option value="30" <?if($_GET['list'] == 30){echo "selected";}?>>30</option>
                                                                          </select>
                                                                        </div>
                                                                        <div class="form-group text-right">
                                                                          <a href="api/storeReg/store_excel.php?channel=<?echo $_GET['channel'];?>&bg_code=<?echo $_GET['bg_code'];?>&mg_code=<?echo $_GET['mg_code'];?>&condition=<?echo $_GET['condition'];?>&condition1=<?echo $_GET['condition1'];?>&condition2=<?echo $_GET['condition2'];?>&date_from=<?echo $date_from;?>&date_to=<?echo $date_to;?>&search=<?echo $_GET['search'];?>&search_content=<?echo $_GET['search_content'];?>">
                                                                            <button type="button" class="btn btn-xs text-right" id="searchButton"><i class="fa fa-download"></i>데이터 저장</button>
                                                                          </a>
                                                                          </div>
                                                                          <!-- form end -->

                                                                        </small></span></div>
                                                                        <div class="col-lg-12">
                                                                          <section id="no-more-tables">
                                                                            <br><table class="table table-bordered text-center">
                                                                              <thead class="text-uppercase">
                                                                                <tr style="text-align:center;font-size:13px;">
                                                                                  <th width="1" class="numeric">NO.</th>
                                                                                  <th width="77" class="numeric">영업담당</th>
                                                                                  <th width="80" class="numeric">지원팀</th>
                                                                                  <th width="77" class="numeric">투자유형</th>
                                                                                  <th width="118" class="numeric">운영자명</th>
                                                                                  <th width="98" class="numeric">운영자코드</th>
                                                                                  <th width="120" class="numeric">매장명</th>
                                                                                  <th width="85" class="numeric">매장코드</th>
                                                                                  <th class="numeric">매장주소</th>
                                                                                  <th width="60"class="numeric">상태</th>
                                                                                  <th width="70" class="numeric">등록일</th>
                                                                                  <th width="77" class="numeric">최종접속일</th>
                                                                                </tr>
                                                                              </thead>
                                                                              <tbody>
                                                                                <?
                                                                                if($_GET['page'] == null){
                                                                                  $i = 0;
                                                                                } else if($_GET['list'] == 10 && $_GET['page'] != null){
                                                                                  $i = $_GET['list'] * ($_GET['page'] - 1);
                                                                                } else if($_GET['list'] == 20 && $_GET['page'] != null) {
                                                                                  $i = $_GET['list'] * ($_GET['page'] - 1);
                                                                                } else if($_GET['list'] == 30 && $_GET['page'] != null) {
                                                                                  $i = $_GET['list'] * ($_GET['page'] - 1 );
                                                                                }

                                                                                // 인증매장 미등록매장 미설치매장
                                                                                if($_GET['condition2'] == 미설치매장 && $_GET['condition'] == 인증매장 && $_GET['condition1'] == 미등록매장){
                                                                                  $sql =   "SELECT * from
                                                                                  (select
                                                                                    c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                                                                    c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                                                                    FROM
                                                                                    (
                                                                                      SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                                                                      FROM
                                                                                      did_log_type_1 AS `pos_exec`
                                                                                      WHERE
                                                                                      pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                                                                      GROUP BY pos_exec.pos_id
                                                                                    )AS `d`
                                                                                    left OUTER JOIN
                                                                                    did_pos_code AS `c`
                                                                                    ON d.pos_id = c.pos_code
                                                                                    where $searchSql $searchDate) DUMMY_ALIAS1
                                                                                    UNION
                                                                                    SELECT * from
                                                                                    (select
                                                                                      c.pos_code AS '매장코드',  d.pos_id AS '포스코드',REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                                                                      c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                                                                      FROM did_pos_code AS `c`
                                                                                      left OUTER JOIN
                                                                                      (
                                                                                        SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                                                                        FROM
                                                                                        did_log_type_1 AS `pos_exec`
                                                                                        WHERE
                                                                                        pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                                                                        GROUP BY pos_exec.pos_id
                                                                                      )AS `d`
                                                                                      ON d.pos_id = c.pos_code
                                                                                      where d.pos_id IS NULL AND $searchSql) DUMMY_ALIAS2
                                                                                      order by 매장코드 is null asc, 등록일 desc, 영업담당 asc, 지원팀 asc, 투자유형 asc, 운영자명 asc, 운영자코드 asc, 매장명 asc, 매장주소 asc
                                                                                      limit $limit, $list";}

                                                                                      //인증매장 미설치매장
                                                                                      else if($_GET['condition2'] == 미설치매장 && $_GET['condition'] == 인증매장 && $_GET['condition1'] == null){
                                                                                        $sql = "SELECT * FROM ( select
                                                                                          c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                                                                          c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                                                                          FROM
                                                                                          (
                                                                                            SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                                                                            FROM
                                                                                            did_log_type_1 AS `pos_exec`
                                                                                            WHERE
                                                                                            pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                                                                            GROUP BY pos_exec.pos_id
                                                                                          )AS `d`
                                                                                          left OUTER JOIN
                                                                                          did_pos_code AS `c`
                                                                                          ON d.pos_id = c.pos_code
                                                                                          WHERE c.pos_code  IS not NULL and $searchSql $searchDate
                                                                                        ) DUMMY_ALIAS1
                                                                                        WHERE 매장코드  IS not NULL
                                                                                        UNION
                                                                                        SELECT * FROM (SELECT
                                                                                          c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                                                                          c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                                                                          FROM did_pos_code AS `c`
                                                                                          left OUTER JOIN
                                                                                          (
                                                                                            SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                                                                            FROM
                                                                                            did_log_type_1 AS `pos_exec`
                                                                                            WHERE
                                                                                            pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                                                                            GROUP BY pos_exec.pos_id
                                                                                          )AS `d`
                                                                                          ON d.pos_id = c.pos_code
                                                                                          WHERE d.pos_id IS null and $searchSql
                                                                                        ) DUMMY_ALIAS2
                                                                                        order by 매장코드 is null asc, 등록일 desc, 영업담당 asc, 지원팀 asc, 투자유형 asc, 운영자명 asc, 운영자코드 asc, 매장명 asc, 매장주소 asc
                                                                                        limit $limit, $list";}

                                                                                        //미설치매장,
                                                                                        else if ($_GET['condition2'] == 미설치매장 && $_GET['condition'] == null && $_GET['condition1'] == null) {
                                                                                          $sql = "SELECT
                                                                                          c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                                                                          c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                                                                          FROM did_pos_code AS `c`
                                                                                          left OUTER JOIN
                                                                                          (
                                                                                            SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                                                                            FROM
                                                                                            did_log_type_1 AS `pos_exec`
                                                                                            WHERE
                                                                                            pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                                                                            GROUP BY pos_exec.pos_id
                                                                                          )AS `d`
                                                                                          ON d.pos_id = c.pos_code
                                                                                          WHERE $searchSql $condition $searchDate
                                                                                           order by c.pos_code is null asc, d.time desc, c.CHANNEL asc, c.bg_code ASC, c.mg_code ASC, c.agency_name  ASC, c.agency_code ASC, c.agency_name ASC, c.agency_code ASC, c.pos_name  ASC, c.pos_address asc
                                                                                          limit $limit, $list"; }

                                                                                          //미설치매장, 미등록매장
                                                                                          else if ($_GET['condition2'] == '미설치매장' && $_GET['condition'] == null && $_GET['condition1'] == 미등록매장) {
                                                                                            $sql = "SELECT * FROM (SELECT
                                                                                              c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                                                                              c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                                                                              FROM did_pos_code AS `c`
                                                                                              left OUTER JOIN
                                                                                              (
                                                                                                SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                                                                                FROM
                                                                                                did_log_type_1 AS `pos_exec`
                                                                                                WHERE
                                                                                                pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                                                                                GROUP BY pos_exec.pos_id
                                                                                              )AS `d`
                                                                                              ON d.pos_id = c.pos_code
                                                                                              WHERE d.pos_id IS NULL and $searchSql
                                                                                            ) DUMMY_ALIAS1
                                                                                            UNION all
                                                                                            SELECT * FROM(
                                                                                              select
                                                                                              c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                                                                              c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                                                                              FROM
                                                                                              (
                                                                                                SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                                                                                FROM
                                                                                                did_log_type_1 AS `pos_exec`
                                                                                                WHERE
                                                                                                pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                                                                                GROUP BY pos_exec.pos_id
                                                                                              )AS `d`
                                                                                              left OUTER JOIN
                                                                                              did_pos_code AS `c`
                                                                                              ON d.pos_id = c.pos_code
                                                                                              WHERE c.pos_code IS null and $searchSql $searchDate) DUMMY_ALIAS2
                                                                                              order by 매장코드 is null asc, 등록일 desc, 영업담당 asc, 지원팀 asc, 투자유형 asc, 운영자명 asc, 운영자코드 asc, 매장명 asc, 매장주소 asc
                                                                                              limit $limit, $list";}

                                                                                              // 미등록매장 인증매장 따로
                                                                                              else {
                                                                                                $sql =
                                                                                                "SELECT
                                                                                                c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
                                                                                                c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
                                                                                                FROM
                                                                                                (
                                                                                                  SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
                                                                                                  FROM
                                                                                                  did_log_type_1 AS `pos_exec`
                                                                                                  WHERE
                                                                                                  pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
                                                                                                  GROUP BY pos_exec.pos_id
                                                                                                )AS `d`
                                                                                                left OUTER JOIN
                                                                                                did_pos_code AS `c`
                                                                                                ON d.pos_id = c.pos_code
                                                                                                where $searchSql $condition $searchDate
                                                                                                 order by c.pos_code is null asc, d.time desc, c.CHANNEL asc, c.bg_code ASC, c.mg_code ASC, c.agency_name  ASC, c.agency_code ASC, c.agency_name ASC, c.agency_code ASC, c.pos_name  ASC, c.pos_address asc
                                                                                                limit $limit, $list";}

                                                                                                $conn->DBQ($sql);
                                                                                                $conn->DBE();

                                                                                                while ($store = $conn->DBF()) { ?>
                                                                                                  <tr style="text-align:center;font-size:13px;">
                                                                                                    <td><?echo $i+1;?></font></td>
                                                                                                    <td><a href="store_info.php?pos_code=<?php echo $store['매장코드'];?>&condition2=<?echo $_GET['condition2'];?>"><?if($store['영업담당'] == '홈/미디어'){?>스마트홈<?}else {echo $store['영업담당'];}?></a></font></td>
                                                                                                    <td><?echo $store['지원팀'];?></font></td>
                                                                                                    <td><?echo $store['투자유형'];?></font></td>
                                                                                                    <td><?echo $store['운영자명'];?></font></td>
                                                                                                    <td><?echo $store['운영자코드'];?></font></td>
                                                                                                    <td><?echo $store['매장명'];?></font></td>
                                                                                                    <td><?if($store['매장코드'] == null){echo $store['포스코드'];} else{echo $store['매장코드'];}?></font></td>
                                                                                                    <td><?echo $store['매장주소'];?>-</font></td>
                                                                                                    <td>
                                                                                                      <?php  if ($store['매장코드'] != null && $store['등록일'] != null) {?>
                                                                                                        인증매장<?}?>
                                                                                                        <?php if ($store['매장코드'] == null  && $store['등록일'] != null ) {?>
                                                                                                          미등록매장<?}?>
                                                                                                          <?php  if ($store['매장코드'] != null && $store['등록일'] == null ) {?>
                                                                                                            미설치매장<?}?>
                                                                                                          </td>
                                                                                                          <td><?echo $store['등록일'];?></td>
                                                                                                          <td><?echo $store['접속일'];?></td>
                                                                                                        </tr>
                                                                                                        <? $i ++; }?>
                                                                                                      </tbody>
                                                                                                    </table>
                                                                                                  </section>
                                                                                                </div>
                                                                                              </div>
                                                                                            </form>
                                                                                            <br>
                                                                                            <div class="text-center">
                                                                                              <ul class="pagination" style="justify-content: center;">
                                                                                                <?echo $paging; ?>
                                                                                              </ul>
                                                                                              <!-- 모달 버튼 -->
                                                                                              <!-- <div class="text-right" style="text-align:right">
                                                                                                <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#exampleModal">
                                                                                                  매장 csv파일 일괄 등록
                                                                                                </button>
                                                                                              </div> -->
                                                                                              <div>
                                                                                                <br>
                                                                                              </div>
                                                                                            </div>
                                                                                            <!-- <div class="card-body"> -->
                                                                                          </div>
                                                                                          <!-- <div class="card col-lg-12"> -->
                                                                                        </div>
                                                                                        <!-- <div class="row mt-2"> -->
                                                                                      </div>
                                                                                      <!-- <div class="container"> -->
                                                                                    </div>
                                                                                    <!-- <div class="main-content-inner"> -->
                                                                                    <!-- main wrapper end -->
                                                                                    <!-- Modal -->
                                                                                    <!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                      <div class="modal-dialog" role="document">
                                                                                        <div class="modal-content">
                                                                                          <div class="modal-header">
                                                                                            <h5 class="modal-title" id="exampleModalLabel">매장 일괄 등록</h5>
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                              <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                          </div>
                                                                                          <div class="modal-body">
                                                                                            <div class="form-group">
                                                                                              <table class="table table-bordered text-center">
                                                                                                <thead class="text-uppercase">
                                                                                                  <tr>
                                                                                                    <th style="text-align:center;" class="numeric">양식파일다운</th>
                                                                                                    <th style="text-align: left;" class="numeric">
                                                                                                      <a href="api/storeReg/csv_down.php">
                                                                                                        <button type="button" class="btn btn-xs text-right" id="searchButton"><i class="fa fa-download"></i>양식 파일 다운로드</button>
                                                                                                      </a></th>
                                                                                                    </tr>
                                                                                                  </thead>
                                                                                                  <tbody>
                                                                                                    <tr style="text-align:center;">
                                                                                                      <form method="post" name="form" action="api/storeReg/csv_upload.php" enctype="multipart/form-data" >
                                                                                                        <td class="numeric"><strong>파일 업로드</strong></td>
                                                                                                        <td class="numeric"><input multiple="multiple" type="file" name="upfile[]" id="upfile" accept=".csv"></td>
                                                                                                      </tr>
                                                                                                    </tbody>
                                                                                                  </table>
                                                                                                </div>
                                                                                              </div>
                                                                                              <div class="modal-footer">
                                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">취소</button>
                                                                                                <input type="submit" class="btn btn-primary" onclick="setConfirm()" value="등록">
                                                                                              </form>
                                                                                            </div>
                                                                                          </div>
                                                                                        </div>
                                                                                      </div> -->
                                                                                      <!-- Modal END -->
                                                                                      <?$layout->footer($footer);?>
                                                                                      <!-- main wrapper end -->
                                                                                      <?$layout->JsFile("");?>
                                                                                      <?$layout->js($js);?>
                                                                                      <?include 'api/datepicker.php';?>
                                                                                    </body>
                                                                                    </html>
