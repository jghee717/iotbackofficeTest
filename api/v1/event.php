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
              WHEN A.channel IN ('강북','강남')  THEN '1'
              WHEN A.channel IN ('강동', '동부') THEN '2'
              WHEN A.channel IN ('서부','홈/미디어') THEN '3' END AS `chan_group`," ;
    }else if($req['weekN'] == '2' ){
      $group_str = " CASE
              WHEN A.channel IN ('동부','강남')  THEN '1'
              WHEN A.channel IN ('강북', '서부') THEN '2'
              WHEN A.channel IN ('강동','홈/미디어') THEN '3' END AS `chan_group`," ;
    }else if($req['weekN'] == '3' ){
      $group_str = " CASE
              WHEN A.channel IN ('강남','서부')  THEN '1'
              WHEN A.channel IN ('동부', '홈/미디어') THEN '2'
              WHEN A.channel IN ('강동','강북') THEN '3' END AS `chan_group`," ;
    }else if($req['weekN'] == '4' ){
      $group_str = " CASE
              WHEN A.channel IN ('강남','강동')  THEN '1'
              WHEN A.channel IN ('서부', '동부') THEN '2'
              WHEN A.channel IN ('강북','홈/미디어') THEN '3' END AS `chan_group`," ;
    }

    // execPer = 설치율
    //  res1 = 사용율
    //  res2 = 사용횟수


    // exexVal =  실행률 점수
    // useVal =  사용률 점수
    //  useCntVal = 사용횟수 점수

    $data = $database->query("SELECT
          ".$group_str."
          A.channel,
          COUNT(A.pos_code),
          B.exe_cnt ,
          ROUND(B.exe_cnt / COUNT(A.pos_code) * 100,2) AS `execPer` ,
          ROUND(C.touch_cnt / B.exe_cnt * 100,2) AS `res1` ,
          ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2)  AS `res2` ,

          ROUND((B.exe_cnt / COUNT(A.pos_code) * 100) / 2, 2) AS `exexVal` ,
          ROUND(((C.touch_cnt / B.exe_cnt * 100) / 3.333) , 2) AS `useVal`,
          ROUND((D.start_cnt / B.exe_cnt * 1.7), 2) AS `useCntVal`,

          ROUND(((C.touch_cnt / B.exe_cnt * 100) / 3.333) + (D.start_cnt / B.exe_cnt * 1.7) , 1) AS `result`

        FROM did_pos_code AS A
        LEFT JOIN
        (

        		SELECT
        		  did_pos_code.channel
        		  ,COUNT(did_pos_code.pos_code) AS 'exe_cnt'
        		 FROM did_pos_code
        		LEFT JOIN
        		(
        		  SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
        		  FROM did_log_type_1
        		  WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$req['eDate']."'
        		  GROUP BY pos_id
        		)AS A
        		ON
        		 did_pos_code.pos_code = A.pos_id
        		 WHERE A.pos_id IS NOT NULL
        		 GROUP BY channel

        )AS B
        ON
        A.channel = B.channel
        LEFT JOIN
        (
          		SELECT
          		  did_pos_code.channel
          		  ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
          		 FROM did_pos_code
          		LEFT JOIN
          		(
                SELECT pos_id
                   FROM did_log_type_3
                WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$req['sDate']."' AND '".$req['eDate']."'
                 AND page_id = 'p900005'
                GROUP BY pos_id
                    UNION
          		  SELECT pos_id
          		  FROM did_log_type_4
          		  WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$req['sDate']."' AND '".$req['eDate']."'
          		  GROUP BY pos_id
          		)AS A
          		ON
          		 did_pos_code.pos_code = A.pos_id
          		 WHERE A.pos_id IS NOT NULL
          		 GROUP BY channel

        )AS C
          ON
            A.channel = C.channel
        LEFT JOIN
        (

          		SELECT
          				B.channel
          				, B.pos_code
          			,	SUM(B.start_cnt)  AS 'start_cnt'
          		FROM
          		(
            			SELECT
            			    did_pos_code.channel,
            			    did_pos_code.pos_code
            			    ,SUM(A.cnt) AS 'start_cnt'
            			   FROM did_pos_code
            			  LEFT JOIN
            			  (
            				SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) ,  COUNT(pos_id) AS `cnt`
            				   FROM did_log_type_3
            				WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$req['sDate']."' AND '".$req['eDate']."'
            				 AND page_id = 'p900005'
            				GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
            				   UNION ALL
            				SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
            				   FROM did_log_type_4
            				WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$req['sDate']."' AND '".$req['eDate']."'
            				GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
            			  )AS A
            			  ON
            			   did_pos_code.pos_code = A.pos_id
            			   WHERE A.pos_id IS NOT NULL
            			   GROUP BY did_pos_code.pos_code
            		)AS B
            		GROUP BY B.channel

        )AS D
        ON
        A.channel = D.channel
        GROUP BY A.channel
        ORDER BY chan_group
    ")->fetchAll();

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
  }else if($method == "POST"){
    $data = $database->select('did_event', array('pos_code','pos_name','TIMESTAMP'), array('TIMESTAMP[~]' => $req["date"]));
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
  }
