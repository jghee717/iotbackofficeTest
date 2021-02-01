<?
// include '../dbconn.php';
// include '../common.php';

require_once str_replace('/dashReg','',__DIR__.'/dbconn.php');
require_once str_replace('/dashReg','',__DIR__.'/common.php');

$conn = new DBC();
$conn->DBI();

#------------------------기간별 운영 데이터-------------------------#
// 전체 매장
// $sql = "SELECT COUNT(*) FROM did_pos_code";
// $conn->DBQ($sql);
// $conn->DBE();
// $total_store = $conn->DBF();

// 전체 매장중 설치 매장
// $sql = "SELECT COUNT(d.pos_id) AS `cnt`
// FROM
// (
//    SELECT UPPER(pos_exec.pos_id)AS `pos_id`
//    FROM
//    did_log_type_1 AS `pos_exec`
//    WHERE
//    pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
//    GROUP BY pos_exec.pos_id
// )AS `d`
// LEFT JOIN
// did_pos_code AS `c`
// ON d.pos_id = c.pos_code;
// ";
// $conn->DBQ($sql);
// $conn->DBE();
// $installed_store = $conn->DBF();

// 총 앱 실행 이번주
$sql = "
SELECT COUNT(a.pos_id)
FROM
(
	SELECT a.pos_id
	FROM
	(
	   SELECT a.pos_id
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
	   SELECT a.pos_id
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
	   SELECT a.pos_id
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
";
$conn->DBQ($sql);
$conn->DBE();
$sum_exec_app_total_1 = $conn->DBF();

// 총 앱 실행 저번주
$sql = "
SELECT COUNT(a.pos_id)
FROM
(
	SELECT a.pos_id
	FROM
	(
	   SELECT a.pos_id
	   FROM did_pos_code
	   INNER JOIN
	   (
	      SELECT pos_id
	      FROM did_log_type_3
	      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
	      AND pos_id IS NOT NULL AND pos_id != ''
	      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("-1 week",  strtotime($date_from)))."' AND '".date("Y-m-d", strtotime("-1 week",  strtotime($date_to)))."'
	   )a ON did_pos_code.pos_code = a.pos_id
	   WHERE pos_code IS NOT NULL AND pos_code != ''
	   GROUP BY pos_id
	   UNION ALL
	   SELECT a.pos_id
	   FROM did_pos_code
	   INNER JOIN
	   (
	      SELECT pos_id
	      FROM did_log_type_3
	      WHERE space_id IN('s000001','s000002','s000003','s000004')
	      AND pos_id IS NOT NULL AND pos_id != ''
	      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("-1 week",  strtotime($date_from)))."' AND '".date("Y-m-d", strtotime("-1 week",  strtotime($date_to)))."'
	   )a ON did_pos_code.pos_code = a.pos_id
	   WHERE pos_code IS NOT NULL AND pos_code != ''
	   GROUP BY pos_id
	   UNION ALL
	   SELECT a.pos_id
	   FROM did_pos_code
	   INNER JOIN
	   (
	      SELECT pos_id
	      FROM did_log_type_4
	      WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("-1 week",  strtotime($date_from)))."' AND '".date("Y-m-d", strtotime("-1 week",  strtotime($date_to)))."'
	      AND pos_id IS NOT NULL AND pos_id != ''
	   )a ON did_pos_code.pos_code = a.pos_id
	   WHERE pos_code IS NOT NULL AND pos_code != ''
	   GROUP BY pos_id
	)a
	GROUP BY a.pos_id
)a
";
$conn->DBQ($sql);
$conn->DBE();
$sum_exec_app_total_2 = $conn->DBF();

// 기간별 총 실행 매장
$sql = "
SELECT COUNT(a.pos_id)
FROM
(
	SELECT a.pos_id
	FROM
	(
	   SELECT a.pos_id
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
	   SELECT a.pos_id
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
	   SELECT a.pos_id
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
";
$conn->DBQ($sql);
$conn->DBE();
$weekly_all = $conn->DBF();

// 기간별 신규 매장 건수
$sql = "
SELECT COUNT(a.cnt)
FROM
(
	SELECT LOG1.pos_id, MIN(LOG1.TIMESTAMP) AS 'cnt'
	FROM did_log_type_1 LOG1 INNER JOIN did_pos_code pos ON LOG1.pos_id = pos.pos_code
	GROUP BY LOG1.pos_id
)a
WHERE DATE(a.cnt) BETWEEN '".$date_from."' AND '".$date_to."'
";
$conn->DBQ($sql);
$conn->DBE();
$sum_weekly_new = $conn->DBF();

#------------------------UV 공간 이동 전체-------------------------#
// 홈 -> 공간 전체 저번달 uv
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT pos_id, device_id, TIMESTAMP, page_id,
		DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_log_type_2
		WHERE page_id = 'p900005' AND is_enter = '1'
		AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
    GROUP BY pos_id, device_id, TIMESTAMP
	)a
	INNER JOIN
	(
		SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
		FROM did_log_type_3
		WHERE space_id IS NOT NULL AND page_id = 'p900005'
		AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
		GROUP BY TIMESTAMP
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT pos_id, device_id, TIMESTAMP, space_id,
		DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_log_type_3
		WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
		AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
    GROUP BY pos_id, device_id, TIMESTAMP
	)b
	INNER JOIN
	(
		SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
		FROM did_log_type_4
		WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
		AND page_id = 'p900003' AND target_id IS NOT NULL
		GROUP BY TIMESTAMP
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_last_month_all_total = $conn->DBF();

// 홈 -> 공간 전체 이번달 uv
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT pos_id, device_id, TIMESTAMP, page_id,
		DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_log_type_2
		WHERE page_id = 'p900005' AND is_enter = '1'
		AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
    GROUP BY pos_id, device_id, TIMESTAMP
	)a
	INNER JOIN
	(
		SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
		FROM did_log_type_3
		WHERE space_id IS NOT NULL AND page_id = 'p900005'
		AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
		GROUP BY TIMESTAMP
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT pos_id, device_id, TIMESTAMP, space_id,
		DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_log_type_3
		WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
		AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
    GROUP BY pos_id, device_id, TIMESTAMP
	)b
	INNER JOIN
	(
		SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
		FROM did_log_type_4
		WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
		AND page_id = 'p900003' AND target_id IS NOT NULL
		GROUP BY TIMESTAMP
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_month_all_total = $conn->DBF();

// 홈 -> 공간 전체 이번주 uv
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT pos_id, device_id, TIMESTAMP, page_id,
		DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_log_type_2
		WHERE page_id = 'p900005' AND is_enter = '1'
		AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
    GROUP BY pos_id, device_id, TIMESTAMP
	)a
	INNER JOIN
	(
		SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
		FROM did_log_type_3
		WHERE space_id IS NOT NULL AND page_id = 'p900005'
		AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
		GROUP BY TIMESTAMP
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
	FROM
	(
		SELECT pos_id, device_id, TIMESTAMP, space_id,
		DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
		FROM did_log_type_3
		WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
		AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
    GROUP BY pos_id, device_id, TIMESTAMP
	)b
	INNER JOIN
	(
		SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
		FROM did_log_type_4
		WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
		AND page_id = 'p900003' AND target_id IS NOT NULL
		GROUP BY TIMESTAMP
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_week_all_total = $conn->DBF();

// 홈 -> 공간 u+tv 저번달 uv
$sql = "
SELECT COUNT(a.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT pos_id, device_id, TIMESTAMP, page_id,
	DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_log_type_2
	WHERE page_id = 'p900005' AND is_enter = '1'
	AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
  GROUP BY pos_id, device_id, TIMESTAMP
)a
INNER JOIN
(
	SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
	FROM did_log_type_3
	WHERE space_id IS NOT NULL AND page_id = 'p900005'
	AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
	GROUP BY TIMESTAMP
)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_last_month_all_tv = $conn->DBF();

// 홈 -> 공간 u+tv 이번달 uv
$sql = "
SELECT COUNT(a.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT pos_id, device_id, TIMESTAMP, page_id,
	DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_log_type_2
	WHERE page_id = 'p900005' AND is_enter = '1'
	AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
  GROUP BY pos_id, device_id, TIMESTAMP
)a
INNER JOIN
(
	SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
	FROM did_log_type_3
	WHERE space_id IS NOT NULL AND page_id = 'p900005'
	AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
	GROUP BY TIMESTAMP
)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_month_all_tv = $conn->DBF();

// 홈 -> 공간 u+tv 이번주 uv
$sql = "
SELECT COUNT(a.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT pos_id, device_id, TIMESTAMP, page_id,
	DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_log_type_2
	WHERE page_id = 'p900005' AND is_enter = '1'
	AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
  GROUP BY pos_id, device_id, TIMESTAMP
)a
INNER JOIN
(
	SELECT log_key, pos_id, device_id, TIMESTAMP, page_id, space_id
	FROM did_log_type_3
	WHERE space_id IS NOT NULL AND page_id = 'p900005'
	AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
	GROUP BY TIMESTAMP
)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_week_all_tv = $conn->DBF();

// 홈 -> 공간 u+iot 저번달 uv
$sql = "
SELECT COUNT(b.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT pos_id, device_id, TIMESTAMP, space_id,
	DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_log_type_3
	WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
	AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
  GROUP BY pos_id, device_id, TIMESTAMP
)b
INNER JOIN
(
	SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
	FROM did_log_type_4
	WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
	AND page_id = 'p900003' AND target_id IS NOT NULL
	GROUP BY TIMESTAMP
)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
	AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_last_month_all_iot = $conn->DBF();

// 홈 -> 공간 u+iot 이번달 uv
$sql = "
SELECT COUNT(b.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT pos_id, device_id, TIMESTAMP, space_id,
	DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_log_type_3
	WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
	AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
  GROUP BY pos_id, device_id, TIMESTAMP
)b
INNER JOIN
(
	SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
	FROM did_log_type_4
	WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
	AND page_id = 'p900003' AND target_id IS NOT NULL
	GROUP BY TIMESTAMP
)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
	AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_month_all_iot = $conn->DBF();

// 홈 -> 공간 u+iot 이번주 uv
$sql = "
SELECT COUNT(b.TIMESTAMP) AS 'cnt'
FROM
(
	SELECT pos_id, device_id, TIMESTAMP, space_id,
	DATE_FORMAT(DATE_ADD(TIMESTAMP, INTERVAL 30 SECOND),'%Y%m%d%H%i%s') AS 'diff'
	FROM did_log_type_3
	WHERE space_id BETWEEN 's000001' AND 's000004' AND page_id = 'p900002'
	AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
  GROUP BY pos_id, device_id, TIMESTAMP
)b
INNER JOIN
(
	SELECT log_seq, pos_id, device_id, TIMESTAMP, space_id, target_id
	FROM did_log_type_4
	WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
	AND page_id = 'p900003' AND target_id IS NOT NULL
	GROUP BY TIMESTAMP
)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
	AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_week_all_iot = $conn->DBF();

#------------------------ UV 공간 이동 P코드점 -------------------------#
// 홈 -> 공간 전체 P코드 저번달 UV
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
			GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
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
			WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
			AND page_id = 'p900003' AND target_id IS NOT NULL
      GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_last_month_pcode_total = $conn->DBF();

// 홈 -> 공간 전체 P코드 이번달 UV
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
			GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
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
			WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
			AND page_id = 'p900003' AND target_id IS NOT NULL
      GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_month_pcode_total = $conn->DBF();

// 홈 -> 공간 전체 P코드 이번주 UV
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_week_pcode_total = $conn->DBF();

// 홈 -> 공간 u+tv P코드 저번달 UV
$sql = "
SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
		AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
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
		AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
		GROUP BY TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_last_month_pcode_tv = $conn->DBF();

// 홈 -> 공간 u+tv P코드 이번달 UV
$sql = "
SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
		AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
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
		AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
		GROUP BY TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_month_pcode_tv = $conn->DBF();

// 홈 -> 공간 u+tv P코드 이번주 UV
$sql = "
SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_week_pcode_tv = $conn->DBF();

// 홈 -> 공간 u+iot P코드 저번달 UV
$sql = "
SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
		AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
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
		WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
		AND page_id = 'p900003' AND target_id IS NOT NULL
    GROUP BY TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
	AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_last_month_pcode_iot = $conn->DBF();

// 홈 -> 공간 u+iot P코드 이번달 UV
$sql = "
SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
		AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
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
		WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
		AND page_id = 'p900003' AND target_id IS NOT NULL
    GROUP BY TIMESTAMP
	)a ON did_pos_code.pos_code = a.pos_id
)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
	AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_month_pcode_iot = $conn->DBF();

// 홈 -> 공간 u+iot P코드 이번주 UV
$sql = "
SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_week_pcode_iot = $conn->DBF();

#------------------------PV 공간 이동 전체-------------------------#
// 홈 -> 공간 전체 저번달 pv
$sql = "
SELECT a.cnt + b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
     SELECT pos_id
      FROM did_log_type_2
      WHERE page_id != 'p900006'
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a
)a,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
      (
        SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_last_month_all_total = $conn->DBF();


// 홈 -> 공간 전체 이번달 pv
$sql = "
SELECT a.cnt + b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_2
      WHERE page_id != 'p900006'
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a
)a,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_month_all_total = $conn->DBF();


// 홈 -> 공간 전체 이번주 pv
$sql = "
SELECT a.cnt + b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_2
      WHERE page_id != 'p900006'
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a
)a,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_week_all_total = $conn->DBF();

// 홈 -> 공간 u+tv 저번달 pv
$sql = "
SELECT b.cnt  AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a
)b";
$conn->DBQ($sql);
$conn->DBE();
$pv_last_month_all_tv = $conn->DBF();

// 홈 -> 공간 u+tv 이번달 pv
$sql = "
SELECT b.cnt  AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a
)b";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_month_all_tv = $conn->DBF();

// 홈 -> 공간 u+tv 이번주 pv
$sql = "
SELECT b.cnt  AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a
)b";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_week_all_tv = $conn->DBF();

// 홈 -> 공간 u+iot 저번달 pv
$sql = "
SELECT c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_last_month_all_iot = $conn->DBF();

// 홈 -> 공간 u+iot 이번달 pv
$sql = "
SELECT c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_month_all_iot = $conn->DBF();

// 홈 -> 공간 u+iot 이번주 pv
$sql = "
SELECT c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_week_all_iot = $conn->DBF();

#------------------------ PV 공간 이동 P코드점 -------------------------#
// 홈 -> 공간 전체 P코드 저번달 pv
$sql = "
SELECT a.cnt + b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_2
      WHERE page_id != 'p900006'
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)a,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_last_month_pcode_total = $conn->DBF();

// 홈 -> 공간 전체 P코드 이번달 pv
$sql = "
SELECT a.cnt + b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_2
      WHERE page_id != 'p900006'
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)a,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_month_pcode_total = $conn->DBF();

// 홈 -> 공간 전체 P코드 이번주 pv
$sql = "
SELECT a.cnt + b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_2
      WHERE page_id != 'p900006'
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)a,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_week_pcode_total = $conn->DBF();

// 홈 -> 공간 u+tv P코드 저번달 pv
$sql = "
SELECT  b.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)b";
$conn->DBQ($sql);
$conn->DBE();
$pv_last_month_pcode_tv = $conn->DBF();

// 홈 -> 공간 u+tv P코드 이번달 pv
$sql = "
SELECT  b.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)b";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_month_pcode_tv = $conn->DBF();

// 홈 -> 공간 u+tv P코드 이번주 pv
$sql = "
SELECT  b.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)b";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_week_pcode_tv = $conn->DBF();

// 홈 -> 공간 u+iot P코드 저번달 pv
$sql = "
SELECT c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_last_month_pcode_iot = $conn->DBF();

// 홈 -> 공간 u+iot P코드 이번달 pv
$sql = "
SELECT c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_month_pcode_iot = $conn->DBF();

// 홈 -> 공간 u+iot P코드 이번주 pv
$sql = "
SELECT c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN  '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_week_pcode_iot = $conn->DBF();

// 인증된 매장
$sql = "
SELECT count(d.pos_id)
FROM
(
SELECT UPPER(pos_exec.pos_id) AS `pos_id`, MIN(pos_exec.TIMESTAMP) AS `time`,
MAX(pos_exec.TIMESTAMP) AS `time2`
FROM did_log_type_1 AS `pos_exec`
WHERE pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
GROUP BY pos_exec.pos_id
) AS `d`
INNER
JOIN did_pos_code AS `c` ON d.pos_id = c.pos_code
WHERE c.pos_code IS NOT NULL";
$conn->DBQ($sql);
$conn->DBE();
$certificate_store = $conn->DBF();

// 미인증된 매장
$sql = "
SELECT COUNT(d.pos_id)
FROM (
SELECT UPPER(pos_exec.pos_id) AS `pos_id`, MIN(pos_exec.TIMESTAMP) AS `time`, MAX(pos_exec.TIMESTAMP) AS `time2`
FROM did_log_type_1 AS `pos_exec`
WHERE pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
GROUP BY pos_exec.pos_id
)AS `d`
LEFT OUTER
JOIN did_pos_code AS `c` ON d.pos_id = c.pos_code
WHERE c.pos_code IS NULL";
$conn->DBQ($sql);
$conn->DBE();
$un_certificate_store = $conn->DBF();

// 미사용 매장
$sql = "
SELECT count(c.pos_code)
FROM did_pos_code AS `c`
LEFT OUTER JOIN
(
SELECT UPPER(pos_exec.pos_id) AS `pos_id`, MIN(pos_exec.TIMESTAMP) AS `time`, MAX(pos_exec.TIMESTAMP) AS `time2`
FROM did_log_type_1 AS `pos_exec`
WHERE pos_exec.pos_id IS NOT NULL AND pos_exec.pos_id != ''
GROUP BY pos_exec.pos_id
)AS `d`
ON d.pos_id = c.pos_code
WHERE d.pos_id IS NULL";
$conn->DBQ($sql);
$conn->DBE();
$un_use_store = $conn->DBF();

// 전체 pv 이용 현황 오늘
$sql = "
SELECT b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d")."' AND '".date("Y-m-d")."'
   )a ON did_pos_code.pos_code = a.pos_id
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d")."' AND '".date("Y-m-d")."'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m-d")."' AND '".date("Y-m-d")."'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_today = $conn->DBF();

// 전체 uv 이용 현황 오늘
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d")."' AND '".date("Y-m-d")."'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d")."' AND '".date("Y-m-d")."'
			GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d")."' AND '".date("Y-m-d")."'
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
			WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m-d")."' AND '".date("Y-m-d")."'
			AND page_id = 'p900003' AND target_id IS NOT NULL
      GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_today = $conn->DBF();

// 전체 pv 이용 현황 어제
$sql = "
SELECT b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("-1 days"))."' AND '".date("Y-m-d", strtotime("-1 days"))."'
   )a ON did_pos_code.pos_code = a.pos_id
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("-1 days"))."' AND '".date("Y-m-d", strtotime("-1 days"))."'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("-1 days"))."' AND '".date("Y-m-d", strtotime("-1 days"))."'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_yesterday = $conn->DBF();

// 전체 uv 이용 현황 어제
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("-1 days"))."' AND '".date("Y-m-d", strtotime("-1 days"))."'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("-1 days"))."' AND '".date("Y-m-d", strtotime("-1 days"))."'
			GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("-1 days"))."' AND '".date("Y-m-d", strtotime("-1 days"))."'
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
			WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("-1 days"))."' AND '".date("Y-m-d", strtotime("-1 days"))."'
			AND page_id = 'p900003' AND target_id IS NOT NULL
      GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_yesterday = $conn->DBF();

// 전체 pv 이용 현황 저번주
$sql = "
SELECT b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$date_from." -7 days"))."' AND '".date("Y-m-d", strtotime("".$date_to." -7 days"))."'
   )a ON did_pos_code.pos_code = a.pos_id
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$date_from." -7 days"))."' AND '".date("Y-m-d", strtotime("".$date_to." -7 days"))."'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$date_from." -7 days"))."' AND '".date("Y-m-d", strtotime("".$date_to." -7 days"))."'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_last_week = $conn->DBF();

// 전체 uv 이용 현황 저번주
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$date_from." -7 days"))."' AND '".date("Y-m-d", strtotime("".$date_to." -7 days"))."'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$date_from." -7 days"))."' AND '".date("Y-m-d", strtotime("".$date_to." -7 days"))."'
			GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$date_from." -7 days"))."' AND '".date("Y-m-d", strtotime("".$date_to." -7 days"))."'
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
			WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$date_from." -7 days"))."' AND '".date("Y-m-d", strtotime("".$date_to." -7 days"))."'
			AND page_id = 'p900003' AND target_id IS NOT NULL
      GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_last_week = $conn->DBF();

// 전체 pv 이용 현황 이번주
$sql = "
SELECT b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".$date_from."' AND '".$date_to."'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_week = $conn->DBF();

// 전체 uv 이용 현황 이번주
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_week = $conn->DBF();

// 전체 pv 이용 현황 저번달
$sql = "
SELECT b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_last_month = $conn->DBF();

// 전체 uv 이용 현황 저번달
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
			GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
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
			WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-30 days"))."-01' AND '".date("Y-m", strtotime("-30 days"))."-31'
			AND page_id = 'p900003' AND target_id IS NOT NULL
      GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_last_month = $conn->DBF();

// 전체 pv 이용 현황 이번달
$sql = "
SELECT b.cnt + c.cnt + d.cnt AS 'cnt'
FROM
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)b,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_3
      WHERE space_id IN('s000001','s000002','s000003','s000004')
      AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)c,
(
   SELECT COUNT(pos_id) AS 'cnt'
   FROM did_pos_code
   INNER JOIN
   (
      SELECT pos_id
      FROM did_log_type_4
      WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
   )a ON did_pos_code.pos_code = a.pos_id
)d";
$conn->DBQ($sql);
$conn->DBE();
$pv_this_month = $conn->DBF();

// 전체 uv 이용 현황 이번달
$sql = "
SELECT a.cnt + b.cnt AS 'cnt'
FROM
(
	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
			GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
)a,
(
	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
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
			WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("-0 days"))."-01' AND '".date("Y-m", strtotime("-0 days"))."-31'
			AND page_id = 'p900003' AND target_id IS NOT NULL
      GROUP BY TIMESTAMP
		)a ON did_pos_code.pos_code = a.pos_id
	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
)b
";
$conn->DBQ($sql);
$conn->DBE();
$uv_this_month = $conn->DBF();

/******************** pv 이용 현황 일별 ************************/

for($i=11; $i>-1; $i--) {
	$j = -1 * $i;
  $sql = "
  SELECT b.cnt + c.cnt + d.cnt AS 'cnt'
  FROM
  (
     SELECT COUNT(pos_id) AS 'cnt'
     FROM did_pos_code
     INNER JOIN
     (
        SELECT pos_id
        FROM did_log_type_3
        WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
        AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$j." days"))."' AND '".date("Y-m-d", strtotime("".$j." days"))."'
     )a ON did_pos_code.pos_code = a.pos_id
  )b,
  (
     SELECT COUNT(pos_id) AS 'cnt'
     FROM did_pos_code
     INNER JOIN
     (
        SELECT pos_id
        FROM did_log_type_3
        WHERE space_id IN('s000001','s000002','s000003','s000004')
        AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$j." days"))."' AND '".date("Y-m-d", strtotime("".$j." days"))."'
     )a ON did_pos_code.pos_code = a.pos_id
  )c,
  (
     SELECT COUNT(pos_id) AS 'cnt'
     FROM did_pos_code
     INNER JOIN
     (
        SELECT pos_id
        FROM did_log_type_4
        WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$j." days"))."' AND '".date("Y-m-d", strtotime("".$j." days"))."'
     )a ON did_pos_code.pos_code = a.pos_id
  )d";
  $conn->DBQ($sql);
  $conn->DBE();
	${'per_day_'. $i} = $conn->DBF();
}

/******************** uv 이용 현황 일별 ************************/
for($i=11; $i>-1; $i--) {
	$j = -1 * $i;
  $sql = "
  SELECT a.cnt + b.cnt AS 'cnt'
  FROM
  (
  	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
  			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$j." days"))."' AND '".date("Y-m-d", strtotime("".$j." days"))."'
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
  			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$j." days"))."' AND '".date("Y-m-d", strtotime("".$j." days"))."'
  			GROUP BY TIMESTAMP
  		)a ON did_pos_code.pos_code = a.pos_id
  	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
  	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
  )a,
  (
  	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
  			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$j." days"))."' AND '".date("Y-m-d", strtotime("".$j." days"))."'
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
  			WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$j." days"))."' AND '".date("Y-m-d", strtotime("".$j." days"))."'
  			AND page_id = 'p900003' AND target_id IS NOT NULL
        GROUP BY TIMESTAMP
  		)a ON did_pos_code.pos_code = a.pos_id
  	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
  		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
  )b
  ";
  $conn->DBQ($sql);
  $conn->DBE();
	${'uv_per_day_'. $i} = $conn->DBF();
}

/******************** pv 이용 현황 주별 ************************/
$fir_day1 = date("Y-m-d", strtotime("-11 week",  strtotime($date_from)));
$a = 11;
for($i=0; $i<12; $i++) {
	$j = 7 * $i;
  $jj = $j+6;
  if($i==0){
    $sql = "
    SELECT b.cnt + c.cnt + d.cnt AS 'cnt'
    FROM
    (
       SELECT COUNT(pos_id) AS 'cnt'
       FROM did_pos_code
       INNER JOIN
       (
          SELECT pos_id
          FROM did_log_type_3
          WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
          AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$fir_day1." 0 days"))."' AND '".date("Y-m-d", strtotime("".$fir_day1." +6 days"))."'
       )a ON did_pos_code.pos_code = a.pos_id
    )b,
    (
       SELECT COUNT(pos_id) AS 'cnt'
       FROM did_pos_code
       INNER JOIN
       (
          SELECT pos_id
          FROM did_log_type_3
          WHERE space_id IN('s000001','s000002','s000003','s000004')
          AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$fir_day1." 0 days"))."' AND '".date("Y-m-d", strtotime("".$fir_day1." +6 days"))."'
       )a ON did_pos_code.pos_code = a.pos_id
    )c,
    (
       SELECT COUNT(pos_id) AS 'cnt'
       FROM did_pos_code
       INNER JOIN
       (
          SELECT pos_id
          FROM did_log_type_4
          WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$fir_day1." 0 days"))."' AND '".date("Y-m-d", strtotime("".$fir_day1." +6 days"))."'
       )a ON did_pos_code.pos_code = a.pos_id
    )d";
  } else {
    $sql = "
    SELECT b.cnt + c.cnt + d.cnt AS 'cnt'
    FROM
    (
       SELECT COUNT(pos_id) AS 'cnt'
       FROM did_pos_code
       INNER JOIN
       (
          SELECT pos_id
          FROM did_log_type_3
          WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
          AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$fir_day1." ".$j."  days"))."' AND '".date("Y-m-d", strtotime("".$fir_day1." ".$jj." days"))."'
       )a ON did_pos_code.pos_code = a.pos_id
    )b,
    (
       SELECT COUNT(pos_id) AS 'cnt'
       FROM did_pos_code
       INNER JOIN
       (
          SELECT pos_id
          FROM did_log_type_3
          WHERE space_id IN('s000001','s000002','s000003','s000004')
          AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$fir_day1." ".$j."  days"))."' AND '".date("Y-m-d", strtotime("".$fir_day1." ".$jj." days"))."'
       )a ON did_pos_code.pos_code = a.pos_id
    )c,
    (
       SELECT COUNT(pos_id) AS 'cnt'
       FROM did_pos_code
       INNER JOIN
       (
          SELECT pos_id
          FROM did_log_type_4
          WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$fir_day1." ".$j."  days"))."' AND '".date("Y-m-d", strtotime("".$fir_day1." ".$jj." days"))."'
       )a ON did_pos_code.pos_code = a.pos_id
    )d";
  }
	$conn->DBQ($sql);
	$conn->DBE();
	${'per_week_'. $a} = $conn->DBF();
	$a--;
}

/******************** uv 이용 현황 주별 ************************/
$fir_day1 = date("Y-m-d", strtotime("-11 week",  strtotime($date_from)));
$a = 11;
for($i=0; $i<12; $i++) {
  $j = 0;
	$j = 7 * $i;
  $jj = 0;
  $jj = $j+6;
  if($i==0){
    $sql = "
    SELECT a.cnt + b.cnt AS 'cnt'
    FROM
    (
    	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
    			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$fir_day1." 0 days"))."' AND '".date("Y-m-d", strtotime("".$fir_day1." +6 days"))."'
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
    			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$fir_day1." 0 days"))."' AND '".date("Y-m-d", strtotime("".$fir_day1." +6 days"))."'
    			GROUP BY TIMESTAMP
    		)a ON did_pos_code.pos_code = a.pos_id
    	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
    	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
    )a,
    (
    	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
    			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$fir_day1." 0 days"))."' AND '".date("Y-m-d", strtotime("".$fir_day1." +6 days"))."'
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
    			WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$fir_day1." 0 days"))."' AND '".date("Y-m-d", strtotime("".$fir_day1." +6 days"))."'
    			AND page_id = 'p900003' AND target_id IS NOT NULL
          GROUP BY TIMESTAMP
    		)a ON did_pos_code.pos_code = a.pos_id
    	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
    		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
    )b
    ";
  } else {
    $sql = "
    SELECT a.cnt + b.cnt AS 'cnt'
    FROM
    (
    	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
    			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$fir_day1." ".$j."  days"))."' AND '".date("Y-m-d", strtotime("".$fir_day1." ".$jj." days"))."'
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
    			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$fir_day1." ".$j."  days"))."' AND '".date("Y-m-d", strtotime("".$fir_day1." ".$jj." days"))."'
    			GROUP BY TIMESTAMP
    		)a ON did_pos_code.pos_code = a.pos_id
    	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
    	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
    )a,
    (
    	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
    			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$fir_day1." ".$j."  days"))."' AND '".date("Y-m-d", strtotime("".$fir_day1." ".$jj." days"))."'
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
    			WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m-d", strtotime("".$fir_day1." ".$j."  days"))."' AND '".date("Y-m-d", strtotime("".$fir_day1." ".$jj." days"))."'
    			AND page_id = 'p900003' AND target_id IS NOT NULL
          GROUP BY TIMESTAMP
    		)a ON did_pos_code.pos_code = a.pos_id
    	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
    		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
    )b
    ";
  }
	$conn->DBQ($sql);
	$conn->DBE();
	${'uv_per_week_'. $a} = $conn->DBF();
	$a--;
}

/******************** pv 이용 현황 월별 ************************/
for($i=11; $i>-1; $i--) {
	$b = -30 * $i;
  if($i==0){
    $sql = "
    SELECT b.cnt + c.cnt + d.cnt AS 'cnt'
    FROM
    (
       SELECT COUNT(pos_id) AS 'cnt'
       FROM did_pos_code
       INNER JOIN
       (
          SELECT pos_id
          FROM did_log_type_3
          WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
          AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("".$date_to." +0 days"))."-01' AND '".date("Y-m", strtotime("".$date_to." +0 days"))."-31'
       )a ON did_pos_code.pos_code = a.pos_id
    )b,
    (
       SELECT COUNT(pos_id) AS 'cnt'
       FROM did_pos_code
       INNER JOIN
       (
          SELECT pos_id
          FROM did_log_type_3
          WHERE space_id IN('s000001','s000002','s000003','s000004')
          AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("".$date_to." +0 days"))."-01' AND '".date("Y-m", strtotime("".$date_to." +0 days"))."-31'
       )a ON did_pos_code.pos_code = a.pos_id
    )c,
    (
       SELECT COUNT(pos_id) AS 'cnt'
       FROM did_pos_code
       INNER JOIN
       (
          SELECT pos_id
          FROM did_log_type_4
          WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("".$date_to." +0 days"))."-01' AND '".date("Y-m", strtotime("".$date_to." +0 days"))."-31'
       )a ON did_pos_code.pos_code = a.pos_id
    )d";
  } else {
    $sql = "
    SELECT b.cnt + c.cnt + d.cnt AS 'cnt'
    FROM
    (
       SELECT COUNT(pos_id) AS 'cnt'
       FROM did_pos_code
       INNER JOIN
       (
          SELECT pos_id
          FROM did_log_type_3
          WHERE space_id NOT IN('s000001', 's000002', 's000003', 's000004')
          AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("".$date_to." ".$b." days"))."-01' AND '".date("Y-m", strtotime("".$date_to." ".$b." days"))."-31'
       )a ON did_pos_code.pos_code = a.pos_id
    )b,
    (
       SELECT COUNT(pos_id) AS 'cnt'
       FROM did_pos_code
       INNER JOIN
       (
          SELECT pos_id
          FROM did_log_type_3
          WHERE space_id IN('s000001','s000002','s000003','s000004')
          AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("".$date_to." ".$b." days"))."-01' AND '".date("Y-m", strtotime("".$date_to." ".$b." days"))."-31'
       )a ON did_pos_code.pos_code = a.pos_id
    )c,
    (
       SELECT COUNT(pos_id) AS 'cnt'
       FROM did_pos_code
       INNER JOIN
       (
          SELECT pos_id
          FROM did_log_type_4
          WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("".$date_to." ".$b." days"))."-01' AND '".date("Y-m", strtotime("".$date_to." ".$b." days"))."-31'
       )a ON did_pos_code.pos_code = a.pos_id
    )d";
  }
  $conn->DBQ($sql);
  $conn->DBE();
	${'per_month_'. $i} = $conn->DBF();
}

/******************** uv 이용 현황 월별 ************************/
for($i=11; $i>-1; $i--) {
	$b = -30 * $i;
  if($i==0){
    $sql = "
    SELECT a.cnt + b.cnt AS 'cnt'
    FROM
    (
    	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
    			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("".$date_to." +0 days"))."-01' AND '".date("Y-m", strtotime("".$date_to." +0 days"))."-31'
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
    			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("".$date_to." +0 days"))."-01' AND '".date("Y-m", strtotime("".$date_to." +0 days"))."-31'
    			GROUP BY TIMESTAMP
    		)a ON did_pos_code.pos_code = a.pos_id
    	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
    	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
    )a,
    (
    	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
    			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("".$date_to." +0 days"))."-01' AND '".date("Y-m", strtotime("".$date_to." +0 days"))."-31'
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
    			WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("".$date_to." +0 days"))."-01' AND '".date("Y-m", strtotime("".$date_to." +0 days"))."-31'
    			AND page_id = 'p900003' AND target_id IS NOT NULL
          GROUP BY TIMESTAMP
    		)a ON did_pos_code.pos_code = a.pos_id
    	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
    		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
    )b
    ";
  } else {
    $sql = "
    SELECT a.cnt + b.cnt AS 'cnt'
    FROM
    (
    	SELECT COUNT(a.TIMESTAMP) AS 'cnt'
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
    			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("".$date_to." ".$b." days"))."-01' AND '".date("Y-m", strtotime("".$date_to." ".$b." days"))."-31'
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
    			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("".$date_to." ".$b." days"))."-01' AND '".date("Y-m", strtotime("".$date_to." ".$b." days"))."-31'
    			GROUP BY TIMESTAMP
    		)a ON did_pos_code.pos_code = a.pos_id
    	)b ON a.pos_id = b.pos_id AND a.device_id = b.device_id AND a.page_id = b.page_id
    	AND b.TIMESTAMP BETWEEN a.TIMESTAMP AND a.diff
    )a,
    (
    	SELECT COUNT(b.TIMESTAMP) AS 'cnt'
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
    			AND DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("".$date_to." ".$b." days"))."-01' AND '".date("Y-m", strtotime("".$date_to." ".$b." days"))."-31'
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
    			WHERE DATE(TIMESTAMP) BETWEEN '".date("Y-m", strtotime("".$date_to." ".$b." days"))."-01' AND '".date("Y-m", strtotime("".$date_to." ".$b." days"))."-31'
    			AND page_id = 'p900003' AND target_id IS NOT NULL
          GROUP BY TIMESTAMP
    		)a ON did_pos_code.pos_code = a.pos_id
    	)c ON b.space_id = c.space_id AND b.pos_id = c.pos_id AND b.device_id = c.device_id
    		AND c.TIMESTAMP BETWEEN b.timestamp AND b.diff
    )b
    ";
  }
  $conn->DBQ($sql);
  $conn->DBE();
	${'uv_per_month_'. $i} = $conn->DBF();
}

$sql = "
SELECT
(
  SELECT SUM(b.cnt)
  FROM did_pos_code
  LEFT JOIN
  (
     SELECT pos_id, COUNT(DISTINCT device_id) AS 'cnt'
     FROM did_log_type_1
     WHERE pos_id IS NOT NULL AND pos_id != ''
     GROUP BY pos_id, device_id
  )b ON did_pos_code.pos_code = b.pos_id
  WHERE did_pos_code.CHANNEL IS NOT NULL AND did_pos_code.CHANNEL != ''
)AS '디바이스수',
(SELECT MAX(app_version) FROM did_log_type_1 WHERE app_version != 'test')AS 'app',
(
  SELECT COUNT(DISTINCT a.device_id)
  FROM
  (
    SELECT pos_id, device_id
    FROM did_log_type_1
    WHERE app_version IN
    (
      SELECT MAX(app_version)
      FROM did_log_type_1
      WHERE app_version != 'test'
    )
  )AS a
  LEFT JOIN did_pos_code ON a.pos_id = did_pos_code.pos_code
  WHERE did_pos_code.CHANNEL IS NOT NULL AND did_pos_code.CHANNEL != ''
)AS '최신버전사용'
FROM did_log_type_1
LIMIT 1
";
$conn->DBQ($sql);
$conn->DBE();
$app = $conn->DBF();

$sql = "
SELECT
(
  SELECT SUM(b.cnt)
  FROM did_pos_code
  LEFT JOIN
  (
     SELECT pos_id, COUNT(DISTINCT device_id) AS 'cnt'
     FROM did_log_type_1
     WHERE pos_id IS NOT NULL AND pos_id != ''
     GROUP BY pos_id, device_id
  )b ON did_pos_code.pos_code = b.pos_id
  WHERE did_pos_code.CHANNEL IS NOT NULL AND did_pos_code.CHANNEL != ''
)AS '디바이스수',
(SELECT MAX(contents_version) FROM did_log_type_1 WHERE contents_version != 'test')AS 'app',
(
  SELECT COUNT(distinct a.device_id)
  FROM
  (
    SELECT pos_id, device_id
    FROM did_log_type_1
    WHERE contents_version IN
    (
      SELECT MAX(contents_version)
      FROM did_log_type_1
      WHERE contents_version != 'test'
    ) AND pos_id IS NOT NULL AND pos_id != ''
    GROUP BY pos_id, device_id
  )AS a
  LEFT JOIN did_pos_code ON a.pos_id = did_pos_code.pos_code
  WHERE did_pos_code.CHANNEL IS NOT NULL AND did_pos_code.CHANNEL != ''
)AS '최신버전사용'
FROM did_log_type_1
LIMIT 1
";
$conn->DBQ($sql);
$conn->DBE();
$contents = $conn->DBF();

if(number_format(($sum_exec_app_total_1[0]/$certificate_store[0])*100,2) == nan){
  $temp1 = '0%';
}else{
  $temp1 = number_format(($sum_exec_app_total_1[0]/$certificate_store[0])*100,1).'%';
}

if(number_format(($sum_exec_app_total_1[0] - $sum_exec_app_total_2[0])/$sum_exec_app_total_2[0] * 100,1) == nan){
  $temp2 = '0%';
}else{
  $temp2 = number_format(($sum_exec_app_total_1[0] - $sum_exec_app_total_2[0])/$sum_exec_app_total_2[0] * 100,1).'%';
}

if(number_format(($app['최신버전사용']/$app['디바이스수'])*100,2) == nan){
  $temp3 = '0%';
}else{
  $temp3 = number_format(($app['최신버전사용']/$app['디바이스수'])*100,2).'%';
}

if(number_format(($contents['최신버전사용']/$contents['디바이스수'])*100,2) == nan){
  $temp4 = '0%';
}else{
  $temp4 = number_format(($contents['최신버전사용']/$contents['디바이스수'])*100,2).'%';
}

$sql = "
insert into pre_dashboard(regist_store, exe_new_store, exe_per_pro, app_use, pv_doughnut, pv_line_day, pv_line_week, pv_line_month, uv_doughnut, uv_line_day, uv_line_week, uv_line_month, app_contents)
values('".$certificate_store[0].",".$un_certificate_store[0].",".$un_use_store[0]."'
,'".$weekly_all[0].",".$sum_weekly_new[0]."'
,'".$temp1.",".$temp2."'
,'".$uv_last_month_all_total[0].",".$uv_this_month_all_total[0].",".$uv_this_week_all_total[0].",".$uv_last_month_all_tv[0]."
,".$uv_this_month_all_tv[0].",".$uv_this_week_all_tv[0].",".$uv_last_month_all_iot[0].",".$uv_this_month_all_iot[0]."
,".$uv_this_week_all_iot[0].",".$uv_last_month_pcode_total[0].",".$uv_this_month_pcode_total[0].",".$uv_this_week_pcode_total[0]."
,".$uv_last_month_pcode_tv[0].",".$uv_this_month_pcode_tv[0].",".$uv_this_week_pcode_tv[0].",".$uv_last_month_pcode_iot[0]."
,".$uv_this_month_pcode_iot[0].",".$uv_this_week_pcode_iot[0]."
,".$pv_last_month_all_total[0].",".$pv_this_month_all_total[0].",".$pv_this_week_all_total[0].",".$pv_last_month_all_tv[0]."
,".$pv_this_month_all_tv[0].",".$pv_this_week_all_tv[0].",".$pv_last_month_all_iot[0].",".$pv_this_month_all_iot[0]."
,".$pv_this_week_all_iot[0].",".$pv_last_month_pcode_total[0].",".$pv_this_month_pcode_total[0].",".$pv_this_week_pcode_total[0]."
,".$pv_last_month_pcode_tv[0].",".$pv_this_month_pcode_tv[0].",".$pv_this_week_pcode_tv[0].",".$pv_last_month_pcode_iot[0]."
,".$pv_this_month_pcode_iot[0].",".$pv_this_week_pcode_iot[0]."'
,'".$pv_yesterday[0].",".$pv_today[0].",".$pv_last_week[0].",".$pv_this_week[0]."
,".$pv_last_month[0].",".$pv_this_month[0]."'
,'".$per_day_0[0].",".$per_day_1[0].",".$per_day_2[0].",".$per_day_3[0].",".$per_day_4[0].",".$per_day_5[0]."
,".$per_day_6[0].",".$per_day_7[0].",".$per_day_8[0].",".$per_day_9[0].",".$per_day_10[0].",".$per_day_11[0]."'
,'".$per_week_0[0].",".$per_week_1[0].",".$per_week_2[0].",".$per_week_3[0].",".$per_week_4[0].",".$per_week_5[0]."
,".$per_week_6[0].",".$per_week_7[0].",".$per_week_8[0].",".$per_week_9[0].",".$per_week_10[0].",".$per_week_11[0]."'
,'".$per_month_0[0].",".$per_month_1[0].",".$per_month_2[0].",".$per_month_3[0].",".$per_month_4[0].",".$per_month_5[0]."
,".$per_month_6[0].",".$per_month_7[0].",".$per_month_8[0].",".$per_month_9[0].",".$per_month_10[0].",".$per_month_11[0]."'
,'".$uv_yesterday[0].",".$uv_today[0].",".$uv_last_week[0].",".$uv_this_week[0]."
,".$uv_last_month[0].",".$uv_this_month[0]."'
,'".$uv_per_day_0[0].",".$uv_per_day_1[0].",".$uv_per_day_2[0].",".$uv_per_day_3[0].",".$uv_per_day_4[0].",".$uv_per_day_5[0]."
,".$uv_per_day_6[0].",".$uv_per_day_7[0].",".$uv_per_day_8[0].",".$uv_per_day_9[0].",".$uv_per_day_10[0].",".$uv_per_day_11[0]."'
,'".$uv_per_week_0[0].",".$uv_per_week_1[0].",".$uv_per_week_2[0].",".$uv_per_week_3[0].",".$uv_per_week_4[0].",".$uv_per_week_5[0]."
,".$uv_per_week_6[0].",".$uv_per_week_7[0].",".$uv_per_week_8[0].",".$uv_per_week_9[0].",".$uv_per_week_10[0].",".$uv_per_week_11[0]."'
,'".$uv_per_month_0[0].",".$uv_per_month_1[0].",".$uv_per_month_2[0].",".$uv_per_month_3[0].",".$uv_per_month_4[0].",".$uv_per_month_5[0]."
,".$uv_per_month_6[0].",".$uv_per_month_7[0].",".$uv_per_month_8[0].",".$uv_per_month_9[0].",".$uv_per_month_10[0].",".$uv_per_month_11[0]."'
,'".$app['디바이스수'].",".$app['app'].",".$app['최신버전사용'].",".$temp3."
,".$contents['디바이스수'].",".$contents['app'].",".$contents['최신버전사용'].",".$temp4."')
";
// echo $sql;
// echo $pv_last_month_all_total[0];
$conn->DBQ($sql);
$conn->DBE();
?>
