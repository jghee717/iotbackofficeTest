<?php
require_once('../../assets/PHPExcel/Classes/PHPExcel.php');
include '../common.php';
include '../dbconn.php';

$filename = iconv("UTF-8", "EUC-KR", "매장 이용 순위 (".$date_from." - ".$date_to.")");




$conn = new DBC();
$conn->DBI();



$objPHPExcel = new PHPExcel();



$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
$objPHPExcel->getActiveSheet()->getStyle("A1:N1")->getFont()->setBold(true);


switch ($_GET['search']) {
	case '전체':
	if($_GET['search_text'] == ''){
		$search = "where (a.agency_name like '%%' or a.agency_code like '%' or
		a.pos_name like '%%' or a.pos_id like '%%')  ";
	} else {
		$search = "where (a.agency_name like '%".$_GET['search_text']."%' or a.agency_code like '%".$_GET['search_text']."%' or
		a.pos_name like '%".$_GET['search_text']."%' or a.pos_id like '%".$_GET['search_text']."%') ";
	}
	break;

	case '운영자':
	$search = "where a.agency_name like '%".$_GET['search_text']."%' ";
	break;

	case '운영자코드':
	$search = "where a.agency_code like '%".$_GET['search_text']."%' ";
	break;

	case '매장명':
	$search = "where a.pos_name like '%".$_GET['search_text']."%' ";
	break;

	case '매장코드':
	$search = "where a.pos_id like '%".$_GET['search_text']."%' ";
	break;
}

if($_GET['channel'] == null){
	$searchChannel = " ";
} else {
	$searchChannel = " and replace(a.CHANNEL,'홈/미디어','스마트홈') = '".$_GET['channel']."' ";
}

if($_GET['bg_code'] == null){
	$searchBg = " ";
} else {
	$searchBg = " and a.bg_code = '".$_GET['bg_code']."' ";
}
$order = $_GET['order'];

$objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue("A1", "순위")
          ->setCellValue("B1", "영업담당")
          ->setCellValue("C1", "지원팀")
          ->setCellValue("D1", "운영자")
          ->setCellValue("E1", "운영자코드")
          ->setCellValue("F1", "매장명")
          ->setCellValue("G1", "매장코드")
          ->setCellValue("H1", "총 PV")
          ->setCellValue("I1", "총 UV")
					->setCellValue("J1", "평균체류시간")
          ->setCellValue("K1", "홈->침실")
          ->setCellValue("L1", "홈->거실")
          ->setCellValue("M1", "홈->주방")
          ->setCellValue("N1", "홈->아이방");

      $sql = "
      SELECT replace(a.CHANNEL,'홈/미디어','스마트홈') AS '영업담당',
      		 a.bg_code AS '지원팀', a.agency_name AS '운영자',
      		 a.agency_code AS '운영자코드', a.pos_name AS '매장명',
      		 a.pos_id AS '매장코드',
      		 a.cnt AS '총PV',
      		 c.cnt AS '총UV',
      		 b.avg_dif,
         (
           SELECT count(pos_id)
           FROM did_log_type_3
           WHERE page_id='p900002' AND pos_id= a.pos_id AND
           space_id='s000001' AND DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
         )AS '홈->침실',
         (
           SELECT count(pos_id)
           FROM did_log_type_3
           WHERE page_id='p900002' AND pos_id= a.pos_id AND
           space_id='s000002' AND DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
         )AS '홈->거실',
         (
           SELECT count(pos_id)
           FROM did_log_type_3
           WHERE page_id='p900002' AND pos_id= a.pos_id AND
           space_id='s000003' AND DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
         )AS '홈->주방',
         (
           SELECT count(pos_id)
           FROM did_log_type_3
           WHERE page_id='p900002' AND pos_id= a.pos_id AND
           space_id='s000004' AND DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
         )AS '홈->아이방'
      FROM
      (
      	SELECT a.pos_id, SUM(a.cnt)AS 'cnt',
      	   a.CHANNEL, a.bg_code, a.agency_name,
      	   a.agency_code, a.pos_name
      	FROM
      	(
      	   SELECT a.pos_id, COUNT(pos_id) AS 'cnt',
      	   did_pos_code.CHANNEL, did_pos_code.bg_code, did_pos_code.agency_name,
      	   did_pos_code.agency_code, did_pos_code.pos_name
      	   FROM did_pos_code
      	   INNER JOIN
      	   (
      	      SELECT pos_id
      	      FROM did_log_type_3
      	      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      	      AND pos_id IS NOT NULL AND pos_id != ''
      	      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
      	   )a ON did_pos_code.pos_code = a.pos_id
      	   WHERE pos_code IS NOT NULL AND pos_code != ''
      	   GROUP BY pos_id
      	   UNION ALL
      	   SELECT a.pos_id, COUNT(pos_id) AS 'cnt',
      	   did_pos_code.CHANNEL, did_pos_code.bg_code, did_pos_code.agency_name,
      	   did_pos_code.agency_code, did_pos_code.pos_name
      	   FROM did_pos_code
      	   INNER JOIN
      	   (
      	      SELECT pos_id
      	      FROM did_log_type_3
      	      WHERE space_id IN('s000001','s000002','s000003','s000004')
      	      AND pos_id IS NOT NULL AND pos_id != ''
      	      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
      	   )a ON did_pos_code.pos_code = a.pos_id
      	   WHERE pos_code IS NOT NULL AND pos_code != ''
      	   GROUP BY pos_id
      	   UNION ALL
      	   SELECT a.pos_id, COUNT(pos_id) AS 'cnt',
      	   did_pos_code.CHANNEL, did_pos_code.bg_code, did_pos_code.agency_name,
      	   did_pos_code.agency_code, did_pos_code.pos_name
      	   FROM did_pos_code
      	   INNER JOIN
      	   (
      	      SELECT pos_id
      	      FROM did_log_type_4
      	      WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
      	      AND pos_id IS NOT NULL AND pos_id != ''
      	   )a ON did_pos_code.pos_code = a.pos_id
      	   WHERE pos_code IS NOT NULL AND pos_code != ''
      	   GROUP BY pos_id
      	)a
      	GROUP BY a.pos_id
      )a
      LEFT JOIN
      (
      	SELECT a.pos_id, SUM(a.cnt)AS 'cnt'
      	FROM
      	(
      	   SELECT a.pos_id, COUNT(a.TIMESTAMP) AS 'cnt'
      	   FROM
      	   (
      	      SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id,
      	         DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
      	      FROM did_pos_code
      	      INNER JOIN
      	      (
      	         SELECT pos_id, device_id, TIMESTAMP, page_id
      	         FROM did_log_type_2
      	         WHERE page_id = 'p900005' AND is_enter = '1'
      	         AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
      					 GROUP BY pos_id, device_id, TIMESTAMP
      	      )a ON did_pos_code.pos_code = a.pos_id
      	   )a
      	   INNER JOIN
      	   (
      	      SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.page_id, a.space_id
      	      FROM did_pos_code
      	      INNER JOIN
      	      (
      	         SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
      	         FROM did_log_type_3
      	         WHERE space_id IS NOT NULL AND page_id = 'p900005'
      	         AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
      	         GROUP BY TIMESTAMP
      	      )a ON did_pos_code.pos_code = a.pos_id
      	   )b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
      	   AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
      	   GROUP BY pos_id
      	   UNION ALL
      	   SELECT b.pos_id, COUNT(b.TIMESTAMP) AS 'cnt'
      	   FROM
      	   (
      	      SELECT a.pos_id, a.device_id, a.TIMESTAMP, a.space_id,
      	         DATE_FORMAT(DATE_ADD(a.TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
      	      FROM did_pos_code
      	      INNER JOIN
      	      (
      	         SELECT pos_id, device_id, TIMESTAMP, space_id
      	         FROM did_log_type_3
      	         WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
      	         AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
      					 GROUP BY pos_id, device_id, TIMESTAMP
      	      )a ON did_pos_code.pos_code = a.pos_id
      	   )b
      	   INNER JOIN
      	   (
      	      SELECT a.log_seq, a.pos_id, a.device_id, a.TIMESTAMP, a.space_id, a.target_id
      	      FROM did_pos_code
      	      INNER JOIN
      	      (
      	         SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
      	         FROM did_log_type_4
      	         WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
      	         AND page_id = 'p900003' AND target_id IS NOT NULL
      	         GROUP BY TIMESTAMP
      	      )a ON did_pos_code.pos_code = a.pos_id
      	   )c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
      	      AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
      	   GROUP BY pos_id
      	)a
      	GROUP BY a.pos_id
      )c ON c.pos_id = a.pos_id
      LEFT JOIN
      (
      	SELECT *,
      			 SEC_TO_TIME(AVG(TIME_TO_SEC(E.diff))) AS avg_dif
      	FROM
      	(
      		SELECT A.pos_id,
      				 A.device_id,
      				 @var,
      				 CASE WHEN @user = device_id THEN 0 ELSE @var := 0 END,
      				 TIMEDIFF( STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s') , @var) AS diff,
      				 @user := device_id,@var := STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')
      		FROM
      		(
      			SELECT *
      			FROM did_log_type_2
      			WHERE DATE(STR_TO_DATE(TIMESTAMP,'%Y%m%d%H%i%s')) BETWEEN '".$date_from."' AND '".$date_to."'
      			GROUP BY device_id , TIMESTAMP ORDER BY device_id,TIMESTAMP
      		)AS A,
      		(
      			SELECT @var := 0, @user := ''
      		)AS t
      	)AS E
      	WHERE E.diff IS NOT NULL AND E.diff < '00:15:00'
      	GROUP BY E.pos_id
      )b ON a.pos_id = b.pos_id
      $search $searchChannel $searchBg
      GROUP BY a.pos_id
      ORDER BY 총UV $order, 매장코드, 매장명, 운영자코드, 운영자, 영업담당, 지원팀, avg_dif
      ";
			$conn->DBQ($sql);
			$conn->DBE();
      $i = 0;
			$j = 2;
			while($stats = $conn->DBF()) {
				if($stats['영업담당'] == null)
				{
					$data = '-';
				}
        else if($stats['영업담당'] == '홈/미디어')
        {
          $data = '스마트홈';
        }
				else
				{
					$data = $stats['영업담당'];
				}

				if($stats['지원팀'] == null)
				{
					$data2 = '-';
				}
				else
				{
					$data2 = $stats['지원팀'];
				}

				if($stats['운영자'] == null)
				{
					$data3 = '-';
				}
				else
				{
					$data3 = $stats['운영자'];
				}

				if($stats['운영자코드'] == null)
				{
					$data4 = '-';
				}
				else
				{
					$data4 = $stats['운영자코드'];
				}

				if($stats['매장명'] == null)
				{
					$data5 = '-';
				}
				else
				{
					$data5 = $stats['매장명'];
				}

				if($stats['매장코드'] == null)
				{
					$data6 = '-';
				}
				else
				{
					$data6 = $stats['매장코드'];
				}

				if($stats['총PV'] == null)
				{
					$data7 = '-';
				}
				else
				{
					$data7 = number_format($stats['총PV']);
				}

        if($stats['총UV'] == null)
				{
					$data8 = '-';
				}
				else
				{
					$data8 = number_format($stats['총UV']);
				}

				if($stats['avg_dif'] == null)
				{
					$data9 = '00분 01초';
				}
				else
				{
					$data9 = substr($stats['avg_dif'],3,2).'분 '.substr($stats['avg_dif'],6,2).'초';
				}

        if($stats['홈->침실'] == null)
				{
					$data10 = '-';
				}
				else
				{
					$data10 = $stats['홈->침실'];
				}

        if($stats['홈->거실'] == null)
        {
          $data11 = '-';
        }
        else
        {
          $data11= $stats['홈->거실'];
        }

        if($stats['홈->주방'] == null)
        {
          $data12= '-';
        }
        else
        {
          $data12= $stats['홈->주방'];
        }

        if($stats['홈->아이방'] == null)
        {
          $data13= '-';
        }
        else
        {
          $data13= $stats['홈->아이방'];
        }








				$objPHPExcel->setActiveSheetIndex(0)
				          ->setCellValue("A$j", $i+1)
				          ->setCellValue("B$j", $data)
				          ->setCellValue("C$j", $data2)
				          ->setCellValue("D$j", $data3)
				          ->setCellValue("E$j", $data4)
				          ->setCellValue("F$j", $data5)
				          ->setCellValue("G$j", $data6)
				          ->setCellValue("H$j", $data7)
									->setCellValue("I$j", $data8)
                  ->setCellValue("J$j", $data9)
                  ->setCellValue("K$j", $data10)
                  ->setCellValue("L$j", $data11)
                  ->setCellValue("M$j", $data12)
                  ->setCellValue("N$j", $data13);

									$i++;
									$j++;
				}
			$objPHPExcel->getActiveSheet()->getStyle("A1:N$j")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle("A1:N$j")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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
