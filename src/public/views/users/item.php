<?php

$page = "item";
$group = "users";

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
          <form action="/users/itemadd" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">หน่วยงาน</label>
              <div class="col-xl-6 col-md-8">
                <div class="input-group">
                  <input type="text" class="form-control form-control-sm" value="<?php echo $user['user_name'] ?>" readonly>
                </div>
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-sm-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-sm">
                    <thead>
                      <tr>
                        <th width="5%">#</th>
                        <th width="45%">อุปกรณ์</th>
                        <th width="10%">จำนวน</th>
                        <th width="10%">หน่วยนับ</th>
                        <th width="30%">หมายเหตุ</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $items = $Users->item_fetch([$user_id]);
                      foreach ($items as $key => $item) :
                        $key++;
                        $text[] = $item['text'];
                      ?>
                        <tr>
                          <td class="text-center"><?php echo $key ?></td>
                          <td><?php echo $item['item_name'] ?></td>
                          <td>
                            <input type="hidden" name="item__id[]" value="<?php echo $item['item_id'] ?>">
                            <input type="number" class="form-control form-control-sm text-center" name="item__amount[]" min="1" value="<?php echo $item['item_amount'] ?>">
                          </td>
                          <td class="text-center"><?php echo $item['item_unit'] ?></td>
                          <td>
                            <input type="text" class="form-control form-control-sm" name="item__remark[]" value="<?php echo $item['item_remark'] ?>">
                          </td>
                        </tr>
                      <?php endforeach  ?>
                      <tr class="tr_add">
                        <td class="text-center">
                          <button type="button" class="btn btn-sm btn-success increase">+</button>
                          <button type="button" class="btn btn-sm btn-danger decrease">-</button>
                        </td>
                        <td>
                          <select class="form-select form-select-sm item" name="item_id[]" data-placeholder="-- เลือก --"></select>
                        </td>
                        <td>
                          <input type="number" class="form-control form-control-sm text-center amount" name="item_amount[]" min="1">
                        </td>
                        <td class="text-center">
                          <span class="unit"></span>
                        </td>
                        <td>
                          <input type="text" class="form-control form-control-sm" name="item_remark[]">
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="row mb-2">
              <label class="col-xl-4 col-md-4 col-form-label text-xl-end">รายละเอียดเพิ่มเติม</label>
              <div class="col-xl-6 col-md-6">
                <textarea class="form-control form-control-sm" name="text" rows="4"><?php echo (count($items) > 0 ? (implode("", array_unique($text))) : "") ?></textarea>
              </div>
            </div>

            <div class="row justify-content-center mb-2">
              <div class="col-xl-3 col-md-6">
                <button type="submit" class="btn btn-success btn-sm w-100">
                  <i class="fas fa-check pe-2"></i>ยืนยัน
                </button>
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
</script>