<?php
require_once 'sections/header.php';
checkAdmin();

// Get filter values from URL, with defaults
$filter_user = $_GET['user'] ?? '';
$filter_category = $_GET['category'] ?? '';
$filter_priority = $_GET['priority'] ?? '';
$filter_status = $_GET['status'] ?? '';
$filter_search = $_GET['search'] ?? '';

// Get lists for filter dropdowns
$users = getAllUsers();
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
                <a class="profile"><i class="fas fa-sticky-note"></i>مدیریت یادداشت‌ها</a>
                <div class="titles">
                    <h1 class="title">مدیریت و فیلتر یادداشت‌ها</h1>
                </div>
            </div>

            <?php if (isset($_SESSION['admin_message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['admin_message_type']; ?> alert-dismissible fade show mx-3 mt-3" role="alert">
                    <i class="fas fa-<?php echo $_SESSION['admin_message_type'] == 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
                    <?php echo $_SESSION['admin_message']; unset($_SESSION['admin_message'], $_SESSION['admin_message_type']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row mycards mx-auto">
                <div class="col-12">
                    <div class="box shadow-md border-0">
                        <h2 class="mb-3"><i class="fas fa-filter text-primary"></i> فیلتر و جستجو</h2>
                        <form action="admin-notes.php" method="GET" class="d-flex gap-2 align-items-center flex-wrap p-3 bg-light rounded">
                            <select name="user" class="form-select form-select-sm">
                                <option value="">همه کاربران</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?php echo $user['id']; ?>" <?php if ($filter_user == $user['id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($user['display_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <select name="category" class="form-select form-select-sm">
                                <option value="">همه دسته‌بندی‌ها</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php if ($filter_category == $cat['id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <select name="priority" class="form-select form-select-sm">
                                <option value="">همه اولویت‌ها</option>
                                <option value="high" <?php if ($filter_priority == 'high') echo 'selected'; ?>>بالا</option>
                                <option value="medium" <?php if ($filter_priority == 'medium') echo 'selected'; ?>>متوسط</option>
                                <option value="low" <?php if ($filter_priority == 'low') echo 'selected'; ?>>پایین</option>
                            </select>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">همه وضعیت‌ها</option>
                                <option value="done" <?php if ($filter_status == 'done') echo 'selected'; ?>>تکمیل شده</option>
                                <option value="pending" <?php if ($filter_status == 'pending') echo 'selected'; ?>>در انتظار</option>
                            </select>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="جستجو در متن..." value="<?php echo htmlspecialchars($filter_search); ?>">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> اعمال</button>
                            <a href="admin-notes.php" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i> پاک کردن</a>
                        </form>
                    </div>
                </div>

                <div class="col-12">
                    <div class="box shadow-md border-0 mt-3">
                        <h2 class="mb-3"><i class="fas fa-list-ul text-success"></i> لیست یادداشت‌ها</h2>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="notesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>متن یادداشت</th>
                                        <th>کاربر</th>
                                        <th>دسته‌بندی</th>
                                        <th>تاریخ ایجاد</th>
                                        <th>اولویت</th>
                                        <th>وضعیت</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $notes = getFilteredNotesAdmin($filter_user, $filter_category, $filter_priority, $filter_status, $filter_search);
                                    if (empty($notes)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted p-4">
                                                <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                                هیچ یادداشتی با این مشخصات یافت نشد.
                                            </td>
                                        </tr>
                                    <?php else:
                                        foreach ($notes as $note) {
                                            $priorityClass = $note['priority'] == 'high' ? 'badge bg-danger' : ($note['priority'] == 'medium' ? 'badge bg-warning text-dark' : 'badge bg-success');
                                            $statusClass = $note['is_done'] ? 'badge bg-success' : 'badge bg-secondary';
                                            $statusText = $note['is_done'] ? 'تکمیل شده' : 'در انتظار';
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars(mb_substr($note['note_text'], 0, 60)) . (mb_strlen($note['note_text']) > 60 ? '...' : '') . "</td>";
                                            echo "<td>" . htmlspecialchars($note['display_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($note['category_name'] ?? '-') . "</td>";
                                            echo "<td>" . date('Y/m/d', strtotime($note['created_at'])) . "</td>";
                                            echo "<td><span class='$priorityClass'>" . ($note['priority'] == 'high' ? 'بالا' : ($note['priority'] == 'medium' ? 'متوسط' : 'پایین')) . "</span></td>";
                                            echo "<td><span class='$statusClass'>$statusText</span></td>";
                                            echo "<td>";
                                            echo "<button class='btn btn-sm btn-info me-1' onclick='viewNote(" . $note['id'] . ")' title='مشاهده'><i class='fas fa-eye'></i></button>";
                                            echo "<button class='btn btn-sm btn-warning me-1' onclick='editNote(" . $note['id'] . ")' title='ویرایش'><i class='fas fa-edit'></i></button>";
                                            echo "<button class='btn btn-sm btn-danger' onclick='confirmDeleteNote(" . $note['id'] . ")' title='حذف'><i class='fas fa-trash'></i></button>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    endif;
                                    ?>
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