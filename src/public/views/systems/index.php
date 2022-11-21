<?php
$page = "system";
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
          <h4 class="text-center">ข้อมูลระบบ</h4>
        </div>
        <div class="card-body">
          <form action="/system/edit" method="POST" class="needs-validation" novalidate>
            <div class="mt-3 row" style="display: none;">
              <label class="col-sm-5 col-form-label text-md-end">รหัส</label>
              <div class="col-sm-3">
                <input type="text" class="form-control form-control-sm" name="id" value="<?php echo $system['id'] ?>" readonly>
              </div>
            </div>
            <div class="mt-3 row">
              <label class="col-sm-5 col-form-label text-md-end">ชื่อระบบ</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" name="brand" value="<?php echo $system['brand'] ?>" required>
                <div class="invalid-feedback">
                  กรุณากรอกช่องนี้.
                </div>
              </div>
            </div>
            <div class="mt-3 row">
              <label class="col-sm-5 col-form-label text-md-end">ขื่ออีเมล์</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" name="email" value="<?php echo $system['email'] ?>" required>
                <div class="invalid-feedback">
                  กรุณากรอกช่องนี้.
                </div>
              </div>
            </div>
            <div class="mt-3 row">
              <label class="col-sm-5 col-form-label text-md-end">รหัสผ่านอีเมล์</label>
              <div class="col-sm-3">
                <input type="password" class="form-control form-control-sm" name="email_password" value="<?php echo $system['email_password'] ?>" required>
                <div class="invalid-feedback">
                  กรุณากรอกช่องนี้.
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="email_password">
                  <label class="form-check-label" for="email_password">
                    แสดงรหัสผ่าน
                  </label>
                </div>
              </div>
            </div>
            <div class="mt-3 row">
              <label class="col-sm-5 col-form-label text-md-end">รหัสผ่านเริ่มต้น</label>
              <div class="col-sm-3">
                <input type="password" class="form-control form-control-sm" name="default_password" value="<?php echo $system['default_password'] ?>" required>
                <div class="invalid-feedback">
                  กรุณากรอกช่องนี้.
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="default_password">
                  <label class="form-check-label" for="default_password">
                    แสดงรหัสผ่าน
                  </label>
                </div>
              </div>
            </div>
            <div class="mt-3 row justify-content-center">
              <div class="col-xl-3">
                <button class="btn btn-sm btn-primary w-100" type="submit">
                  <i class="fa fa-check pe-2"></i> ยืนยัน
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
  $(document).on("click", "#default_password", function() {
    let checked = $(this).is(':checked');
    if (checked) {
      $("input[name='default_password']").prop("type", "text");
    } else {
      $("input[name='default_password']").prop("type", "password");
    }
  });

  $(document).on("click", "#email_password", function() {
    let checked = $(this).is(':checked');
    if (checked) {
      $("input[name='email_password']").prop("type", "text");
    } else {
      $("input[name='email_password']").prop("type", "password");
    }
  });
</script>