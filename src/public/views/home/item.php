<?php
$page = "item";
$group = "report";

include_once(__DIR__ . "/../../includes/header.php");
include_once(__DIR__ . "/../../includes/sidebar.php");
?>

<main id="main" class="main">
  <div class="row justify-content-center">
    <?php include_once(__DIR__ . "/../../includes/alert.php"); ?>
    <div class="col-xl-12">
      <div class="card shadow">
        <div class="card-header">
          <h4 class="text-center">รายการอุปกรณ์ (รายจังหวัด)</h4>
        </div>
        <div class="card-body">

          <div class="row my-3">
            <div class="col-xl-12">
              <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm province w-100">
                  <thead>
                    <tr>
                      <th width="30%">หน่วยงาน</th>
                      <th width="30%">อุปกรณ์</th>
                      <th width="10%">จำนวน</th>
                      <th width="10%">หน่วยนับ</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="col-xl-12 py-3">
      <div class="card shadow">
        <div class="card-header">
          <h4 class="text-center">รายการอุปกรณ์ (รายเขต)</h4>
        </div>
        <div class="card-body">

          <div class="row my-3">
            <div class="col-xl-12">
              <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm zone w-100">
                  <thead>
                    <tr>
                      <th width="5%">#</th>
                      <th width="10%">เขต</th>
                      <th width="20%">อุปกรณ์</th>
                      <th width="10%">จำนวน</th>
                      <th width="10%">หน่วยนับ</th>
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
</main>

<?php
include_once(__DIR__ . "/../../includes/footer.php");
?>
<script>
$(".province").DataTable({
  serverSide: true,
  scrollX: true,
  searching: true,
  order: [],
  ajax: {
    url: "/provinceitem",
    type: "POST",
  },
  columnDefs: [{
    targets: [2, 3],
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

$(".zone").DataTable({
  serverSide: true,
  scrollX: true,
  searching: true,
  order: [],
  ajax: {
    url: "/zoneitem",
    type: "POST",
  },
  columnDefs: [{
    targets: [0, 3, 4],
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