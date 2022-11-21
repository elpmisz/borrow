<?php
$page = (!empty($page) ? $page : "");
$group = (!empty($group) ? $group : "");
?>
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      <a class="nav-link <?php echo ($page === "index" ? "" : "collapsed") ?>" data-bs-target="#report"
        data-bs-toggle="collapse" href="#">
        <i class="fa fa-house"></i> <span>รายงาน</span>
        <i class="fa fa-chevron-down ms-auto"></i>
      </a>
      <ul id="report" class="nav-content <?php echo ($group === "report" ? "show" : "collapse") ?>">
        <li>
          <a class="nav-link <?php echo ($page === "borrow" ? "active" : "collapsed") ?>" href="/home">
            <i class="fa fa-circle"></i> <span>รายงานการยืม - คืน</span>
          </a>
        </li>
        <li>
          <a class="nav-link <?php echo ($page === "item" ? "active" : "collapsed") ?>" href="/item">
            <i class="fa fa-circle"></i> <span>รายงานอุปกรณ์</span>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php echo ($group === "users" ? "" : "collapsed") ?>" data-bs-target="#user"
        data-bs-toggle="collapse" href="#">
        <i class="fa fa-user"></i> <span>หน่วยงาน</span>
        <i class="fa fa-chevron-down ms-auto"></i>
      </a>
      <ul id="user" class="nav-content <?php echo ($group === "users" ? "show" : "collapse") ?>">
        <li>
          <a class="nav-link <?php echo ($page === "profile" ? "active" : "collapsed") ?>" href="/users/profile">
            <i class="fa fa-circle"></i> <span>ข้อมูลหน่วยงาน</span>
          </a>
        </li>
        <li>
          <a class="nav-link <?php echo ($page === "item" ? "active" : "collapsed") ?>" href="/users/item">
            <i class="fa fa-circle"></i> <span>ข้อมูลอุปกรณ์</span>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php echo ($group === "service" ? "" : "collapsed") ?>" data-bs-target="#service"
        data-bs-toggle="collapse" href="#">
        <i class="fa fa-list"></i> <span>บริการ</span>
        <i class="fa fa-chevron-down ms-auto"></i>
      </a>
      <ul id="service" class="nav-content <?php echo ($group === "service" ? "show" : "collapse") ?>">
        <li>
          <a class="nav-link <?php echo ($page === "borrow" ? "active" : "collapsed") ?>" href="/borrow">
            <i class="fa fa-circle"></i> <span>ระบบยืม - คืน อุปกรณ์</span>
          </a>
        </li>
      </ul>
    </li>

    <?php if ($user['user_level'] === 9) : ?>
    <li class="nav-item">
      <a class="nav-link <?php echo ($group === "system" ? "" : "collapsed") ?>" data-bs-target="#system"
        data-bs-toggle="collapse" href="#">
        <i class="fa fa-file-lines"></i> <span>ตั้งค่าระบบ</span>
        <i class="fa fa-chevron-down ms-auto"></i>
      </a>
      <ul id="system" class="nav-content <?php echo ($group === "system" ? "show" : "collapse") ?> ">
        <li>
          <a class="nav-link <?php echo ($page === "items" ? "active" : "collapsed") ?>" href="/items">
            <i class="fa fa-circle"></i> <span>ข้อมูลอุปกรณ์</span>
          </a>
        </li>
        <li>
          <a class="nav-link <?php echo ($page === "users" ? "active" : "collapsed") ?>" href="/users">
            <i class="fa fa-circle"></i> <span>ข้อมูลผู้ใช้งาน</span>
          </a>
        </li>
        <li>
          <a class="nav-link <?php echo ($page === "system" ? "active" : "collapsed") ?>" href="/system">
            <i class="fa fa-circle"></i> <span>ข้อมูลระบบ</span>
          </a>
        </li>
      </ul>
    </li>
    <?php endif; ?>
  </ul>
</aside>