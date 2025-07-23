<?php
require_once 'sections/header.php';
checkAdmin();

// اعلان‌ها
if (isset($_SESSION['admin_message'])) {
    $alertType = $_SESSION['admin_message_type'] ?? 'info';
    $alertMsg = $_SESSION['admin_message'];
    unset($_SESSION['admin_message'], $_SESSION['admin_message_type']);
}

// آمار کلی
$totalUsers = getTotalUsers();
$totalNotes = getTotalNotes();
$completedNotes = getCompletedNotes();
$overdueTasks = getOverdueTasksCount();
$todayTasks = getTodayTasksCount();
$highPriority = getHighPriorityTasksCount();

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
                <a class="profile"><i class="fas fa-chart-bar"></i>گزارش‌ها</a>
                <div class="titles">
                    <h1 class="title">گزارش‌های آماری</h1>
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
                <div class="col-lg-6 mb-4">
                    <div class="box shadow-md border-0">
                        <h2 class="mb-3"><i class="fas fa-chart-pie text-primary"></i> آمار کلی</h2>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-users text-primary"></i> کل کاربران</span>
                                <span class="fw-bold text-primary"><?php echo $totalUsers; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-sticky-note text-success"></i> کل یادداشت‌ها</span>
                                <span class="fw-bold text-success"><?php echo $totalNotes; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-check-circle text-warning"></i> یادداشت‌های تکمیل شده</span>
                                <span class="fw-bold text-warning"><?php echo $completedNotes; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-exclamation-triangle text-danger"></i> کارهای تأخیر افتاده</span>
                                <span class="fw-bold text-danger"><?php echo $overdueTasks; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-calendar-day text-info"></i> کارهای امروز</span>
                                <span class="fw-bold text-info"><?php echo $todayTasks; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-star text-success"></i> کارهای مهم</span>
                                <span class="fw-bold text-success"><?php echo $highPriority; ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="box shadow-md border-0">
                        <h2 class="mb-3"><i class="fas fa-chart-line text-info"></i> نمودار آماری (نمونه)</h2>
                        <canvas id="statsChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="row mycards mx-auto">
                <div class="col-12">
                    <div class="box shadow-md border-0">
                        <h2 class="mb-3"><i class="fas fa-list text-secondary"></i> گزارش فعالیت کاربران (نمونه)</h2>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>نام کاربر</th>
                                        <th>تعداد یادداشت‌ها</th>
                                        <th>تعداد یادداشت‌های تکمیل شده</th>
                                        <th>تعداد کارهای تأخیر افتاده</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach (getAllUsers() as $user) {
                                        $total = getUserNotesCount($user['id']);
                                        $completed = getUserCompletedNotesCount($user['id']);
                                        $overdue = getUserOverdueTasksCount($user['id']);
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($user['display_name']) . "</td>";
                                        echo "<td>" . $total . "</td>";
                                        echo "<td>" . $completed . "</td>";
                                        echo "<td>" . $overdue . "</td>";
                                        echo "</tr>";
                                    }
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
<!-- نمودار نمونه با Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('statsChart').getContext('2d');
    var statsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['کاربران', 'یادداشت‌ها', 'تکمیل شده', 'تأخیر افتاده', 'امروز', 'مهم'],
            datasets: [{
                label: 'آمار کلی',
                data: [<?php echo $totalUsers; ?>, <?php echo $totalNotes; ?>, <?php echo $completedNotes; ?>, <?php echo $overdueTasks; ?>, <?php echo $todayTasks; ?>, <?php echo $highPriority; ?>],
                backgroundColor: [
                    '#667eea', '#20c997', '#fd7e14', '#dc3545', '#17a2b8', '#28a745'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
<?php require_once 'sections/footer.php'; ?> 