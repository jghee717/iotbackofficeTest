<?php
include '../PHPExcel/Classes/PHPExcel.php';
include 'db.php';
function column_char($i){
  return chr( 65 + $i );
}

$stad=$_POST['startd'];
$endd=$_POST['endd'];
$sot=$_POST['sort'];

$term=$stad.'~'.$endd;
switch ($sot) {
  case 'AV':
  $sot='평균체류시간';
  break;
  case 'EX':
  $sot='종료횟수';
  break;
  default:
  // code...
  break;
}

// 자료 생성
$headers = array('순위','페이지','PV','UV','평균체류시간','종료횟수');
$rows = array();
$db = new DBC;
$query=$_POST['query'];
$db->DBI();
$db->DBQ($query);
$db->DBE();
$go=1;
while ($result=$db->DBF()) {
  if ($result['page']=='') {
    $result['page']='서비스전체보기';
  }
  array_push($rows,array($go,$result['page'],$result['PV'].'회',$result['UV'].'회',round($result['AV'],1).'초',$result['EX'].'회'));
  $go++;
}

$data = array_merge(array($headers), $rows);

// 스타일 지정
$widths = array(5, 6, 20, 8, 8, 15,10);
$header_bgcolor = 'FFABCDEF';

// 엑셀 생성
$last_char = column_char( count($headers) - 1 )+1;

$excel = new PHPExcel();
$excel->setActiveSheetIndex(0)->setCellValue("B2", $term)->setCellValue("D2", "페이지별 순위")->setCellValue("G2", $sot.'순');
//$excel->setActiveSheetIndex(0)->getStyle( "B3:${last_char}3" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
//$excel->setActiveSheetIndex(0)->getStyle( "B:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
$excel->getActiveSheet()->fromArray($data,NULL,'B3');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$fname="페이지별분석(".$term.")";
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.$fname.'.xlsx');
header('Cache-Control: max-age=0');

$writer->save('php://output');
?>
