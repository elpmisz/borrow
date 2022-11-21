<?php

use app\classes\System;
use app\classes\User;
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

$Users = new User();
$Systems = new System();
$Validation = new Validation();

$system = $Systems->fetch();

if ($action === "add") :
  try {
    $login = (isset($_POST['login']) ? $Validation->input($_POST['login']) : "");
    $name = (isset($_POST['name']) ? $Validation->input($_POST['name']) : "");
    $email = (isset($_POST['email']) ? $Validation->input($_POST['email']) : "");
    $contact = (isset($_POST['contact']) ? $Validation->input($_POST['contact']) : "");
    $province = (isset($_POST['province']) ? $Validation->input($_POST['province']) : "");
    $level = (isset($_POST['level']) ? $Validation->input($_POST['level']) : "");
    $password = $system['default_password'];
    $options = ["cost" => 15, "salt" => "ceb20772e0c9d240c75eb26b0e37abee"];
    $hash = password_hash($password, PASSWORD_BCRYPT, $options);

    if (!empty($email)) {
      $format_email = $Validation->email($email);
      if (!$format_email) {
        $Validation->alert("danger", "รูปแบบอีเมล์ไม่ถูกต้อง กรุณาตรวจสอบข้อมูล", "/users");
      }
    }

    $Users->user_detail_insert([$name, $email, $contact, $province]);
    $user_id = $Users->last_insert_id();
    $Users->user_login_insert([$user_id, $login, $hash, $level]);

    $Validation->alert("success", "เพิ่มข้อมูลเรียบร้อย", "/users");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;

if ($action === "adminupdate") :
  try {
    $user_id = (isset($_POST['user_id']) ? $Validation->input($_POST['user_id']) : "");
    $login = (isset($_POST['login']) ? $Validation->input($_POST['login']) : "");
    $name = (isset($_POST['name']) ? $Validation->input($_POST['name']) : "");
    $email = (isset($_POST['email']) ? $Validation->input($_POST['email']) : "");
    $contact = (isset($_POST['contact']) ? $Validation->input($_POST['contact']) : "");
    $province = (isset($_POST['province']) ? $Validation->input($_POST['province']) : "");
    $level = (isset($_POST['level']) ? $Validation->input($_POST['level']) : "");
    $status = (isset($_POST['status']) ? $Validation->input($_POST['status']) : "");

    $Users->user_detail_update([$name, $email, $contact, $province, $status, $user_id]);
    $Users->user_login_update([$login, $level, $user_id]);

    $Validation->alert("success", "ดำเนินการเรียบร้อย", "/users/view/{$user_id}");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;

if ($action === "userupdate") :
  try {
    $id = (isset($_POST['id']) ? $Validation->input($_POST['id']) : "");
    $name = (isset($_POST['name']) ? $Validation->input($_POST['name']) : "");
    $email = (isset($_POST['email']) ? $Validation->input($_POST['email']) : "");
    $contact = (isset($_POST['contact']) ? $Validation->input($_POST['contact']) : "");
    $province = (isset($_POST['province']) ? $Validation->input($_POST['province']) : "");
    $status = 1;

    $Users->user_detail_update([$name, $email, $contact, $province, $status, $id]);

    $Validation->alert("success", "ดำเนินการเรียบร้อย", "/users/profile");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;

if ($action === "provinceselect") :
  try {
    $keyword = (isset($_POST['q']) ? $Validation->input($_POST['q']) : "");
    $result = $Users->province_select($keyword);

    $data = [];
    foreach ($result as $row) :
      $data[] = [
        "id" => $row['code'],
        "text" => $row['name']
      ];
    endforeach;

    echo json_encode($data);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;

if ($action === "zonefilter") :
  try {
    $keyword = (isset($_POST['q']) ? $Validation->input($_POST['q']) : "");
    $result = $Users->zone_filter($keyword);

    $data = [];
    foreach ($result as $row) :
      $data[] = [
        "id" => $row['zone_id'],
        "text" => $row['zone_name']
      ];
    endforeach;

    echo json_encode($data);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;

if ($action === "itemselect") :
  try {
    $keyword = (isset($_POST['q']) ? $Validation->input($_POST['q']) : "");
    $result = $Users->item_select($keyword);

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

if ($action === "itemdetail") :
  try {
    $item = (isset($_POST['item']) ? $Validation->input($_POST['item']) : "");
    $data = $Users->item_detail([$item]);

    echo json_encode($data);
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;

if ($action === "itemadd") :
  try {
    $text = (isset($_POST['text']) ? $Validation->input($_POST['text']) : "");

    if (isset($_POST['item_id'])) {
      foreach (array_filter($_POST['item_id']) as $key => $value) {
        $item = (isset($_POST['item_id']) ? $Validation->input($_POST['item_id'][$key]) : "");
        $amount = (isset($_POST['item_amount']) ? $Validation->input($_POST['item_amount'][$key]) : "");
        $remark = (isset($_POST['item_remark']) ? $Validation->input($_POST['item_remark'][$key]) : "");

        $count = $Users->item_count([$user_id, $item]);
        if (intval($count) === 0) {
          $Users->item_insert([$user_id, $text, $item, $amount, $remark]);
        }
      }
    }

    if (isset($_POST['item__id'])) {
      foreach (array_filter($_POST['item__id']) as $key => $value) {
        $item = (isset($_POST['item__id']) ? $Validation->input($_POST['item__id'][$key]) : "");
        $amount = (isset($_POST['item__amount']) ? $Validation->input($_POST['item__amount'][$key]) : "");
        $remark = (isset($_POST['item__remark']) ? $Validation->input($_POST['item__remark'][$key]) : "");

        $Users->item_update([$text, $amount, $remark, $item]);
      }
    }

    $Validation->alert("success", "ดำเนินการเรียบร้อย", "/users/item");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;

if ($action === "change") :
  try {
    $password = (isset($_POST['password']) ? $Validation->input($_POST['password']) : "");
    $password2 = (isset($_POST['password2']) ? $Validation->input($_POST['password2']) : "");
    $options = ["cost" => 15, "salt" => "ceb20772e0c9d240c75eb26b0e37abee"];
    $hash = password_hash($password, PASSWORD_BCRYPT, $options);

    if ($password != $password2) {
      $Validation->alert("danger", "รหัสผ่านไม่เหมือนกัน กรุณากรอกอีกครั้ง", "/users/profile");
    }

    $Users->change_password([$hash, $user_id]);

    $Validation->alert("success", "ดำเนินการเรียบร้อย", "/users/profile");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;
