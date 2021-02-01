<?php
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: *");

  $method = $_SERVER["REQUEST_METHOD"];
  $req = $_REQUEST;

  include 'db_config.php';

  if($method == "GET"){
    $group_str = "";

    if($req['weekN'] == '1'){
      $group_str = " CASE
              WHEN install.channel IN ('강북','강남')  THEN '1'
              WHEN install.channel IN ('강동', '동부') THEN '2'
              WHEN install.channel IN ('서부','홈/미디어') THEN '3' END AS `chan_group`," ;
    }else if($req['weekN'] == '2' ){
      $group_str = " CASE
              WHEN install.channel IN ('동부','강남')  THEN '1'
              WHEN install.channel IN ('강북', '서부') THEN '2'
              WHEN install.channel IN ('강동','홈/미디어') THEN '3' END AS `chan_group`," ;
    }else if($req['weekN'] == '3' ){
      $group_str = " CASE
              WHEN install.channel IN ('강남','서부')  THEN '1'
              WHEN install.channel IN ('동부', '홈/미디어') THEN '2'
              WHEN install.channel IN ('강동','강북') THEN '3' END AS `chan_group`," ;
    }else if($req['weekN'] == '4' ){
      $group_str = " CASE
              WHEN install.channel IN ('강남','강동')  THEN '1'
              WHEN install.channel IN ('서부', '동부') THEN '2'
              WHEN install.channel IN ('강북','홈/미디어') THEN '3' END AS `chan_group`," ;
    }

    $data = $database->query("SELECT
          ".$group_str."
          install.CHANNEL,
          exe.cnt AS '실행수',
          a.cnt AS '매장수',
          install.cnt AS '설치수',
          ROUND((install.cnt / a.cnt)*100, 2) AS '설치율',
          IFNULL(ROUND((exe.cnt / install.cnt)*100, 2),'-') AS '실행율',
          IFNULL((ROUND(exe.cnt / install.cnt, 2)*30),'-') AS '환산30',
          total_exe.start_cnt AS '총사용횟수',
          IFNULL(ROUND(total_exe.start_cnt / install.cnt, 2),'-') '평균사용횟수',
          IFNULL((ROUND(exe.cnt / install.cnt, 2) * 30) + (ROUND(total_exe.start_cnt / install.cnt, 2) *  1.7),'-') AS '환산70'
          FROM
          (
            SELECT CHANNEL, COUNT(pos_code) AS 'cnt'
            FROM did_pos_code
            WHERE CHANNEL IS NOT null
            GROUP BY CHANNEL
          )a
          LEFT JOIN
          (
            SELECT did_pos_code.CHANNEL, COUNT(did_pos_code.pos_code) AS 'cnt'
             FROM did_pos_code
             LEFT JOIN
             (
                SELECT pos_id
                FROM did_log_type_1
                WHERE DATE(TIMESTAMP) <= '".$req['eDate']."'
                GROUP BY pos_id
             )AS A ON did_pos_code.pos_code = A.pos_id
             WHERE A.pos_id IS NOT NULL AND did_pos_code.CHANNEL IS NOT NULL
             GROUP BY channel
          )install ON a.CHANNEL = install.channel
          LEFT JOIN
          (
             SELECT did_pos_code.CHANNEL ,COUNT(did_pos_code.pos_code) AS 'cnt'
             FROM did_pos_code
             LEFT JOIN
             (
               SELECT pos_id
               FROM did_log_type_3
                WHERE DATE(TIMESTAMP) BETWEEN '".$req['sDate']."' AND '".$req['eDate']."'
                AND page_id = 'p900005'
                GROUP BY pos_id
                UNION
                SELECT pos_id
                FROM did_log_type_4
                WHERE DATE(TIMESTAMP) BETWEEN '".$req['sDate']."' AND '".$req['eDate']."'
                GROUP BY pos_id
             )AS A ON did_pos_code.pos_code = A.pos_id
             WHERE A.pos_id IS NOT NULL AND did_pos_code.CHANNEL IS NOT NULL
             GROUP BY CHANNEL
          )exe ON install.CHANNEL = exe.CHANNEL
          LEFT JOIN
          (
            SELECT B.CHANNEL, B.pos_code, SUM(B.start_cnt)  AS 'start_cnt'
             FROM
             (
                SELECT did_pos_code.CHANNEL, did_pos_code.pos_code, SUM(A.cnt) AS 'start_cnt'
                FROM did_pos_code
                LEFT JOIN
                (
                  SELECT pos_id, DATE(TIMESTAMP), COUNT(pos_id) AS `cnt`
                  FROM did_log_type_3
                  WHERE DATE(TIMESTAMP) BETWEEN '".$req['sDate']."' AND '".$req['eDate']."'
                  AND page_id = 'p900005'
                  GROUP BY pos_id, DATE(TIMESTAMP)
                  UNION ALL
                  SELECT pos_id, DATE(TIMESTAMP), COUNT(pos_id)  AS `cnt`
                  FROM did_log_type_4
                  WHERE DATE(TIMESTAMP) BETWEEN '".$req['sDate']."' AND '".$req['eDate']."'
                  GROUP BY pos_id, DATE(TIMESTAMP)
                )AS A
                ON did_pos_code.pos_code = A.pos_id WHERE A.pos_id IS NOT NULL GROUP BY did_pos_code.pos_code
             )AS B
             GROUP BY B.CHANNEL
            )total_exe ON exe.channel = total_exe.CHANNEL
            WHERE install.CHANNEL IS NOT NULL ANd install.CHANNEL != ''
            ORDER BY chan_group
    ")->fetchAll();

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
  }else if($method == "POST"){
    $data = $database->select('did_event', array('pos_code','pos_name','TIMESTAMP'), array('TIMESTAMP[~]' => $req["date"]));
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
  }
