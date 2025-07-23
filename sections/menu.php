<?php if (isset($_SESSION['loggedin'])) { 
    // Check if user is admin
    $username = $_SESSION['loggedin'];
    $getUser = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");
    $userArray = mysqli_fetch_array($getUser);
    $isAdmin = $userArray['is_admin'];
?>
    <ul id="menu">
        <li class="menu-item"><a href="index.php"><i class="fas fa-home"></i>داشبورد</a></li>
        <li class="menu-item"><a href="notes.php"><i class="fas fa-book"></i>یادداشت ها</a></li>
        <li class="menu-item"><a href="calendar.php"><i class="fas fa-calendar-alt"></i>تقویم کارها</a></li>
        <li class="menu-item"><a href="setting.php"><i class="fas fa-wrench"></i>تنظیمات</a></li>
        <?php if ($isAdmin) { ?>
            <li class="menu-item"><a href="admin.php"><i class="fas fa-user-shield"></i>پنل مدیریت</a></li>
        <?php } ?>
        <li class="menu-item"><a href="?logout"><i class="fas fa-power-off"></i>خروج</a></li>
    </ul>
<?php } else {  ?>
    <ul id="menu">
        <li class="menu-item"><a href="landing.php"><i class="fas fa-home"></i>صفحه اصلی</a></li>
        <li class="menu-item"><a href="login.php"><i class="fas fa-user"></i>ورود</a></li>
        <li class="menu-item"><a href="register.php"><i class="fas fa-plus"></i>ثبت نام</a></li>
    </ul>
<?php } ?>