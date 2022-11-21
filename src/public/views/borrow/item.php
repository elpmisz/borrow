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
          <h4 class="text-center">ข้อมูลอุปกรณ์</h4>
        </div>
        <div class="card-body">

          <div class="row mb-2">
            <div class="col-sm-12">
              <div class="table-responsive">
                <table class="table table-bordered table-sm">
                  <thead>
                    <tr>
                      <th width="25%">หน่วยงาน</th>
                      <th width="25%">อุปกรณ์</th>
                      <th width="10%">หน่วยนับ</th>
                      <th width="10%">จำนวน</th>
                      <th width="30%">หมายเหตุ</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

          <div class="row justify-content-center mb-2">
            <div class="col-xl-3 col-md-6">
              <a href="/borrow/manage" class="btn btn-danger btn-sm w-100">
                <i class="fa fa-arrow-left pe-2"></i>กลับหน้าจัดการ
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