<?php

$page = "borrow";
$group = "service";

include_once(__DIR__ . "/../../includes/header.php");
include_once(__DIR__ . "/../../includes/sidebar.php");

$param = (isset($params) ? explode("/", $params) : "");
$request_id = (!empty($param[0]) ? $param[0] : "");

$row = $Borrows->request_fetch([$request_id]);
$items = ($row['type_id'] === 1
  ? $Borrows->item_fetch([$user['zone_id'], $request_id], $row['user_id'])
  : $Borrows->item_return_fetch([$row['user_id']], $request_id));
?>

<main id="main" class="main">
  <div class="row justify-content-center">
    <?php include_once(__DIR__ . "/../../includes/alert.php"); ?>
    <div class="col-xl-12">
      <div class="card shadow">
        <div class="card-header">
          <h4 class="text-center">รายละเอียด</h4>
        </div>
        <div class="card-body">
          <form action="/borrow/update" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
            <div class="row mb-2" style="display: none;">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">รายการ</label>
              <div class="col-xl-2 col-md-8">
                <input type="text" class="form-control form-control-sm" name="id" value="<?php echo $row['request_id'] ?>" readonly>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">หน่วยงาน</label>
              <div class="col-xl-6 col-md-8">
                <input type="text" class="form-control form-control-sm" value="<?php echo $row['user_name'] ?>" readonly>
              </div>
            </div>
            <div class="row mb-2" style="display: none;">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">บริการ</label>
              <div class="col-xl-2 col-md-8">
                <input type="text" class="form-control form-control-sm" name="type" value="<?php echo $row['type_id'] ?>" readonly>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">บริการ</label>
              <div class="col-xl-2 col-md-8">
                <input type="text" class="form-control form-control-sm" value="<?php echo $row['type_name'] ?>" readonly>
              </div>
            </div>

            <?php if ($row['type_id'] === 1) : ?>
              <div class="row mb-2">
                <label class="col-xl-4 col-md-4 col-form-label text-xl-end">วันที่ ยืม</label>
                <div class="col-xl-4 col-md-4 ">
                  <input type="text" class="form-control form-control-sm date_borrow" name="date_borrow" value="<?php echo $row['date_borrow'] ?>">
                  <div class="invalid-feedback">
                    กรุณากรอกช่องนี้.
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <?php if ($row['type_id'] === 2) : ?>
              <div class="row mb-2 div_return">
                <label class="col-xl-4 col-md-4 col-form-label text-xl-end">วันที่ คืน</label>
                <div class="col-xl-4 col-md-4 ">
                  <input type="text" class="form-control form-control-sm date_return" name="date_return" value="<?php echo $row['date_return'] ?>">
                  <div class="invalid-feedback">
                    กรุณากรอกช่องนี้.
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end text">จุดประสงค์การยืม</label>
              <div class="col-xl-6 col-md-8">
                <textarea class="form-control form-control-sm" name="text" rows="3" required><?php echo $row['text'] ?></textarea>
                <div class="invalid-feedback">
                  กรุณากรอกช่องนี้.
                </div>
              </div>
            </div>

            <?php if ($row['type_id'] === 1) : ?>
              <div class="row mb-2 div_borrow">
                <label class="col-xl-4 col-md-4 col-form-label text-xl-end">Google Maps</label>
                <div class="col-xl-6 col-md-8 pt-2">
                  <a href=" https://www.google.co.th/maps" target="_blank">คลิก</a>
                </div>
              </div>
            <?php endif; ?>

            <?php if ($row['type_id'] === 1) : ?>
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
                        <?php foreach ($items as $item) : ?>
                          <tr>
                            <td class="text-center">
                              <a href="javascript:void(0)" class="badge text-bg-danger fw-lighter item_delete" id="<?php echo $item['request_location'] ?>">ลบ</a>
                            </td>
                            <td><?php echo $item['item_name'] ?></td>
                            <td class="text-center">
                              <input type="hidden" class="form-control form-control-sm text-center" name="item__id[]" value="<?php echo $item['request_id'] ?>" readonly>
                              <input type="number" class="form-control form-control-sm text-center amount" name="item__amount[]" value="<?php echo $item['request_amount'] ?>" min="1">
                            </td>
                            <td class="text-center"><?php echo $item['item_unit'] ?></td>
                            <td>
                              <input type="text" class="form-control form-control-sm" name="item__location[]" value="<?php echo $item['request_location'] ?>">
                            </td>
                            <td>
                              <input type="text" class="form-control form-control-sm" name="item__text[]" value="<?php echo $item['request_text'] ?>">
                            </td>
                          </tr>
                        <?php endforeach; ?>
                        <tr class="tr_add">
                          <td class="text-center">
                            <button type="button" class="btn btn-sm btn-success increase">+</button>
                            <button type="button" class="btn btn-sm btn-danger decrease">-</button>
                          </td>
                          <td>
                            <select class="form-select form-select-sm item" name="item_id[]" data-placeholder="-- เลือก --"></select>
                            <div class="invalid-feedback">
                              กรุณาเลือกช่องนี้.
                            </div>
                          </td>
                          <td>
                            <input type="number" class="form-control form-control-sm text-center amount" name="item_amount[]" min="1">
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
            <?php endif; ?>

            <?php if ($row['type_id'] === 2) : ?>
              <div class="row mb-2 div_return">
                <div class="col-sm-12">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm w-100">
                      <thead>
                        <tr>
                          <th width="10%">#</th>
                          <th width="30%">อุปกรณ์</th>
                          <th width="10%">จำนวน (ยืม)</th>
                          <th width="10%">จำนวน (คืน)</th>
                          <th width="10%">หน่วยนับ</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        foreach ($items as $key => $item) :
                          $key++;
                        ?>
                          <tr>
                            <td class="text-center"><?php echo $key ?></td>
                            <td><?php echo $item['item_name'] ?></td>
                            <td class="text-center"><?php echo $item['balance'] ?></td>
                            <td>
                              <input type="hidden" class="form-control form-control-sm text-center" name="borrow_id[]" value="<?php echo $item['item_id'] ?>">
                              <input type="number" class="form-control form-control-sm text-center" name="borrow_amount[]" value="<?php echo $item['item_return'] ?>" <?php echo ($row['type_id'] === 2 ? 'min="1"' : "")  ?> max="<?php echo $item['balance'] ?>">
                            </td>
                            <td class="text-center"><?php echo $item['item_unit'] ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            <?php endif; ?>

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

  $(".date_borrow, .date_return").on('keydown paste', function(e) {
    e.preventDefault();
  });

  $(".date_borrow").daterangepicker({
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

  $(".date_return").daterangepicker({
    singleDatePicker: true,
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

  $(".date_borrow").on("apply.daterangepicker", function(ev, picker) {
    $(this).val(picker.startDate.format("DD/MM/YYYY") + " - " + picker.endDate.format("DD/MM/YYYY"));
  });

  $(".date_borrow").on("cancel.daterangepicker", function(ev, picker) {
    $(this).val("");
  });

  let type = parseInt($("input[name='type']").val());
  if (type === 1) {
    $(".text").text("จุดประสงค์การยืม");
    $(".date_borrow").prop("required", true);
    $(".date_return").prop("required", false);
  } else {
    $(".text").text("รายละเอียดเพิ่มเติม");
    $(".date_borrow").prop("required", false);
    $(".date_return").prop("required", true);
  }

  $(document).on("click", ".item_delete", function(e) {
    let item = $(this).prop("id");
    let request = $("input[name='id']").val();
    e.preventDefault();
    Swal.fire({
      title: "ยืนยันที่จะทำรายการ?",
      text: "ลบข้อมูล!",
      icon: "error",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ยืนยัน",
      cancelButtonText: "ปิด",
    }).then((result) => {
      if (result.value) {
        window.location.href = "/borrow/itemdelete/" + item + "/" + request;
      } else {
        return false;
      }
    })
  });
</script>