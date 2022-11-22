<?php

$page = "items";
$group = "system";

include_once(__DIR__ . "/../../includes/header.php");
include_once(__DIR__ . "/../../includes/sidebar.php");

$param = (isset($params) ? explode("/", $params) : "");
$id = (!empty($param[0]) ? $param[0] : "");

$row = $Items->item_fetch([$id]);
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
          <form action="/items/update" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
            <div class="row mb-2" style="display: none;">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">รหัส</label>
              <div class="col-xl-2 col-md-6">
                <input type="email" class="form-control form-control-sm" name="id" value="<?php echo $row['id'] ?>" readonly>
                <div class="invalid-feedback">
                  กรุณากรอกช่องนี้.
                </div>
              </div>
            </div>

            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">ชื่อ</label>
              <div class="col-xl-4 col-md-8">
                <input type="text" class="form-control form-control-sm" name="name" value="<?php echo $row['name'] ?>" required>
              </div>
            </div>
            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">ระดับ</label>
              <div class="col-xl-8 col-md-8">
                <div class="form-check form-check-inline pt-2">
                  <input class="form-check-input" type="radio" name="type" id="topic" value="1" <?php echo (intval($row['type']) === 1 ? "checked" : "") ?>>
                  <label class="form-check-label text-primary" for="topic">หลัก</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="type" id="sub" value="2" <?php echo (intval($row['type']) === 2 ? "checked" : "") ?>>
                  <label class="form-check-label text-success" for="sub">ย่อย</label>
                </div>
              </div>
            </div>

            <div class="row mb-2 div_sub">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">อ้างอิง</label>
              <div class="col-xl-4 col-md-6">
                <select class="form-select form-select-sm reference" name="reference" data-placeholder="-- อ้างอิง --"></select>
                <div class="invalid-feedback">
                  กรุณาเลือกช่องนี้.
                </div>
              </div>
            </div>
            <div class="row mb-2 div_sub">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">หน่วย</label>
              <div class="col-xl-2 col-md-8">
                <input type="text" class="form-control form-control-sm" name="unit" value="<?php echo $row['unit'] ?>">
                <div class="invalid-feedback">
                  กรุณากรอกช่องนี้.
                </div>
              </div>
            </div>

            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">สถานะ</label>
              <div class="col-xl-8 col-md-8">
                <div class="form-check form-check-inline pt-2">
                  <input class="form-check-input" type="radio" name="status" id="active" value="1" <?php echo (intval($row['status']) === 1 ? "checked" : "") ?>>
                  <label class="form-check-label text-success" for="active">
                    <i class="fa fa-check-circle pe-2"></i>ใช้งาน
                  </label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="status" id="inactive" value="2" <?php echo (intval($row['status']) === 2 ? "checked" : "") ?>>
                  <label class="form-check-label text-danger" for="inactive">
                    <i class="fa fa-times-circle pe-2"></i>ระงับการใช้งาน
                  </label>
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
                <a href="/items" class="btn btn-danger btn-sm w-100">
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
  let type = parseInt($("input[name='type']:checked").val());
  if (type === 2) {
    $(".div_sub").show();
    $(".reference").prop("required", true);

    let reference_selected = new Option(<?php echo "'{$row['reference_name']}', '{$row['reference_id']}'" ?>, true, true);
    $(".reference").append(reference_selected).trigger("change");
  } else {
    $(".reference").prop("required", false);
    $(".div_sub").hide();
  }

  $(document).on("change", "input[name='type']", function() {
    let type = parseInt($(this).val());

    if (type === 2) {
      $(".div_sub").show();
      $(".reference").prop("required", true);
    } else {
      $(".reference").prop("required", false);
      $(".div_sub").hide();
      $(".reference").empty();
    }
  });

  $(".reference").each(function() {
    $(this).select2({
      containerCssClass: "select2--small",
      dropdownCssClass: "select2--small",
      dropdownParent: $(this).parent(),
      width: "100%",
      allowClear: true,
      ajax: {
        url: "/items/referenceselect",
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