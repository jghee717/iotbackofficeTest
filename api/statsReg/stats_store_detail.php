<?php
require_once('../../assets/PHPExcel/Classes/PHPExcel.php');
include '../common.php';
include '../dbconn.php';


$filename = iconv("UTF-8", "EUC-KR", "현장 지원팀 운영현황 (".$date_from." - ".$date_to.")");



$conn = new DBC();
$conn->DBI();

$objPHPExcel = new PHPExcel();



$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);







$objPHPExcel->getActiveSheet()->getStyle("A1:J1")->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)
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


      // $sql ="
      // SELECT
      //  A.channel,
      //  A.bg_code, COUNT(A.pos_code) AS '매장수',
      //  B.exe_cnt AS '설치수',
      //  B.exe_cnt / COUNT(A.pos_code) * 100 AS '설치율',
      //  C.touch_cnt / COUNT(A.pos_code) * 100 AS '사용율',
      //  C.touch_cnt AS '실행수',
      //  D.start_cnt AS '사용횟수'
      // FROM did_pos_code AS A
      // LEFT JOIN
      //  (
      // SELECT
      //            did_pos_code.CHANNEL,
      //            did_pos_code.bg_code
      //           , COUNT(did_pos_code.pos_code) AS 'exe_cnt'
      // FROM did_pos_code
      // LEFT JOIN
      //            (
      // SELECT pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
      // FROM did_log_type_1
      // WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) <= '".$date_to."'
      // GROUP BY pos_id
      //           ) AS A ON
      //                    did_pos_code.pos_code = A.pos_id
      // WHERE A.pos_id IS NOT NULL
      // GROUP BY bg_code
      // ) AS B ON
      //  A.bg_code = B.bg_code
      // LEFT JOIN
      //  (
      //    SELECT
      //                did_pos_code.channel,
      //                did_pos_code.bg_code
      //                ,COUNT(did_pos_code.pos_code) AS 'touch_cnt'
      //               FROM did_pos_code
      //              LEFT JOIN
      //              (
      //              SELECT pos_id
      //                 FROM did_log_type_3
      //              WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
      //               AND page_id = 'p900005'
      //              GROUP BY pos_id
      //                  UNION
      //                SELECT pos_id
      //                FROM did_log_type_4
      //                WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
      //                GROUP BY pos_id
      //              )AS A
      //              ON
      //               did_pos_code.pos_code = A.pos_id
      //               WHERE A.pos_id IS NOT NULL
      //               GROUP BY bg_code
      // ) AS C ON
      //  A.bg_code = C.bg_code
      // LEFT JOIN
      //  (
      // SELECT
      //          B.CHANNEL
      //         ,B.bg_code
      //         , B.pos_code
      //         , SUM(B.start_cnt) AS 'start_cnt'
      // FROM
      //          (
      // SELECT
      //          did_pos_code.channel,
      //          did_pos_code.bg_code,
      //          did_pos_code.pos_code
      //         , SUM(A.cnt) AS 'start_cnt'
      // FROM did_pos_code
      // LEFT JOIN
      //          (
      // SELECT pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')), COUNT(pos_id) AS `cnt`
      // FROM did_log_type_3
      // WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."' AND page_id = 'p900005'
      // GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) UNION ALL
      // SELECT pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')), COUNT(pos_id) AS `cnt`
      // FROM did_log_type_4
      // WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
      // GROUP BY pos_id, DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s'))
      //         ) AS A ON
      //          did_pos_code.pos_code = A.pos_id
      // WHERE A.pos_id IS NOT NULL
      // GROUP BY did_pos_code.pos_code
      //         ) AS B
      // GROUP BY B.bg_code
      //
      // ) AS D ON
      //  A.bg_code = D.bg_code
      // GROUP BY A.bg_code
      // HAVING A.CHANNEL = '".$_GET['channel']."' AND A.bg_code = '".$_GET['bg_code']."'
      // ";

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
         WHERE A.pos_id IS NOT NULL AND CHANNEL = '".$_GET['channel']."'
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
         WHERE A.pos_id IS NOT NULL AND CHANNEL = '".$_GET['channel']."'
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
            WHERE A.pos_id IS NOT NULL AND CHANNEL = '".$_GET['channel']."'
            GROUP BY did_pos_code.bg_code
         )AS B
         GROUP BY B.bg_code
      )AS D ON A.bg_code = D.bg_code
      WHERE A.channel IS NOT NULL AND A.CHANNEL != '' AND A.CHANNEL = '".$_GET['channel']."' AND A.bg_code = '".$_GET['bg_code']."'
      GROUP BY A.bg_code
      ORDER BY A.bg_code
      ";
      $conn->DBQ($sql);
      $conn->DBE();
      $i = 1;

      while($row=$conn->DBF()){


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

        if($row['channel'] == '홈/미디어')
        {
          $data8 = '스마트홈';
        }
        else
        {
          $data8 = $row['channel'];
        }

        if($row['평균사용률'] == null)
        {
          $data9 = '-';
        }
        else
        {
          $data9 = $row['평균사용률'];
        }


        $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue("A2", $i)
                  ->setCellValue("B2", $data8)
                  ->setCellValue("C2", $row['bg_code'])
                  ->setCellValue("D2", $data2)
                  ->setCellValue("E2", $data3)
                  ->setCellValue("F2", $data4)
                  ->setCellValue("G2", $data5)
                  ->setCellValue("H2", $data6)
                  ->setCellValue("I2", $data7)
                  ->setCellValue("J2", $data9);


                  $i++;
                }


                $objPHPExcel->getActiveSheet()->getStyle("A4:J4")->getFont()->setBold(true);
                $objPHPExcel->setActiveSheetIndex(0)
                          ->setCellValue("A4", "NO.")
                          ->setCellValue("B4", "영업담당")
                          ->setCellValue("C4", "지원팀")
                          ->setCellValue("D4", "POS코드")
                          ->setCellValue("E4", "매장명")
                          ->setCellValue("F4", "설치수")
                          ->setCellValue("G4", "설치율")
                          ->setCellValue("H4", "실행수")
                          ->setCellValue("I4", "실행율")
                          ->setCellValue("J4", "총 사용횟수");


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
       HAVING A.CHANNEL = '".$_GET['channel']."' AND A.bg_code ='".$_GET['bg_code']."'
      ";
      $conn->DBQ($sql);
      $conn->DBE();
      $j = 1;
      $count = 5;
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

        $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue("A$count", $j)
                  ->setCellValue("B$count", $data8)
                  ->setCellValue("C$count", $row['bg_code'])
                  ->setCellValue("D$count", $row['POS코드'])
                  ->setCellValue("E$count", $row['매장명'])
                  ->setCellValue("F$count", $data3)
                  ->setCellValue("G$count", $data4)
                  ->setCellValue("H$count", $data5)
                  ->setCellValue("I$count", $data6)
                  ->setCellValue("J$count", $data7);


      $j++;
      $count++;
    }




$objPHPExcel->getActiveSheet()->getStyle("A1:J$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A1:J$count")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


$objPHPExcel->setActiveSheetIndex(0)->setTitle('현장지원팀 운영 현황');











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
