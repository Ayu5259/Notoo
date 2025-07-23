<?php
require_once 'sections/header.php';
checkAdmin();

// اعلان‌ها
if (isset($_SESSION['admin_message'])) {
    $alertType = $_SESSION['admin_message_type'] ?? 'info';
    $alertMsg = $_SESSION['admin_message'];
    unset($_SESSION['admin_message'], $_SESSION['admin_message_type']);
}

$userData = getUserData();
$categories = getAllCategories();
?>
<div class="container-fluid min-vh-100 d-flex flex-column">
    <div class="row flex-grow-1">
        <div class="col-lg-2 col-md-3 sidebar">
            <h2 class="logo">پنل مدیریت</h2>
            <div class="devider"></div>
            <?php require_once 'sections/admin-menu.php' ?>
        </div>
        <div class="col-lg-10 col-md-9 content g-0">
            <div class="bg">
                <a class="profile"><i class="fas fa-cog"></i>تنظیمات سیستم</a>
                <div class="titles">
                    <h1 class="title">تنظیمات سیستم</h1>
                </div>
            </div>

            <?php if (isset($alertMsg)): ?>
                <div class="alert alert-<?php echo $alertType; ?> alert-dismissible fade show mx-3 mt-3" role="alert">
                    <i class="fas fa-<?php echo $alertType == 'success' ? 'check-circle' : ($alertType == 'danger' ? 'exclamation-triangle' : 'info-circle'); ?>"></i>
                    <?php echo $alertMsg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row mycards mx-auto">
                <div class="col-lg-8 mx-auto">
                    <div class="box shadow-md border-0 mb-4">
                        <h2 class="mb-3"><i class="fas fa-info-circle text-primary"></i> اطلاعات کلی سایت</h2>
                        <form action="inc/functions.php" method="post">
                            <div class="row p-3">
                                <div class="col-4 mb-2"><input type="text" name="display-name" value="<?php echo $userData['display_name']; ?>" class="form-control" placeholder="نام شما"></div>
                                <div class="col-4 mb-2"><input type="text" name="title" value="<?php echo $userData['title']; ?>" class="form-control" placeholder="عنوان اصلی"></div>
                                <div class="col-4 mb-2"><input type="text" name="subtitle" value="<?php echo $userData['subtitle']; ?>" class="form-control" placeholder="عنوان فرعی"></div>
                            </div>
                            <input type="submit" name="do-update" class="btn btn-success ms-4" value="بروزرسانی">
                        </form>
                    </div>

                    <div class="box shadow-md border-0">
                        <h2 class="mb-3"><i class="fas fa-tags text-warning"></i> مدیریت دسته‌بندی‌ها</h2>
                        <form action="inc/functions.php" method="post" class="mb-4">
                            <div class="row align-items-center">
                                <div class="col-4 mb-2">
                                    <input type="text" name="category-name" class="form-control" placeholder="نام دسته‌بندی" required>
                                </div>
                                <div class="col-4 mb-2">
                                    <input type="color" name="category-color" class="form-control form-control-color" value="#293462">
                                </div>
                                <div class="col-4 mb-2">
                                    <button type="submit" name="add-category" class="btn btn-primary">افزودن دسته‌بندی</button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>نام دسته‌بندی</th>
                                        <th>رنگ</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $cat): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($cat['name']); ?></td>
                                            <td><span class="badge" style="background: <?php echo $cat['color']; ?>; color: #fff;">&nbsp;&nbsp;&nbsp;</span></td>
                                            <td>
                                                <form action="inc/functions.php" method="post" class="d-inline">
                                                    <input type="hidden" name="delete-category" value="<?php echo $cat['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('آیا مطمئن هستید؟')"><i class="fas fa-trash"></i> حذف</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once 'sections/footer.php'; ?> 