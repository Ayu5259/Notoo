<?php 
require_once 'sections/header.php';
checkLogin();
$userData = getUserData(); 
?>
<div class="container-fluid min-vh-100 d-flex flex-column">
    <div class="row flex-grow-1">
        <div class="col-lg-2 col-md-3 sidebar">
            <h2 class="logo">تقویم کارها</h2>
            <div class="devider"></div>
            <div class="searchbox">
                <?php require_once 'sections/search.php' ?>
            </div>
            <?php require_once 'sections/menu.php' ?>
        </div>
        <div class="col-lg-10 col-md-9 content g-0">
            <div class="bg">
                <a class="profile"><i class="fas fa-calendar-alt"></i>تقویم کارها</a>
                <div class="titles">
                    <h1 class="title">تقویم کارها</h1>
                    <h2 class="title">مدیریت زمان‌بندی کارها</h2>
                </div>
            </div>

            <!-- فیلترهای تقویم -->
            <div class="row mycards mx-auto">
                <div class="col-12">
                    <div class="box shadow-md">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2><i class="fas fa-filter"></i>فیلترها</h2>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                                <i class="fas fa-plus"></i>افزودن کار جدید
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">دسته‌بندی</label>
                                <select id="categoryFilter" class="form-control">
                                    <option value="">همه دسته‌ها</option>
                                    <?php
                                    $categories = getUserCategories();
                                    foreach ($categories as $category) {
                                        echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">اولویت</label>
                                <select id="priorityFilter" class="form-control">
                                    <option value="">همه اولویت‌ها</option>
                                    <option value="high">بالا</option>
                                    <option value="medium">متوسط</option>
                                    <option value="low">پایین</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">از تاریخ</label>
                                <input type="date" id="startDate" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">تا تاریخ</label>
                                <input type="date" id="endDate" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- تقویم ماهانه -->
            <div class="row mycards mx-auto">
                <div class="col-12">
                    <div class="box shadow-md">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2><i class="fas fa-calendar"></i>نمایش ماهانه</h2>
                            <div>
                                <button class="btn btn-outline-primary" onclick="previousMonth()">
                                    <i class="fas fa-chevron-right"></i>ماه قبل
                                </button>
                                <span class="mx-3" id="currentMonth"></span>
                                <button class="btn btn-outline-primary" onclick="nextMonth()">
                                    ماه بعد<i class="fas fa-chevron-left"></i>
                                </button>
                            </div>
                        </div>
                        <div id="calendarContainer">
                            <!-- تقویم با JavaScript نمایش داده می‌شود -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- لیست کارها -->
            <div class="row mycards mx-auto">
                <div class="col-12">
                    <div class="box shadow-md">
                        <h2><i class="fas fa-list"></i>لیست کارها</h2>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>کار</th>
                                        <th>دسته‌بندی</th>
                                        <th>اولویت</th>
                                        <th>تاریخ انجام</th>
                                        <th>وضعیت</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="tasksTableBody">
                                    <?php
                                    $allTasks = getUserNotes();
                                    foreach ($allTasks as $task) {
                                        $priorityClass = $task['priority'] == 'high' ? 'text-danger' : ($task['priority'] == 'medium' ? 'text-warning' : 'text-success');
                                        $priorityText = $task['priority'] == 'high' ? 'بالا' : ($task['priority'] == 'medium' ? 'متوسط' : 'پایین');
                                        $statusClass = $task['due_date'] && $task['due_date'] < date('Y-m-d') ? 'text-danger' : 'text-success';
                                        $statusText = $task['due_date'] && $task['due_date'] < date('Y-m-d') ? 'تأخیر' : 'در حال انجام';
                                        
                                        echo "<tr>";
                                        echo "<td>" . $task['note_text'] . "</td>";
                                        echo "<td>";
                                        if ($task['category_name']) {
                                            echo '<span class="badge" style="background-color: ' . $task['category_color'] . '">' . $task['category_name'] . '</span>';
                                        } else {
                                            echo '<span class="text-muted">بدون دسته</span>';
                                        }
                                        echo "</td>";
                                        echo "<td><span class='$priorityClass'>$priorityText</span></td>";
                                        echo "<td>" . ($task['due_date'] ? date('Y/m/d', strtotime($task['due_date'])) : 'تعیین نشده') . "</td>";
                                        echo "<td><span class='$statusClass'>$statusText</span></td>";
                                        echo "<td>";
                                        echo "<a href='inc/functions.php?done=" . $task['id'] . "' class='btn btn-sm btn-success me-1'>تکمیل</a>";
                                        echo "<a href='inc/functions.php?delete=" . $task['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"آیا از حذف این کار اطمینان دارید؟\")'>حذف</a>";
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
        </div>
    </div>
</div>

<!-- Modal افزودن کار جدید -->
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">افزودن کار جدید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="inc/functions.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">متن کار</label>
                        <textarea name="user-note" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">دسته‌بندی</label>
                        <select name="category" class="form-control">
                            <option value="">بدون دسته‌بندی</option>
                            <?php
                            foreach ($categories as $category) {
                                echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاریخ انجام</label>
                        <input type="date" name="due_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">اولویت</label>
                        <select name="priority" class="form-control">
                            <option value="low">پایین</option>
                            <option value="medium" selected>متوسط</option>
                            <option value="high">بالا</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary">افزودن کار</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.calendar-day {
    min-height: 100px;
    border: 1px solid #dee2e6;
    padding: 5px;
    position: relative;
}

.calendar-day.today {
    background-color: #e3f2fd;
}

.calendar-day.has-tasks {
    background-color: #fff3cd;
}

.task-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    margin: 1px;
}

.task-dot.high { background-color: #dc3545; }
.task-dot.medium { background-color: #ffc107; }
.task-dot.low { background-color: #28a745; }

.calendar-header {
    background-color: #f8f9fa;
    font-weight: bold;
    text-align: center;
    padding: 10px;
    border: 1px solid #dee2e6;
}

.other-month {
    background-color: #f8f9fa;
    color: #6c757d;
}
</style>

<script>
let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

// نمایش تقویم
function renderCalendar() {
    const container = document.getElementById('calendarContainer');
    const monthNames = [
        'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
        'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
    ];
    
    document.getElementById('currentMonth').textContent = monthNames[currentMonth] + ' ' + currentYear;
    
    const firstDay = new Date(currentYear, currentMonth, 1);
    const lastDay = new Date(currentYear, currentMonth + 1, 0);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - firstDay.getDay());
    
    let calendarHTML = `
        <div class="row">
            <div class="col calendar-header">یکشنبه</div>
            <div class="col calendar-header">دوشنبه</div>
            <div class="col calendar-header">سه‌شنبه</div>
            <div class="col calendar-header">چهارشنبه</div>
            <div class="col calendar-header">پنج‌شنبه</div>
            <div class="col calendar-header">جمعه</div>
            <div class="col calendar-header">شنبه</div>
        </div>
    `;
    
    for (let week = 0; week < 6; week++) {
        calendarHTML += '<div class="row">';
        for (let day = 0; day < 7; day++) {
            const date = new Date(startDate);
            date.setDate(startDate.getDate() + (week * 7) + day);
            
            const isCurrentMonth = date.getMonth() === currentMonth;
            const isToday = date.toDateString() === new Date().toDateString();
            
            let dayClass = 'calendar-day';
            if (!isCurrentMonth) dayClass += ' other-month';
            if (isToday) dayClass += ' today';
            
            calendarHTML += `
                <div class="col ${dayClass}">
                    <div class="d-flex justify-content-between">
                        <span>${date.getDate()}</span>
                        <div class="task-dots"></div>
                    </div>
                </div>
            `;
        }
        calendarHTML += '</div>';
    }
    
    container.innerHTML = calendarHTML;
}

function previousMonth() {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    renderCalendar();
}

function nextMonth() {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    renderCalendar();
}

// فیلتر کردن کارها
function filterTasks() {
    const categoryFilter = document.getElementById('categoryFilter').value;
    const priorityFilter = document.getElementById('priorityFilter').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    // اینجا می‌توانید کد فیلتر کردن را اضافه کنید
    console.log('Filtering tasks...', { categoryFilter, priorityFilter, startDate, endDate });
}

// اضافه کردن event listeners
document.addEventListener('DOMContentLoaded', function() {
    renderCalendar();
    
    document.getElementById('categoryFilter').addEventListener('change', filterTasks);
    document.getElementById('priorityFilter').addEventListener('change', filterTasks);
    document.getElementById('startDate').addEventListener('change', filterTasks);
    document.getElementById('endDate').addEventListener('change', filterTasks);
});
</script>

<?php require_once 'sections/footer.php'; ?> 