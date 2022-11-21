<?php

$page = "items";
$group = "system";

include_once(__DIR__ . "/../../includes/header.php");
include_once(__DIR__ . "/../../includes/sidebar.php");

if ($user['user_level'] === 1) {
  header("Location: /error");
}
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

          <div class="row">
            <div class="col-xl-3 col-md-6 mb-2">
              <a href="javascript:void(0)" class="btn btn-success btn-sm w-100 btn_report">
                <i class="fa fa-file-alt pe-2"></i>รายงาน
              </a>
            </div>

            <div class="col-xl-3 col-md-6 offset-xl-6 mb-2">
              <a href="/items/request" class="btn btn-danger btn-sm w-100">
                <i class="fa fa-plus pe-2"></i>เพิ่ม
              </a>
            </div>
          </div>

          <div class="row my-3">
            <div class="col-xl-12">
              <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm data w-100">
                  <thead>
                    <tr>
                      <th width="10%">#</th>
                      <th width="30%">อุปกรณ์</th>
                      <th width="20%">อ้างอิง</th>
                      <th width="10%">หน่วย</th>
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
  filter_data();

  function filter_data(status) {
    let data = $(".data").DataTable({
      serverSide: true,
      scrollX: true,
      searching: true,
      order: [],
      ajax: {
        url: "/items/data",
        type: "POST",
        data: {
          status: status
        }
      },
      columnDefs: [{
        targets: [0, 2, 3],
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
  }

  $(document).on("click", ".count", function() {
    let status = $(this).prop("id");
    if (status) {
      $(".data").DataTable().destroy();
      filter_data(status);
    } else {
      $(".data").DataTable().destroy();
      filter_data();
    }
  });
</script>