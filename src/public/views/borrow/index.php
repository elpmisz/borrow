<?php

$page = "borrow";
$group = "service";

include_once(__DIR__ . "/../../includes/header.php");
include_once(__DIR__ . "/../../includes/sidebar.php");

print_r($user);
?>

<main id="main" class="main">
  <div class="row justify-content-center">
    <?php include_once(__DIR__ . "/../../includes/alert.php"); ?>
    <div class="col-xl-12">
      <div class="card shadow">
        <div class="card-header">
          <h4 class="text-center">ระบบยืม - คืน อุปกรณ์</h4>
        </div>
        <div class="card-body">

          <div class="row justify-content-end">
            <?php if ($user['user_level'] === 9) : ?>
            <div class="col-xl-3 col-md-6 mb-2">
              <a href="/borrow/manage" class="btn btn-primary btn-sm w-100">
                <i class="fa fa-file-alt pe-2"></i>จัดการระบบ
              </a>
            </div>
            <?php endif; ?>

            <div class="col-xl-3 col-md-6 mb-2">
              <a href="/borrow/request" class="btn btn-danger btn-sm w-100">
                <i class="fa fa-plus pe-2"></i>ใช้บริการ
              </a>
            </div>
          </div>

          <?php
          $count = $Borrows->auth_request($user['user_level'], $user['zone_id']);
          if ($count > 0 && in_array($user['user_level'], [2, 9])) :
          ?>
          <div class="card shadow my-2">
            <div class="card-header">
              <h4 class="text-center">รายการที่รอดำเนินการ</h4>
            </div>
            <div class="card-body">
              <div class="row my-3">
                <div class="col-xl-12">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm approve w-100">
                      <thead>
                        <tr>
                          <th width="5%">#</th>
                          <th width="20%">ผู้ใช้บริการ</th>
                          <th width="20%">จุดประสงค์</th>
                          <th width="5%">บริการ</th>
                          <th width="30%">อุปกรณ์</th>
                          <th width="10%">วันที่</th>
                          <th width="10%">วันที่ทำรายการ</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <?php endif; ?>

          <div class="card shadow my-2">
            <div class="card-header">
              <h4 class="text-center">รายการขอใช้บริการ</h4>
            </div>
            <div class="card-body">
              <div class="row my-3">
                <div class="col-xl-12">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm request w-100">
                      <thead>
                        <tr>
                          <th width="10%">#</th>
                          <th width="20%">จุดประสงค์</th>
                          <th width="10%">บริการ</th>
                          <th width="30%">อุปกรณ์</th>
                          <th width="10%">วันที่</th>
                          <th width="10%">วันที่ทำรายการ</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
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
<script>
$(".request").DataTable({
  serverSide: true,
  scrollX: true,
  searching: true,
  order: [],
  ajax: {
    url: "/borrow/datarequest",
    type: "POST",
  },
  columnDefs: [{
    targets: [0, 2, 4, 5],
    className: "text-center",
  }],
  oLanguage: {
    sLengthMenu: "แสดง _MENU_ ลำดับ ต่อหน้า",
    sZeroRecords: "ไม่พบข้อมูลที่ค้นหา",
    sInfo: "แสดง _START_ ถึง _END_ ของ _TOTAL_ ลำดับ",
    sInfoEmpty: "แสดง 0 ถึง 0 ของ 0 ลำดับ",
    sInfoFiltered: "(จากทั้งหมด _MAX_ ลำดับ)",
    sSearch: "ค้นหา :",
    oPaginate: {
      sFirst: "หน้าแรก",
      sLast: "หน้าสุดท้าย",
      sNext: "ถัดไป",
      sPrevious: "ก่อนหน้า"
    }
  }
});

$(".approve").DataTable({
  serverSide: true,
  scrollX: true,
  searching: true,
  order: [],
  ajax: {
    url: "/borrow/dataapprove",
    type: "POST",
  },
  columnDefs: [{
    targets: [0, 3, 5, 6],
    className: "text-center",
  }],
  oLanguage: {
    sLengthMenu: "แสดง _MENU_ ลำดับ ต่อหน้า",
    sZeroRecords: "ไม่พบข้อมูลที่ค้นหา",
    sInfo: "แสดง _START_ ถึง _END_ ของ _TOTAL_ ลำดับ",
    sInfoEmpty: "แสดง 0 ถึง 0 ของ 0 ลำดับ",
    sInfoFiltered: "(จากทั้งหมด _MAX_ ลำดับ)",
    sSearch: "ค้นหา :",
    oPaginate: {
      sFirst: "หน้าแรก",
      sLast: "หน้าสุดท้าย",
      sNext: "ถัดไป",
      sPrevious: "ก่อนหน้า"
    }
  }
});
</script>