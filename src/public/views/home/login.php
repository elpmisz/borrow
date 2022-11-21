<?php
session_start();

$user__id = (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "");
if ($user__id) {
  header("Location: /home ");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>เข้าสู่ระบบ</title>
  <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/vendor/fortawesome/font-awesome/css/all.min.css" rel="stylesheet">
  <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body>

  <div class="container">
    <div class="row justify-content-center my-5">
      <?php include_once(__DIR__ . "/../../includes/alert.php"); ?>
      <div class="col-xl-4">
        <div class="card">
          <div class="card-body">
            <div class="py-3">
              <h3 class="text-center">เข้าสู่ระบบ</h3>
            </div>
            <form action="/auth/login" method="POST" class="row g-3 needs-validation" novalidate>
              <div class="input-group">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control" placeholder="ชื่อผู้ใช้ระบบ" name="name" required>
                <div class="invalid-feedback">
                  กรุณาระบุ ชื่อผู้ใช้ระบบ.
                </div>
              </div>
              <div class="input-group">
                <span class="input-group-text"><i class="fa fa-key"></i></span>
                <input type="password" class="form-control" placeholder="รหัสผ่าน" name="password" required>
                <div class="invalid-feedback">
                  กรุณาระบุ รหัสผ่าน.
                </div>
              </div>
              <div class="col-12">
                <button class="btn btn-primary w-100" type="submit">
                  <i class="fa fa-check pe-2"></i> ยืนยัน
                </button>
              </div>
              <div class="col-6 offset-6">
                <p class="small text-end"><a href="/forget"><span class="text-danger">ลืมรหัสผ่าน?</span></a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <script src="/vendor/components/jquery/jquery.min.js"></script>
  <script src="/assets/js/main.js"></script>
</body>

</html>