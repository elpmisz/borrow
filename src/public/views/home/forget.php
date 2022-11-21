<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>ลืมรหัสผ่าน</title>
  <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/vendor/fortawesome/font-awesome/css/all.min.css" rel="stylesheet">
  <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body>

  <div class="container">
    <div class="row justify-content-center my-5">
      <div class="col-xl-4">
        <div class="card mb-3">
          <div class="card-body">
            <div class="py-3">
              <h3 class="text-center">ลืมรหัสผ่าน?</h3>
            </div>
            <form action="/auth/forget" method="POST" class="row g-3 needs-validation" novalidate>
              <div class="input-group">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control" placeholder="ชื่อผู้ใช้ระบบ" name="name" required>
                <div class="invalid-feedback">
                  กรุณาระบุ ชื่อผู้ใช้ระบบ.
                </div>
              </div>
              <div class="col-12">
                <button class="btn btn-primary w-100" type="submit">
                  <i class="fa fa-check pe-2"></i> ยืนยัน
                </button>
              </div>
              <div class="col-12">
                <a href="/login" class="btn btn-danger w-100">
                  <i class="fa fa-arrow-left pe-2"></i> กลับหน้าหลัก
                </a>
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