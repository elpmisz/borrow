<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
require_once(__DIR__ . "/../../includes/connection.php");
require_once(__DIR__ . "/../../vendor/autoload.php");

$user_id = (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "");

$stmt = $dbcon->prepare("SELECT COUNT(*) FROM user_item");
$stmt->execute();
$count = $stmt->fetchColumn();

$column = ["A.status", "A.id", "A.name", "A.province_code", "C.zone_id", "B.level"];

$keyword = (isset($_POST['search']['value']) ? $_POST['search']['value'] : "");
$order = (isset($_POST['order']) ? $_POST['order'] : "");
$order_column = (isset($_POST['order']['0']['column']) ? $_POST['order']['0']['column'] : "");
$order_dir = (isset($_POST['order']['0']['dir']) ? $_POST['order']['0']['dir'] : "");
$limit_start = (isset($_POST['start']) ? $_POST['start'] : "");
$limit_length = (isset($_POST['length']) ? $_POST['length'] : "");
$draw = (isset($_POST['draw']) ? $_POST['draw'] : "");

$sql = "SELECT D.name item_name,D.unit item_unit,SUM(A.amount) total,
CONCAT(C.zone_id,'/',A.item_id) zone_id,CONCAT('เขต ',C.zone_id) zone_name
FROM user_item A
LEFT JOIN user_detail B 
ON A.user_id = B.id
LEFT JOIN province C 
ON B.province_code = C.code 
LEFT JOIN item D
ON A.item_id = D.id ";

if ($keyword) {
  $sql .= " AND (A.name LIKE '%{$keyword}%' OR A.email LIKE '%{$keyword}%' OR B.name LIKE '%{$keyword}%') ";
}

$sql .= " GROUP BY A.item_id, C.zone_id ";

if ($order) {
  $sql .= "ORDER BY {$column[$order_column]} {$order_dir} ";
} else {
  $sql .= "ORDER BY C.zone_id ASC, A.item_id ASC ";
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
  $view = "<a href='javascript:void(0)' class='badge text-bg-primary fw-lighter btn_view' id='{$row['zone_id']}'>รายละเอียด</a>";
  $data[] = [
    "0" => $view,
    "1" => $row['zone_name'],
    "2" => $row['item_name'],
    "3" => $row['total'],
    "4" => $row['item_unit'],

  ];
}

$output = [
  "draw"    => $draw,
  "recordsTotal"  =>  $count,
  "recordsFiltered" => $filter,
  "data"    => $data
];

echo json_encode($output);
