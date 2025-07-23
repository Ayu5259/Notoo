<?php 
require_once 'sections/header.php';
checkAdmin();
$userData = getUserData(); 

// Get statistics
$totalUsers = getTotalUsers();
$totalNotes = getTotalNotes();
$completedNotes = getCompletedNotes();
$totalCategories = getTotalCategories();
$pendingNotes = $totalNotes - $completedNotes;
$completionRate = $totalNotes > 0 ? round(($completedNotes / $totalNotes) * 100, 1) : 0;
$adminUsers = getAdminUsersCount();
$regularUsers = $totalUsers - $adminUsers;
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
                <a class="profile"><i class="fas fa-user-shield"></i>مدیر سیستم</a>
                <div class="titles">
                    <h1 class="title">پنل مدیریت</h1>
                    <h2 class="title">خوش آمدید <?php echo getUserDisplayname(); ?></h2>
                </div>
            </div>

            <!-- اعلان‌های سیستم -->
            <?php if (isset($_SESSION['admin_message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['admin_message_type'] ?? 'info'; ?> alert-dismissible fade show mx-3 mt-3" role="alert">
                    <i class="fas fa-<?php echo $_SESSION['admin_message_type'] == 'success' ? 'check-circle' : ($_SESSION['admin_message_type'] == 'danger' ? 'exclamation-triangle' : 'info-circle'); ?>"></i>
                    <?php echo $_SESSION['admin_message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['admin_message'], $_SESSION['admin_message_type']); ?>
            <?php endif; ?>

            <!-- آمار کلی -->
            <div class="row mycards mx-auto">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="box stats-card shadow-md border-0 position-relative overflow-hidden" style="border-top: 5px solid #667eea;">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-primary shadow-lg me-2">
                                <i class="fas fa-users text-white fa-2x"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0 fw-bold text-primary"><?php echo $totalUsers; ?></h3>
                                <p class="text-muted mb-0">کل کاربران</p>
                                <small class="text-muted"><?php echo $adminUsers; ?> ادمین، <?php echo $regularUsers; ?> کاربر عادی</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="box stats-card shadow-md border-0 position-relative overflow-hidden" style="border-top: 5px solid #20c997;">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-success shadow-lg me-2">
                                <i class="fas fa-sticky-note text-white fa-2x"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0 fw-bold text-success"><?php echo $totalNotes; ?></h3>
                                <p class="text-muted mb-0">کل یادداشت‌ها</p>
                                <small class="text-muted"><?php echo $pendingNotes; ?> در انتظار، <?php echo $completedNotes; ?> تکمیل شده</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="box stats-card shadow-md border-0 position-relative overflow-hidden" style="border-top: 5px solid #fd7e14;">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-warning shadow-lg me-2">
                                <i class="fas fa-check-circle text-white fa-2x"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0 fw-bold text-warning"><?php echo $completionRate; ?>%</h3>
                                <p class="text-muted mb-0">نرخ تکمیل</p>
                                <small class="text-muted"><?php echo $completedNotes; ?> از <?php echo $totalNotes; ?> یادداشت</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="box stats-card shadow-md border-0 position-relative overflow-hidden" style="border-top: 5px solid #764ba2;">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon bg-info shadow-lg me-2">
                                <i class="fas fa-tags text-white fa-2x"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="mb-0 fw-bold text-info"><?php echo $totalCategories; ?></h3>
                                <p class="text-muted mb-0">کل دسته‌بندی‌ها</p>
                                <small class="text-muted">دسته‌بندی‌های فعال</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- گزارش‌های سریع -->
            <div class="row mycards mx-auto">
                <div class="col-12">
                    <div class="box shadow-md border-0">
                        <h2 class="mb-3"><i class="fas fa-chart-line text-primary"></i> گزارش‌های سریع</h2>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="quick-report-card">
                                    <i class="fas fa-calendar-day text-primary fa-2x mb-2"></i>
                                    <h4 class="mb-1"><?php echo getTodayTasksCount(); ?></h4>
                                    <p class="text-muted mb-0">کارهای امروز</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="quick-report-card">
                                    <i class="fas fa-exclamation-triangle text-warning fa-2x mb-2"></i>
                                    <h4 class="mb-1"><?php echo getOverdueTasksCount(); ?></h4>
                                    <p class="text-muted mb-0">کارهای تأخیر افتاده</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="quick-report-card">
                                    <i class="fas fa-clock text-info fa-2x mb-2"></i>
                                    <h4 class="mb-1"><?php echo getUpcomingTasksCount(); ?></h4>
                                    <p class="text-muted mb-0">کارهای آینده</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="quick-report-card">
                                    <i class="fas fa-star text-success fa-2x mb-2"></i>
                                    <h4 class="mb-1"><?php echo getHighPriorityTasksCount(); ?></h4>
                                    <p class="text-muted mb-0">کارهای مهم</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- مدیریت کاربران -->
            <div class="row mycards mx-auto">
                <div class="col-12">
                    <div class="box shadow-md border-0">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
                            <h2 class="mb-0"><i class="fas fa-users text-success"></i> مدیریت کاربران</h2>
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
                                        echo "<button class='btn btn-sm btn-warning me-1' onclick='editUser(" . $user['id'] . ")' title='ویرایش'>";
                                        echo "<i class='fas fa-edit'></i>";
                                        echo "</button>";
                                        if ($user['id'] != getUserId()) {
                                            echo "<button class='btn btn-sm btn-danger' onclick='confirmDeleteUser(" . $user['id'] . ", \"" . $user['display_name'] . "\")' title='حذف'>";
                                            echo "<i class='fas fa-trash'></i>";
                                            echo "</button>";
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

            <!-- آمار روزانه -->
            <div class="row mycards mx-auto">
                <div class="col-lg-6">
                    <div class="box shadow-md">
                        <h2><i class="fas fa-chart-line"></i>یادداشت‌های اخیر</h2>
                        <div class="list-group">
                            <?php
                            $recentNotes = getRecentNotes(5);
                            foreach ($recentNotes as $note) {
                                echo "<div class='list-group-item'>";
                                echo "<div class='d-flex justify-content-between align-items-center'>";
                                echo "<div>";
                                echo "<h6 class='mb-1'>" . substr($note['note_text'], 0, 50) . "...</h6>";
                                echo "<small class='text-muted'>" . $note['display_name'] . "</small>";
                                echo "</div>";
                                echo "<small class='text-muted'>" . date('Y/m/d', strtotime($note['created_at'])) . "</small>";
                                echo "</div>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="box shadow-md">
                        <h2><i class="fas fa-calendar"></i>کارهای امروز</h2>
                        <div class="list-group">
                            <?php
                            $todayTasks = getTodayTasks();
                            if (empty($todayTasks)) {
                                echo "<div class='list-group-item text-center text-muted'>هیچ کاری برای امروز برنامه‌ریزی نشده است</div>";
                            } else {
                                foreach ($todayTasks as $task) {
                                    $priorityClass = $task['priority'] == 'high' ? 'text-danger' : ($task['priority'] == 'medium' ? 'text-warning' : 'text-success');
                                    echo "<div class='list-group-item'>";
                                    echo "<div class='d-flex justify-content-between align-items-center'>";
                                    echo "<div>";
                                    echo "<h6 class='mb-1'>" . $task['note_text'] . "</h6>";
                                    echo "<small class='text-muted'>" . $task['display_name'] . "</small>";
                                    echo "</div>";
                                    echo "<span class='$priorityClass'>" . ucfirst($task['priority']) . "</span>";
                                    echo "</div>";
                                    echo "</div>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
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

<style>
/* Admin Panel Specific Styles */
.stats-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--border-color) 0%, var(--border-color) 100%);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: all 0.3s ease;
}

.stats-card:hover .stats-icon {
    transform: scale(1.1);
}

/* Quick Reports Section */
.quick-report-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid #dee2e6;
}

.quick-report-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
}

.quick-report-card i {
    transition: all 0.3s ease;
}

.quick-report-card:hover i {
    transform: scale(1.2);
}

/* Table Improvements */
.table th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #495057;
    padding: 15px 12px;
}

.table td {
    padding: 12px;
    vertical-align: middle;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

/* Filter and Search */
.form-select-sm, .form-control-sm {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-select-sm:focus, .form-control-sm:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Alert Improvements */
.alert {
    border-radius: 10px;
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    color: #0c5460;
}

/* Button Improvements */
.btn-sm {
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.btn-sm:hover {
    transform: translateY(-1px);
}

/* Badge Improvements */
.badge {
    font-size: 0.75rem;
    padding: 6px 10px;
    border-radius: 20px;
    font-weight: 500;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stats-card {
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .stats-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .quick-report-card {
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .table td, .table th {
        padding: 8px 6px;
    }
    
    .btn-sm {
        padding: 4px 8px;
        font-size: 0.8rem;
    }
    
    .d-flex.flex-column.flex-md-row {
        gap: 10px !important;
    }
}

@media (max-width: 576px) {
    .stats-card {
        padding: 12px;
    }
    
    .stats-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .quick-report-card {
        padding: 12px;
    }
    
    .quick-report-card i {
        font-size: 1.5rem !important;
    }
    
    .table-responsive {
        font-size: 0.8rem;
    }
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.slide-in {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from { transform: translateX(-100%); }
    to { transform: translateX(0); }
}

/* Custom Scrollbar for Admin Panel */
.content::-webkit-scrollbar {
    width: 8px;
}

.content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.content::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 10px;
}

.content::-webkit-scrollbar-thumb:hover {
    background: #5a6fd8;
}
</style>

<script>
function editUser(userId) {
    // اینجا می‌توانید کد ویرایش کاربر را اضافه کنید
    alert('ویرایش کاربر با ID: ' + userId);
}

function deleteUser(userId) {
    if (confirm('آیا از حذف این کاربر اطمینان دارید؟')) {
        window.location.href = 'inc/functions.php?delete-user=' + userId;
    }
}
</script>

<?php require_once 'sections/footer.php'; ?> 