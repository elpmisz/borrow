<?php

$page = "users";
$group = "system";

include_once(__DIR__ . "/../../includes/header.php");
include_once(__DIR__ . "/../../includes/sidebar.php");

$count = $Users->user_count();

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
          <h4 class="text-center">ข้อมูลผู้ใช้งาน</h4>
        </div>
        <div class="card-body">

          <div class="row">
            <div class="col-xl-3 col-md-6 mb-2">
              <div class="card text-bg-success shadow py-2 count" id="1">
                <div class="card-body">
                  <h3 class="text-end"><?php echo ($count['total'] ? $count['total'] : 0) ?></h3>
                  <h5 class="text-end">ผู้ใช้งานทั้งหมด</h5>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-2">
              <div class="card text-bg-warning shadow py-2 count" id="2">
                <div class="card-body">
                  <h3 class="text-end"><?php echo ($count['total'] ? $count['district'] : 0) ?></h3>
                  <h5 class="text-end">ผู้ใช้ระดับเขต</h5>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-2">
              <div class="card text-bg-primary shadow py-2 count" id="3">
                <div class="card-body">
                  <h3 class="text-end"><?php echo ($count['total'] ? $count['province'] : 0) ?></h3>
                  <h5 class="text-end">ผู้ใช้ระดับจังหวัด</h5>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-2">
              <div class="card text-bg-danger shadow py-2 count" id="4">
                <div class="card-body">
                  <h3 class="text-end"><?php echo ($count['total'] ? $count['admin'] : 0) ?></h3>
                  <h5 class="text-end">ผู้ดูแลระบบ</h5>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xl-3 col-md-6 mb-2">
              <a href="javascript:void(0)" class="btn btn-success btn-sm w-100 btn_report">
                <i class="fa fa-file-alt pe-2"></i>รายงาน
              </a>
            </div>

            <div class="col-xl-3 col-md-6 offset-xl-3 mb-2">
              <select class="form-select form-select-sm w-100 zone_filter" data-placeholder="-- เลือก --"></select>
            </div>

            <div class="col-xl-3 col-md-6 mb-2">
              <a href="/users/request" class="btn btn-danger btn-sm w-100">
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
                      <th width="10%">รหัส</th>
                      <th width="20%">ชื่อหน่วยงาน</th>
                      <th width="20%">จังหวัด</th>
                      <th width="10%">เขต</th>
                      <th width="10%">ระดับ</th>
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

  function filter_data(status, zone) {
    let data = $(".data").DataTable({
      serverSide: true,
      scrollX: true,
      searching: true,
      order: [],
      ajax: {
        url: "/users/data",
        type: "POST",
        data: {
          status: status,
          zone: zone
        }
      },
      columnDefs: [{
        targets: [0, 1, 4, 5],
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

  $(document).on("change", ".zone_filter", function() {
    let status = 1;
    let zone = $(this).val();

    console.log(status + " / " + zone)
    if (status || zone) {
      $(".data").DataTable().destroy();
      filter_data(status, zone);
    } else {
      $(".data").DataTable().destroy();
      filter_data();
    }
  });

  $(".zone_filter").each(function() {
    $(this).select2({
      containerCssClass: "select2--small",
      dropdownCssClass: "select2--small",
      dropdownParent: $(this).parent(),
      width: "100%",
      allowClear: true,
      ajax: {
        url: "/users/zonefilter",
        method: 'POST',
        dataType: 'json',
        delay: 100,
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  });
</script>