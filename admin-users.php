<?php
require_once 'sections/header.php';
checkAdmin();

// آمار کاربران
$totalUsers = getTotalUsers();
$adminUsers = getAdminUsersCount();
$regularUsers = $totalUsers - $adminUsers;

// اعلان‌ها
if (isset($_SESSION['admin_message'])) {
    $alertType = $_SESSION['admin_message_type'] ?? 'info';
    $alertMsg = $_SESSION['admin_message'];
    unset($_SESSION['admin_message'], $_SESSION['admin_message_type']);
}
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
                <a class="profile"><i class="fas fa-users-cog"></i>مدیریت کاربران</a>
                <div class="titles">
                    <h1 class="title">مدیریت کاربران</h1>
                    <h2 class="title">کل کاربران: <?php echo $totalUsers; ?> | ادمین: <?php echo $adminUsers; ?> | عادی: <?php echo $regularUsers; ?></h2>
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
                <div class="col-12">
                    <div class="box shadow-md border-0">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
                            <h2 class="mb-0"><i class="fas fa-users text-success"></i> لیست کاربران</h2>
                            <div class="d-flex gap-2 align-items-center">
                                <select id="userTypeFilter" class="form-select form-select-sm w-auto">
                                    <option value="">همه کاربران</option>
                                    <option value="admin">فقط ادمین‌ها</option>
                                    <option value="user">فقط کاربران عادی</option>
                                </select>
                                <input type="text" id="userSearch" class="form-control form-control-sm w-auto" placeholder="جستجو کاربر...">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fas fa-plus"></i> افزودن کاربر
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="usersTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>نام نمایشی</th>
                                        <th>نام کاربری</th>
                                        <th>نوع کاربر</th>
                                        <th>تاریخ ثبت‌نام</th>
                                        <th>آخرین ورود</th>
                                        <th>تعداد یادداشت‌ها</th>
                                        <th>وضعیت</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $users = getAllUsers();
                                    foreach ($users as $user) {
                                        $userType = $user['is_admin'] ? 'ادمین' : 'کاربر عادی';
                                        $userTypeClass = $user['is_admin'] ? 'badge bg-danger' : 'badge bg-primary';
                                        $rowClass = $user['is_admin'] ? 'table-warning' : '';
                                        $notesCount = getUserNotesCount($user['id']);
                                        $statusClass = $notesCount > 10 ? 'text-success' : ($notesCount > 5 ? 'text-warning' : 'text-muted');
                                        $statusText = $notesCount > 10 ? 'فعال' : ($notesCount > 5 ? 'متوسط' : 'کم‌فعال');
                                        echo "<tr class='$rowClass' data-user-type='" . ($user['is_admin'] ? 'admin' : 'user') . "'>";
                                        echo "<td><strong>" . $user['display_name'] . "</strong></td>";
                                        echo "<td>" . $user['username'] . "</td>";
                                        echo "<td><span class='$userTypeClass'>$userType</span></td>";
                                        echo "<td>" . date('Y/m/d', strtotime($user['created_at'])) . "</td>";
                                        echo "<td>" . (isset($user['last_login']) ? date('Y/m/d H:i', strtotime($user['last_login'])) : 'هرگز') . "</td>";
                                        echo "<td><span class='$statusClass'>$notesCount</span></td>";
                                        echo "<td><span class='badge bg-light text-dark'>$statusText</span></td>";
                                        echo "<td>";
                                        echo "<button class='btn btn-sm btn-info me-1' onclick='openChangePasswordModal(" . $user['id'] . ")' title='تغییر رمز عبور'><i class='fas fa-key'></i></button>";
                                        if ($user['id'] != getUserId()) {
                                            echo "<button class='btn btn-sm btn-danger' onclick='confirmDeleteUser(" . $user['id'] . ", \"" . $user['display_name'] . "\")' title='حذف'><i class='fas fa-trash'></i></button>";
                                        }
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal افزودن کاربر -->
            <div class="modal fade" id="addUserModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">افزودن کاربر جدید</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="inc/functions.php" method="POST">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">نام نمایشی</label>
                                    <input type="text" name="display-name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">نام کاربری</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">رمز عبور</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">نوع کاربر</label>
                                    <select name="user-type" class="form-control">
                                        <option value="0">کاربر عادی</option>
                                        <option value="1">ادمین</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                                <button type="submit" name="do-add-user" class="btn btn-primary">افزودن کاربر</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal ویرایش کاربر -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش اطلاعات کاربر</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="inc/functions.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="user-id" id="editUserId">
                        <div class="mb-3">
                            <label class="form-label">نام نمایشی</label>
                            <input type="text" name="display-name" id="editDisplayName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نام کاربری</label>
                            <input type="text" name="username" id="editUsername" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نوع کاربر</label>
                            <select name="user-type" id="editUserType" class="form-control">
                                <option value="0">کاربر عادی</option>
                                <option value="1">ادمین</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                        <button type="submit" name="do-edit-user" class="btn btn-primary">ذخیره تغییرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php require_once 'sections/footer.php'; ?> 