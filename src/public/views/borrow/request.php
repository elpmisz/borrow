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
          <h4 class="text-center">ใช้บริการยืม - คืน</h4>
        </div>
        <div class="card-body">
          <form action="/borrow/add" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">หน่วยงาน</label>
              <div class="col-xl-6 col-md-8">
                <input type="text" class="form-control form-control-sm" value="<?php echo $user['user_name'] ?>"
                  readonly>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">บริการ</label>
              <div class="col-xl-8 col-md-8">
                <div class="form-check form-check-inline pt-2">
                  <input class="form-check-input" type="radio" name="type" id="active" value="1">
                  <label class="form-check-label text-success" for="active">ยืม</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="type" id="inactive" value="2">
                  <label class="form-check-label text-danger" for="inactive">คืน</label>
                </div>
              </div>
            </div>

            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end date_text">วันที่ ยืม</label>
              <div class="col-xl-4 col-md-4 ">
                <input type="text" class="form-control form-control-sm date" name="date" required>
                <div class="invalid-feedback">
                  กรุณากรอกช่องนี้.
                </div>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end text">จุดประสงค์การยืม</label>
              <div class="col-xl-6 col-md-8">
                <textarea class="form-control form-control-sm" name="text" rows="3" required></textarea>
                <div class="invalid-feedback">
                  กรุณากรอกช่องนี้.
                </div>
              </div>
            </div>
            <div class="row mb-2 div_borrow">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">Google Maps</label>
              <div class="col-xl-6 col-md-8 pt-2">
                <a href=" https://www.google.co.th/maps" target="_blank">คลิก</a>
              </div>
            </div>

            <div class="row mb-2 div_borrow">
              <div class="col-sm-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-sm">
                    <thead>
                      <tr>
                        <th width="5%">#</th>
                        <th width="35%">อุปกรณ์</th>
                        <th width="10%">จำนวน</th>
                        <th width="10%">หน่วยนับ</th>
                        <th width="20%">ละติจูด, ลองจิจูด</th>
                        <th width="20%">หมายเหตุ</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="tr_add">
                        <td class="text-center">
                          <button type="button" class="btn btn-sm btn-success increase">+</button>
                          <button type="button" class="btn btn-sm btn-danger decrease">-</button>
                        </td>
                        <td>
                          <select class="form-select form-select-sm item" name="item_id[]"
                            data-placeholder="-- เลือก --" required></select>
                          <div class="invalid-feedback">
                            กรุณาเลือกช่องนี้.
                          </div>
                        </td>
                        <td>
                          <input type="number" class="form-control form-control-sm text-center amount"
                            name="item_amount[]" min="1">
                        </td>
                        <td class="text-center">
                          <span class="unit"></span>
                        </td>
                        <td>
                          <input type="text" class="form-control form-control-sm" name="item_location[]">
                        </td>
                        <td>
                          <input type="text" class="form-control form-control-sm" name="item_text[]">
                        </td>
                      </tr>
                    </tbody>
                  </table>
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
                <a href="/borrow" class="btn btn-danger btn-sm w-100">
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
$(".item").each(function() {
  $(this).select2({
    containerCssClass: "select2--small",
    dropdownCssClass: "select2--small",
    dropdownParent: $(this).parent(),
    width: "100%",
    allowClear: true,
    ajax: {
      url: "/users/itemselect",
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

$(document).on("change", ".item", function() {
  let _this = $(this);
  let item = $(this).val();
  $.ajax({
    url: '/users/itemdetail',
    method: 'POST',
    data: {
      item: item
    },
    dataType: 'json',
    success: function(data) {
      _this.closest("tr").find(".unit").text(data.item_unit);
      _this.closest("tr").find(".amount").prop("required", true)
    },
  });
});

$(".decrease").hide();
$(document).on("click", ".increase", function() {
  $(".item").select2("destroy");
  let row = $(".tr_add:last");
  let clone = row.clone();
  clone.find("input, select").val("");
  clone.find(".increase").hide();
  clone.find(".decrease").show();
  clone.find(".decrease").on("click", function() {
    $(this).closest("tr").remove();
  });
  row.after(clone);
  clone.show();

  $(".item").each(function() {
    $(this).select2({
      containerCssClass: "select2--small",
      dropdownCssClass: "select2--small",
      dropdownParent: $(this).parent(),
      width: "100%",
      allowClear: true,
      ajax: {
        url: "/users/itemselect",
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
});

$(".date").on('keydown paste', function(e) {
  e.preventDefault();
});

$(".date").daterangepicker({
  autoUpdateInput: false,
  showDropdowns: true,
  locale: {
    "format": "DD/MM/YYYY",
    "applyLabel": "ยืนยัน",
    "cancelLabel": "ยกเลิก",
    "daysOfWeek": [
      "อา", "จ", "อ", "พ", "พฤ", "ศ", "ส"
    ],
    "monthNames": [
      "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
      "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
    ]
  },
  "applyButtonClasses": "btn-success",
  "cancelClass": "btn-danger"
});

$(".date").on("apply.daterangepicker", function(ev, picker) {
  $(this).val(picker.startDate.format("DD/MM/YYYY") + " - " + picker.endDate.format("DD/MM/YYYY"));
});

$(".date").on("cancel.daterangepicker", function(ev, picker) {
  $(this).val("");
});

$(".div_borrow").hide();
$(document).on("click", "input[name='type']", function() {
  let type = parseInt($(this).val());
  $(".date").val("");
  if (type === 1) {
    $(".date_text").text("วันที่ ยืม");
    $(".text").text("จุดประสงค์การยืม");
    $(".div_borrow").show();
  } else {
    $(".date_text").text("วันที่ คืน");
    $(".text").text("รายละเอียดเพิ่มเติม");
    $(".div_borrow").hide();
  }
});
</script>