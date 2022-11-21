<?php

$page = "users";
$group = "system";

include_once(__DIR__ . "/../../includes/header.php");
include_once(__DIR__ . "/../../includes/sidebar.php");
?>

<main id="main" class="main">
  <div class="row justify-content-center">
    <?php include_once(__DIR__ . "/../../includes/alert.php"); ?>
    <div class="col-xl-12">
      <div class="card shadow">
        <div class="card-header">
          <h4 class="text-center">เพิ่มข้อมูล</h4>
        </div>
        <div class="card-body">
          <form action="/users/add" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">รหัสหน่วยงาน</label>
              <div class="col-xl-2 col-md-8">
                <div class="input-group">
                  <input type="text" class="form-control form-control-sm" name="login" required>
                </div>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">หน่วยงาน</label>
              <div class="col-xl-8 col-md-8">
                <div class="input-group">
                  <input type="text" class="form-control form-control-sm" name="name" required>
                  <div class="invalid-feedback">
                    กรุณากรอกช่องนี้.
                  </div>
                </div>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">อีเมล</label>
              <div class="col-xl-4 col-md-8">
                <input type="email" class="form-control form-control-sm" name="email">
                <div class="invalid-feedback">
                  กรุณากรอกช่องนี้.
                </div>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">ติดต่อ</label>
              <div class="col-xl-8 col-md-8">
                <input type="text" class="form-control form-control-sm" name="contact">
                <div class="invalid-feedback">
                  กรุณากรอกช่องนี้.
                </div>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">จังหวัด</label>
              <div class="col-xl-4 col-md-6">
                <select class="form-select form-select-sm province" name="province" data-placeholder="-- จังหวัด --"></select>
                <div class="invalid-feedback">
                  กรุณาเลือกช่องนี้.
                </div>
              </div>
            </div>

            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">ระดับ</label>
              <div class="col-xl-4 col-md-6">
                <select class="form-select form-select-sm level" name="level" data-placeholder="-- ระดับ --" required></select>
                <div class="invalid-feedback">
                  กรุณาเลือกช่องนี้.
                </div>
              </div>
            </div>

            <div class="row justify-content-center mb-2">
              <div class="col-xl-3 col-md-6">
                <button type="submit" class="btn btn-success btn-sm w-100">
                  <i class="fas fa-check pe-2"></i>ยืนยัน
                </button>
              </div>
              <div class="col-xl-3 col-md-6">
                <a href="/users" class="btn btn-danger btn-sm w-100">
                  <i class="fa fa-arrow-left pe-2"></i>กลับหน้าหลัก
                </a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<?php
include_once(__DIR__ . "/../../includes/footer.php");
?>
<script>
  let level = [{
    id: "",
    text: "-- ระดับ --"
  }, {
    id: 1,
    text: "ผู้ใช้ระดับจังหวัด"
  }, {
    id: 2,
    text: "ผู้ใช้ระดับเขต"
  }, {
    id: 9,
    text: "ผู้ดูแลระบบ"
  }];

  $(".level").each(function() {
    $(this).select2({
      containerCssClass: "select2--small",
      dropdownCssClass: "select2--small",
      dropdownParent: $(this).parent(),
      width: "100%",
      allowClear: true,
      data: level
    });
  });

  $(".province").each(function() {
    $(this).select2({
      containerCssClass: "select2--small",
      dropdownCssClass: "select2--small",
      dropdownParent: $(this).parent(),
      width: "100%",
      allowClear: true,
      ajax: {
        url: "/users/provinceselect",
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