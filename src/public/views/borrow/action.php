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

    $type = (isset($_POST['type']) ? intval($Validation->input($_POST['type'])) : "");
    $date = ($type === 1 ? $Validation->input($_POST['date_borrow']) : $Validation->input($_POST['date_return']));
    $conv = ($type === 1 ? explode("-", $date) : "");
    $start = ($type === 1 ? date("Y-m-d", strtotime(str_replace("/", "-", trim($conv[0]))))
      : date("Y-m-d", strtotime(str_replace("/", "-", trim($date)))));
    $end = ($type === 1 ? date("Y-m-d", strtotime(str_replace("/", "-", trim($conv[1]))))
      : date("Y-m-d", strtotime(str_replace("/", "-", trim($date)))));
    $text = (isset($_POST['text']) ? $Validation->input($_POST['text']) : "");
    $status = ($type === 1 ? 1 : 2);

    echo "<pre>";
    print_r($_POST);

    echo $type . "<br>";
    echo $start  . "<br>";
    echo $end  . "<br>";
    echo $status  . "<br>";
    die();

    $Borrow->request_insert([$type, $user_id, $start, $end, $text, $status]);
    $request_id = $Borrow->last_insert_id();

    if (isset($_POST['item_id'])) :
      foreach (array_filter($_POST['item_id']) as $key => $row) :
        $item_id = (isset($_POST['item_id'][$key]) ? $_POST['item_id'][$key] : "");
        $item_amount = (isset($_POST['item_amount'][$key]) ? $_POST['item_amount'][$key] : "");
        $item_location = (isset($_POST['item_location'][$key]) ? $_POST['item_location'][$key] : "");
        $item_text = (isset($_POST['item_text'][$key]) ? $_POST['item_text'][$key] : "");

        $Borrow->item_insert([$request_id, $item_id, $item_amount, $item_amount, $item_location, $item_text]);
      endforeach;
    endif;

    $Validation->alert("success", "เพิ่มข้อมูลเรียบร้อย", "/borrow/view/{$request_id}");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;

if ($action === "update") :
  try {
    $request_id = (isset($_POST['id']) ? $Validation->input($_POST['id']) : "");
    $type = (isset($_POST['type']) ? $Validation->input($_POST['type']) : "");
    $date = (isset($_POST['date']) ? $Validation->input($_POST['date']) : "");
    $conv = (!empty($date) ? explode("-", $date) : "");
    $start = (!empty($date) ? date("Y-m-d", strtotime(str_replace("/", "-", trim($conv[0])))) : "");
    $end = (!empty($date) ? date("Y-m-d", strtotime(str_replace("/", "-", trim($conv[1])))) : "");
    $text = (isset($_POST['text']) ? $Validation->input($_POST['text']) : "");

    $Borrow->request_update([$start, $end, $text, $request_id]);

    // INSERT
    if (!empty($_POST['item_id'])) :
      foreach (array_filter($_POST['item_id']) as $key => $row) :
        $item_id = (isset($_POST['item_id'][$key]) ? $_POST['item_id'][$key] : "");
        $item_amount = (isset($_POST['item_amount'][$key]) ? $_POST['item_amount'][$key] : "");
        $item_location = (isset($_POST['item_location'][$key]) ? $_POST['item_location'][$key] : "");
        $item_text = (isset($_POST['item_text'][$key]) ? $_POST['item_text'][$key] : "");

        $item_count = $Borrow->item_count([$request_id, $item_id]);
        if ($item_count === 0) {
          $Borrow->item_insert([$request_id, $item_id, $item_amount, $item_amount, $item_location, $item_text]);
        }
      endforeach;
    endif;

    // UPDATE
    if (!empty($_POST['item__id'])) :
      foreach (array_filter($_POST['item__id']) as $key => $row) :
        $item__id = (isset($_POST['item__id'][$key]) ? $_POST['item__id'][$key] : "");
        $item__amount = (isset($_POST['item__amount'][$key]) ? $_POST['item__amount'][$key] : "");
        $item__location = (isset($_POST['item__location'][$key]) ? $_POST['item__location'][$key] : "");
        $item__text = (isset($_POST['item__text'][$key]) ? $_POST['item__text'][$key] : "");

        $Borrow->item_update([$item__amount, $item__amount, $item__location, $item__text, $item__id]);
      endforeach;
    endif;

    $Validation->alert("success", "ปรับปรุงข้อมูลเรียบร้อย", "/borrow/view/{$request_id}");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;

if ($action === "approve") :
  try {
    $request_id = (isset($_POST['id']) ? $Validation->input($_POST['id']) : "");
    $status = (isset($_POST['status']) ? $Validation->input($_POST['status']) : "");
    $text = (isset($_POST['text']) ? $Validation->input($_POST['text']) : "");

    $Borrow->request_approve([$user_id, $text, $status, $request_id]);

    if (!empty($_POST['item__id'])) :
      foreach (array_filter($_POST['item__id']) as $key => $row) :
        $item__id = (isset($_POST['item__id'][$key]) ? $_POST['item__id'][$key] : "");
        $item__confirm = (isset($_POST['item__confirm'][$key]) ? $_POST['item__confirm'][$key] : "");
        $item__remark = (isset($_POST['item__remark'][$key]) ? $_POST['item__remark'][$key] : "");

        $Borrow->item_approve([$item__confirm, $item__remark, $item__id]);
      endforeach;
    endif;

    $Validation->alert("success", "ดำเนินการเรียบร้อย", "/borrow");
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