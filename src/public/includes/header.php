<?php

use app\classes\Borrow;
use app\classes\Dashboard;
use app\classes\Item;
use app\classes\System;
use app\classes\User;

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . "/../vendor/autoload.php");
$user_id = (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "");

$Systems = new System();
$Users = new User();
$Items = new Item();
$Borrows = new Borrow();
$Dashboards = new Dashboard();

$system = $Systems->fetch();
$user = $Users->user_id_fetch([$user_id]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo $system['brand'] ?></title>
  <link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/vendor/fortawesome/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="/vendor/pnikolov/bootstrap-daterangepicker/css/daterangepicker.min.css">
  <link rel="stylesheet" href="/vendor/fullcalendar/fullcalendar/dist/fullcalendar.min.css">
  <link rel="stylesheet" href="/vendor/select2/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="/assets/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
      <a href="/home" class="logo d-flex align-items-center">
        <span class="d-none d-lg-block"><?php echo $system['brand'] ?></span>
      </a>
      <i class="fa fa-bars toggle-sidebar-btn"></i>
    </div>

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $user['user_name'] ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $user['user_name'] ?></h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="/users/profile">
                <i class="fa fa-user"></i> ข้อมูลส่วนตัว
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center logout" href="javascript:void(0)">
                <i class="fa fa-right-from-bracket"></i> ออกจากระบบ
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </header>