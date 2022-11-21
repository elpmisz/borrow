<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
require_once(__DIR__ . "/../../includes/connection.php");
require_once(__DIR__ . "/../../vendor/autoload.php");

$user_id = (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "");

$stmt = $dbcon->prepare("SELECT COUNT(*) FROM user_detail");
$stmt->execute();
$count = $stmt->fetchColumn();

$column = ["A.status", "A.id", "A.name", "A.province_code", "C.zone_id", "B.level"];

$status = (isset($_POST['status']) ? intval($_POST['status']) : "");
$zone = (isset($_POST['zone']) ? intval($_POST['zone']) : "");

$keyword = (isset($_POST['search']['value']) ? $_POST['search']['value'] : "");
$order = (isset($_POST['order']) ? $_POST['order'] : "");
$order_column = (isset($_POST['order']['0']['column']) ? $_POST['order']['0']['column'] : "");
$order_dir = (isset($_POST['order']['0']['dir']) ? $_POST['order']['0']['dir'] : "");
$limit_start = (isset($_POST['start']) ? $_POST['start'] : "");
$limit_length = (isset($_POST['length']) ? $_POST['length'] : "");
$draw = (isset($_POST['draw']) ? $_POST['draw'] : "");

$sql = "SELECT A.id user_id,A.name user_name,A.email user_email,A.contact user_contact,A.province_code,
B.name login_name,C.name province_name,CONCAT('เขต ',C.zone_id) district_name,
CASE 
  WHEN B.level = 1 THEN 'ผู้ใช้ระดับจังหวัด'
  WHEN B.level = 2 THEN 'ผู้ใช้ระดับเขต'
  WHEN B.level = 9 THEN 'ผู้ดูแลระบบ'
  ELSE NULL
END level_name,
CASE 
  WHEN B.level = 1 THEN 'warning'
  WHEN B.level = 2 THEN 'primary'
  WHEN B.level = 9 THEN 'danger'
  ELSE NULL
END level_color,
IF(A.status = 1,'รายละเอียด','ระงับการใช้งาน') status_name,
IF(A.status = 1,'success','danger') status_color
FROM user_detail A
LEFT JOIN user_login B
ON A.id = B.user_id
LEFT JOIN province C
ON A.province_code = C.code ";

if ($status === 2) {
  $sql .= " WHERE B.level = 2 ";
} elseif ($status === 3) {
  $sql .= " WHERE B.level = 1 ";
} elseif ($status === 4) {
  $sql .= " WHERE B.level = 9 ";
} else {
  $sql .= " WHERE A.id != '' ";
}

if ($zone) {
  $sql .= " AND C.zone_id = {$zone} ";
}

if ($keyword) {
  $sql .= " AND (A.name LIKE '%{$keyword}%' OR A.email LIKE '%{$keyword}%' OR B.name LIKE '%{$keyword}%') ";
}


if ($order) {
  $sql .= "ORDER BY {$column[$order_column]} {$order_dir} ";
} else {
  $sql .= "ORDER BY A.status ASC, B.level DESC, C.zone_id ASC, A.id ASC ";
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
  $level = "<span class='badge text-bg-{$row['level_color']} fw-lighter'>{$row['level_name']}</span>";
  $status = "<a href='/users/view/{$row['user_id']}' class='badge text-bg-{$row['status_color']} fw-lighter'>{$row['status_name']}</a>";
  $data[] = [
    "0" => $status,
    "1" => $row['login_name'],
    "2" => $row['user_name'],
    "3" => $row['province_name'],
    "4" => $row['district_name'],
    "5" => $level,
  ];
}

$output = [
  "draw"    => $draw,
  "recordsTotal"  =>  $count,
  "recordsFiltered" => $filter,
  "data"    => $data
];

echo json_encode($output);
