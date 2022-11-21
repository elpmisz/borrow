<?php

use app\classes\Item;
use app\classes\System;
use app\classes\Validation;

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
date_default_timezone_set("Asia/Bangkok");

require_once(__DIR__ . "/../../vendor/autoload.php");

$param = (isset($params) ? explode("/", $params) : header("Location: /error"));
$action = (isset($param[0]) ? $param[0] : "");
$param1 = (isset($param[1]) ? $param[1] : "");
$param2 = (isset($param[2]) ? $param[2] : "");

$user_id = (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "");

$Items = new Item();
$Systems = new System();
$Validation = new Validation();

$system = $Systems->fetch();

if ($action === "add") :
  try {
    $name = (isset($_POST['name']) ? $Validation->input($_POST['name']) : "");
    $type = (isset($_POST['type']) ? $Validation->input($_POST['type']) : "");
    $reference = (isset($_POST['reference']) ? $Validation->input($_POST['reference']) : "");
    $unit = (isset($_POST['unit']) ? $Validation->input($_POST['unit']) : "");

    $Items->item_insert([$name, $type, $reference, $unit]);

    $Validation->alert("success", "เพิ่มข้อมูลเรียบร้อย", "/items");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;

if ($action === "update") :
  try {
    $id = (isset($_POST['id']) ? $Validation->input($_POST['id']) : "");
    $name = (isset($_POST['name']) ? $Validation->input($_POST['name']) : "");
    $type = (isset($_POST['type']) ? $Validation->input($_POST['type']) : "");
    $reference = (isset($_POST['reference']) ? $Validation->input($_POST['reference']) : "");
    $unit = (isset($_POST['unit']) ? $Validation->input($_POST['unit']) : "");
    $status = (isset($_POST['status']) ? $Validation->input($_POST['status']) : "");

    $Items->item_update([$name, $type, $reference, $unit, $status, $id]);

    $Validation->alert("success", "ดำเนินการเรียบร้อย", "/items/view/{$id}");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;


if ($action === "referenceselect") :
  try {
    $keyword = (isset($_POST['q']) ? $Validation->input($_POST['q']) : "");
    $result = $Items->reference_select($keyword);

    $data = [];
    foreach ($result as $row) :
      $data[] = [
        "id" => $row['id'],
        "text" => $row['name']
      ];
    endforeach;

    echo json_encode($data);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;
