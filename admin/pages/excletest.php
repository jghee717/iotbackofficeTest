<?php
include '../PHPExcel/Classes/PHPExcel.php';
function column_char($i) { return chr( 65 + $i ); }

// 자료 생성
$headers = array('ID','부서ID','이름','이메일','나이');
$rows = array(
	array(1, 1, '한놈', 'maarten@example.com', 24),
	array(2, 1, '두시기', 'paul@example.com', 30),
	array(3, 2, '석삼', 'bill.a@example.com', 29),
	array(4, 3, '석삼', 'bill.g@example.com', 25),
);
$data = array_merge(array($headers), $rows);

// 스타일 지정
$widths = array(6, 8, 8, 30, 6);
$header_bgcolor = 'FFABCDEF';

// 엑셀 생성
$last_char = column_char( count($headers) - 1 )+1;

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0)->getStyle( "B2:${last_char}2" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
$excel->setActiveSheetIndex(0)->getStyle( "B:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
$excel->getActiveSheet()->fromArray($data,NULL,'B2');
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="web-test.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
?>
