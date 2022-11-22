<?php

use app\classes\Dashboard;
use app\classes\Validation;

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
date_default_timezone_set("Asia/Bangkok");

require_once(__DIR__ . '/../../vendor/autoload.php');

$param = (isset($params) ? explode("/", $params) : header("Location: /error"));
$action = (isset($param[0]) ? $param[0] : "");
$param1 = (isset($param[1]) ? $param[1] : "");
$param2 = (isset($param[2]) ? $param[2] : "");

$Dashboard = new Dashboard();
$Validation = new Validation();

if ($action === "itemdetail") :
  try {
    $zone = (isset($_POST['zone']) ? $Validation->input($_POST['zone']) : "");
    $item = (isset($_POST['item']) ? $Validation->input($_POST['item']) : "");
    $result = $Dashboard->item_by_id([$zone, $item]);

    echo json_encode($result);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;
