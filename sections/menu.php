<?php if (isset($_SESSION['loggedin'])) { ?>
    <ul id="menu">
        <li class="menu-item"><a href="index.php"><i class="fas fa-home"></i>داشبورد</a></li>
        <li class="menu-item"><a href="notes.php"><i class="fas fa-book"></i>یادداشت ها</a></li>
        <li class="menu-item"><a href="setting.php"><i class="fas fa-wrench"></i>تنظیمات</a></li>
        <li class="menu-item"><a href="?logout"><i class="fas fa-power-off"></i>خروج</a></li>
    </ul>
<?php } else {  ?>
    <ul id="menu">
        <li class="menu-item"><a href="login.php"><i class="fas fa-user"></i>ورود</a></li>
        <li class="menu-item"><a href="register.php"><i class="fas fa-plus"></i>ثبت نام</a></li>

    </ul>
<?php } ?>