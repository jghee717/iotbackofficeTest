<?php
require_once('../../assets/PHPExcel/Classes/PHPExcel.php');
include '../common.php';
include '../dbconn.php';





$filename = iconv("UTF-8", "EUC-KR", "기간별 통계 (".$date_from." - ".$date_to.")");

$conn = new DBC();
$conn->DBI();


$sql = "select * from pre_period where timestamp >= '".$date_from."' and timestamp <= '".$date_to."' ";
$conn->DBQ($sql);
$conn->DBE();
$row=$conn->DBF();

$install_table = explode('|',$row[1]);
$app_util = explode('|',$row[2]);
$app_use = explode('|',$row[3]);

$objPHPExcel = new PHPExcel();


$date_from2 = str_replace('-', '/', $date_from);
$date_to2 = str_replace('-', '/', $date_to);

$first_day1 = date("Y-m-d", strtotime("-7 week",  strtotime($date_from)));
$end_day1 = date("Y-m-d", strtotime("-7 week",  strtotime($date_to)));

$first_day2 = date("Y/m/d", strtotime("-7 week",  strtotime($date_from2)));
$end_day2 = date("Y/m/d", strtotime("-7 week",  strtotime($date_to2)));

$first_month1 = date("Y-".substr($date_from,5,2)."-01");
$end_month1 = date("Y-".substr($date_from,5,2)."-31");
$first_month2 = date("Y/".substr($date_from2,5,2)."/01");
$end_month2 = date("Y/".substr($date_from2,5,2)."/31");

$fourWeek_first1 = $date_from;
$fourWeek_end1 = $date_to;

$fourWeek_first2 = str_replace('-', '/', $date_from);
$fourWeek_end2 = str_replace('-','/', $date_to);


$today_month = explode('-', $date_from);
switch($today_month[1])
{
  case 01:
  $today2 = strtotime("-31 days", strtotime($date_from));
  $today3 = strtotime("-0 days", strtotime($date_from));
  $date_condition = date("Y-m", $today2).'-01';
  $date_condition2 = date("Y-m", $today2).'-30';
  $date_condition3 = date("Y-m", $today3).'-01';
  $date_condition4 = date("Y-m", $today3).'-31';
  break;

  case 02:
  $today2 = strtotime("-28 days", strtotime($date_from));
  $today3 = strtotime("-0 days", strtotime($date_from));
  $date_condition = date("Y-m", $today2).'-01';
  $date_condition2 = date("Y-m", $today2).'-31';
  $date_condition3 = date("Y-m", $today3).'-01';
  $date_condition4 = date("Y-m", $today3).'-28';
  break;

  case 03:
  $today2 = strtotime("-31 days", strtotime($date_from));
  $today3 = strtotime("-0 days", strtotime($date_from));
  $date_condition = date("Y-m", $today2).'-01';
  $date_condition2 = date("Y-m", $today2).'-28';
  $date_condition3 = date("Y-m", $today3).'-01';
  $date_condition4 = date("Y-m", $today3).'-31';
  break;

  case 04:
  $today2 = strtotime("-30 days", strtotime($date_from));
  $today3 = strtotime("-0 days", strtotime($date_from));

  $date_condition = date("Y-m", $today2).'-01';
  $date_condition2 = date("Y-m", $today2).'-31';
  $date_condition3 = date("Y-m", $today3).'-01';
  $date_condition4 = date("Y-m", $today3).'-30';
  break;

  case 05:
  $today2 = strtotime("-31 days", strtotime($date_from));
  $today3 = strtotime("-0 days", strtotime($date_from));

  $date_condition = date("Y-m", $today2).'-01';
  $date_condition2 = date("Y-m", $today2).'-30';
  $date_condition3 = date("Y-m", $today3).'-01';
  $date_condition4 = date("Y-m", $today3).'-31';
  break;

  case 06:
  $today2 = strtotime("-30 days", strtotime($date_from));
  $today3 = strtotime("-0 days", strtotime($date_from));
  $date_condition = date("Y-m", $today2).'-01';
  $date_condition2 = date("Y-m", $today2).'-31';
  $date_condition3 = date("Y-m", $today3).'-01';
  $date_condition4 = date("Y-m", $today3).'-30';
  break;

  case 07:
  $today2 = strtotime("-31 days", strtotime($date_from));
  $today3 = strtotime("-0 days", strtotime($date_from));
  $date_condition = date("Y-m", $today2).'-01';
  $date_condition2 = date("Y-m", $today2).'-30';
  $date_condition3 = date("Y-m", $today3).'-01';
  $date_condition4 = date("Y-m", $today3).'-31';
  break;

  case 10:
  $today2 = strtotime("-31 days", strtotime($date_from));
  $today3 = strtotime("-0 days", strtotime($date_from));
  $date_condition = date("Y-m", $today2).'-01';
  $date_condition2 = date("Y-m", $today2).'-30';
  $date_condition3 = date("Y-m", $today3).'-01';
  $date_condition4 = date("Y-m", $today3).'-31';
  break;

  case 11:
  $today2 = strtotime("-30 days", strtotime($date_from));
  $today3 = strtotime("-0 days", strtotime($date_from));
  $date_condition = date("Y-m", $today2).'-01';
  $date_condition2 = date("Y-m", $today2).'-31';
  $date_condition3 = date("Y-m", $today3).'-01';
  $date_condition4 = date("Y-m", $today3).'-30';
  break;

  case 12:
  $today2 = strtotime("-31 days", strtotime($date_from));
  $today3 = strtotime("-0 days", strtotime($date_from));
  $date_condition = date("Y-m", $today2).'-01';
  $date_condition2 = date("Y-m", $today2).'-30';
  $date_condition3 = date("Y-m", $today3).'-01';
  $date_condition4 = date("Y-m", $today3).'-31';
  break;

  default:
  $today2 = strtotime("-30 days", strtotime($date_from));
  $today3 = strtotime("-0 days", strtotime($date_from));
  $date_condition = date("Y-m", $today2).'-01';
  $date_condition2 = date("Y-m", $today2).'-31';
  $date_condition3 = date("Y-m", $today3).'-01';
  $date_condition4 = date("Y-m", $today3).'-30';
  break;
}



                                      $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(32);
                                      $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(24);
                                      $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(24);
                                      $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
                                      $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
                                      $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(24);
                                      $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
                                      $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
                                      $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(24);
                                      $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
                                      $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);

                                      $objPHPExcel->getActiveSheet()->getStyle("B3")->getFont()->getColor()->setARGB("FFFF0000");
                                      $objPHPExcel->getActiveSheet()->getStyle("E9")->getFont()->getColor()->setARGB("FFFF0000");
                                      $objPHPExcel->getActiveSheet()->getStyle("E10")->getFont()->getColor()->setARGB("FFFF0000");
                                      $objPHPExcel->getActiveSheet()->getStyle("E11")->getFont()->getColor()->setARGB("FFFF0000");
                                      $objPHPExcel->getActiveSheet()->getStyle("E12")->getFont()->getColor()->setARGB("FFFF0000");
                                      $objPHPExcel->getActiveSheet()->getStyle("E13")->getFont()->getColor()->setARGB("FFFF0000");
                                      $objPHPExcel->getActiveSheet()->getStyle("E14")->getFont()->getColor()->setARGB("FFFF0000");
                                      $objPHPExcel->getActiveSheet()->getStyle("E15")->getFont()->getColor()->setARGB("FFFF0000");
                                      $objPHPExcel->getActiveSheet()->getStyle("E16")->getFont()->getColor()->setARGB("FFFF0000");
                                      $objPHPExcel->getActiveSheet()->getStyle("A1:K23")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                      $objPHPExcel->getActiveSheet()->getStyle("A1:K23")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);



$objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue("A1", "전체 수")
          ->setCellValue("A2", "설치 수")
          ->setCellValue("A3", "설치 비율")
          ->setCellValue("D1", "앱 설치")
          ->setCellValue("D2", "총 앱 실행")
          ->setCellValue("D3", "총 앱 실행 비율");






        $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValueExplicit("B1", number_format($install_table[0]));





      $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit("B2", number_format($install_table[1]))
                ->setCellValueExplicit("B3", $install_table[2])
                ->setCellValueExplicit("E1", number_format($install_table[3]));





          $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueExplicit("E2", number_format($install_table[4]))
                    ->setCellValueExplicit("E3", $install_table[5]);




                    $objPHPExcel->setActiveSheetIndex(0)
                              ->setCellValue("A8", "기간")
                              ->setCellValue("B8", "기간별 총 실행 매장 수")
                              ->setCellValue("C8", "기간별 신규등록 매장 수")
                              ->setCellValue("D8", "기간별 실행 비중")
                              ->setCellValue("E8", "지난주 대비 실행률");



                                $objPHPExcel->setActiveSheetIndex(0)
                      ->setCellValue("A9", $app_util[0])
                      ->setCellValue("B9", $app_util[1])
                      ->setCellValue("C9", $app_util[2])
                      ->setCellValue("D9", $app_util[3])
                      ->setCellValue("E9", $app_util[4])
                      ->setCellValue("A10", $app_util[5])
                      ->setCellValue("B10", $app_util[6])
                      ->setCellValue("C10", $app_util[7])
                      ->setCellValue("D10", $app_util[8])
                      ->setCellValue("E10", $app_util[9])
                      ->setCellValue("A11", $app_util[10])
                      ->setCellValue("B11", $app_util[11])
                      ->setCellValue("C11", $app_util[12])
                      ->setCellValue("D11", $app_util[13])
                      ->setCellValue("E11", $app_util[14])
                      ->setCellValue("A12", $app_util[15])
                      ->setCellValue("B12", $app_util[16])
                      ->setCellValue("C12", $app_util[17])
                      ->setCellValue("D12", $app_util[18])
                      ->setCellValue("E12", $app_util[19])
                      ->setCellValue("A13", $app_util[20])
                      ->setCellValue("B13", $app_util[21])
                      ->setCellValue("C13", $app_util[22])
                      ->setCellValue("D13", $app_util[23])
                      ->setCellValue("E13", $app_util[24])
                      ->setCellValue("A14", $app_util[25])
                      ->setCellValue("B14", $app_util[26])
                      ->setCellValue("C14", $app_util[27])
                      ->setCellValue("D14", $app_util[28])
                      ->setCellValue("E14", $app_util[29])
                      ->setCellValue("A15", $app_util[30])
                      ->setCellValue("B15", $app_util[31])
                      ->setCellValue("C15", $app_util[32])
                      ->setCellValue("D15", $app_util[33])
                      ->setCellValue("E15", $app_util[34])
                      ->setCellValue("A16", $app_util[35])
                      ->setCellValue("B16", $app_util[36])
                      ->setCellValue("C16", $app_util[37])
                      ->setCellValue("D16", $app_util[38])
                      ->setCellValue("E16", $app_util[39]);









                    $objPHPExcel->setActiveSheetIndex(0)
                              ->mergeCells("A18:B19")
                              ->setCellValue("A18", "구분")
                              ->mergeCells("C18:E18")
                              ->setCellValue("C18", "전체")
                              ->mergeCells("F18:H18")
                              ->setCellValue("F18", "U+tv 체험하기")
                              ->mergeCells("I18:K18")
                              ->setCellValue("I18", "U+IoT 체험하기")
                              ->mergeCells("A20:A21")
                              ->setCellValue("A20", "UV")
                              ->mergeCells("A22:A23")
                              ->setCellValue("A22", "PV")
                              ->setCellValue("B20", "전체")
                              ->setCellValue("B21", "P코드점")
                              ->setCellValue("B22", "전체")
                              ->setCellValue("B23", "P코드점")
                              ->setCellValue("C19", "지난달")
                              ->setCellValue("D19", "이번달(누적)")
                              ->setCellValue("E19", "이번주")
                              ->setCellValue("F19", "지난달")
                              ->setCellValue("G19", "이번달(누적)")
                              ->setCellValue("H19", "이번주")
                              ->setCellValue("I19", "지난달")
                              ->setCellValue("J19", "이번달(누적)")
                              ->setCellValue("K19", "이번주");




                              $objPHPExcel->setActiveSheetIndex(0)
                              ->setCellValue("C20", number_format($app_use[3]+$app_use[6]))
                              ->setCellValue("D20", number_format($app_use[4]+$app_use[7]))
                              ->setCellValue("E20", number_format($app_use[5]+$app_use[8]))
                              ->setCellValue("F20", number_format($app_use[3]))
                              ->setCellValue("G20", number_format($app_use[4]))
                              ->setCellValue("H20", number_format($app_use[5]))
                              ->setCellValue("I20", number_format($app_use[6]))
                              ->setCellValue("J20", number_format($app_use[7]))
                              ->setCellValue("K20", number_format($app_use[8]))
                              ->setCellValue("C21", number_format($app_use[12]+$app_use[15]))
                              ->setCellValue("D21", number_format($app_use[13]+$app_use[16]))
                              ->setCellValue("E21", number_format($app_use[14]+$app_use[17]))
                              ->setCellValue("F21", number_format($app_use[12]))
                              ->setCellValue("G21", number_format($app_use[13]))
                              ->setCellValue("H21", number_format($app_use[14]))
                              ->setCellValue("I21", number_format($app_use[15]))
                              ->setCellValue("J21", number_format($app_use[16]))
                              ->setCellValue("K21", number_format($app_use[17]))
                              ->setCellValue("C22", number_format($app_use[21]+$app_use[24]))
                              ->setCellValue("D22", number_format($app_use[22]+$app_use[25]))
                              ->setCellValue("E22", number_format($app_use[23]+$app_use[26]))
                              ->setCellValue("F22", number_format($app_use[21]))
                              ->setCellValue("G22", number_format($app_use[22]))
                              ->setCellValue("H22", number_format($app_use[23]))
                              ->setCellValue("I22", number_format($app_use[24]))
                              ->setCellValue("J22", number_format($app_use[25]))
                              ->setCellValue("K22", number_format($app_use[26]))
                              ->setCellValue("C23", number_format($app_use[30]+$app_use[33]))
                              ->setCellValue("D23", number_format($app_use[31]+$app_use[34]))
                              ->setCellValue("E23", number_format($app_use[32]+$app_use[35]))
                              ->setCellValue("F23", number_format($app_use[30]))
                              ->setCellValue("G23", number_format($app_use[31]))
                              ->setCellValue("H23", number_format($app_use[32]))
                              ->setCellValue("I23", number_format($app_use[33]))
                              ->setCellValue("J23", number_format($app_use[34]))
                              ->setCellValue("K23", number_format($app_use[35]));





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
