<?php

use app\classes\Borrow;
use app\classes\Validation;

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
date_default_timezone_set("Asia/Bangkok");

require_once(__DIR__ . "/../../vendor/autoload.php");

$param = (isset($params) ? explode("/", $params) : header("Location: /error"));
$action = (!empty($param[0]) ? $param[0] : "");
$param1 = (!empty($param[1]) ? $param[1] : "");
$param2 = (!empty($param[2]) ? $param[2] : "");

$user_id = (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "");

$Borrow = new Borrow();
$Validation = new Validation();

if ($action === "add") :
  try {
    $type = (isset($_POST['type']) ? $Validation->input($_POST['type']) : "");
    $date = (isset($_POST['date']) ? $Validation->input($_POST['date']) : "");
    $conv = (!empty($date) ? explode("-", $date) : "");
    $start = (!empty($date) ? date("Y-m-d", strtotime(str_replace("/", "-", trim($conv[0])))) : "");
    $end = (!empty($date) ? date("Y-m-d", strtotime(str_replace("/", "-", trim($conv[1])))) : "");
    $text = (isset($_POST['text']) ? $Validation->input($_POST['text']) : "");

    $Borrow->request_insert([$type, $user_id, $start, $end, $text]);
    $request_id = $Borrow->last_insert_id();

    if (isset($_POST['item_id'])) :
      foreach (array_filter($_POST['item_id']) as $key => $row) :
        $item_id = (isset($_POST['item_id'][$key]) ? $_POST['item_id'][$key] : "");
        $item_amount = (isset($_POST['item_amount'][$key]) ? $_POST['item_amount'][$key] : "");
        $location = (isset($_POST['item_location'][$key]) ? $_POST['item_location'][$key] : "");
        $text = (isset($_POST['item_text'][$key]) ? $_POST['item_text'][$key] : "");

        $Borrow->item_insert([$request_id, $item_id, $item_amount, $item_amount, $location, $text]);
      endforeach;
    endif;

    $Validation->alert("success", "เพิ่มข้อมูลเรียบร้อย", "/borrow/view/{$request_id}");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;

if ($action === "update") :
  try {
    echo "<pre>";
    print_r($_POST);
    die();
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;

if ($action === "itemdelete") :
  try {
    $item_id = (!empty($param1) ? $param1 : "");
    $request_id = (!empty($param2) ? $param2 : "");

    $Validation->alert("success", "ปรับปรุงข้อมูลเรียบร้อย", "/borrow/view/{$request_id}");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;