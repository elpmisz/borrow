<?php

$page = "borrow";
$group = "service";

include_once(__DIR__ . "/../../includes/header.php");
include_once(__DIR__ . "/../../includes/sidebar.php");
?>

<main id="main" class="main">
  <div class="row justify-content-center">
    <?php include_once(__DIR__ . "/../../includes/alert.php"); ?>
    <div class="col-xl-12">
      <div class="card shadow">
        <div class="card-header">
          <h4 class="text-center">จัดการระบบยืม - คืน อุปกรณ์</h4>
        </div>
        <div class="card-body">

          <div class="row justify-content-end">
            <div class="col-xl-3 col-md-6 mb-2">
              <a href="/borrow/item" class="btn btn-danger btn-sm w-100">
                <i class="fa fa-file-alt pe-2"></i>ข้อมูลอุปกรณ์
              </a>
            </div>
          </div>


          <div class="row justify-content-center mb-2">
            <div class="col-xl-3 col-md-6">
              <a href="/borrow" class="btn btn-danger btn-sm w-100">
                <i class="fa fa-arrow-left pe-2"></i>กลับหน้าหลัก
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php
include_once(__DIR__ . "/../../includes/footer.php");
?>