<?php
require_once('../../assets/PHPExcel/Classes/PHPExcel.php');
//include '../common.php';
include 'layout/layout.php';
include '../dbconn.php';

$conn = new DBC();
$conn->DBI();

$objPHPExcel = new PHPExcel();

//날짜
$date_from = date($_GET['date_from']);
$date_to = date($_GET['date_to']);
$curDate = date('Y-m-d');

if($date_from == null && $date_to == null)
{
	$filename = iconv("UTF-8", "EUC-KR", "매장 관리");
}
else
{
	$filename = iconv("UTF-8", "EUC-KR", "매장 관리 (".$date_from." - ".$date_to.")");
}


//상태 인증매장 미등록매장 검색 쿼리
if($_GET['condition'] == 인증매장 && $_GET['condition1'] == 미등록매장 && $_GET['condition2'] == null){
	$condition = "";
}
if($_GET['condition'] == null && $_GET['condition1'] == 미등록매장 && $_GET['condition2'] == null){
	$condition = "and c.pos_code IS NULL";
}
if($_GET['condition'] == 인증매장 && $_GET['condition1'] == null && $_GET['condition2'] == null){
	$condition = "and c.pos_code IS not NULL";
}
if($_GET['condition'] == null && $_GET['condition1'] == null && $_GET['condition2'] == null){
	$condition = '';
}
if($_GET['condition2'] == 미설치매장 && $_GET['condition'] == null && $_GET['condition1'] == null){
	$condition ='';
}


// 검색 쿼리
if($_GET['condition2'] == 미설치매장 && $_GET['condition'] == 인증매장 && $_GET['condition1'] == 미등록매장){
	if($date_from == $date_to && $date_from != null && $date_to != null){
		$searchDate = "and d.time like '".$date_from."%'";}
		else if ($date_from == null && $date_to == null) {
			$searchDate = "";
		}
		else {
			$searchDate = " and d.time between '".$date_from."' and '".$date_to."'";}
		}

		else if($_GET['condition2'] == 미설치매장 && $_GET['condition'] == 인증매장 && $_GET['condition1'] == null){
			if($date_from == $date_to && $date_from != null && $date_to != null){
				$searchDate = "and d.time like '".$date_from."%'";}
				else if ($date_from == null && $date_to == null) {
					$searchDate = "";
				}
				else {
					$searchDate = " and d.time between '".$date_from."' and '".$date_to."'";}
				}

				else if($_GET['condition2'] == 미설치매장 && $_GET['condition'] == null && $_GET['condition1'] == null){
					$searchDate ='';
				}

				else if($_GET['condition2'] == null && $_GET['condition'] == 인증매장 && $_GET['condition1'] == 미등록매장){
					if($date_from == $date_to && $date_from != null && $date_to != null){
						$searchDate = "and d.time like '".$date_from."%'";}
						else if ($date_from == null && $date_to == null) {
							$searchDate = "";
						}
						else {
							$searchDate = " and d.time between '".$date_from."' and '".$date_to."'";}
						}

						else if($date_from == $date_to && $date_from != null && $date_to != null){
							$searchDate = "and d.time like '".$date_from."%'";}
							else if ($date_from == null && $date_to == null) {
								$searchDate = "";
							}
							else {
								$searchDate = " and d.time between '".$date_from."' and '".$date_to."'";
							}

							$searchSql;
							if($_GET['channel'] != "" && $_GET['bg_code'] != "" && $_GET['mg_code'] != "" && $_GET['search'] != "" && $_GET['search_content'] != ""){
								$channel = $_GET['channel'];
								$bg_code = $_GET['bg_code'];
								$mg_code = $_GET['mg_code'];
								if($_GET['search'] == 전체){
									$search = "c.agency_name like '%".$_GET['search_content']. "%' or c.agency_code like  '%".$_GET['search_content']. "%' or c.pos_name like '%".$_GET['search_content']. "%' or c.pos_code like   '%".$_GET['search_content']. "%' or d.pos_id like   '%".$_GET['search_content']. "%' or c.pos_address";
								}else if ($_GET['search'] == 'c.pos_code') {
									$search = "c.pos_code like  '%".$_GET['search_content']. "%' or d.pos_id";
								}
								else{
									$search = $_GET['search'];}
									$search_content = $_GET['search_content'];
									$sCase = 11;
								}
								else if($_GET['channel'] != "" && $_GET['bg_code'] != "" && $_GET['search'] != "" && $_GET['search_content'] != ""){
									$channel = $_GET['channel'];
									$bg_code = $_GET['bg_code'];
									if($_GET['search'] == 전체){
										$search = "c.agency_name like '%".$_GET['search_content']. "%' or c.agency_code like  '%".$_GET['search_content']. "%' or c.pos_name like '%".$_GET['search_content']. "%' or c.pos_code like   '%".$_GET['search_content']. "%' or d.pos_id like   '%".$_GET['search_content']. "%' or c.pos_address";
									}else if ($_GET['search'] == 'c.pos_code') {
										$search = "c.pos_code like  '%".$_GET['search_content']. "%' or d.pos_id";
									}
									else{
										$search = $_GET['search'];}
										$search_content = $_GET['search_content'];
										$sCase = 10;
									}
									else if($_GET['channel'] != "" && $_GET['mg_code'] != "" && $_GET['search'] != "" && $_GET['search_content'] != ""){
										$channel = $_GET['channel'];
										$mg_code = $_GET['mg_code'];
										if($_GET['search'] == 전체){
											$search = "c.agency_name like '%".$_GET['search_content']. "%' or c.agency_code like  '%".$_GET['search_content']. "%' or c.pos_name like '%".$_GET['search_content']. "%' or c.pos_code like   '%".$_GET['search_content']. "%' or d.pos_id like   '%".$_GET['search_content']. "%' or c.pos_address";
										}else if ($_GET['search'] == 'c.pos_code') {
											$search = "c.pos_code like  '%".$_GET['search_content']. "%' or d.pos_id";
										}
										else{
											$search = $_GET['search'];}
											$search_content = $_GET['search_content'];
											$sCase = 9;
										}
										else if($_GET['channel'] != "" && $_GET['bg_code'] != "" && $_GET['mg_code'] != ""){
											$channel = $_GET['channel'];
											$bg_code = $_GET['bg_code'];
											$mg_code = $_GET['mg_code'];
											$sCase = 8;
										}
										else if($_GET['channel'] != "" && $_GET['search'] !="" && $_GET['search_content'] != ""){
											$channel = $_GET['channel'];
											if($_GET['search'] == 전체){
												$search = "c.agency_name like '%".$_GET['search_content']. "%' or c.agency_code like  '%".$_GET['search_content']. "%' or c.pos_name like '%".$_GET['search_content']. "%' or c.pos_code like   '%".$_GET['search_content']. "%' or d.pos_id like   '%".$_GET['search_content']. "%' or c.pos_address";
											}else if ($_GET['search'] == 'c.pos_code') {
												$search = "c.pos_code like  '%".$_GET['search_content']. "%' or d.pos_id";
											}
											else{
												$search = $_GET['search'];}
												$search_content = $_GET['search_content'];
												$sCase = 7;
											}
											else if($_GET['mg_code'] != "" && $_GET['search'] !="" && $_GET['search_content'] != ""){
												$mg_code = $_GET['mg_code'];
												if($_GET['search'] == 전체){
													$search = "c.agency_name like '%".$_GET['search_content']. "%' or c.agency_code like  '%".$_GET['search_content']. "%' or c.pos_name like '%".$_GET['search_content']. "%' or c.pos_code like   '%".$_GET['search_content']. "%' or d.pos_id like   '%".$_GET['search_content']. "%' or c.pos_address";
												}else if ($_GET['search'] == 'c.pos_code') {
													$search = "c.pos_code like  '%".$_GET['search_content']. "%' or d.pos_id";
												}
												else{
													$search = $_GET['search'];}
													$search_content = $_GET['search_content'];
													$sCase = 6;
												}
												else if($_GET['search'] != "" && $_GET['search_content'] != ""){
													if($_GET['search'] == 전체){
														$search = "c.agency_name like '%".$_GET['search_content']. "%' or c.agency_code like  '%".$_GET['search_content']. "%' or c.pos_name like '%".$_GET['search_content']. "%' or c.pos_code like   '%".$_GET['search_content']. "%' or d.pos_id like   '%".$_GET['search_content']. "%' or c.pos_address";
													}else if ($_GET['search'] == 'c.pos_code') {
														$search = "c.pos_code like  '%".$_GET['search_content']. "%' or d.pos_id";
													}
													else {
														$search = $_GET['search'];}
														$search_content = $_GET['search_content'];
														$sCase = 5;
													}
													else if($_GET['channel'] != "" && $_GET['bg_code'] != ""){
														$channel = $_GET['channel'];
														$bg_code = $_GET['bg_code'];
														$sCase = 4;
													}
													else if($_GET['channel'] != "" && $_GET['mg_code'] != ""){
														$channel = $_GET['channel'];
														$mg_code = $_GET['mg_code'];
														$sCase = 3;
													}
													else if($_GET['channel'] != ""){
														$channel = $_GET['channel'];
														$sCase = 2;
													}
													else if($_GET['mg_code'] != ""){
														$mg_code = $_GET['mg_code'];
														$sCase = 1;
													}


													switch($sCase)
													{
														case 11:
														$searchSql = "  REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "' and c.bg_code = '" .$bg_code. "'  and c.mg_code = '" .$mg_code. "' and (".$search." like '%" .$search_content. "%')";
														break;

														case 10:
														$searchSql = "  REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "' and c.bg_code = '" .$bg_code. "'and (".$search." like '%" .$search_content. "%')";
														break;

														case 9:
														$searchSql = " REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "' and c.mg_code = '" .$mg_code. "' and (".$search." like '%" .$search_content. "%')";
														break;

														case 8:
														$searchSql = "  REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "' and c.bg_code = '" .$bg_code. "'  and mg_code = '" .$mg_code. "'";
														break;

														case 7:
														$searchSql = "  REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "' and (".$search." like '%" .$search_content. "%')";
														break;

														case 6:
														$searchSql = "  c.mg_code = '" .$mg_code. "' and (".$search." like '%" .$search_content. "%')";
														break;

														case 5:
														$searchSql = "  (".$search." like '%".$search_content."%')";
														break;

														case 4:
														$searchSql = "  REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "' and c.bg_code = '" .$bg_code. "'";
														break;

														case 3:
														$searchSql = "  REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "' and c.mg_code = '" .$mg_code. "'";
														break;

														case 2:
														$searchSql = "  REPLACE(c.CHANNEL,'홈/미디어','스마트홈') = '" .$channel. "'";
														break;

														case 1:
														$searchSql = "  c.mg_code = '" .$mg_code. "'";
														break;

														default:
														if($_GET['condition'] == 인증매장 && $_GET['condition1'] == 미등록매장 && $_GET['condition2'] == 미설치매장 ){
															$searchSql = "(c.pos_code IS not NULL or c.pos_code IS NULL)";}

															else if($_GET['condition'] == null && $_GET['condition1'] == 미등록매장 && $_GET['condition2'] == 미설치매장 ){
																$searchSql = "(c.pos_code IS NULL OR c.pos_code IS NOT NULL)";
															}
															else if($_GET['condition'] == 인증매장 && $_GET['condition1'] == 미등록매장 && $_GET['condition2'] == null ){

																$searchSql = "  (c.pos_code IS not NULL or c.pos_code IS NULL)";
															}

															else if($_GET['condition'] == null && $_GET['condition1'] == 미등록매장 && $_GET['condition2'] == null ){
																$searchSql = " c.pos_code IS NULL";
															}
															else if($_GET['condition'] == null && $_GET['condition1'] == null && $_GET['condition2'] == 미설치매장 ){
																$searchSql = "d.pos_id IS null";
															}else {
																$searchSql = " c.pos_code IS not NULL";
															}

															break;
														}


														//페이징
														// 인증매장 미등록매장 미설치매장
														if($_GET['condition2'] == 미설치매장 && $_GET['condition'] == 인증매장 && $_GET['condition1'] == 미등록매장){
															$sql =   "SELECT * from
															(select
																c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
																c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
																FROM
																(
																	SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
																	FROM
																	did_log_type_1 AS `pos_exec`
																	WHERE
																	pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
																	GROUP BY pos_exec.pos_id
																)AS `d`
																left OUTER JOIN
																did_pos_code AS `c`
																ON d.pos_id = c.pos_code
																where $searchSql $searchDate) DUMMY_ALIAS1
																UNION
																SELECT * from
																(select
																	c.pos_code AS '매장코드',  d.pos_id AS '포스코드',REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
																	c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
																	FROM did_pos_code AS `c`
																	left OUTER JOIN
																	(
																		SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
																		FROM
																		did_log_type_1 AS `pos_exec`
																		WHERE
																		pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
																		GROUP BY pos_exec.pos_id
																	)AS `d`
																	ON d.pos_id = c.pos_code
																	where d.pos_id IS NULL AND $searchSql) DUMMY_ALIAS2
																	order by 매장코드 is null asc, 등록일 desc, 영업담당 asc, 지원팀 asc, 투자유형 asc, 운영자명 asc, 운영자코드 asc, 매장명 asc, 매장주소 asc
																	";}

																	//인증매장 미설치매장
																	else if($_GET['condition2'] == 미설치매장 && $_GET['condition'] == 인증매장 && $_GET['condition1'] == null){
																		$sql = "SELECT * FROM ( select
																			c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
																			c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
																			FROM
																			(
																				SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
																				FROM
																				did_log_type_1 AS `pos_exec`
																				WHERE
																				pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
																				GROUP BY pos_exec.pos_id
																			)AS `d`
																			left OUTER JOIN
																			did_pos_code AS `c`
																			ON d.pos_id = c.pos_code
																			WHERE c.pos_code  IS not NULL and $searchSql $searchDate
																		) DUMMY_ALIAS1
																		WHERE 매장코드  IS not NULL
																		UNION
																		SELECT * FROM (SELECT
																			c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
																			c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
																			FROM did_pos_code AS `c`
																			left OUTER JOIN
																			(
																				SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
																				FROM
																				did_log_type_1 AS `pos_exec`
																				WHERE
																				pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
																				GROUP BY pos_exec.pos_id
																			)AS `d`
																			ON d.pos_id = c.pos_code
																			WHERE d.pos_id IS null and $searchSql
																		) DUMMY_ALIAS2
																		order by 매장코드 is null asc, 등록일 desc, 영업담당 asc, 지원팀 asc, 투자유형 asc, 운영자명 asc, 운영자코드 asc, 매장명 asc, 매장주소 asc
																		";}

																		//미설치매장,
																		else if ($_GET['condition2'] == 미설치매장 && $_GET['condition'] == null && $_GET['condition1'] == null) {
																			$sql = "SELECT
																			c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
																			c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
																			FROM did_pos_code AS `c`
																			left OUTER JOIN
																			(
																				SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
																				FROM
																				did_log_type_1 AS `pos_exec`
																				WHERE
																				pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
																				GROUP BY pos_exec.pos_id
																			)AS `d`
																			ON d.pos_id = c.pos_code
																			WHERE $searchSql $condition $searchDate
																			order by c.pos_code is null asc, d.time desc, c.CHANNEL asc, c.bg_code ASC, c.mg_code ASC, c.agency_name  ASC, c.agency_code ASC, c.agency_name ASC, c.agency_code ASC, c.pos_name  ASC, c.pos_address asc
																			"; }

																			//미설치매장, 미등록매장
																			else if ($_GET['condition2'] == '미설치매장' && $_GET['condition'] == null && $_GET['condition1'] == 미등록매장) {
																				$sql = "SELECT * FROM (SELECT
																					c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
																					c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
																					FROM did_pos_code AS `c`
																					left OUTER JOIN
																					(
																						SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
																						FROM
																						did_log_type_1 AS `pos_exec`
																						WHERE
																						pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
																						GROUP BY pos_exec.pos_id
																					)AS `d`
																					ON d.pos_id = c.pos_code
																					WHERE d.pos_id IS NULL and $searchSql
																				) DUMMY_ALIAS1
																				UNION all
																				SELECT * FROM(
																					select
																					c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
																					c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
																					FROM
																					(
																						SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
																						FROM
																						did_log_type_1 AS `pos_exec`
																						WHERE
																						pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
																						GROUP BY pos_exec.pos_id
																					)AS `d`
																					left OUTER JOIN
																					did_pos_code AS `c`
																					ON d.pos_id = c.pos_code
																					WHERE c.pos_code IS null and $searchSql $searchDate) DUMMY_ALIAS2
																					order by 매장코드 is null asc, 등록일 desc, 영업담당 asc, 지원팀 asc, 투자유형 asc, 운영자명 asc, 운영자코드 asc, 매장명 asc, 매장주소 asc
																					";}

																					// 미등록매장 인증매장 따로
																					else {
																						$sql =
																						"SELECT
																						c.pos_code AS '매장코드',  d.pos_id AS '포스코드', REPLACE(c.CHANNEL,'홈/미디어','스마트홈') AS '영업담당', c.bg_code AS '지원팀', c.mg_code AS '투자유형', c.agency_name AS '운영자명', c.agency_code AS '운영자코드', c.pos_name AS '매장명',
																						c.pos_address '매장주소', d.time AS '등록일', d.TIME2 AS '접속일'
																						FROM
																						(
																							SELECT UPPER(pos_exec.pos_id)AS `pos_id`, date(min(pos_exec.TIMESTAMP)) AS `time`, date(max(pos_exec.TIMESTAMP)) AS `time2`
																							FROM
																							did_log_type_1 AS `pos_exec`
																							WHERE
																							pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
																							GROUP BY pos_exec.pos_id
																						)AS `d`
																						left OUTER JOIN
																						did_pos_code AS `c`
																						ON d.pos_id = c.pos_code
																						where $searchSql $condition $searchDate
																						order by c.pos_code is null asc, d.time desc, c.CHANNEL asc, c.bg_code ASC, c.mg_code ASC, c.agency_name  ASC, c.agency_code ASC, c.agency_name ASC, c.agency_code ASC, c.pos_name  ASC, c.pos_address asc
																						";}
																						$conn->DBQ($sql);
																						$conn->DBE();



																						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
																						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(11);
																						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(11);
																						$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(19);
																						$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(28);
																						$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
																						$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
																						$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
																						$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50);
																						$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(14);
																						$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
																						$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);

																						$objPHPExcel->getActiveSheet()->getStyle("A1:L1")->getFont()->setBold(true);

																						$objPHPExcel->setActiveSheetIndex(0)
																						->setCellValue("A1", "NO.")
																						->setCellValue("B1", "영업담당")
																						->setCellValue("C1", "지원팀")
																						->setCellValue("D1", "투자유형")
																						->setCellValue("E1", "운영자명")
																						->setCellValue("F1", "운영자코드")
																						->setCellValue("G1", "매장명")
																						->setCellValue("H1", "매장코드(POS코드)")
																						->setCellValue("I1", "매장주소")
																						->setCellValue("J1", "상태")
																						->setCellValue("K1", "등록일")
																						->setCellValue("L1", "최종 접속일");

																						$i = 0;
																						$j = 2;
																						while($store = $conn->DBF()) {
																							if($store['영업담당'] == '홈/미디어')
																							{
																								$data = '스마트홈';
																							}
																							else
																							{
																								$data = $store['영업담당'];
																							}


																							if($store['매장코드'] == null)
																							{
																								$store_code = $store['포스코드'];
																							}
																							else
																							{
																								$store_code = $store['매장코드'];
																							}


																							if ($store['매장코드'] != null && $store['지원팀'] != null && $store['등록일'] != null)
																							{
																								$store_state = '인증매장';
																							}
																							else if ($store['코드'] == null && $store['지원팀'] == null && $store['등록일'] != null )
																							{
																								$store_state = '미등록매장';
																							}
																							else if ($store['매장코드'] != null && $store['등록일'] == null )
																							{
																								$store_state = '미설치매장';
																							}

																							$objPHPExcel->setActiveSheetIndex(0)
																							->setCellValue("A$j", $i+1)
																							->setCellValue("B$j", $data)
																							->setCellValue("C$j", $store['지원팀'])
																							->setCellValue("D$j", $store['투자유형'])
																							->setCellValue("E$j", $store['운영자명'])
																							->setCellValue("F$j", $store['운영자코드'])
																							->setCellValue("G$j", $store['매장명'])
																							->setCellValue("H$j", $store_code)
																							->setCellValue("I$j", $store['매장주소'])
																							->setCellValue("J$j", $store_state)
																							->setCellValue("K$j", $store['등록일'])
																							->setCellValue("L$j", $store['접속일']);


																							$j++;
																							$i++;
																						}



																						$objPHPExcel->getActiveSheet()->getStyle("A1:L$j")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
																						$objPHPExcel->getActiveSheet()->getStyle("A1:L$j")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


																						header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
																						header('Content-Disposition: attachment;filename='.$filename.'.xlsx');


																						// If you're serving to IE over SSL, then the following may be needed
																						header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
																						header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified

																						header ('Pragma: public'); // HTTP/1.0

																						$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
																						$objWriter->save('php://output');
																						exit;
