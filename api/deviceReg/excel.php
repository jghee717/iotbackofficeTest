<?php
require_once('../../assets/PHPExcel/Classes/PHPExcel.php');
include '../dbconn.php';



  //DB 접속
	$conn = new DBC();
	$conn->DBI();

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	$filename = iconv("UTF-8", "EUC-KR", "디바이스 관리");


	if(isset($_GET['order']))
	{
		$order = $_GET['order'];
	} else
	{
		$order = "desc";
	}


	//검색
	if($_GET['channel'] != "" && $_GET['search'] == "전체" && $_GET['bg_code'] == "" && $_GET['search_text'] != "")
	{
		$channel = $_GET['channel'];
		$search = $_GET['search'];
	  $text = $_GET['search_text'];
		$sCase = 7;
	}
	else if($_GET['channel'] != "" && $_GET['search'] != "" && $_GET['bg_code'] == "" && $_GET['search_text'] != "")
	{
		$channel = $_GET['channel'];
		$search = $_GET['search'];
	  $text = $_GET['search_text'];
		$sCase = 8;
	}
	else if($_GET['channel'] != "" && $_GET['bg_code'] == "")
	{
		$channel = $_GET['channel'];
		$sCase = 6;
	}
	else if($_GET['channel'] != "" && $_GET['search'] == "전체" && $_GET['bg_code'] != "" && $_GET['search_text'] != "")
	{
		$channel = $_GET['channel'];
		$bg_code = $_GET['bg_code'];
	  $search = $_GET['search'];
	  $text = $_GET['search_text'];
		$sCase = 5;
	}

	else if($_GET['search'] == "전체" && $_GET['search_text'] != "")
	{
		$search = $_GET['search'];
	  $text = $_GET['search_text'];
		$sCase = 4;
	}

	else if($_GET['channel'] != "" && $_GET['search'] != "" && $_GET['bg_code'] != "" && $_GET['search_text'] != "")
	{
		$channel = $_GET['channel'];
		$bg_code = $_GET['bg_code'];
	  $search = $_GET['search'];
	  $text = $_GET['search_text'];
		$sCase = 3;
	}
	else if($_GET['channel'] != "" && $_GET['bg_code'] != "")
	{
		$channel = $_GET['channel'];
		$bg_code = $_GET['bg_code'];
		$sCase = 2;
	}
	else if($_GET['search'] != "" && $_GET['search_text'] != ""){
		$search = $_GET['search'];
		$text = $_GET['search_text'];
		$sCase = 1;
	}
	//검색 스위치
	switch($sCase)
	{
		case 1:
		$searchSql = ' where '.$search.' like "%'.$text.'%" ';
		break;

		case 2:
		$searchSql = ' where REPLACE(CHANNEL,"홈/미디어","스마트홈") = "' .$channel. '" and bg_code = "' .$bg_code. '" ';
		break;

		case 3:
		$searchSql = ' where REPLACE(CHANNEL,"홈/미디어","스마트홈") = "' .$channel. '" and bg_code = "' .$bg_code. '" and '.$search.' like "%' .$text. '%" ';
		break;

		case 4:
		$searchSql = ' where a.agency_name like "%' .$text. '%" or a.agency_code like "%' .$text. '%" or a.pos_name like "%' .$text. '%" or
		a.pos_code like "%' .$text. '%" ';
		break;

		case 5:
		$searchSql = ' where REPLACE(CHANNEL,"홈/미디어","스마트홈") = "' .$channel. '" and bg_code = "' .$bg_code. '" and
		(a.agency_name like "%' .$text. '%" or a.agency_code like "%' .$text. '%" or a.pos_name like "%' .$text. '%" or
		 a.pos_code like "%' .$text. '%") ';
		break;
		case 6:
		$searchSql = ' where REPLACE(CHANNEL,"홈/미디어","스마트홈") = "' .$channel. '" ';
		break;
		case 7:
		$searchSql = ' where REPLACE(CHANNEL,"홈/미디어","스마트홈") = "' .$channel. '" and
		(a.agency_name like "%' .$text. '%" or a.agency_code like "%' .$text. '%" or a.pos_name like "%' .$text. '%" or
		 a.pos_code like "%' .$text. '%") ';
		break;
		case 8:
		$searchSql = ' where REPLACE(CHANNEL,"홈/미디어","스마트홈") = "' .$channel. '" and '.$search.' like "%' .$text. '%" ';
		break;
	}


	// 디바이스 ID, 최종접속일 배열 선언
	$sql = "SELECT REPLACE(CHANNEL,'홈/미디어','스마트홈') AS '영업담당'
	, bg_code AS '지원팀'
	, a.agency_name AS '운영자명'
	, a.agency_code AS '운영자코드'
	, a.pos_name AS '매장명'
	, a.pos_code AS '매장코드'
	, a.pos_address AS '매장주소'
	, COUNT(DISTINCT b.device_id) AS '등록수'
	, b.device_id AS '디바이스'
	, MAX(updated_at) AS '최종접속일'
	FROM did_pos_code AS a
	LEFT JOIN
	(
		SELECT pos_id,device_id,MAX(timestamp) AS updated_at
		FROM did_log_type_1
		WHERE pos_id IS NOT NULL AND pos_id != '' AND pos_id != 'P123456'
		GROUP BY pos_id, device_id
	) AS b
	ON a.pos_code = b.pos_id ".$searchSql." GROUP BY a.pos_code, b.device_id ORDER BY MAX(updated_at) $order,channel,bg_code,a.agency_name,a.pos_name";

	$conn->DBQ($sql);
	$conn->DBE();

	$count1 = 0;
	$count2 = 0;
	$count4 = 0;
	$device_id_array = array();
	$device_day_array = array();
	while($row2 = $conn->DBF())
	{
		$device_id_array2[$count1] = $row2['디바이스'];
		$device_day_array2[$count1] = $row2['최종접속일'];
		$count1++;
		}


	$sql = "SELECT REPLACE(CHANNEL,'홈/미디어','스마트홈') AS '영업담당'
	, bg_code AS '지원팀'
	, a.agency_name AS '운영자명'
	, a.agency_code AS '운영자코드'
	, a.pos_name AS '매장명'
	, a.pos_code AS '매장코드'
	, a.pos_address AS '매장주소'
	, COUNT(DISTINCT b.device_id) AS '등록수'
	, b.device_id AS '디바이스'
	, MAX(updated_at) AS '최종접속일'
	FROM did_pos_code AS a
	LEFT JOIN
	(
		SELECT pos_id,device_id,Max(timestamp) AS updated_at
		FROM did_log_type_1
		WHERE pos_id IS NOT NULL AND pos_id != '' AND pos_id != 'P123456'
		GROUP BY pos_id, device_id
	) AS b
	ON a.pos_code = b.pos_id ".$searchSql." GROUP BY a.pos_code ORDER by MAX(updated_at) $order,channel,bg_code,a.agency_name,a.pos_name";

	$conn->DBQ($sql);
	$conn->DBE();

	$pos_name_array[] = array();
	$device_num_array[] = array();

	while($row1 = $conn->DBF())
	{
		$count3 = 0;
		if($row1['등록수'] == 0)
		{
			$count4++;
		}
		while($count3 < $row1['등록수'])
		{

			$device_id_array[$count2][$count3] = $device_id_array2[$count4];
			$device_day_array[$count2][$count3] = $device_day_array2[$count4];
			$count3++;
			$count4++;
		}
		$count2++;

	}


	$sql = "SELECT REPLACE(CHANNEL,'홈/미디어','스마트홈') AS '영업담당'
	, bg_code AS '지원팀'
	, a.agency_name AS '운영자명'
	, a.agency_code AS '운영자코드'
	, a.pos_name AS '매장명'
	, a.pos_code AS '매장코드'
	, a.pos_address AS '매장주소'
	, COUNT(DISTINCT b.device_id) AS '등록수'
	, b.device_id AS '디바이스'
	, MAX(updated_at) AS '최종접속일'
	FROM did_pos_code AS a
	LEFT JOIN
	(
		SELECT pos_id,device_id,Max(timestamp) AS updated_at
		FROM did_log_type_1
		WHERE pos_id IS NOT NULL AND pos_id != '' AND pos_id != 'P123456'
		GROUP BY pos_id, device_id
	) AS b
	ON a.pos_code = b.pos_id ".$searchSql." GROUP BY a.pos_code ORDER BY MAX(updated_at) $order,channel,bg_code,a.agency_name,a.pos_name";

	$conn->DBQ($sql);
	$conn->DBE();
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getStyle("A1:J1")->getFont()->setBold(true);


	// Add some data
	$objPHPExcel->setActiveSheetIndex(0)
		         ->setCellValue("A1", "NO.")
		         ->setCellValue("B1", "영업담당")
		         ->setCellValue("C1", "지원팀")
		         ->setCellValue("D1", "운영자명")
		         ->setCellValue("E1", "운영자코드")
		         ->setCellValue("F1", "매장명")
		         ->setCellValue("G1", "매장코드(POS코드)")
		         ->setCellValue("H1", "등록 디바이스 수")
		         ->setCellValue("I1", "디바이스 ID")
		         ->setCellValue("J1", "최종 접속일");

	// Miscellaneous glyphs, UTF-8
	$j = 2;
	$i = 0;
	while($device = $conn->DBF()) {

		$count5 = 1;
		if($device[등록수] == 1) {
		$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("A$j", $i+1)
						->setCellValue("B$j", $device[영업담당])
						->setCellValue("C$j", $device[지원팀])
						->setCellValue("D$j", $device[운영자명])
						->setCellValue("E$j", $device[운영자코드])
						->setCellValue("F$j", $device[매장명])
						->setCellValue("G$j", $device[매장코드])
						->setCellValue("H$j", $device[등록수])
						->setCellValue("I$j", $device_id_array[$i][0])
						->setCellValue("J$j", substr($device_day_array[$i][0], 0, 4)."-".substr($device_day_array[$i][0], 4, 2)."-".substr($device_day_array[$i][0], 6, 2)." "
							.substr($device_day_array[$i][0], 8, 2).":".substr($device_day_array[$i][0], 10, 2).":".substr($device_day_array[$i][0], 12, 2));
					}
					else if($device[등록수] == 0)
					{
						$objPHPExcel->setActiveSheetIndex(0)
										->setCellValue("A$j", $i+1)
										->setCellValue("B$j", $device[영업담당])
										->setCellValue("C$j", $device[지원팀])
										->setCellValue("D$j", $device[운영자명])
										->setCellValue("E$j", $device[운영자코드])
										->setCellValue("F$j", $device[매장명])
										->setCellValue("G$j", $device[매장코드])
										->setCellValue("H$j", $device[등록수])
										->setCellValue("I$j", '-')
										->setCellValue("J$j", '-');
					}
					else if($device[등록수] > 1)
					{
						$l = $j+1;
						$k = $j+$device[등록수]-1;
						$objPHPExcel->setActiveSheetIndex(0)
										->mergeCells("A$j:A$k")
										->setCellValue("A$j", $i+1)
										->mergeCells("B$j:B$k")
										->setCellValue("B$j", $device[영업담당])
										->mergeCells("C$j:C$k")
										->setCellValue("C$j", $device[지원팀])
										->mergeCells("D$j:D$k")
										->setCellValue("D$j", $device[운영자명])
										->mergeCells("E$j:E$k")
										->setCellValue("E$j", $device[운영자코드])
										->mergeCells("F$j:F$k")
										->setCellValue("F$j", $device[매장명])
										->mergeCells("G$j:G$k")
										->setCellValue("G$j", $device[매장코드])
										->mergeCells("H$j:H$k")
										->setCellValue("H$j", $device[등록수])
										->setCellValue("I$j", $device_id_array[$i][0])
										->setCellValue("J$j", substr($device_day_array[$i][0], 0, 4)."-".substr($device_day_array[$i][0], 4, 2)."-".substr($device_day_array[$i][0], 6, 2)." "
											.substr($device_day_array[$i][0], 8, 2).":".substr($device_day_array[$i][0], 10, 2).":".substr($device_day_array[$i][0], 12, 2));
										while($count5 < $device[등록수])
										{
											$objPHPExcel->setActiveSheetIndex(0)
											->setCellValue("I$l", $device_id_array[$i][$count5])
											->setCellValue("J$l", substr($device_day_array[$i][$count5], 0, 4)."-".substr($device_day_array[$i][$count5], 4, 2)."-".substr($device_day_array[$i][$count5], 6, 2)." "
												.substr($device_day_array[$i][$count5], 8, 2).":".substr($device_day_array[$i][$count5], 10, 2).":".substr($device_day_array[$i][$count5], 12, 2));

											$l++;
											$count5++;
											$j=$k;
										}
									}
									$i++;
									$j++;
								}


								$objPHPExcel->getActiveSheet()->getStyle("A1:J$j")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
								$objPHPExcel->getActiveSheet()->getStyle("A1:J$j")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');


// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified

header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
