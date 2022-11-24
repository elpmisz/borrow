<?php

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
require_once(__DIR__ . "/../../includes/connection.php");
require_once(__DIR__ . "/../../vendor/autoload.php");

$user_id = (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "");

$stmt = $dbcon->prepare("SELECT COUNT(*) FROM request WHERE user_id = {$user_id}");
$stmt->execute();
$count = $stmt->fetchColumn();

$column = ["A.id", "A.id", "A.id", "A.id"];

$status = (isset($_POST['status']) ? intval($_POST['status']) : "");

$keyword = (isset($_POST['search']['value']) ? $_POST['search']['value'] : "");
$order = (isset($_POST['order']) ? $_POST['order'] : "");
$order_column = (isset($_POST['order']['0']['column']) ? $_POST['order']['0']['column'] : "");
$order_dir = (isset($_POST['order']['0']['dir']) ? $_POST['order']['0']['dir'] : "");
$limit_start = (isset($_POST['start']) ? $_POST['start'] : "");
$limit_length = (isset($_POST['length']) ? $_POST['length'] : "");
$draw = (isset($_POST['draw']) ? $_POST['draw'] : "");

$sql = "SELECT A.id request_id,A.text,A.type type_id,IF(A.type = 1,'ยืม','คืน') type_name,B.name user_name,
GROUP_CONCAT(CONCAT(D.name,' [ ',C.amount,' ',D.unit,' ]')) item,
CONCAT(DATE_FORMAT(A.start, '%d/%m/%Y'),' - ', DATE_FORMAT(A.end, '%d/%m/%Y')) date,
DATE_FORMAT(A.created, '%d/%m/%Y - %H:%i น.') created,
CASE A.status
  WHEN 1 THEN 'รออนุมัติ'
  WHEN 2 THEN 'รอรับคืน'
  WHEN 3 THEN 'ดำเนินการเรียบร้อยแล้ว'
  WHEN 4 THEN 'ยกเลิก'
  ELSE NULL
END status_name,
CASE A.status
  WHEN 1 THEN 'primary'
  WHEN 2 THEN 'primary'
  WHEN 3 THEN 'success'
  WHEN 4 THEN 'danger'
  ELSE NULL
END status_color
FROM request A
LEFT JOIN user_detail B
ON A.user_id = B.id
LEFT JOIN request_item C
ON A.id = C.request_id
LEFT JOIN item D
ON C.item_id = D.id
WHERE A.user_id = {$user_id} ";

if ($keyword) {
  $sql .= " AND (A.text LIKE '%{$keyword}%' OR D.name LIKE '%{$keyword}%') ";
}

if ($order) {
  $sql .= "ORDER BY {$column[$order_column]} {$order_dir} ";
} else {
  $sql .= "ORDER BY A.id DESC ";
}

$query = "";
if (!empty($limit_length)) {
  $query .= "LIMIT {$limit_start}, {$limit_length}";
}

$stmt = $dbcon->prepare($sql);
$stmt->execute();
$filter = $stmt->rowCount();
$stmt = $dbcon->prepare($sql . $query);
$stmt->execute();
$result = $stmt->fetchAll();

$data = [];
foreach ($result as $row) {
  $status = "<a href='/borrow/view/{$row['request_id']}'><span class='badge text-bg-{$row['status_color']} fw-lighter'>{$row['status_name']}</span></a>";
  $data[] = [
    "0" => $status,
    "1" => $row['text'],
    "2" => $row['type_name'],
    "3" => str_replace(",", "<br>", $row['item']),
    "4" => str_replace("-", ",<br>", $row['date']),
    "5" => str_replace("-", ",<br>", $row['created']),
  ];
}

$output = [
  "draw"    => $draw,
  "recordsTotal"  =>  $count,
  "recordsFiltered" => $filter,
  "data"    => $data
];

echo json_encode($output);