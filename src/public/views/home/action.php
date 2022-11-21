<?php

use app\classes\User;
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

$Users = new User();
$Validation = new Validation();

if ($action === "login") :
  try {
    $name = (isset($_POST['name']) ? $Validation->input($_POST['name']) : "");
    $password = (isset($_POST['password']) ? $Validation->input($_POST['password']) : "");

    $verify = $Users->password_verify([$name], $password);
    if (!$verify) {
      $Validation->alert("danger", "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง กรุณาตรวจสอบข้อมูล", "/login");
    }

    $row = $Users->user_name_fetch([$name]);
    $_SESSION['user_id'] = $row['user_id'];
    $text = (!empty($row['user_name']) ? $row['user_name'] : "");

    $Validation->alert("primary", "ยินดีต้อนรับ {$text}", "/home");
  } catch (PDOException $e) {
    die($e->getMessage());
  }
endif;
