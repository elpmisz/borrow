<?php

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
require_once(__DIR__ . "/../../includes/connection.php");
require_once(__DIR__ . "/../../vendor/autoload.php");

$user_id = (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "");

$sql = "SELECT COUNT(item_id) 
FROM request_item A
LEFT JOIN request B
ON A.request_id = B.id 
WHERE B.type = 1
AND B.user_id = {$user_id}
GROUP BY A.item_id ";
$stmt = $dbcon->prepare($sql);
$stmt->execute();
$count = $stmt->fetchColumn();

$column = ["A.id", "A.id", "A.id"];

$status = (isset($_POST['status']) ? intval($_POST['status']) : "");

$keyword = (isset($_POST['search']['value']) ? $_POST['search']['value'] : "");
$order = (isset($_POST['order']) ? $_POST['order'] : "");
$order_column = (isset($_POST['order']['0']['column']) ? $_POST['order']['0']['column'] : "");
$order_dir = (isset($_POST['order']['0']['dir']) ? $_POST['order']['0']['dir'] : "");
$limit_start = (isset($_POST['start']) ? $_POST['start'] : "");
$limit_length = (isset($_POST['length']) ? $_POST['length'] : "");
$draw = (isset($_POST['draw']) ? $_POST['draw'] : "");

$sql = "SELECT B.text request_text,DATE_FORMAT(B.end, '%d/%m/%Y') end,
C.name item_name,C.unit item_unit,
SUM(CASE WHEN B.type = 1 THEN A.confirm ELSE 0 END) item_borrow,
SUM(CASE WHEN B.type = 2 THEN A.confirm ELSE 0 END) item_return,
SUM(CASE WHEN B.type = 1 THEN A.confirm ELSE 0 END) - SUM(CASE WHEN B.type = 2 THEN A.confirm ELSE 0 END) balance
FROM request_item A
LEFT JOIN request B
ON A.request_id = B.id
LEFT JOIN item C
ON A.item_id = C.id
WHERE B.user_id = {$user_id}
AND B.status = 3 ";

if ($keyword) {
  $sql .= " AND (B.text LIKE '%{$keyword}%' OR C.name LIKE '%{$keyword}%') ";
}

$sql .= " GROUP BY A.item_id ";

if ($order) {
  $sql .= "ORDER BY {$column[$order_column]} {$order_dir} ";
} else {
  $sql .= "ORDER BY A.id ASC ";
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
  $data[] = [
    "0" => $row['item_name'],
    "1" => $row['balance'],
    "2" => $row['item_unit'],
  ];
}

$output = [
  "draw"    => $draw,
  "recordsTotal"  =>  $count,
  "recordsFiltered" => $filter,
  "data"    => $data
];

echo json_encode($output);
