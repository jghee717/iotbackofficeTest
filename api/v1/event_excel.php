<?php
require_once('../../assets/PHPExcel/Classes/PHPExcel.php');
require_once('../../assets/PHPExcel/Classes/PHPExcel/IOFactory.php');
include '../dbconn.php';


$conn = new DBC();
$conn->DBI();


// 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.
$dateTo = substr($_GET['date_to'], 5, 2);
$yearTo = substr($_GET['date_to'], 2, 2);
$name = iconv("UTF-8", "EUC-KR", "(관리용)스마트홈체험관앱_활용현황_".$yearTo.$dateTo."");
$filepath = "../../data/".$name;
$date_to = $_GET['date_to'];
$battleFrom = $_GET['battleFrom'];
$battleFrom2 = date("Y-m-d", strtotime("+6 days", strtotime($battleFrom)));
$battleFrom3 = date("Y-m-d", strtotime("+27 days", strtotime($battleFrom)));

$path = $_SERVER["DOCUMENT_ROOT"]."/data/(관리용)스마트홈체험관앱_활용현황_1906.xlsx";



// Read the existing excel file
$inputFileType = PHPExcel_IOFactory::identify($path);
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($path);
$objPHPExcel->setActiveSheetIndex(0)->setTitle('① 지원팀별_활용현황');
$objPHPExcel->setActiveSheetIndex(3)->setTitle($date_to.'누적_Raw');
$monthChar = substr($battleFrom, 5, 2);
$dayChar = substr($battleFrom, 8, 2);
$monthChar2 = substr($battleFrom3, 5, 2);
$dayChar2 = substr($battleFrom3, 8, 2);
$objPHPExcel->setActiveSheetIndex(4)->setTitle('배틀기간누적_'.$monthChar.$dayChar.'~'.$monthChar2.$dayChar2.'누적_Raw');

// Update it's data
//////////////////////////////////누적 raw 시트////////////////////////////
//////////////////////////////////누적 raw 시트////////////////////////////
//////////////////////////////////누적 raw 시트////////////////////////////
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
                    WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                     AND page_id = 'p900005'
                    GROUP BY pos_id
                        UNION
                      SELECT pos_id
                      FROM did_log_type_4
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
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
            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
             AND page_id = 'p900005'
            GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
               UNION ALL
            SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
               FROM did_log_type_4
            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
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
$peruse = 0;
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




$objPHPExcel->setActiveSheetIndex(3)
          ->setCellValue("A$count", $i+1)
          ->setCellValue("B$count", $data1)
          ->setCellValue("C$count", $data2)
          ->setCellValue("D$count", $data3)
          ->setCellValue("E$count", $data4)
          ->setCellValue("F$count", $data5)
          ->setCellValue("G$count", $data6)
          ->setCellValue("H$count", $data7);


          $i++;
          $count++;
        }

        $count2 = $count;

        if(number_format(($install/$store)*100,2) == nan)
        {
          $data = '-';
        }
        else
        {
          $data = number_format(($install/$store)*100,2).'%';
        }

        if(number_format(($execute/$install)*100,2) == nan)
        {
          $data2 = '-';
        }
        else
        {
          $data2 = number_format(($execute/$install)*100,2).'%';
        }

        $objPHPExcel->getActiveSheet()->getStyle("A$count2:I$count2")->getFont()->setBold(true);


        $objPHPExcel->setActiveSheetIndex(3)
        ->setCellValue("A$count2", "합계")

        ->setCellValue("C$count2", number_format($store))
        ->setCellValue("D$count2", number_format($install))
        ->setCellValue("E$count2", $data)
        ->setCellValue("F$count2", number_format($execute))
        ->setCellValue("G$count2", $data2)
        ->setCellValue("H$count2", number_format($use));



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
      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
      AND page_id = 'p900005'
      GROUP BY pos_id
      UNION
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
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
         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
         AND page_id = 'p900005'
         GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
         UNION ALL
         SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
         FROM did_log_type_4
         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
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


         $objPHPExcel->setActiveSheetIndex(3)
                   ->setCellValue("J$count4", $j+1)
                   ->setCellValue("K$count4", $data1)
                   ->setCellValue("L$count4", $row['bg_code'])
                   ->setCellValue("M$count4", $data2)
                   ->setCellValue("N$count4", $data3)
                   ->setCellValue("O$count4", $data4)
                   ->setCellValue("P$count4", $data5)
                   ->setCellValue("Q$count4", $data6)
                   ->setCellValue("R$count4", $data7);


                   $j++;
                   $count4++;
                 }


                 $count5 = $count4+1;
                $objPHPExcel->getActiveSheet()->getStyle("J$count5:R$count5")->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(3)
                          ->setCellValue("J$count5", "NO.")
                          ->setCellValue("K$count5", "영업담당")
                          ->setCellValue("L$count5", "지원팀")
                          ->setCellValue("M$count5", "매장수")
                          ->setCellValue("N$count5", "설치수")
                          ->setCellValue("O$count5", "설치율")
                          ->setCellValue("P$count5", "실행수")
                          ->setCellValue("Q$count5", "실행율")
                          ->setCellValue("R$count5", "총 사용횟수");



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
                        WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                        AND page_id = 'p900005'
                        GROUP BY pos_id
                        UNION
                        SELECT pos_id
                        FROM did_log_type_4
                        WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
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
                           WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                           AND page_id = 'p900005'
                           GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                           UNION ALL
                           SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                           FROM did_log_type_4
                           WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
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




        $objPHPExcel->setActiveSheetIndex(3)
                  ->setCellValue("J$count6", $j+1)
                  ->setCellValue("K$count6", $data1)
                  ->setCellValue("L$count6", $row['bg_code'])
                  ->setCellValue("M$count6", $data2)
                  ->setCellValue("N$count6", $data3)
                  ->setCellValue("O$count6", $data4)
                  ->setCellValue("P$count6", $data5)
                  ->setCellValue("Q$count6", $data6)
                  ->setCellValue("R$count6", $data7);


                  $j++;
                  $count6++;
                }
                $count7 = $count6+1;
               $objPHPExcel->getActiveSheet()->getStyle("J$count7:R$count7")->getFont()->setBold(true);
               $objPHPExcel->setActiveSheetIndex(3)
                         ->setCellValue("J$count7", "NO.")
                         ->setCellValue("K$count7", "영업담당")
                         ->setCellValue("L$count7", "지원팀")
                         ->setCellValue("M$count7", "매장수")
                         ->setCellValue("N$count7", "설치수")
                         ->setCellValue("O$count7", "설치율")
                         ->setCellValue("P$count7", "실행수")
                         ->setCellValue("Q$count7", "실행율")
                         ->setCellValue("R$count7", "총 사용횟수");


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
                       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                       AND page_id = 'p900005'
                       GROUP BY pos_id
                       UNION
                       SELECT pos_id
                       FROM did_log_type_4
                       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
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
                          WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                          AND page_id = 'p900005'
                          GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                          UNION ALL
                          SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                          FROM did_log_type_4
                          WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
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


        $objPHPExcel->setActiveSheetIndex(3)
                  ->setCellValue("J$count8", $j+1)
                  ->setCellValue("K$count8", $data1)
                  ->setCellValue("L$count8", $row['bg_code'])
                  ->setCellValue("M$count8", $data2)
                  ->setCellValue("N$count8", $data3)
                  ->setCellValue("O$count8", $data4)
                  ->setCellValue("P$count8", $data5)
                  ->setCellValue("Q$count8", $data6)
                  ->setCellValue("R$count8", $data7);


                  $j++;
                  $count8++;
                }
                $count9 = $count8+1;
               $objPHPExcel->getActiveSheet()->getStyle("J$count9:R$count9")->getFont()->setBold(true);
               $objPHPExcel->setActiveSheetIndex(3)
                         ->setCellValue("J$count9", "NO.")
                         ->setCellValue("K$count9", "영업담당")
                         ->setCellValue("L$count9", "지원팀")
                         ->setCellValue("M$count9", "매장수")
                         ->setCellValue("N$count9", "설치수")
                         ->setCellValue("O$count9", "설치율")
                         ->setCellValue("P$count9", "실행수")
                         ->setCellValue("Q$count9", "실행율")
                         ->setCellValue("R$count9", "총 사용횟수");



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
                       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                       AND page_id = 'p900005'
                       GROUP BY pos_id
                       UNION
                       SELECT pos_id
                       FROM did_log_type_4
                       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
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
                          WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                          AND page_id = 'p900005'
                          GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                          UNION ALL
                          SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                          FROM did_log_type_4
                          WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
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


        $objPHPExcel->setActiveSheetIndex(3)
                  ->setCellValue("J$count10", $j+1)
                  ->setCellValue("K$count10", $data1)
                  ->setCellValue("L$count10", $row['bg_code'])
                  ->setCellValue("M$count10", $data2)
                  ->setCellValue("N$count10", $data3)
                  ->setCellValue("O$count10", $data4)
                  ->setCellValue("P$count10", $data5)
                  ->setCellValue("Q$count10", $data6)
                  ->setCellValue("R$count10", $data7);


                  $j++;
                  $count10++;
                }
                $count11 = $count10+1;
               $objPHPExcel->getActiveSheet()->getStyle("J$count11:R$count11")->getFont()->setBold(true);
               $objPHPExcel->setActiveSheetIndex(3)
               ->setCellValue("J$count11", "NO.")
               ->setCellValue("K$count11", "영업담당")
               ->setCellValue("L$count11", "지원팀")
               ->setCellValue("M$count11", "매장수")
               ->setCellValue("N$count11", "설치수")
               ->setCellValue("O$count11", "설치율")
               ->setCellValue("P$count11", "실행수")
               ->setCellValue("Q$count11", "실행율")
               ->setCellValue("R$count11", "총 사용횟수");

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
             WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
             AND page_id = 'p900005'
             GROUP BY pos_id
             UNION
             SELECT pos_id
             FROM did_log_type_4
             WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
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
                WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                AND page_id = 'p900005'
                GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                UNION ALL
                SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                FROM did_log_type_4
                WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
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


                           $objPHPExcel->setActiveSheetIndex(3)
                                     ->setCellValue("J$count12", $j+1)
                                     ->setCellValue("K$count12", $data1)
                                     ->setCellValue("L$count12", $row['bg_code'])
                                     ->setCellValue("M$count12", $data2)
                                     ->setCellValue("N$count12", $data3)
                                     ->setCellValue("O$count12", $data4)
                                     ->setCellValue("P$count12", $data5)
                                     ->setCellValue("Q$count12", $data6)
                                     ->setCellValue("R$count12", $data7);


                                     $j++;
                                     $count12++;
                                   }

                                    $count13 = $count12+1;
                                   $objPHPExcel->getActiveSheet()->getStyle("J$count13:R$count13")->getFont()->setBold(true);
                                   $objPHPExcel->setActiveSheetIndex(3)
                                             ->setCellValue("J$count13", "NO.")
                                             ->setCellValue("K$count13", "영업담당")
                                             ->setCellValue("L$count13", "지원팀")
                                             ->setCellValue("M$count13", "매장수")
                                             ->setCellValue("N$count13", "설치수")
                                             ->setCellValue("O$count13", "설치율")
                                             ->setCellValue("P$count13", "실행수")
                                             ->setCellValue("Q$count13", "실행율")
                                             ->setCellValue("R$count13", "총 사용횟수");





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
                                           WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                                           AND page_id = 'p900005'
                                           GROUP BY pos_id
                                           UNION
                                           SELECT pos_id
                                           FROM did_log_type_4
                                           WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
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
                                              WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
                                              AND page_id = 'p900005'
                                              GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                              UNION ALL
                                              SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                                              FROM did_log_type_4
                                              WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
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


                                               $objPHPExcel->setActiveSheetIndex(3)
                                                         ->setCellValue("J$count14", $j+1)
                                                         ->setCellValue("K$count14", $data1)
                                                         ->setCellValue("L$count14", $row['bg_code'])
                                                         ->setCellValue("M$count14", $data2)
                                                         ->setCellValue("N$count14", $data3)
                                                         ->setCellValue("O$count14", $data4)
                                                         ->setCellValue("P$count14", $data5)
                                                         ->setCellValue("Q$count14", $data6)
                                                         ->setCellValue("R$count14", $data7);


                                                         $j++;
                                                         $count14++;
                                                       }





//////////////////////////////////주차별 raw 시트////////////////////////////
//////////////////////////////////주차별 raw 시트////////////////////////////
//////////////////////////////////주차별 raw 시트////////////////////////////
for($x = 5; $x < 9; $x++) {

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
       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$battleFrom2."'
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
                   WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                    AND page_id = 'p900005'
                   GROUP BY pos_id
                       UNION
                     SELECT pos_id
                     FROM did_log_type_4
                     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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
           WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
            AND page_id = 'p900005'
           GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
              UNION ALL
           SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
              FROM did_log_type_4
           WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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




$objPHPExcel->setActiveSheetIndex($x)
         ->setCellValue("A$count", $i+1)
         ->setCellValue("B$count", $data1)
         ->setCellValue("C$count", $data2)
         ->setCellValue("D$count", $data3)
         ->setCellValue("E$count", $data4)
         ->setCellValue("F$count", $data5)
         ->setCellValue("G$count", $data6)
         ->setCellValue("H$count", $data7);


         $i++;
         $count++;
       }

       $count2 = $count;

       if(number_format(($install/$store)*100,2) == nan)
       {
         $data = '-';
       }
       else
       {
         $data = number_format(($install/$store)*100,2).'%';
       }

       if(number_format(($execute/$install)*100,2) == nan)
       {
         $data2 = '-';
       }
       else
       {
         $data2 = number_format(($execute/$install)*100,2).'%';
       }

       $objPHPExcel->getActiveSheet()->getStyle("A$count2:I$count2")->getFont()->setBold(true);


       $objPHPExcel->setActiveSheetIndex($x)
       ->setCellValue("A$count2", "합계")

       ->setCellValue("C$count2", number_format($store))
       ->setCellValue("D$count2", number_format($install))
       ->setCellValue("E$count2", $data)
       ->setCellValue("F$count2", number_format($execute))
       ->setCellValue("G$count2", $data2)
       ->setCellValue("H$count2", number_format($use));



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
     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$battleFrom2."'
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
     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
     AND page_id = 'p900005'
     GROUP BY pos_id
     UNION
     SELECT pos_id
     FROM did_log_type_4
     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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
        WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
        AND page_id = 'p900005'
        GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
        UNION ALL
        SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
        FROM did_log_type_4
        WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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


        $objPHPExcel->setActiveSheetIndex($x)
                  ->setCellValue("J$count4", $j+1)
                  ->setCellValue("K$count4", $data1)
                  ->setCellValue("L$count4", $row['bg_code'])
                  ->setCellValue("M$count4", $data2)
                  ->setCellValue("N$count4", $data3)
                  ->setCellValue("O$count4", $data4)
                  ->setCellValue("P$count4", $data5)
                  ->setCellValue("Q$count4", $data6)
                  ->setCellValue("R$count4", $data7);


                  $j++;
                  $count4++;
                }


                $count5 = $count4+1;
               $objPHPExcel->getActiveSheet()->getStyle("J$count5:R$count5")->getFont()->setBold(true);
               $objPHPExcel->setActiveSheetIndex($x)
                         ->setCellValue("J$count5", "NO.")
                         ->setCellValue("K$count5", "영업담당")
                         ->setCellValue("L$count5", "지원팀")
                         ->setCellValue("M$count5", "매장수")
                         ->setCellValue("N$count5", "설치수")
                         ->setCellValue("O$count5", "설치율")
                         ->setCellValue("P$count5", "실행수")
                         ->setCellValue("Q$count5", "실행율")
                         ->setCellValue("R$count5", "총 사용횟수");



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
                       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$battleFrom2."'
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
                       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                       AND page_id = 'p900005'
                       GROUP BY pos_id
                       UNION
                       SELECT pos_id
                       FROM did_log_type_4
                       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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
                          WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                          AND page_id = 'p900005'
                          GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                          UNION ALL
                          SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                          FROM did_log_type_4
                          WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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




       $objPHPExcel->setActiveSheetIndex($x)
                 ->setCellValue("J$count6", $j+1)
                 ->setCellValue("K$count6", $data1)
                 ->setCellValue("L$count6", $row['bg_code'])
                 ->setCellValue("M$count6", $data2)
                 ->setCellValue("N$count6", $data3)
                 ->setCellValue("O$count6", $data4)
                 ->setCellValue("P$count6", $data5)
                 ->setCellValue("Q$count6", $data6)
                 ->setCellValue("R$count6", $data7);


                 $j++;
                 $count6++;
               }
               $count7 = $count6+1;
              $objPHPExcel->getActiveSheet()->getStyle("J$count7:R$count7")->getFont()->setBold(true);
              $objPHPExcel->setActiveSheetIndex($x)
                        ->setCellValue("J$count7", "NO.")
                        ->setCellValue("K$count7", "영업담당")
                        ->setCellValue("L$count7", "지원팀")
                        ->setCellValue("M$count7", "매장수")
                        ->setCellValue("N$count7", "설치수")
                        ->setCellValue("O$count7", "설치율")
                        ->setCellValue("P$count7", "실행수")
                        ->setCellValue("Q$count7", "실행율")
                        ->setCellValue("R$count7", "총 사용횟수");


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
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$battleFrom2."'
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
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                      AND page_id = 'p900005'
                      GROUP BY pos_id
                      UNION
                      SELECT pos_id
                      FROM did_log_type_4
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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
                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                         AND page_id = 'p900005'
                         GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                         UNION ALL
                         SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                         FROM did_log_type_4
                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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


       $objPHPExcel->setActiveSheetIndex($x)
                 ->setCellValue("J$count8", $j+1)
                 ->setCellValue("K$count8", $data1)
                 ->setCellValue("L$count8", $row['bg_code'])
                 ->setCellValue("M$count8", $data2)
                 ->setCellValue("N$count8", $data3)
                 ->setCellValue("O$count8", $data4)
                 ->setCellValue("P$count8", $data5)
                 ->setCellValue("Q$count8", $data6)
                 ->setCellValue("R$count8", $data7);


                 $j++;
                 $count8++;
               }
               $count9 = $count8+1;
              $objPHPExcel->getActiveSheet()->getStyle("J$count9:R$count9")->getFont()->setBold(true);
              $objPHPExcel->setActiveSheetIndex($x)
                        ->setCellValue("J$count9", "NO.")
                        ->setCellValue("K$count9", "영업담당")
                        ->setCellValue("L$count9", "지원팀")
                        ->setCellValue("M$count9", "매장수")
                        ->setCellValue("N$count9", "설치수")
                        ->setCellValue("O$count9", "설치율")
                        ->setCellValue("P$count9", "실행수")
                        ->setCellValue("Q$count9", "실행율")
                        ->setCellValue("R$count9", "총 사용횟수");



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
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$battleFrom2."'
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
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                      AND page_id = 'p900005'
                      GROUP BY pos_id
                      UNION
                      SELECT pos_id
                      FROM did_log_type_4
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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
                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                         AND page_id = 'p900005'
                         GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                         UNION ALL
                         SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                         FROM did_log_type_4
                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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


       $objPHPExcel->setActiveSheetIndex($x)
                 ->setCellValue("J$count10", $j+1)
                 ->setCellValue("K$count10", $data1)
                 ->setCellValue("L$count10", $row['bg_code'])
                 ->setCellValue("M$count10", $data2)
                 ->setCellValue("N$count10", $data3)
                 ->setCellValue("O$count10", $data4)
                 ->setCellValue("P$count10", $data5)
                 ->setCellValue("Q$count10", $data6)
                 ->setCellValue("R$count10", $data7);


                 $j++;
                 $count10++;
               }
               $count11 = $count10+1;
              $objPHPExcel->getActiveSheet()->getStyle("J$count11:R$count11")->getFont()->setBold(true);
              $objPHPExcel->setActiveSheetIndex($x)
              ->setCellValue("J$count11", "NO.")
              ->setCellValue("K$count11", "영업담당")
              ->setCellValue("L$count11", "지원팀")
              ->setCellValue("M$count11", "매장수")
              ->setCellValue("N$count11", "설치수")
              ->setCellValue("O$count11", "설치율")
              ->setCellValue("P$count11", "실행수")
              ->setCellValue("Q$count11", "실행율")
              ->setCellValue("R$count11", "총 사용횟수");

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
            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$battleFrom2."'
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
            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
            AND page_id = 'p900005'
            GROUP BY pos_id
            UNION
            SELECT pos_id
            FROM did_log_type_4
            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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
               WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
               AND page_id = 'p900005'
               GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
               UNION ALL
               SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
               FROM did_log_type_4
               WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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


                          $objPHPExcel->setActiveSheetIndex($x)
                                    ->setCellValue("J$count12", $j+1)
                                    ->setCellValue("K$count12", $data1)
                                    ->setCellValue("L$count12", $row['bg_code'])
                                    ->setCellValue("M$count12", $data2)
                                    ->setCellValue("N$count12", $data3)
                                    ->setCellValue("O$count12", $data4)
                                    ->setCellValue("P$count12", $data5)
                                    ->setCellValue("Q$count12", $data6)
                                    ->setCellValue("R$count12", $data7);


                                    $j++;
                                    $count12++;
                                  }

                                   $count13 = $count12+1;
                                  $objPHPExcel->getActiveSheet()->getStyle("J$count13:R$count13")->getFont()->setBold(true);
                                  $objPHPExcel->setActiveSheetIndex($x)
                                            ->setCellValue("J$count13", "NO.")
                                            ->setCellValue("K$count13", "영업담당")
                                            ->setCellValue("L$count13", "지원팀")
                                            ->setCellValue("M$count13", "매장수")
                                            ->setCellValue("N$count13", "설치수")
                                            ->setCellValue("O$count13", "설치율")
                                            ->setCellValue("P$count13", "실행수")
                                            ->setCellValue("Q$count13", "실행율")
                                            ->setCellValue("R$count13", "총 사용횟수");





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
                                          WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$battleFrom2."'
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
                                          WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                                          AND page_id = 'p900005'
                                          GROUP BY pos_id
                                          UNION
                                          SELECT pos_id
                                          FROM did_log_type_4
                                          WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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
                                             WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                                             AND page_id = 'p900005'
                                             GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                             UNION ALL
                                             SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                                             FROM did_log_type_4
                                             WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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


                                              $objPHPExcel->setActiveSheetIndex($x)
                                                        ->setCellValue("J$count14", $j+1)
                                                        ->setCellValue("K$count14", $data1)
                                                        ->setCellValue("L$count14", $row['bg_code'])
                                                        ->setCellValue("M$count14", $data2)
                                                        ->setCellValue("N$count14", $data3)
                                                        ->setCellValue("O$count14", $data4)
                                                        ->setCellValue("P$count14", $data5)
                                                        ->setCellValue("Q$count14", $data6)
                                                        ->setCellValue("R$count14", $data7);


                                                        $j++;
                                                        $count14++;

                                                      }
                                                      $battleFrom = date("Y-m-d", strtotime("+7 days", strtotime($battleFrom)));
                                                      $battleFrom2 = date("Y-m-d", strtotime("+7 days", strtotime($battleFrom2)));
                                                    }

//////////////////////////////////배틀누적 raw 시트////////////////////////////
//////////////////////////////////배틀누적 raw 시트////////////////////////////
//////////////////////////////////배틀누적 raw 시트////////////////////////////
$battleFrom = $_GET['battleFrom'];
$battleFrom2 = date("Y-m-d", strtotime("+27 days", strtotime($battleFrom)));




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
      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$battleFrom2."'
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
                  WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                   AND page_id = 'p900005'
                  GROUP BY pos_id
                      UNION
                    SELECT pos_id
                    FROM did_log_type_4
                    WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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
          WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
           AND page_id = 'p900005'
          GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
             UNION ALL
          SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
             FROM did_log_type_4
          WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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




$objPHPExcel->setActiveSheetIndex(4)
        ->setCellValue("A$count", $i+1)
        ->setCellValue("B$count", $data1)
        ->setCellValue("C$count", $data2)
        ->setCellValue("D$count", $data3)
        ->setCellValue("E$count", $data4)
        ->setCellValue("F$count", $data5)
        ->setCellValue("G$count", $data6)
        ->setCellValue("H$count", $data7);


        $i++;
        $count++;
      }

      $count2 = $count;

      if(number_format(($install/$store)*100,2) == nan)
      {
        $data = '-';
      }
      else
      {
        $data = number_format(($install/$store)*100,2).'%';
      }

      if(number_format(($execute/$install)*100,2) == nan)
      {
        $data2 = '-';
      }
      else
      {
        $data2 = number_format(($execute/$install)*100,2).'%';
      }

      $objPHPExcel->getActiveSheet()->getStyle("A$count2:I$count2")->getFont()->setBold(true);


      $objPHPExcel->setActiveSheetIndex(4)
      ->setCellValue("A$count2", "합계")

      ->setCellValue("C$count2", number_format($store))
      ->setCellValue("D$count2", number_format($install))
      ->setCellValue("E$count2", $data)
      ->setCellValue("F$count2", number_format($execute))
      ->setCellValue("G$count2", $data2)
      ->setCellValue("H$count2", number_format($use));



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
    WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$battleFrom2."'
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
    WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
    AND page_id = 'p900005'
    GROUP BY pos_id
    UNION
    SELECT pos_id
    FROM did_log_type_4
    WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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
       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
       AND page_id = 'p900005'
       GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
       UNION ALL
       SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
       FROM did_log_type_4
       WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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


       $objPHPExcel->setActiveSheetIndex(4)
                 ->setCellValue("J$count4", $j+1)
                 ->setCellValue("K$count4", $data1)
                 ->setCellValue("L$count4", $row['bg_code'])
                 ->setCellValue("M$count4", $data2)
                 ->setCellValue("N$count4", $data3)
                 ->setCellValue("O$count4", $data4)
                 ->setCellValue("P$count4", $data5)
                 ->setCellValue("Q$count4", $data6)
                 ->setCellValue("R$count4", $data7);


                 $j++;
                 $count4++;
               }


               $count5 = $count4+1;
              $objPHPExcel->getActiveSheet()->getStyle("J$count5:R$count5")->getFont()->setBold(true);
              $objPHPExcel->setActiveSheetIndex(4)
                        ->setCellValue("J$count5", "NO.")
                        ->setCellValue("K$count5", "영업담당")
                        ->setCellValue("L$count5", "지원팀")
                        ->setCellValue("M$count5", "매장수")
                        ->setCellValue("N$count5", "설치수")
                        ->setCellValue("O$count5", "설치율")
                        ->setCellValue("P$count5", "실행수")
                        ->setCellValue("Q$count5", "실행율")
                        ->setCellValue("R$count5", "총 사용횟수");



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
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$battleFrom2."'
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
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                      AND page_id = 'p900005'
                      GROUP BY pos_id
                      UNION
                      SELECT pos_id
                      FROM did_log_type_4
                      WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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
                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                         AND page_id = 'p900005'
                         GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                         UNION ALL
                         SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                         FROM did_log_type_4
                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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




      $objPHPExcel->setActiveSheetIndex(4)
                ->setCellValue("J$count6", $j+1)
                ->setCellValue("K$count6", $data1)
                ->setCellValue("L$count6", $row['bg_code'])
                ->setCellValue("M$count6", $data2)
                ->setCellValue("N$count6", $data3)
                ->setCellValue("O$count6", $data4)
                ->setCellValue("P$count6", $data5)
                ->setCellValue("Q$count6", $data6)
                ->setCellValue("R$count6", $data7);


                $j++;
                $count6++;
              }
              $count7 = $count6+1;
             $objPHPExcel->getActiveSheet()->getStyle("J$count7:R$count7")->getFont()->setBold(true);
             $objPHPExcel->setActiveSheetIndex(4)
                       ->setCellValue("J$count7", "NO.")
                       ->setCellValue("K$count7", "영업담당")
                       ->setCellValue("L$count7", "지원팀")
                       ->setCellValue("M$count7", "매장수")
                       ->setCellValue("N$count7", "설치수")
                       ->setCellValue("O$count7", "설치율")
                       ->setCellValue("P$count7", "실행수")
                       ->setCellValue("Q$count7", "실행율")
                       ->setCellValue("R$count7", "총 사용횟수");


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
                     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$battleFrom2."'
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
                     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                     AND page_id = 'p900005'
                     GROUP BY pos_id
                     UNION
                     SELECT pos_id
                     FROM did_log_type_4
                     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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
                        WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                        AND page_id = 'p900005'
                        GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                        UNION ALL
                        SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                        FROM did_log_type_4
                        WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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


      $objPHPExcel->setActiveSheetIndex(4)
                ->setCellValue("J$count8", $j+1)
                ->setCellValue("K$count8", $data1)
                ->setCellValue("L$count8", $row['bg_code'])
                ->setCellValue("M$count8", $data2)
                ->setCellValue("N$count8", $data3)
                ->setCellValue("O$count8", $data4)
                ->setCellValue("P$count8", $data5)
                ->setCellValue("Q$count8", $data6)
                ->setCellValue("R$count8", $data7);


                $j++;
                $count8++;
              }
              $count9 = $count8+1;
             $objPHPExcel->getActiveSheet()->getStyle("J$count9:R$count9")->getFont()->setBold(true);
             $objPHPExcel->setActiveSheetIndex(4)
                       ->setCellValue("J$count9", "NO.")
                       ->setCellValue("K$count9", "영업담당")
                       ->setCellValue("L$count9", "지원팀")
                       ->setCellValue("M$count9", "매장수")
                       ->setCellValue("N$count9", "설치수")
                       ->setCellValue("O$count9", "설치율")
                       ->setCellValue("P$count9", "실행수")
                       ->setCellValue("Q$count9", "실행율")
                       ->setCellValue("R$count9", "총 사용횟수");



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
                     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$battleFrom2."'
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
                     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                     AND page_id = 'p900005'
                     GROUP BY pos_id
                     UNION
                     SELECT pos_id
                     FROM did_log_type_4
                     WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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
                        WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                        AND page_id = 'p900005'
                        GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                        UNION ALL
                        SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                        FROM did_log_type_4
                        WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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


      $objPHPExcel->setActiveSheetIndex(4)
                ->setCellValue("J$count10", $j+1)
                ->setCellValue("K$count10", $data1)
                ->setCellValue("L$count10", $row['bg_code'])
                ->setCellValue("M$count10", $data2)
                ->setCellValue("N$count10", $data3)
                ->setCellValue("O$count10", $data4)
                ->setCellValue("P$count10", $data5)
                ->setCellValue("Q$count10", $data6)
                ->setCellValue("R$count10", $data7);


                $j++;
                $count10++;
              }
              $count11 = $count10+1;
             $objPHPExcel->getActiveSheet()->getStyle("J$count11:R$count11")->getFont()->setBold(true);
             $objPHPExcel->setActiveSheetIndex(4)
             ->setCellValue("J$count11", "NO.")
             ->setCellValue("K$count11", "영업담당")
             ->setCellValue("L$count11", "지원팀")
             ->setCellValue("M$count11", "매장수")
             ->setCellValue("N$count11", "설치수")
             ->setCellValue("O$count11", "설치율")
             ->setCellValue("P$count11", "실행수")
             ->setCellValue("Q$count11", "실행율")
             ->setCellValue("R$count11", "총 사용횟수");

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
           WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$battleFrom2."'
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
           WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
           AND page_id = 'p900005'
           GROUP BY pos_id
           UNION
           SELECT pos_id
           FROM did_log_type_4
           WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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
              WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
              AND page_id = 'p900005'
              GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
              UNION ALL
              SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
              FROM did_log_type_4
              WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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


                         $objPHPExcel->setActiveSheetIndex(4)
                                   ->setCellValue("J$count12", $j+1)
                                   ->setCellValue("K$count12", $data1)
                                   ->setCellValue("L$count12", $row['bg_code'])
                                   ->setCellValue("M$count12", $data2)
                                   ->setCellValue("N$count12", $data3)
                                   ->setCellValue("O$count12", $data4)
                                   ->setCellValue("P$count12", $data5)
                                   ->setCellValue("Q$count12", $data6)
                                   ->setCellValue("R$count12", $data7);


                                   $j++;
                                   $count12++;
                                 }

                                  $count13 = $count12+1;
                                 $objPHPExcel->getActiveSheet()->getStyle("J$count13:R$count13")->getFont()->setBold(true);
                                 $objPHPExcel->setActiveSheetIndex(4)
                                           ->setCellValue("J$count13", "NO.")
                                           ->setCellValue("K$count13", "영업담당")
                                           ->setCellValue("L$count13", "지원팀")
                                           ->setCellValue("M$count13", "매장수")
                                           ->setCellValue("N$count13", "설치수")
                                           ->setCellValue("O$count13", "설치율")
                                           ->setCellValue("P$count13", "실행수")
                                           ->setCellValue("Q$count13", "실행율")
                                           ->setCellValue("R$count13", "총 사용횟수");





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
                                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$battleFrom2."'
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
                                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                                         AND page_id = 'p900005'
                                         GROUP BY pos_id
                                         UNION
                                         SELECT pos_id
                                         FROM did_log_type_4
                                         WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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
                                            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
                                            AND page_id = 'p900005'
                                            GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
                                            UNION ALL
                                            SELECT pos_id , DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')),  COUNT(pos_id)  AS `cnt`
                                            FROM did_log_type_4
                                            WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) between '".$battleFrom."' and '".$battleFrom2."'
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


                                             $objPHPExcel->setActiveSheetIndex(4)
                                                       ->setCellValue("J$count14", $j+1)
                                                       ->setCellValue("K$count14", $data1)
                                                       ->setCellValue("L$count14", $row['bg_code'])
                                                       ->setCellValue("M$count14", $data2)
                                                       ->setCellValue("N$count14", $data3)
                                                       ->setCellValue("O$count14", $data4)
                                                       ->setCellValue("P$count14", $data5)
                                                       ->setCellValue("Q$count14", $data6)
                                                       ->setCellValue("R$count14", $data7);


                                                       $j++;
                                                       $count14++;

                                                     }


















                                                        $objPHPExcel->setActiveSheetIndex(0);





// Generate an updated excel file
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $name . '.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>
