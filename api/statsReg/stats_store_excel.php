<?php
require_once('../../assets/PHPExcel/Classes/PHPExcel.php');
include '../common.php';
include '../dbconn.php';


$filename = iconv("UTF-8", "EUC-KR", "조직별 통계(".$date_from." - ".$date_to.")");



$conn = new DBC();
$conn->DBI();



$objPHPExcel = new PHPExcel();



$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(24);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(35);

$objPHPExcel->getActiveSheet()->getStyle("A1:J1")->getFont()->setBold(true);



$sql = "
SELECT
          A.channel,
          COUNT(A.pos_code) AS '매장수',
          B.exe_cnt AS '설치수',
          B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율' ,
          C.touch_cnt / COUNT(A.pos_code) * 100  AS '사용율' ,
          C.touch_cnt AS '실행수',
          D.start_cnt  AS '사용횟수',
          ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'


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
        WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
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
                    WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                     AND page_id = 'p900005'
                    GROUP BY pos_id
                        UNION
                      SELECT pos_id
                      FROM did_log_type_4
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                      GROUP BY pos_id
                    )AS A
                    ON
                     did_pos_code.pos_code = A.pos_id
                     WHERE A.pos_id IS NOT NULL
                     GROUP BY CHANNEL

        )AS C
        ON
        A.channel = C.channel
        LEFT JOIN
        (

      SELECT
            B.channel
            , B.pos_code
         ,   SUM(B.start_cnt)  AS 'start_cnt'
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
            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
             AND page_id = 'p900005'
            GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
               UNION ALL
            SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
               FROM did_log_type_4
            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
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
        WHERE A.channel IS NOT NULL
        GROUP BY A.channel
        ORDER BY A.channel
";
$conn->DBQ($sql);
$conn->DBE();
$resCnt = $conn->resultRow();

$store = 0;
$install = 0;
$execute = 0;
$use = 0;
$peruse = 0 ;
$store2 = 0;
$install2 = 0;
$execute2 = 0;
$use2 = 0;

$row = $conn->DBP();

for($i=0; $i<$resCnt; $i++){
  $store += $row[$i]['매장수'];
}
for($i=0; $i<$resCnt; $i++){
  $install += $row[$i]['설치수'];
}
for($i=0; $i<$resCnt; $i++){
  $execute += $row[$i]['실행수'];
}
for($i=0; $i<$resCnt; $i++){
  $use += $row[$i]['사용횟수'];
}
for($i=0; $i<$resCnt; $i++){
  $peruse += $row[$i]['평균사용률'];
}

$objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue("A1", "NO.")
          ->setCellValue("B1", "영업담당")
          ->setCellValue("C1", "매장수")
          ->setCellValue("D1", "설치수")
          ->setCellValue("E1", "설치율")
          ->setCellValue("F1", "실행수")
          ->setCellValue("G1", "실행율")
          ->setCellValue("H1", "총 사용횟수")
					->setCellValue("I1", "매장당 평균 사용횟수");



          $i=0;
          $count = 2;
      for($j=0; $j<$resCnt; $j++){

        if($row[$j]['channel'] == '홈/미디어')
        {
          $data1 = '스마트홈';
        }
        else
        {
          $data1 = $row[$j]['channel'];
        }

        if($row[$j]['매장수'] == null)
        {
          $data2 = '-';
        }
        else
        {
          $data2 = number_format($row[$j]['매장수']);
        }

        if($row[$j]['설치수'] == null)
        {
          $data3 = '-';
        }
        else
        {
          $data3 = number_format($row[$j]['설치수']);
        }

        if($row[$j]['설치율'] == null)
        {
          $data4 = '-';
        }
        else
        {
          $data4 = number_format($row[$j]['설치율'],2).'%';
        }

        if($row[$j]['실행수'] == null)
        {
          $data5 = '-';
        }
        else
        {
          $data5 = number_format($row[$j]['실행수']);
        }

        if(number_format(($row[$j]['실행수']/$row[$j]['설치수'])*100,2) == nan)
        {
          $data6 = '-';
        }
        else
        {
          $data6 = number_format(($row[$j]['실행수']/$row[$j]['설치수'])*100,2).'%';
        }

        if($row[$j]['사용횟수'] == null)
        {
          $data7 = '-';
        }
        else
        {
          $data7 = number_format($row[$j]['사용횟수']);
        }
				if($row[$j]['평균사용률'] == null)
				{
					$data8 = '-';
				}
				else
				{
					$data8 = $row[$j]['평균사용률'];
				}



        $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue("A$count", $i+1)
                  ->setCellValue("B$count", $data1)
                  ->setCellValue("C$count", $data2)
                  ->setCellValue("D$count", $data3)
                  ->setCellValue("E$count", $data4)
                  ->setCellValue("F$count", $data5)
                  ->setCellValue("G$count", $data6)
                  ->setCellValue("H$count", $data7)
									->setCellValue("I$count", $data8);

                  $i++;
                  $count++;
                }
                $count2 = $count;

                if(number_format(($install/$store)*100,1) == nan)
                {
                  $data = '-';
                }
                else
                {
                  $data = number_format(($install/$store)*100,1).'%';
                }

                if(number_format(($execute/$install)*100,1) == nan)
                {
                  $data2 = '-';
                }
                else
                {
                  $data2 = number_format(($execute/$install)*100,1).'%';
                }

                $objPHPExcel->getActiveSheet()->getStyle("A$count2:I$count2")->getFont()->setBold(true);

                $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells("A$count2:B$count2")
                          ->setCellValue("A$count2", "합계")

                          ->setCellValue("C$count2", number_format($store))
                          ->setCellValue("D$count2", number_format($install))
                          ->setCellValue("E$count2", $data)
                          ->setCellValue("F$count2", number_format($execute))
                          ->setCellValue("G$count2", $data2)
                          ->setCellValue("H$count2", number_format($use))
													->setCellValue("I$count2", number_format(($peruse/$resCnt),2));

                          $count3 = $count2+2;



                          $objPHPExcel->getActiveSheet()->getStyle("A$count3:J$count3")->getFont()->setBold(true);
                          $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue("A$count3", "NO.")
                                    ->setCellValue("B$count3", "영업담당")
                                    ->setCellValue("C$count3", "지원팀")
                                    ->setCellValue("D$count3", "매장수")
                                    ->setCellValue("E$count3", "설치수")
                                    ->setCellValue("F$count3", "설치율")
                                    ->setCellValue("G$count3", "실행수")
                                    ->setCellValue("H$count3", "실행율")
                                    ->setCellValue("I$count3", "총 사용횟수")
																		->setCellValue("J$count3", "매장당 평균 사용횟수");



        $sql = "
        SELECT
                  A.channel,
                  A.bg_code,
                  COUNT(A.pos_code) AS '매장수',
                  B.exe_cnt AS '설치수',
                  B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율' ,
                  C.touch_cnt / COUNT(A.pos_code) * 100  AS '사용율' ,
                  C.touch_cnt AS '실행수',
                  D.start_cnt  AS '사용횟수',
                  ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'
        FROM did_pos_code AS A
        LEFT JOIN
        (
          SELECT did_pos_code.channel
                  ,COUNT(did_pos_code.pos_code) AS 'exe_cnt'
                  ,did_pos_code.bg_code
           FROM did_pos_code
           LEFT JOIN
           (
              SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
              FROM did_log_type_1
              WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
              GROUP BY pos_id
           )AS A
           ON did_pos_code.pos_code = A.pos_id
           WHERE A.pos_id IS NOT NULL AND CHANNEL = '강남'
           GROUP BY bg_code
        )AS B ON A.bg_code = B.bg_code
        LEFT JOIN
        (
           SELECT did_pos_code.channel
                  ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                  ,did_pos_code.bg_code
           FROM did_pos_code
           LEFT JOIN
           (
              SELECT pos_id
              FROM did_log_type_3
              WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
              AND page_id = 'p900005'
              GROUP BY pos_id
              UNION
              SELECT pos_id
              FROM did_log_type_4
              WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
              GROUP BY pos_id
           )AS A ON did_pos_code.pos_code = A.pos_id
           WHERE A.pos_id IS NOT NULL AND CHANNEL = '강남'
           GROUP BY bg_code
        )AS C ON A.bg_code = C.bg_code
        LEFT JOIN
        (
           SELECT B.channel
                  ,B.pos_code
                  ,SUM(B.start_cnt)  AS 'start_cnt'
                  ,B.bg_code
           FROM
           (
              SELECT did_pos_code.channel
                  ,did_pos_code.pos_code
                    ,SUM(A.cnt) AS 'start_cnt'
                    ,did_pos_code.bg_code
              FROM did_pos_code
              LEFT JOIN
              (
                 SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) ,  COUNT(pos_id) AS `cnt`
                 FROM did_log_type_3
                 WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                 AND page_id = 'p900005'
                 GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                 UNION ALL
                 SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                 FROM did_log_type_4
                 WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                 GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
              )AS A ON did_pos_code.pos_code = A.pos_id
              WHERE A.pos_id IS NOT NULL AND CHANNEL = '강남'
              GROUP BY did_pos_code.bg_code
           )AS B
           GROUP BY B.bg_code
        )AS D ON A.bg_code = D.bg_code
        WHERE A.channel IS NOT NULL AND A.CHANNEL != '' AND A.CHANNEL = '강남'
        GROUP BY A.bg_code
        ORDER BY A.bg_code
        ";
       $conn->DBQ($sql);
       $conn->DBE();
       $j = 0;
       $count4 = $count3+1;
       while($row=$conn->DBF()){


         if($row['channel'] == '홈/미디어')
         {
           $data1 = '스마트홈';
         }
         else
         {
           $data1 = $row['channel'];
         }

         if($row['매장수'] == null)
         {
           $data2 = '-';
         }
         else
         {
           $data2 = number_format($row['매장수']);
         }

         if($row['설치수'] == null)
         {
           $data3 = '-';
         }
         else
         {
           $data3 = number_format($row['설치수']);
         }

         if($row['설치율'] == null)
         {
           $data4 = '-';
         }
         else
         {
           $data4 = number_format($row['설치율'], 2).'%';
         }

         if($row['실행수'] == null)
         {
           $data5 = '-';
         }
         else
         {
           $data5 = number_format($row['실행수']);
         }

         if(number_format(($row['실행수']/$row['설치수'])*100,2) == nan)
         {
           $data6 = '-';
         }
         else
         {
           $data6 = number_format(($row['실행수']/$row['설치수'])*100,2).'%';
         }

         if($row['사용횟수']==null)
         {
           $data7 = '-';
         }
         else
         {
           $data7 = $row['사용횟수'];
         }

				 if($row['평균사용률']==null)
				 {
					 $data8 = '-';
				 }
				 else
				 {
					 $data8 = $row['평균사용률'];
				 }


         $objPHPExcel->setActiveSheetIndex(0)
                   ->setCellValue("A$count4", $j+1)
                   ->setCellValue("B$count4", $data1)
                   ->setCellValue("C$count4", $row['bg_code'])
                   ->setCellValue("D$count4", $data2)
                   ->setCellValue("E$count4", $data3)
                   ->setCellValue("F$count4", $data4)
                   ->setCellValue("G$count4", $data5)
                   ->setCellValue("H$count4", $data6)
                   ->setCellValue("I$count4", $data7)
									 ->setCellValue("J$count4", $data8);


                   $j++;
                   $count4++;
                 }

                  $count5 = $count4+1;
                 $objPHPExcel->getActiveSheet()->getStyle("A$count5:J$count5")->getFont()->setBold(true);
                 $objPHPExcel->setActiveSheetIndex(0)
                           ->setCellValue("A$count5", "NO.")
                           ->setCellValue("B$count5", "영업담당")
                           ->setCellValue("C$count5", "지원팀")
                           ->setCellValue("D$count5", "매장수")
                           ->setCellValue("E$count5", "설치수")
                           ->setCellValue("F$count5", "설치율")
                           ->setCellValue("G$count5", "실행수")
                           ->setCellValue("H$count5", "실행율")
                           ->setCellValue("I$count5", "총 사용횟수")
													 ->setCellValue("J$count5", "매장당 평균 사용횟수");



       $sql = "
       SELECT
                 A.channel,
                 A.bg_code,
                 COUNT(A.pos_code) AS '매장수',
                 B.exe_cnt AS '설치수',
                 B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율' ,
                 C.touch_cnt / COUNT(A.pos_code) * 100  AS '사용율' ,
                 C.touch_cnt AS '실행수',
                 D.start_cnt  AS '사용횟수',
                 ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'
       FROM did_pos_code AS A
       LEFT JOIN
       (
         SELECT did_pos_code.channel
                 ,COUNT(did_pos_code.pos_code) AS 'exe_cnt'
                 ,did_pos_code.bg_code
          FROM did_pos_code
          LEFT JOIN
          (
             SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
             FROM did_log_type_1
             WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
             GROUP BY pos_id
          )AS A
          ON did_pos_code.pos_code = A.pos_id
          WHERE A.pos_id IS NOT NULL AND CHANNEL = '강동'
          GROUP BY bg_code
       )AS B ON A.bg_code = B.bg_code
       LEFT JOIN
       (
          SELECT did_pos_code.channel
                 ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                 ,did_pos_code.bg_code
          FROM did_pos_code
          LEFT JOIN
          (
             SELECT pos_id
             FROM did_log_type_3
             WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
             AND page_id = 'p900005'
             GROUP BY pos_id
             UNION
             SELECT pos_id
             FROM did_log_type_4
             WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
             GROUP BY pos_id
          )AS A ON did_pos_code.pos_code = A.pos_id
          WHERE A.pos_id IS NOT NULL AND CHANNEL = '강동'
          GROUP BY bg_code
       )AS C ON A.bg_code = C.bg_code
       LEFT JOIN
       (
          SELECT B.channel
                 ,B.pos_code
                 ,SUM(B.start_cnt)  AS 'start_cnt'
                 ,B.bg_code
          FROM
          (
             SELECT did_pos_code.channel
                 ,did_pos_code.pos_code
                   ,SUM(A.cnt) AS 'start_cnt'
                   ,did_pos_code.bg_code
             FROM did_pos_code
             LEFT JOIN
             (
                SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) ,  COUNT(pos_id) AS `cnt`
                FROM did_log_type_3
                WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                AND page_id = 'p900005'
                GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                UNION ALL
                SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                FROM did_log_type_4
                WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
             )AS A ON did_pos_code.pos_code = A.pos_id
             WHERE A.pos_id IS NOT NULL AND CHANNEL = '강동'
             GROUP BY did_pos_code.bg_code
          )AS B
          GROUP BY B.bg_code
       )AS D ON A.bg_code = D.bg_code
       WHERE A.channel IS NOT NULL AND A.CHANNEL != '' AND A.CHANNEL = '강동'
       GROUP BY A.bg_code
       ORDER BY A.bg_code
       ";
       $conn->DBQ($sql);
       $conn->DBE();
         $j = 0;
         $count6 = $count5+1;
       while($row=$conn->DBF()){

         if($row['channel'] == '홈/미디어')
         {
           $data1 = '스마트홈';
         }
         else
         {
           $data1 = $row['channel'];
         }

         if($row['매장수'] == null)
         {
           $data2 = '-';
         }
         else
         {
           $data2 = number_format($row['매장수']);
         }

         if($row['설치수'] == null)
         {
           $data3 = '-';
         }
         else
         {
           $data3 = number_format($row['설치수']);
         }

         if($row['설치율'] == null)
         {
           $data4 = '-';
         }
         else
         {
           $data4 = number_format($row['설치율'], 2).'%';
         }

         if($row['실행수'] == null)
         {
           $data5 = '-';
         }
         else
         {
           $data5 = number_format($row['실행수']);
         }

         if(number_format(($row['실행수']/$row['설치수'])*100,2) == nan)
         {
           $data6 = '-';
         }
         else
         {
           $data6 = number_format(($row['실행수']/$row['설치수'])*100,2).'%';
         }

         if($row['사용횟수']==null)
         {
           $data7 = '-';
         }
         else
         {
           $data7 = $row['사용횟수'];
         }

				 if($row['평균사용률']==null)
				{
					$data8 = '-';
				}
				else
				{
					$data8 = $row['평균사용률'];
				}


         $objPHPExcel->setActiveSheetIndex(0)
                   ->setCellValue("A$count6", $j+1)
                   ->setCellValue("B$count6", $data1)
                   ->setCellValue("C$count6", $row['bg_code'])
                   ->setCellValue("D$count6", $data2)
                   ->setCellValue("E$count6", $data3)
                   ->setCellValue("F$count6", $data4)
                   ->setCellValue("G$count6", $data5)
                   ->setCellValue("H$count6", $data6)
                   ->setCellValue("I$count6", $data7)
									 ->setCellValue("J$count6", $data8);


                   $j++;
                   $count6++;
                 }
                 $count7 = $count6+1;
                $objPHPExcel->getActiveSheet()->getStyle("A$count7:J$count7")->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0)
                          ->setCellValue("A$count7", "NO.")
                          ->setCellValue("B$count7", "영업담당")
                          ->setCellValue("C$count7", "지원팀")
                          ->setCellValue("D$count7", "매장수")
                          ->setCellValue("E$count7", "설치수")
                          ->setCellValue("F$count7", "설치율")
                          ->setCellValue("G$count7", "실행수")
                          ->setCellValue("H$count7", "실행율")
                          ->setCellValue("I$count7", "총 사용횟수")
													->setCellValue("J$count7", "매장당 평균 사용횟수");


        $sql = "
        SELECT
                  A.channel,
                  A.bg_code,
                  COUNT(A.pos_code) AS '매장수',
                  B.exe_cnt AS '설치수',
                  B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율' ,
                  C.touch_cnt / COUNT(A.pos_code) * 100  AS '사용율' ,
                  C.touch_cnt AS '실행수',
                  D.start_cnt  AS '사용횟수',
                  ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'
        FROM did_pos_code AS A
        LEFT JOIN
        (
          SELECT did_pos_code.channel
                  ,COUNT(did_pos_code.pos_code) AS 'exe_cnt'
                  ,did_pos_code.bg_code
           FROM did_pos_code
           LEFT JOIN
           (
              SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
              FROM did_log_type_1
              WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
              GROUP BY pos_id
           )AS A
           ON did_pos_code.pos_code = A.pos_id
           WHERE A.pos_id IS NOT NULL AND CHANNEL = '강북'
           GROUP BY bg_code
        )AS B ON A.bg_code = B.bg_code
        LEFT JOIN
        (
           SELECT did_pos_code.channel
                  ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                  ,did_pos_code.bg_code
           FROM did_pos_code
           LEFT JOIN
           (
              SELECT pos_id
              FROM did_log_type_3
              WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
              AND page_id = 'p900005'
              GROUP BY pos_id
              UNION
              SELECT pos_id
              FROM did_log_type_4
              WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
              GROUP BY pos_id
           )AS A ON did_pos_code.pos_code = A.pos_id
           WHERE A.pos_id IS NOT NULL AND CHANNEL = '강북'
           GROUP BY bg_code
        )AS C ON A.bg_code = C.bg_code
        LEFT JOIN
        (
           SELECT B.channel
                  ,B.pos_code
                  ,SUM(B.start_cnt)  AS 'start_cnt'
                  ,B.bg_code
           FROM
           (
              SELECT did_pos_code.channel
                  ,did_pos_code.pos_code
                    ,SUM(A.cnt) AS 'start_cnt'
                    ,did_pos_code.bg_code
              FROM did_pos_code
              LEFT JOIN
              (
                 SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) ,  COUNT(pos_id) AS `cnt`
                 FROM did_log_type_3
                 WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                 AND page_id = 'p900005'
                 GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                 UNION ALL
                 SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                 FROM did_log_type_4
                 WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                 GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
              )AS A ON did_pos_code.pos_code = A.pos_id
              WHERE A.pos_id IS NOT NULL AND CHANNEL = '강북'
              GROUP BY did_pos_code.bg_code
           )AS B
           GROUP BY B.bg_code
        )AS D ON A.bg_code = D.bg_code
        WHERE A.channel IS NOT NULL AND A.CHANNEL != '' AND A.CHANNEL = '강북'
        GROUP BY A.bg_code
        ORDER BY A.bg_code
        ";

       $conn->DBQ($sql);
       $conn->DBE();
         $j = 0;
         $count8 = $count7+1;
       while($row=$conn->DBF()){

         if($row['channel'] == '홈/미디어')
         {
           $data1 = '스마트홈';
         }
         else
         {
           $data1 = $row['channel'];
         }

         if($row['매장수'] == null)
         {
           $data2 = '-';
         }
         else
         {
           $data2 = number_format($row['매장수']);
         }

         if($row['설치수'] == null)
         {
           $data3 = '-';
         }
         else
         {
           $data3 = number_format($row['설치수']);
         }

         if($row['설치율'] == null)
         {
           $data4 = '-';
         }
         else
         {
           $data4 = number_format($row['설치율'], 2).'%';
         }

         if($row['실행수'] == null)
         {
           $data5 = '-';
         }
         else
         {
           $data5 = number_format($row['실행수']);
         }

         if(number_format(($row['실행수']/$row['설치수'])*100,2) == nan)
         {
           $data6 = '-';
         }
         else
         {
           $data6 = number_format(($row['실행수']/$row['설치수'])*100,2).'%';
         }

         if($row['사용횟수']==null)
         {
           $data7 = '-';
         }
         else
         {
           $data7 = $row['사용횟수'];
         }


				 if($row['평균사용률']==null)
				{
					$data8 = '-';
				}
				else
				{
					$data8 = $row['평균사용률'];
				}


         $objPHPExcel->setActiveSheetIndex(0)
                   ->setCellValue("A$count8", $j+1)
                   ->setCellValue("B$count8", $data1)
                   ->setCellValue("C$count8", $row['bg_code'])
                   ->setCellValue("D$count8", $data2)
                   ->setCellValue("E$count8", $data3)
                   ->setCellValue("F$count8", $data4)
                   ->setCellValue("G$count8", $data5)
                   ->setCellValue("H$count8", $data6)
                   ->setCellValue("I$count8", $data7)
									 ->setCellValue("J$count8", $data8);


                   $j++;
                   $count8++;
                 }
                 $count9 = $count8+1;
                $objPHPExcel->getActiveSheet()->getStyle("A$count9:J$count9")->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0)
                          ->setCellValue("A$count9", "NO.")
                          ->setCellValue("B$count9", "영업담당")
                          ->setCellValue("C$count9", "지원팀")
                          ->setCellValue("D$count9", "매장수")
                          ->setCellValue("E$count9", "설치수")
                          ->setCellValue("F$count9", "설치율")
                          ->setCellValue("G$count9", "실행수")
                          ->setCellValue("H$count9", "실행율")
                          ->setCellValue("I$count9", "총 사용횟수")
													->setCellValue("J$count9", "매장당 평균 사용횟수");



      $sql = "
      SELECT
                A.channel,
                A.bg_code,
                COUNT(A.pos_code) AS '매장수',
                B.exe_cnt AS '설치수',
                B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율' ,
                C.touch_cnt / COUNT(A.pos_code) * 100  AS '사용율' ,
                C.touch_cnt AS '실행수',
                D.start_cnt  AS '사용횟수',
                ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'
      FROM did_pos_code AS A
      LEFT JOIN
      (
        SELECT did_pos_code.channel
                ,COUNT(did_pos_code.pos_code) AS 'exe_cnt'
                ,did_pos_code.bg_code
         FROM did_pos_code
         LEFT JOIN
         (
            SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
            FROM did_log_type_1
            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
            GROUP BY pos_id
         )AS A
         ON did_pos_code.pos_code = A.pos_id
         WHERE A.pos_id IS NOT NULL AND CHANNEL = '동부'
         GROUP BY bg_code
      )AS B ON A.bg_code = B.bg_code
      LEFT JOIN
      (
         SELECT did_pos_code.channel
                ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                ,did_pos_code.bg_code
         FROM did_pos_code
         LEFT JOIN
         (
            SELECT pos_id
            FROM did_log_type_3
            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
            AND page_id = 'p900005'
            GROUP BY pos_id
            UNION
            SELECT pos_id
            FROM did_log_type_4
            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
            GROUP BY pos_id
         )AS A ON did_pos_code.pos_code = A.pos_id
         WHERE A.pos_id IS NOT NULL AND CHANNEL = '동부'
         GROUP BY bg_code
      )AS C ON A.bg_code = C.bg_code
      LEFT JOIN
      (
         SELECT B.channel
                ,B.pos_code
                ,SUM(B.start_cnt)  AS 'start_cnt'
                ,B.bg_code
         FROM
         (
            SELECT did_pos_code.channel
                ,did_pos_code.pos_code
                  ,SUM(A.cnt) AS 'start_cnt'
                  ,did_pos_code.bg_code
            FROM did_pos_code
            LEFT JOIN
            (
               SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) ,  COUNT(pos_id) AS `cnt`
               FROM did_log_type_3
               WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
               AND page_id = 'p900005'
               GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
               UNION ALL
               SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
               FROM did_log_type_4
               WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
               GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
            )AS A ON did_pos_code.pos_code = A.pos_id
            WHERE A.pos_id IS NOT NULL AND CHANNEL = '동부'
            GROUP BY did_pos_code.bg_code
         )AS B
         GROUP BY B.bg_code
      )AS D ON A.bg_code = D.bg_code
      WHERE A.channel IS NOT NULL AND A.CHANNEL != '' AND A.CHANNEL = '동부'
      GROUP BY A.bg_code
      ORDER BY A.bg_code
      ";
       $conn->DBQ($sql);
       $conn->DBE();
         $j = 0;
         $count10 = $count9+1;
       while($row=$conn->DBF()){
         if($row['channel'] == '홈/미디어')
         {
           $data1 = '스마트홈';
         }
         else
         {
           $data1 = $row['channel'];
         }

         if($row['매장수'] == null)
         {
           $data2 = '-';
         }
         else
         {
           $data2 = number_format($row['매장수']);
         }

         if($row['설치수'] == null)
         {
           $data3 = '-';
         }
         else
         {
           $data3 = number_format($row['설치수']);
         }

         if($row['설치율'] == null)
         {
           $data4 = '-';
         }
         else
         {
           $data4 = number_format($row['설치율'], 2).'%';
         }

         if($row['실행수'] == null)
         {
           $data5 = '-';
         }
         else
         {
           $data5 = number_format($row['실행수']);
         }

         if(number_format(($row['실행수']/$row['설치수'])*100,2) == nan)
         {
           $data6 = '-';
         }
         else
         {
           $data6 = number_format(($row['실행수']/$row['설치수'])*100,2).'%';
         }

         if($row['사용횟수']==null)
         {
           $data7 = '-';
         }
         else
         {
           $data7 = $row['사용횟수'];
         }


				 if($row['평균사용률']==null)
				{
					$data8 = '-';
				}
				else
				{
					$data8 = $row['평균사용률'];
				}


         $objPHPExcel->setActiveSheetIndex(0)
                   ->setCellValue("A$count10", $j+1)
                   ->setCellValue("B$count10", $data1)
                   ->setCellValue("C$count10", $row['bg_code'])
                   ->setCellValue("D$count10", $data2)
                   ->setCellValue("E$count10", $data3)
                   ->setCellValue("F$count10", $data4)
                   ->setCellValue("G$count10", $data5)
                   ->setCellValue("H$count10", $data6)
                   ->setCellValue("I$count10", $data7)
									 ->setCellValue("J$count10", $data8);


                   $j++;
                   $count10++;
                 }
                 $count11 = $count10+1;
                $objPHPExcel->getActiveSheet()->getStyle("A$count11:J$count11")->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0)
								->setCellValue("A$count11", "NO.")
								->setCellValue("B$count11", "영업담당")
								->setCellValue("C$count11", "지원팀")
								->setCellValue("D$count11", "매장수")
								->setCellValue("E$count11", "설치수")
								->setCellValue("F$count11", "설치율")
								->setCellValue("G$count11", "실행수")
								->setCellValue("H$count11", "실행율")
								->setCellValue("I$count11", "총 사용횟수")
								->setCellValue("J$count11", "매장당 평균 사용횟수");

                $sql = "
                SELECT
                          A.channel,
                          A.bg_code,
                          COUNT(A.pos_code) AS '매장수',
                          B.exe_cnt AS '설치수',
                          B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율' ,
                          C.touch_cnt / COUNT(A.pos_code) * 100  AS '사용율' ,
                          C.touch_cnt AS '실행수',
                          D.start_cnt  AS '사용횟수',
                          ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'
                FROM did_pos_code AS A
                LEFT JOIN
                (
                  SELECT did_pos_code.channel
                          ,COUNT(did_pos_code.pos_code) AS 'exe_cnt'
                          ,did_pos_code.bg_code
                   FROM did_pos_code
                   LEFT JOIN
                   (
                      SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                      FROM did_log_type_1
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                      GROUP BY pos_id
                   )AS A
                   ON did_pos_code.pos_code = A.pos_id
                   WHERE A.pos_id IS NOT NULL AND CHANNEL = '서부'
                   GROUP BY bg_code
                )AS B ON A.bg_code = B.bg_code
                LEFT JOIN
                (
                   SELECT did_pos_code.channel
                          ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                          ,did_pos_code.bg_code
                   FROM did_pos_code
                   LEFT JOIN
                   (
                      SELECT pos_id
                      FROM did_log_type_3
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                      AND page_id = 'p900005'
                      GROUP BY pos_id
                      UNION
                      SELECT pos_id
                      FROM did_log_type_4
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                      GROUP BY pos_id
                   )AS A ON did_pos_code.pos_code = A.pos_id
                   WHERE A.pos_id IS NOT NULL AND CHANNEL = '서부'
                   GROUP BY bg_code
                )AS C ON A.bg_code = C.bg_code
                LEFT JOIN
                (
                   SELECT B.channel
                          ,B.pos_code
                          ,SUM(B.start_cnt)  AS 'start_cnt'
                          ,B.bg_code
                   FROM
                   (
                      SELECT did_pos_code.channel
                          ,did_pos_code.pos_code
                            ,SUM(A.cnt) AS 'start_cnt'
                            ,did_pos_code.bg_code
                      FROM did_pos_code
                      LEFT JOIN
                      (
                         SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) ,  COUNT(pos_id) AS `cnt`
                         FROM did_log_type_3
                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                         AND page_id = 'p900005'
                         GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                         UNION ALL
                         SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                         FROM did_log_type_4
                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                         GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                      )AS A ON did_pos_code.pos_code = A.pos_id
                      WHERE A.pos_id IS NOT NULL AND CHANNEL = '서부'
                      GROUP BY did_pos_code.bg_code
                   )AS B
                   GROUP BY B.bg_code
                )AS D ON A.bg_code = D.bg_code
                WHERE A.channel IS NOT NULL AND A.CHANNEL != '' AND A.CHANNEL = '서부'
                GROUP BY A.bg_code
                ORDER BY A.bg_code
                ";
													$conn->DBQ($sql);
													$conn->DBE();
													$j = 0;
													$count12 = $count11+1;
													while($row=$conn->DBF()){


														if($row['channel'] == '홈/미디어')
														{
															$data1 = '스마트홈';
														}
														else
														{
															$data1 = $row['channel'];
														}

														if($row['매장수'] == null)
														{
															$data2 = '-';
														}
														else
														{
															$data2 = number_format($row['매장수']);
														}

														if($row['설치수'] == null)
														{
															$data3 = '-';
														}
														else
														{
															$data3 = number_format($row['설치수']);
														}

														if($row['설치율'] == null)
														{
															$data4 = '-';
														}
														else
														{
															$data4 = number_format($row['설치율'], 2).'%';
														}

														if($row['실행수'] == null)
														{
															$data5 = '-';
														}
														else
														{
															$data5 = number_format($row['실행수']);
														}

														if(number_format(($row['실행수']/$row['설치수'])*100,2) == nan)
														{
															$data6 = '-';
														}
														else
														{
															$data6 = number_format(($row['실행수']/$row['설치수'])*100,2).'%';
														}

														if($row['사용횟수']==null)
														{
															$data7 = '-';
														}
														else
														{
															$data7 = $row['사용횟수'];
														}

														if($row['평균사용률']==null)
														{
															$data8 = '-';
														}
														else
														{
															$data8 = $row['평균사용률'];
														}


														$objPHPExcel->setActiveSheetIndex(0)
																			->setCellValue("A$count12", $j+1)
																			->setCellValue("B$count12", $data1)
																			->setCellValue("C$count12", $row['bg_code'])
																			->setCellValue("D$count12", $data2)
																			->setCellValue("E$count12", $data3)
																			->setCellValue("F$count12", $data4)
																			->setCellValue("G$count12", $data5)
																			->setCellValue("H$count12", $data6)
																			->setCellValue("I$count12", $data7)
																			->setCellValue("J$count12", $data8);


																			$j++;
																			$count12++;
																		}

																		 $count13 = $count12+1;
																		$objPHPExcel->getActiveSheet()->getStyle("A$count13:J$count13")->getFont()->setBold(true);
																		$objPHPExcel->setActiveSheetIndex(0)
																							->setCellValue("A$count13", "NO.")
																							->setCellValue("B$count13", "영업담당")
																							->setCellValue("C$count13", "지원팀")
																							->setCellValue("D$count13", "매장수")
																							->setCellValue("E$count13", "설치수")
																							->setCellValue("F$count13", "설치율")
																							->setCellValue("G$count13", "실행수")
																							->setCellValue("H$count13", "실행율")
																							->setCellValue("I$count13", "총 사용횟수")
																							->setCellValue("J$count13", "매장당 평균 사용횟수");





                                              $sql = "
                                              SELECT
                                                        A.channel,
                                                        A.bg_code,
                                                        COUNT(A.pos_code) AS '매장수',
                                                        B.exe_cnt AS '설치수',
                                                        B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율' ,
                                                        C.touch_cnt / COUNT(A.pos_code) * 100  AS '사용율' ,
                                                        C.touch_cnt AS '실행수',
                                                        D.start_cnt  AS '사용횟수',
                                                        ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'
                                              FROM did_pos_code AS A
                                              LEFT JOIN
                                              (
                                                SELECT did_pos_code.channel
                                                        ,COUNT(did_pos_code.pos_code) AS 'exe_cnt'
                                                        ,did_pos_code.bg_code
                                                 FROM did_pos_code
                                                 LEFT JOIN
                                                 (
                                                    SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                    FROM did_log_type_1
                                                    WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                                                    GROUP BY pos_id
                                                 )AS A
                                                 ON did_pos_code.pos_code = A.pos_id
                                                 WHERE A.pos_id IS NOT NULL AND CHANNEL = '홈/미디어'
                                                 GROUP BY bg_code
                                              )AS B ON A.bg_code = B.bg_code
                                              LEFT JOIN
                                              (
                                                 SELECT did_pos_code.channel
                                                        ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                                                        ,did_pos_code.bg_code
                                                 FROM did_pos_code
                                                 LEFT JOIN
                                                 (
                                                    SELECT pos_id
                                                    FROM did_log_type_3
                                                    WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                    AND page_id = 'p900005'
                                                    GROUP BY pos_id
                                                    UNION
                                                    SELECT pos_id
                                                    FROM did_log_type_4
                                                    WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                    GROUP BY pos_id
                                                 )AS A ON did_pos_code.pos_code = A.pos_id
                                                 WHERE A.pos_id IS NOT NULL AND CHANNEL = '홈/미디어'
                                                 GROUP BY bg_code
                                              )AS C ON A.bg_code = C.bg_code
                                              LEFT JOIN
                                              (
                                                 SELECT B.channel
                                                        ,B.pos_code
                                                        ,SUM(B.start_cnt)  AS 'start_cnt'
                                                        ,B.bg_code
                                                 FROM
                                                 (
                                                    SELECT did_pos_code.channel
                                                        ,did_pos_code.pos_code
                                                          ,SUM(A.cnt) AS 'start_cnt'
                                                          ,did_pos_code.bg_code
                                                    FROM did_pos_code
                                                    LEFT JOIN
                                                    (
                                                       SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) ,  COUNT(pos_id) AS `cnt`
                                                       FROM did_log_type_3
                                                       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                       AND page_id = 'p900005'
                                                       GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                       UNION ALL
                                                       SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                                                       FROM did_log_type_4
                                                       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                       GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                    )AS A ON did_pos_code.pos_code = A.pos_id
                                                    WHERE A.pos_id IS NOT NULL AND CHANNEL = '홈/미디어'
                                                    GROUP BY did_pos_code.bg_code
                                                 )AS B
                                                 GROUP BY B.bg_code
                                              )AS D ON A.bg_code = D.bg_code
                                              WHERE A.channel IS NOT NULL AND A.CHANNEL != '' AND A.CHANNEL = '홈/미디어'
                                              GROUP BY A.bg_code
                                              ORDER BY A.bg_code
                                              ";
																							$conn->DBQ($sql);
																							$conn->DBE();
																							$j = 0;
																							$count14 = $count13+1;
																							while($row=$conn->DBF()){


																								if($row['channel'] == '홈/미디어')
																								{
																									$data1 = '스마트홈';
																								}
																								else
																								{
																									$data1 = $row['channel'];
																								}

																								if($row['매장수'] == null)
																								{
																									$data2 = '-';
																								}
																								else
																								{
																									$data2 = number_format($row['매장수']);
																								}

																								if($row['설치수'] == null)
																								{
																									$data3 = '-';
																								}
																								else
																								{
																									$data3 = number_format($row['설치수']);
																								}

																								if($row['설치율'] == null)
																								{
																									$data4 = '-';
																								}
																								else
																								{
																									$data4 = number_format($row['설치율'], 2).'%';
																								}

																								if($row['실행수'] == null)
																								{
																									$data5 = '-';
																								}
																								else
																								{
																									$data5 = number_format($row['실행수']);
																								}

																								if(number_format(($row['실행수']/$row['설치수'])*100,2) == nan)
																								{
																									$data6 = '-';
																								}
																								else
																								{
																									$data6 = number_format(($row['실행수']/$row['설치수'])*100,2).'%';
																								}

																								if($row['사용횟수']==null)
																								{
																									$data7 = '-';
																								}
																								else
																								{
																									$data7 = $row['사용횟수'];
																								}

																								if($row['평균사용률']==null)
																								{
																									$data8 = '-';
																								}
																								else
																								{
																									$data8 = $row['평균사용률'];
																								}


																								$objPHPExcel->setActiveSheetIndex(0)
																													->setCellValue("A$count14", $j+1)
																													->setCellValue("B$count14", $data1)
																													->setCellValue("C$count14", $row['bg_code'])
																													->setCellValue("D$count14", $data2)
																													->setCellValue("E$count14", $data3)
																													->setCellValue("F$count14", $data4)
																													->setCellValue("G$count14", $data5)
																													->setCellValue("H$count14", $data6)
																													->setCellValue("I$count14", $data7)
																													->setCellValue("J$count14", $data8);


																													$j++;
																													$count14++;
																												}
																												$objPHPExcel->getActiveSheet()->getStyle("A1:J$count14")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
																												$objPHPExcel->getActiveSheet()->getStyle("A1:J$count14")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
																												$objPHPExcel->setActiveSheetIndex(0)->setTitle('매장별 운영 데이터');




																												$objPHPExcel->createSheet();
																												$objPHPExcel->setActiveSheetIndex(1)
																												->setCellValue("A1", "NO.")
																												          ->setCellValue("B1", "영업담당")
																												          ->setCellValue("C1", "지원팀")
																												          ->setCellValue("D1", "매장수")
																												          ->setCellValue("E1", "설치수")
																												          ->setCellValue("F1", "설치율")
																												          ->setCellValue("G1", "실행수")
																												          ->setCellValue("H1", "실행율")
																												          ->setCellValue("I1", "총 사용횟수")
                                                                  ->setCellValue("J1", "매장당 평균 사용횟수");
																											$objPHPExcel->getActiveSheet()->getStyle("A1:J1")->getFont()->setBold(true);
																											$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
																											$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
																											$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
																											$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
																											$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
																											$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
																											$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
																											$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
																											$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
																											$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);



                                                      $sql = "
                                                      SELECT
                                                        A.channel,
                                                        A.bg_code,
                                                        COUNT(A.pos_code) AS '매장수',
                                                        B.exe_cnt AS '설치수',
                                                        B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율' ,
                                                        C.touch_cnt / COUNT(A.pos_code) * 100  AS '사용율' ,
                                                        C.touch_cnt AS '실행수',
                                                        D.start_cnt  AS '사용횟수',
                                                        ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'
                                              FROM did_pos_code AS A
                                              LEFT JOIN
                                              (
                                                 SELECT did_pos_code.channel
                                                        ,COUNT(did_pos_code.pos_code) AS 'exe_cnt'
                                                        ,did_pos_code.bg_code
                                                 FROM did_pos_code
                                                 LEFT JOIN
                                                 (
                                                    SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                    FROM did_log_type_1
                                                    WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                                                    GROUP BY pos_id
                                                 )AS A
                                                 ON did_pos_code.pos_code = A.pos_id
                                                 WHERE A.pos_id IS NOT NULL AND CHANNEL = '강남'
                                                 GROUP BY bg_code
                                              )AS B ON A.bg_code = B.bg_code
                                              LEFT JOIN
                                              (
                                                 SELECT did_pos_code.channel
                                                        ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                                                        ,did_pos_code.bg_code
                                                 FROM did_pos_code
                                                 LEFT JOIN
                                                 (
                                                    SELECT pos_id
                                                    FROM did_log_type_3
                                                    WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                    AND page_id = 'p900005'
                                                    GROUP BY pos_id
                                                    UNION
                                                    SELECT pos_id
                                                    FROM did_log_type_4
                                                    WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                    GROUP BY pos_id
                                                 )AS A ON did_pos_code.pos_code = A.pos_id
                                                 WHERE A.pos_id IS NOT NULL AND CHANNEL = '강남'
                                                 GROUP BY bg_code
                                              )AS C ON A.bg_code = C.bg_code
                                              LEFT JOIN
                                              (
                                                 SELECT B.channel
                                                        ,B.pos_code
                                                        ,SUM(B.start_cnt)  AS 'start_cnt'
                                                        ,B.bg_code
                                                 FROM
                                                 (
                                                    SELECT did_pos_code.channel
                                                          ,did_pos_code.pos_code
                                                          ,SUM(A.cnt) AS 'start_cnt'
                                                          ,did_pos_code.bg_code
                                                    FROM did_pos_code
                                                    LEFT JOIN
                                                    (
                                                       SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) ,  COUNT(pos_id) AS `cnt`
                                                       FROM did_log_type_3
                                                       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                       AND page_id = 'p900005'
                                                       GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                       UNION ALL
                                                       SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                                                       FROM did_log_type_4
                                                       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                       GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                    )AS A ON did_pos_code.pos_code = A.pos_id
                                                    WHERE A.pos_id IS NOT NULL AND CHANNEL = '강남'
                                                    GROUP BY did_pos_code.bg_code
                                                 )AS B
                                                 GROUP BY B.bg_code
                                              )AS D ON A.bg_code = D.bg_code
                                              WHERE A.channel IS NOT NULL AND A.CHANNEL != '' AND A.CHANNEL = '강남'
                                              GROUP BY A.bg_code
                                              ORDER BY A.bg_code
                                                      ";
                                                     $conn->DBQ($sql);
                                                     $conn->DBE();
                                                     $j = 1;
                                                     $count4 = 2;
                                                     while($row=$conn->DBF()){


                                                       if($row['channel'] == '홈/미디어')
                                                       {
                                                         $data1 = '스마트홈';
                                                       }
                                                       else
                                                       {
                                                         $data1 = $row['channel'];
                                                       }

                                                       if($row['매장수'] == null)
                                                       {
                                                         $data2 = '-';
                                                       }
                                                       else
                                                       {
                                                         $data2 = number_format($row['매장수']);
                                                       }

                                                       if($row['설치수'] == null)
                                                       {
                                                         $data3 = '-';
                                                       }
                                                       else
                                                       {
                                                         $data3 = number_format($row['설치수']);
                                                       }

                                                       if($row['설치율'] == null)
                                                       {
                                                         $data4 = '-';
                                                       }
                                                       else
                                                       {
                                                         $data4 = number_format($row['설치율'], 2).'%';
                                                       }

                                                       if($row['실행수'] == null)
                                                       {
                                                         $data5 = '-';
                                                       }
                                                       else
                                                       {
                                                         $data5 = number_format($row['실행수']);
                                                       }

                                                       if(number_format(($row['실행수']/$row['설치수'])*100,2) == nan)
                                                       {
                                                         $data6 = '-';
                                                       }
                                                       else
                                                       {
                                                         $data6 = number_format(($row['실행수']/$row['설치수'])*100,2).'%';
                                                       }

                                                       if($row['사용횟수']==null)
                                                       {
                                                         $data7 = '-';
                                                       }
                                                       else
                                                       {
                                                         $data7 = $row['사용횟수'];
                                                       }

                                                      if($row['평균사용률']==null)
                                                      {
                                                        $data8 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data8 = $row['평균사용률'];
                                                      }


                                                       $objPHPExcel->setActiveSheetIndex(1)
                                                                 ->setCellValue("A$count4", $j)
                                                                 ->setCellValue("B$count4", $data1)
                                                                 ->setCellValue("C$count4", $row['bg_code'])
                                                                 ->setCellValue("D$count4", $data2)
                                                                 ->setCellValue("E$count4", $data3)
                                                                 ->setCellValue("F$count4", $data4)
                                                                 ->setCellValue("G$count4", $data5)
                                                                 ->setCellValue("H$count4", $data6)
                                                                 ->setCellValue("I$count4", $data7)
                                                                 ->setCellValue("J$count4", $data8);


                                                                 $j++;
                                                                 $count4++;
                                                               }







                                                                        $sql = "
                                                                        SELECT
                                                                          A.channel,
                                                                          A.bg_code,
                                                                          COUNT(A.pos_code) AS '매장수',
                                                                          B.exe_cnt AS '설치수',
                                                                          B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율' ,
                                                                          C.touch_cnt / COUNT(A.pos_code) * 100  AS '사용율' ,
                                                                          C.touch_cnt AS '실행수',
                                                                          D.start_cnt  AS '사용횟수',
                                                                          ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'
                                                                FROM did_pos_code AS A
                                                                LEFT JOIN
                                                                (
                                                                   SELECT did_pos_code.channel
                                                                          ,COUNT(did_pos_code.pos_code) AS 'exe_cnt'
                                                                          ,did_pos_code.bg_code
                                                                   FROM did_pos_code
                                                                   LEFT JOIN
                                                                   (
                                                                      SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                                      FROM did_log_type_1
                                                                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                                                                      GROUP BY pos_id
                                                                   )AS A
                                                                   ON did_pos_code.pos_code = A.pos_id
                                                                   WHERE A.pos_id IS NOT NULL AND CHANNEL = '동부'
                                                                   GROUP BY bg_code
                                                                )AS B ON A.bg_code = B.bg_code
                                                                LEFT JOIN
                                                                (
                                                                   SELECT did_pos_code.channel
                                                                          ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                                                                          ,did_pos_code.bg_code
                                                                   FROM did_pos_code
                                                                   LEFT JOIN
                                                                   (
                                                                      SELECT pos_id
                                                                      FROM did_log_type_3
                                                                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                                      AND page_id = 'p900005'
                                                                      GROUP BY pos_id
                                                                      UNION
                                                                      SELECT pos_id
                                                                      FROM did_log_type_4
                                                                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                                      GROUP BY pos_id
                                                                   )AS A ON did_pos_code.pos_code = A.pos_id
                                                                   WHERE A.pos_id IS NOT NULL AND CHANNEL = '동부'
                                                                   GROUP BY bg_code
                                                                )AS C ON A.bg_code = C.bg_code
                                                                LEFT JOIN
                                                                (
                                                                   SELECT B.channel
                                                                          ,B.pos_code
                                                                          ,SUM(B.start_cnt)  AS 'start_cnt'
                                                                          ,B.bg_code
                                                                   FROM
                                                                   (
                                                                      SELECT did_pos_code.channel
                                                                            ,did_pos_code.pos_code
                                                                            ,SUM(A.cnt) AS 'start_cnt'
                                                                            ,did_pos_code.bg_code
                                                                      FROM did_pos_code
                                                                      LEFT JOIN
                                                                      (
                                                                         SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) ,  COUNT(pos_id) AS `cnt`
                                                                         FROM did_log_type_3
                                                                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                                         AND page_id = 'p900005'
                                                                         GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                                         UNION ALL
                                                                         SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                                                                         FROM did_log_type_4
                                                                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                                         GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                                      )AS A ON did_pos_code.pos_code = A.pos_id
                                                                      WHERE A.pos_id IS NOT NULL AND CHANNEL = '동부'
                                                                      GROUP BY did_pos_code.bg_code
                                                                   )AS B
                                                                   GROUP BY B.bg_code
                                                                )AS D ON A.bg_code = D.bg_code
                                                                WHERE A.channel IS NOT NULL AND A.CHANNEL != '' AND A.CHANNEL = '동부'
                                                                GROUP BY A.bg_code
                                                                ORDER BY A.bg_code
                                                                        ";
                                                    $conn->DBQ($sql);
                                                    $conn->DBE();


                                                    while($row=$conn->DBF()){

                                                      if($row['channel'] == '홈/미디어')
                                                      {
                                                        $data1 = '스마트홈';
                                                      }
                                                      else
                                                      {
                                                        $data1 = $row['channel'];
                                                      }

                                                      if($row['매장수'] == null)
                                                      {
                                                        $data2 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data2 = number_format($row['매장수']);
                                                      }

                                                      if($row['설치수'] == null)
                                                      {
                                                        $data3 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data3 = number_format($row['설치수']);
                                                      }

                                                      if($row['설치율'] == null)
                                                      {
                                                        $data4 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data4 = number_format($row['설치율'], 2).'%';
                                                      }

                                                      if($row['실행수'] == null)
                                                      {
                                                        $data5 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data5 = number_format($row['실행수']);
                                                      }

                                                      if(number_format(($row['실행수']/$row['설치수'])*100,2) == nan)
                                                      {
                                                        $data6 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data6 = number_format(($row['실행수']/$row['설치수'])*100,2).'%';
                                                      }

                                                      if($row['사용횟수']==null)
                                                      {
                                                        $data7 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data7 = $row['사용횟수'];
                                                      }

                                                      if($row['평균사용률']==null)
                                                      {
                                                        $data8 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data8 = $row['평균사용률'];
                                                      }




                                                      $objPHPExcel->setActiveSheetIndex(1)
                                                                ->setCellValue("A$count4", $j)
                                                                ->setCellValue("B$count4", $data1)
                                                                ->setCellValue("C$count4", $row['bg_code'])
                                                                ->setCellValue("D$count4", $data2)
                                                                ->setCellValue("E$count4", $data3)
                                                                ->setCellValue("F$count4", $data4)
                                                                ->setCellValue("G$count4", $data5)
                                                                ->setCellValue("H$count4", $data6)
                                                                ->setCellValue("I$count4", $data7)
                                                                ->setCellValue("J$count4", $data8);


                                                                $j++;
                                                                $count4++;
                                                              }




                                                                       $sql = "
                                                                       SELECT
                                                                         A.channel,
                                                                         A.bg_code,
                                                                         COUNT(A.pos_code) AS '매장수',
                                                                         B.exe_cnt AS '설치수',
                                                                         B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율' ,
                                                                         C.touch_cnt / COUNT(A.pos_code) * 100  AS '사용율' ,
                                                                         C.touch_cnt AS '실행수',
                                                                         D.start_cnt  AS '사용횟수',
                                                                         ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'
                                                               FROM did_pos_code AS A
                                                               LEFT JOIN
                                                               (
                                                                  SELECT did_pos_code.channel
                                                                         ,COUNT(did_pos_code.pos_code) AS 'exe_cnt'
                                                                         ,did_pos_code.bg_code
                                                                  FROM did_pos_code
                                                                  LEFT JOIN
                                                                  (
                                                                     SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                                     FROM did_log_type_1
                                                                     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                                                                     GROUP BY pos_id
                                                                  )AS A
                                                                  ON did_pos_code.pos_code = A.pos_id
                                                                  WHERE A.pos_id IS NOT NULL AND CHANNEL = '강북'
                                                                  GROUP BY bg_code
                                                               )AS B ON A.bg_code = B.bg_code
                                                               LEFT JOIN
                                                               (
                                                                  SELECT did_pos_code.channel
                                                                         ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                                                                         ,did_pos_code.bg_code
                                                                  FROM did_pos_code
                                                                  LEFT JOIN
                                                                  (
                                                                     SELECT pos_id
                                                                     FROM did_log_type_3
                                                                     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                                     AND page_id = 'p900005'
                                                                     GROUP BY pos_id
                                                                     UNION
                                                                     SELECT pos_id
                                                                     FROM did_log_type_4
                                                                     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                                     GROUP BY pos_id
                                                                  )AS A ON did_pos_code.pos_code = A.pos_id
                                                                  WHERE A.pos_id IS NOT NULL AND CHANNEL = '강북'
                                                                  GROUP BY bg_code
                                                               )AS C ON A.bg_code = C.bg_code
                                                               LEFT JOIN
                                                               (
                                                                  SELECT B.channel
                                                                         ,B.pos_code
                                                                         ,SUM(B.start_cnt)  AS 'start_cnt'
                                                                         ,B.bg_code
                                                                  FROM
                                                                  (
                                                                     SELECT did_pos_code.channel
                                                                           ,did_pos_code.pos_code
                                                                           ,SUM(A.cnt) AS 'start_cnt'
                                                                           ,did_pos_code.bg_code
                                                                     FROM did_pos_code
                                                                     LEFT JOIN
                                                                     (
                                                                        SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) ,  COUNT(pos_id) AS `cnt`
                                                                        FROM did_log_type_3
                                                                        WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                                        AND page_id = 'p900005'
                                                                        GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                                        UNION ALL
                                                                        SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                                                                        FROM did_log_type_4
                                                                        WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                                        GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                                     )AS A ON did_pos_code.pos_code = A.pos_id
                                                                     WHERE A.pos_id IS NOT NULL AND CHANNEL = '강북'
                                                                     GROUP BY did_pos_code.bg_code
                                                                  )AS B
                                                                  GROUP BY B.bg_code
                                                               )AS D ON A.bg_code = D.bg_code
                                                               WHERE A.channel IS NOT NULL AND A.CHANNEL != '' AND A.CHANNEL = '강북'
                                                               GROUP BY A.bg_code
                                                               ORDER BY A.bg_code
                                                                       ";

                                                    $conn->DBQ($sql);
                                                    $conn->DBE();


                                                    while($row=$conn->DBF()){

                                                      if($row['channel'] == '홈/미디어')
                                                      {
                                                        $data1 = '스마트홈';
                                                      }
                                                      else
                                                      {
                                                        $data1 = $row['channel'];
                                                      }

                                                      if($row['매장수'] == null)
                                                      {
                                                        $data2 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data2 = number_format($row['매장수']);
                                                      }

                                                      if($row['설치수'] == null)
                                                      {
                                                        $data3 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data3 = number_format($row['설치수']);
                                                      }

                                                      if($row['설치율'] == null)
                                                      {
                                                        $data4 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data4 = number_format($row['설치율'], 2).'%';
                                                      }

                                                      if($row['실행수'] == null)
                                                      {
                                                        $data5 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data5 = number_format($row['실행수']);
                                                      }

                                                      if(number_format(($row['실행수']/$row['설치수'])*100,2) == nan)
                                                      {
                                                        $data6 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data6 = number_format(($row['실행수']/$row['설치수'])*100,2).'%';
                                                      }

                                                      if($row['사용횟수']==null)
                                                      {
                                                        $data7 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data7 = $row['사용횟수'];
                                                      }


                                                      if($row['평균사용률']==null)
                                                     {
                                                       $data8 = '-';
                                                     }
                                                     else
                                                     {
                                                       $data8 = $row['평균사용률'];
                                                     }


                                                      $objPHPExcel->setActiveSheetIndex(1)
                                                                ->setCellValue("A$count4", $j)
                                                                ->setCellValue("B$count4", $data1)
                                                                ->setCellValue("C$count4", $row['bg_code'])
                                                                ->setCellValue("D$count4", $data2)
                                                                ->setCellValue("E$count4", $data3)
                                                                ->setCellValue("F$count4", $data4)
                                                                ->setCellValue("G$count4", $data5)
                                                                ->setCellValue("H$count4", $data6)
                                                                ->setCellValue("I$count4", $data7)
                                                                ->setCellValue("J$count4", $data8);


                                                                $j++;
                                                              $count4++;
                                                              }




                                                                       $sql = "
                                                                       SELECT
                                                                         A.channel,
                                                                         A.bg_code,
                                                                         COUNT(A.pos_code) AS '매장수',
                                                                         B.exe_cnt AS '설치수',
                                                                         B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율' ,
                                                                         C.touch_cnt / COUNT(A.pos_code) * 100  AS '사용율' ,
                                                                         C.touch_cnt AS '실행수',
                                                                         D.start_cnt  AS '사용횟수',
                                                                         ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'
                                                               FROM did_pos_code AS A
                                                               LEFT JOIN
                                                               (
                                                                  SELECT did_pos_code.channel
                                                                         ,COUNT(did_pos_code.pos_code) AS 'exe_cnt'
                                                                         ,did_pos_code.bg_code
                                                                  FROM did_pos_code
                                                                  LEFT JOIN
                                                                  (
                                                                     SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                                     FROM did_log_type_1
                                                                     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                                                                     GROUP BY pos_id
                                                                  )AS A
                                                                  ON did_pos_code.pos_code = A.pos_id
                                                                  WHERE A.pos_id IS NOT NULL AND CHANNEL = '서부'
                                                                  GROUP BY bg_code
                                                               )AS B ON A.bg_code = B.bg_code
                                                               LEFT JOIN
                                                               (
                                                                  SELECT did_pos_code.channel
                                                                         ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                                                                         ,did_pos_code.bg_code
                                                                  FROM did_pos_code
                                                                  LEFT JOIN
                                                                  (
                                                                     SELECT pos_id
                                                                     FROM did_log_type_3
                                                                     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                                     AND page_id = 'p900005'
                                                                     GROUP BY pos_id
                                                                     UNION
                                                                     SELECT pos_id
                                                                     FROM did_log_type_4
                                                                     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                                     GROUP BY pos_id
                                                                  )AS A ON did_pos_code.pos_code = A.pos_id
                                                                  WHERE A.pos_id IS NOT NULL AND CHANNEL = '서부'
                                                                  GROUP BY bg_code
                                                               )AS C ON A.bg_code = C.bg_code
                                                               LEFT JOIN
                                                               (
                                                                  SELECT B.channel
                                                                         ,B.pos_code
                                                                         ,SUM(B.start_cnt)  AS 'start_cnt'
                                                                         ,B.bg_code
                                                                  FROM
                                                                  (
                                                                     SELECT did_pos_code.channel
                                                                           ,did_pos_code.pos_code
                                                                           ,SUM(A.cnt) AS 'start_cnt'
                                                                           ,did_pos_code.bg_code
                                                                     FROM did_pos_code
                                                                     LEFT JOIN
                                                                     (
                                                                        SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) ,  COUNT(pos_id) AS `cnt`
                                                                        FROM did_log_type_3
                                                                        WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))BETWEEN '".$date_from."' AND '".$date_to."'
                                                                        AND page_id = 'p900005'
                                                                        GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                                        UNION ALL
                                                                        SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                                                                        FROM did_log_type_4
                                                                        WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                                        GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                                     )AS A ON did_pos_code.pos_code = A.pos_id
                                                                     WHERE A.pos_id IS NOT NULL AND CHANNEL = '서부'
                                                                     GROUP BY did_pos_code.bg_code
                                                                  )AS B
                                                                  GROUP BY B.bg_code
                                                               )AS D ON A.bg_code = D.bg_code
                                                               WHERE A.channel IS NOT NULL AND A.CHANNEL != '' AND A.CHANNEL = '서부'
                                                               GROUP BY A.bg_code
                                                               ORDER BY A.bg_code
                                                                       ";
                                                    $conn->DBQ($sql);
                                                    $conn->DBE();


                                                    while($row=$conn->DBF()){
                                                      if($row['channel'] == '홈/미디어')
                                                      {
                                                        $data1 = '스마트홈';
                                                      }
                                                      else
                                                      {
                                                        $data1 = $row['channel'];
                                                      }

                                                      if($row['매장수'] == null)
                                                      {
                                                        $data2 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data2 = number_format($row['매장수']);
                                                      }

                                                      if($row['설치수'] == null)
                                                      {
                                                        $data3 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data3 = number_format($row['설치수']);
                                                      }

                                                      if($row['설치율'] == null)
                                                      {
                                                        $data4 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data4 = number_format($row['설치율'], 2).'%';
                                                      }

                                                      if($row['실행수'] == null)
                                                      {
                                                        $data5 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data5 = number_format($row['실행수']);
                                                      }

                                                      if(number_format(($row['실행수']/$row['설치수'])*100,2) == nan)
                                                      {
                                                        $data6 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data6 = number_format(($row['실행수']/$row['설치수'])*100,2).'%';
                                                      }

                                                      if($row['사용횟수']==null)
                                                      {
                                                        $data7 = '-';
                                                      }
                                                      else
                                                      {
                                                        $data7 = $row['사용횟수'];
                                                      }


                                                      if($row['평균사용률']==null)
                                                     {
                                                       $data8 = '-';
                                                     }
                                                     else
                                                     {
                                                       $data8 = $row['평균사용률'];
                                                     }


                                                      $objPHPExcel->setActiveSheetIndex(1)
                                                                ->setCellValue("A$count4", $j)
                                                                ->setCellValue("B$count4", $data1)
                                                                ->setCellValue("C$count4", $row['bg_code'])
                                                                ->setCellValue("D$count4", $data2)
                                                                ->setCellValue("E$count4", $data3)
                                                                ->setCellValue("F$count4", $data4)
                                                                ->setCellValue("G$count4", $data5)
                                                                ->setCellValue("H$count4", $data6)
                                                                ->setCellValue("I$count4", $data7)
                                                                ->setCellValue("J$count4", $data8);


                                                                $j++;
                                                                $count4++;
                                                              }


                                                             $sql = "
                                                             SELECT
                                                               A.channel,
                                                               A.bg_code,
                                                               COUNT(A.pos_code) AS '매장수',
                                                               B.exe_cnt AS '설치수',
                                                               B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율' ,
                                                               C.touch_cnt / COUNT(A.pos_code) * 100  AS '사용율' ,
                                                               C.touch_cnt AS '실행수',
                                                               D.start_cnt  AS '사용횟수',
                                                               ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'
                                                     FROM did_pos_code AS A
                                                     LEFT JOIN
                                                     (
                                                        SELECT did_pos_code.channel
                                                               ,COUNT(did_pos_code.pos_code) AS 'exe_cnt'
                                                               ,did_pos_code.bg_code
                                                        FROM did_pos_code
                                                        LEFT JOIN
                                                        (
                                                           SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                           FROM did_log_type_1
                                                           WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                                                           GROUP BY pos_id
                                                        )AS A
                                                        ON did_pos_code.pos_code = A.pos_id
                                                        WHERE A.pos_id IS NOT NULL AND CHANNEL = '강동'
                                                        GROUP BY bg_code
                                                     )AS B ON A.bg_code = B.bg_code
                                                     LEFT JOIN
                                                     (
                                                        SELECT did_pos_code.channel
                                                               ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                                                               ,did_pos_code.bg_code
                                                        FROM did_pos_code
                                                        LEFT JOIN
                                                        (
                                                           SELECT pos_id
                                                           FROM did_log_type_3
                                                           WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                           AND page_id = 'p900005'
                                                           GROUP BY pos_id
                                                           UNION
                                                           SELECT pos_id
                                                           FROM did_log_type_4
                                                           WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                           GROUP BY pos_id
                                                        )AS A ON did_pos_code.pos_code = A.pos_id
                                                        WHERE A.pos_id IS NOT NULL AND CHANNEL = '강동'
                                                        GROUP BY bg_code
                                                     )AS C ON A.bg_code = C.bg_code
                                                     LEFT JOIN
                                                     (
                                                        SELECT B.channel
                                                               ,B.pos_code
                                                               ,SUM(B.start_cnt)  AS 'start_cnt'
                                                               ,B.bg_code
                                                        FROM
                                                        (
                                                           SELECT did_pos_code.channel
                                                                 ,did_pos_code.pos_code
                                                                 ,SUM(A.cnt) AS 'start_cnt'
                                                                 ,did_pos_code.bg_code
                                                           FROM did_pos_code
                                                           LEFT JOIN
                                                           (
                                                              SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) ,  COUNT(pos_id) AS `cnt`
                                                              FROM did_log_type_3
                                                              WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                              AND page_id = 'p900005'
                                                              GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                              UNION ALL
                                                              SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                                                              FROM did_log_type_4
                                                              WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                              GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                           )AS A ON did_pos_code.pos_code = A.pos_id
                                                           WHERE A.pos_id IS NOT NULL AND CHANNEL = '강동'
                                                           GROUP BY did_pos_code.bg_code
                                                        )AS B
                                                        GROUP BY B.bg_code
                                                     )AS D ON A.bg_code = D.bg_code
                                                     WHERE A.channel IS NOT NULL AND A.CHANNEL != '' AND A.CHANNEL = '강동'
                                                     GROUP BY A.bg_code
                                                     ORDER BY A.bg_code
                                                             ";
                                                                       $conn->DBQ($sql);
                                                                       $conn->DBE();


                                                                       while($row=$conn->DBF()){


                                                                         if($row['channel'] == '홈/미디어')
                                                                         {
                                                                           $data1 = '스마트홈';
                                                                         }
                                                                         else
                                                                         {
                                                                           $data1 = $row['channel'];
                                                                         }

                                                                         if($row['매장수'] == null)
                                                                         {
                                                                           $data2 = '-';
                                                                         }
                                                                         else
                                                                         {
                                                                           $data2 = number_format($row['매장수']);
                                                                         }

                                                                         if($row['설치수'] == null)
                                                                         {
                                                                           $data3 = '-';
                                                                         }
                                                                         else
                                                                         {
                                                                           $data3 = number_format($row['설치수']);
                                                                         }

                                                                         if($row['설치율'] == null)
                                                                         {
                                                                           $data4 = '-';
                                                                         }
                                                                         else
                                                                         {
                                                                           $data4 = number_format($row['설치율'], 2).'%';
                                                                         }

                                                                         if($row['실행수'] == null)
                                                                         {
                                                                           $data5 = '-';
                                                                         }
                                                                         else
                                                                         {
                                                                           $data5 = number_format($row['실행수']);
                                                                         }

                                                                         if(number_format(($row['실행수']/$row['설치수'])*100,2) == nan)
                                                                         {
                                                                           $data6 = '-';
                                                                         }
                                                                         else
                                                                         {
                                                                           $data6 = number_format(($row['실행수']/$row['설치수'])*100,2).'%';
                                                                         }

                                                                         if($row['사용횟수']==null)
                                                                         {
                                                                           $data7 = '-';
                                                                         }
                                                                         else
                                                                         {
                                                                           $data7 = $row['사용횟수'];
                                                                         }

                                                                         if($row['평균사용률']==null)
                                                                         {
                                                                           $data8 = '-';
                                                                         }
                                                                         else
                                                                         {
                                                                           $data8 = $row['평균사용률'];
                                                                         }


                                                                         $objPHPExcel->setActiveSheetIndex(1)
                                                                                   ->setCellValue("A$count4", $j)
                                                                                   ->setCellValue("B$count4", $data1)
                                                                                   ->setCellValue("C$count4", $row['bg_code'])
                                                                                   ->setCellValue("D$count4", $data2)
                                                                                   ->setCellValue("E$count4", $data3)
                                                                                   ->setCellValue("F$count4", $data4)
                                                                                   ->setCellValue("G$count4", $data5)
                                                                                   ->setCellValue("H$count4", $data6)
                                                                                   ->setCellValue("I$count4", $data7)
                                                                                   ->setCellValue("J$count4", $data8);


                                                                                   $j++;
                                                                                   $count4++;
                                                                                 }







                                                                                           $sql = "
                                                                                           SELECT
                                                                                             A.channel,
                                                                                             A.bg_code,
                                                                                             COUNT(A.pos_code) AS '매장수',
                                                                                             B.exe_cnt AS '설치수',
                                                                                             B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율' ,
                                                                                             C.touch_cnt / COUNT(A.pos_code) * 100  AS '사용율' ,
                                                                                             C.touch_cnt AS '실행수',
                                                                                             D.start_cnt  AS '사용횟수',
                                                                                             ROUND((D.start_cnt / B.exe_cnt / 20 * 25), 2) AS '평균사용률'
                                                                                   FROM did_pos_code AS A
                                                                                   LEFT JOIN
                                                                                   (
                                                                                      SELECT did_pos_code.channel
                                                                                             ,COUNT(did_pos_code.pos_code) AS 'exe_cnt'
                                                                                             ,did_pos_code.bg_code
                                                                                      FROM did_pos_code
                                                                                      LEFT JOIN
                                                                                      (
                                                                                         SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                                                         FROM did_log_type_1
                                                                                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                                                                                         GROUP BY pos_id
                                                                                      )AS A
                                                                                      ON did_pos_code.pos_code = A.pos_id
                                                                                      WHERE A.pos_id IS NOT NULL AND CHANNEL = '홈/미디어'
                                                                                      GROUP BY bg_code
                                                                                   )AS B ON A.bg_code = B.bg_code
                                                                                   LEFT JOIN
                                                                                   (
                                                                                      SELECT did_pos_code.channel
                                                                                             ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
                                                                                             ,did_pos_code.bg_code
                                                                                      FROM did_pos_code
                                                                                      LEFT JOIN
                                                                                      (
                                                                                         SELECT pos_id
                                                                                         FROM did_log_type_3
                                                                                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                                                         AND page_id = 'p900005'
                                                                                         GROUP BY pos_id
                                                                                         UNION
                                                                                         SELECT pos_id
                                                                                         FROM did_log_type_4
                                                                                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                                                         GROUP BY pos_id
                                                                                      )AS A ON did_pos_code.pos_code = A.pos_id
                                                                                      WHERE A.pos_id IS NOT NULL AND CHANNEL = '홈/미디어'
                                                                                      GROUP BY bg_code
                                                                                   )AS C ON A.bg_code = C.bg_code
                                                                                   LEFT JOIN
                                                                                   (
                                                                                      SELECT B.channel
                                                                                             ,B.pos_code
                                                                                             ,SUM(B.start_cnt)  AS 'start_cnt'
                                                                                             ,B.bg_code
                                                                                      FROM
                                                                                      (
                                                                                         SELECT did_pos_code.channel
                                                                                               ,did_pos_code.pos_code
                                                                                               ,SUM(A.cnt) AS 'start_cnt'
                                                                                               ,did_pos_code.bg_code
                                                                                         FROM did_pos_code
                                                                                         LEFT JOIN
                                                                                         (
                                                                                            SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) ,  COUNT(pos_id) AS `cnt`
                                                                                            FROM did_log_type_3
                                                                                            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                                                            AND page_id = 'p900005'
                                                                                            GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                                                            UNION ALL
                                                                                            SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                                                                                            FROM did_log_type_4
                                                                                            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
                                                                                            GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                                                                         )AS A ON did_pos_code.pos_code = A.pos_id
                                                                                         WHERE A.pos_id IS NOT NULL AND CHANNEL = '홈/미디어'
                                                                                         GROUP BY did_pos_code.bg_code
                                                                                      )AS B
                                                                                      GROUP BY B.bg_code
                                                                                   )AS D ON A.bg_code = D.bg_code
                                                                                   WHERE A.channel IS NOT NULL AND A.CHANNEL != '' AND A.CHANNEL = '홈/미디어'
                                                                                   GROUP BY A.bg_code
                                                                                   ORDER BY A.bg_code
                                                                                           ";
                                                                                           $conn->DBQ($sql);
                                                                                           $conn->DBE();


                                                                                           while($row=$conn->DBF()){


                                                                                             if($row['channel'] == '홈/미디어')
                                                                                             {
                                                                                               $data1 = '스마트홈';
                                                                                             }
                                                                                             else
                                                                                             {
                                                                                               $data1 = $row['channel'];
                                                                                             }

                                                                                             if($row['매장수'] == null)
                                                                                             {
                                                                                               $data2 = '-';
                                                                                             }
                                                                                             else
                                                                                             {
                                                                                               $data2 = number_format($row['매장수']);
                                                                                             }

                                                                                             if($row['설치수'] == null)
                                                                                             {
                                                                                               $data3 = '-';
                                                                                             }
                                                                                             else
                                                                                             {
                                                                                               $data3 = number_format($row['설치수']);
                                                                                             }

                                                                                             if($row['설치율'] == null)
                                                                                             {
                                                                                               $data4 = '-';
                                                                                             }
                                                                                             else
                                                                                             {
                                                                                               $data4 = number_format($row['설치율'], 2).'%';
                                                                                             }

                                                                                             if($row['실행수'] == null)
                                                                                             {
                                                                                               $data5 = '-';
                                                                                             }
                                                                                             else
                                                                                             {
                                                                                               $data5 = number_format($row['실행수']);
                                                                                             }

                                                                                             if(number_format(($row['실행수']/$row['설치수'])*100,2) == nan)
                                                                                             {
                                                                                               $data6 = '-';
                                                                                             }
                                                                                             else
                                                                                             {
                                                                                               $data6 = number_format(($row['실행수']/$row['설치수'])*100,2).'%';
                                                                                             }

                                                                                             if($row['사용횟수']==null)
                                                                                             {
                                                                                               $data7 = '-';
                                                                                             }
                                                                                             else
                                                                                             {
                                                                                               $data7 = $row['사용횟수'];
                                                                                             }

                                                                                             if($row['평균사용률']==null)
                                                                                             {
                                                                                               $data8 = '-';
                                                                                             }
                                                                                             else
                                                                                             {
                                                                                               $data8 = $row['평균사용률'];
                                                                                             }


                                                                                             $objPHPExcel->setActiveSheetIndex(1)
                                                                                                       ->setCellValue("A$count4", $j)
                                                                                                       ->setCellValue("B$count4", $data1)
                                                                                                       ->setCellValue("C$count4", $row['bg_code'])
                                                                                                       ->setCellValue("D$count4", $data2)
                                                                                                       ->setCellValue("E$count4", $data3)
                                                                                                       ->setCellValue("F$count4", $data4)
                                                                                                       ->setCellValue("G$count4", $data5)
                                                                                                       ->setCellValue("H$count4", $data6)
                                                                                                       ->setCellValue("I$count4", $data7)
                                                                                                       ->setCellValue("J$count4", $data8);


                                                                                                       $j++;
                                                                                                       $count4++;
                                                                                                     }



																																				$countc = $count4+1;

																												                $objPHPExcel->getActiveSheet()->getStyle("A$countc:J$countc")->getFont()->setBold(true);
																												                $objPHPExcel->setActiveSheetIndex(1)
																												                          ->setCellValue("A$countc", "NO.")
																												                          ->setCellValue("B$countc", "영업담당")
																												                          ->setCellValue("C$countc", "지원팀")
																												                          ->setCellValue("D$countc", "POS코드")
																												                          ->setCellValue("E$countc", "매장명")
																												                          ->setCellValue("F$countc", "설치수")
																												                          ->setCellValue("G$countc", "설치율")
																												                          ->setCellValue("H$countc", "실행수")
																												                          ->setCellValue("I$countc", "실행율")
																												                          ->setCellValue("J$countc", "총 사용횟수");


																												      $sql = "
																												      SELECT
																												        A.CHANNEL,
																												        A.bg_code,
																												        A.pos_code AS 'POS코드',
																												        A.pos_name AS '매장명',
																												        B.exe_cnt AS '설치수',
																												        B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율',
																												        C.touch_cnt / COUNT(A.pos_code) * 100 AS '사용율',
																												        C.touch_cnt AS '실행수',
																												        D.start_cnt AS '사용횟수'
																												       FROM did_pos_code AS A
																												       LEFT JOIN
																												        (
																												       SELECT
																												                  did_pos_code.CHANNEL,
																												                  did_pos_code.bg_code,
																												                  did_pos_code.pos_code,
																												                  COUNT(did_pos_code.pos_code) AS 'exe_cnt'
																												       FROM did_pos_code
																												       LEFT JOIN
																												                  (
																												                   SELECT pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
																												                   FROM did_log_type_1
																												                   WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
																												                   GROUP BY pos_id
																												                 ) AS A ON
																												                          did_pos_code.pos_code = A.pos_id
																												       WHERE A.pos_id IS NOT NULL
																												       GROUP BY pos_code
																												       ) AS B ON
																												        A.pos_code = B.pos_code
																												       LEFT JOIN
																												        (
																												          SELECT
																												                      did_pos_code.channel,
																												                      did_pos_code.bg_code,
																												                      did_pos_code.pos_code
																												                      ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
																												                     FROM did_pos_code
																												                    LEFT JOIN
																												                    (
																												                    SELECT pos_id
																												                       FROM did_log_type_3
																												                    WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
																												                     AND page_id = 'p900005'
																												                    GROUP BY pos_id
																												                        UNION
																												                      SELECT pos_id
																												                      FROM did_log_type_4
																												                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
																												                      GROUP BY pos_id
																												                    )AS A
																												                    ON
																												                     did_pos_code.pos_code = A.pos_id
																												                     WHERE A.pos_id IS NOT NULL
																												                     GROUP BY pos_code

																												       ) AS C ON
																												        A.pos_code = C.pos_code
																												       LEFT JOIN
																												        (
																												       SELECT
																												                B.CHANNEL
																												               ,B.bg_code
																												               , B.pos_code
																												               , SUM(B.start_cnt) AS 'start_cnt'
																												       FROM
																												                (
																												       SELECT
																												                did_pos_code.channel,
																												                did_pos_code.bg_code,
																												                did_pos_code.pos_code
																												               , SUM(A.cnt) AS 'start_cnt'
																												       FROM did_pos_code
																												       LEFT JOIN
																												                (
																												       SELECT pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')), COUNT(pos_id) AS `cnt`
																												       FROM did_log_type_3
																												       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."' AND page_id = 'p900005'
																												       GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) UNION ALL
																												       SELECT pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')), COUNT(pos_id) AS `cnt`
																												       FROM did_log_type_4
																												       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
																												       GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
																												               ) AS A ON
																												                did_pos_code.pos_code = A.pos_id
																												       WHERE A.pos_id IS NOT NULL
																												       GROUP BY did_pos_code.pos_code
																												               ) AS B
																												       GROUP BY B.pos_code

																												       ) AS D ON
																												        A.pos_code = D.pos_code
																												       GROUP BY A.pos_code
																															 order by A.CHANNEL,
																															 A.bg_code

																												      ";
																												      $conn->DBQ($sql);
																												      $conn->DBE();
																												      $j = 1;

																															$countd = $countc+1;
																												      while($row=$conn->DBF()){
																												        if($row['디바이스수'] >= 1){ $temp = 1; }


																												        if($row['설치수'] == null)
																												        {
																												          $data3 = '-';
																												        }
																												        else
																												        {
																												          $data3 = number_format($row['설치수']);
																												        }

																												        if($row['설치율'] == null)
																												        {
																												          $data4 = '-';
																												        }
																												        else
																												        {
																												          $data4 = number_format($row['설치율'], 2).'%';
																												        }

																												        if($row['실행수'] == null)
																												        {
																												          $data5 = '-';
																												        }
																												        else
																												        {
																												          $data5 = number_format($row['실행수']);
																												        }

																												        if(number_format(($row['실행수']/$row['설치수'])*100,2) == nan)
																												        {
																												          $data6 = '-';
																												        }
																												        else
																												        {
																												          $data6 = number_format(($row['실행수']/$row['설치수'])*100,2).'%';
																												        }

																												        if($row['사용횟수']==null)
																												        {
																												          $data7 = '-';
																												        }
																												        else
																												        {
																												          $data7 = $row['사용횟수'];
																												        }

																												        if($row['CHANNEL'] == '홈/미디어')
																												        {
																												          $data8 = '스마트홈';
																												        }
																												        else
																												        {
																												          $data8 = $row['CHANNEL'];
																												        }

																												        $objPHPExcel->setActiveSheetIndex(1)
																												                  ->setCellValue("A$countd", $j)
																												                  ->setCellValue("B$countd", $data8)
																												                  ->setCellValue("C$countd", $row['bg_code'])
																												                  ->setCellValue("D$countd", $row['POS코드'])
																												                  ->setCellValue("E$countd", $row['매장명'])
																												                  ->setCellValue("F$countd", $data3)
																												                  ->setCellValue("G$countd", $data4)
																												                  ->setCellValue("H$countd", $data5)
																												                  ->setCellValue("I$countd", $data6)
																												                  ->setCellValue("J$countd", $data7);


																												      $j++;
																												      $countd++;
																												    }




																												$objPHPExcel->getActiveSheet()->getStyle("A1:J$countd")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
																												$objPHPExcel->getActiveSheet()->getStyle("A1:J$countd")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
																												$objPHPExcel->setActiveSheetIndex(1)->setTitle('현장지원팀 운영 현황');

																												$objPHPExcel->setActiveSheetIndex(0);
















																						header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
																						header('Content-Disposition: attachment;filename='.$filename.'.xlsx');


																						// If you're serving to IE over SSL, then the following may be needed
																						header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
																						header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified

																						header ('Pragma: public'); // HTTP/1.0

																						$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
																						$objWriter->save('php://output');
																						exit;
																						?>
