<?php require_once 'sections/header.php';
checkLogin();
$userData = getUserData(); ?>
<div class="container-fluid min-vh-100 d-flex flex-column">
    <div class="row flex-grow-1">
        <div class="col-lg-2 col-md-3 sidebar">
            <h2 class="logo">یادداشت ها</h2>
            <div class="devider"></div>
            <div class="searchbox">
                <?php require_once 'sections/search.php' ?>
            </div>
            <?php require_once 'sections/menu.php' ?>

            <div class="upgrade">
                <a href="#" class=""><i class="fas fa-trophy"></i>خرید نسخه کامل</a>
            </div>
        </div>
        <div class="col-lg-10 col-md-9 content g-0">
            <div class="bg">
                <a class="profile"><i class="fas fa-user"></i>مشاهده پروفایل</a>
                <div class="titles">
                    <h1 class="title"><?php echo $userData['title'] ?> <?php echo getUserDisplayname(); ?></h1>
                    <h2 class="title"><?php echo $userData['subtitle'] ?></h2>
                </div>
            </div>

            <!-- کارهای امروز -->
            <div class="row mycards mx-auto">
                <div class="col-12">
                    <div class="box shadow-md">
                        <h2><i class="fas fa-calendar-day"></i>کارهای امروز</h2>
                        <div class="row">
                            <?php
                            $todayTasks = getTodayTasks();
                            if (empty($todayTasks)) {
                                echo '<div class="col-12 text-center text-muted py-4">هیچ کاری برای امروز برنامه‌ریزی نشده است</div>';
                            } else {
                                foreach ($todayTasks as $task) {
                                    $priorityClass = $task['priority'] == 'high' ? 'border-danger' : ($task['priority'] == 'medium' ? 'border-warning' : 'border-success');
                                    $priorityText = $task['priority'] == 'high' ? 'اولویت بالا' : ($task['priority'] == 'medium' ? 'اولویت متوسط' : 'اولویت پایین');
                                    $priorityColor = $task['priority'] == 'high' ? 'text-danger' : ($task['priority'] == 'medium' ? 'text-warning' : 'text-success');
                                    
                                    echo '<div class="col-lg-4 col-md-6 mb-3">';
                                    echo '<div class="card h-100 ' . $priorityClass . ' border-2">';
                                    echo '<div class="card-body">';
                                    echo '<h6 class="card-title">' . $task['note_text'] . '</h6>';
                                    if ($task['category_name']) {
                                        echo '<span class="badge" style="background-color: ' . $task['category_color'] . '">' . $task['category_name'] . '</span>';
                                    }
                                    echo '<p class="card-text"><small class="' . $priorityColor . '">' . $priorityText . '</small></p>';
                                    if ($task['due_date']) {
                                        echo '<p class="card-text"><small class="text-muted">تاریخ: ' . date('Y/m/d', strtotime($task['due_date'])) . '</small></p>';
                                    }
                                    echo '</div>';
                                    echo '<div class="card-footer bg-transparent">';
                                    echo '<a href="inc/functions.php?done=' . $task['id'] . '" class="btn btn-sm btn-success">تکمیل شد</a>';
                                    echo '<a href="inc/functions.php?delete=' . $task['id'] . '" class="btn btn-sm btn-danger float-end" onclick="return confirm(\'آیا از حذف این کار اطمینان دارید؟\')">حذف</a>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- کارهای آینده -->
            <div class="row mycards mx-auto">
                <div class="col-lg-8">
                    <div class="box shadow-md">
                        <h2><i class="fas fa-clock"></i>کارهای آینده</h2>
                        <div class="list-group">
                            <?php
                            $upcomingTasks = getUpcomingTasks(5);
                            if (empty($upcomingTasks)) {
                                echo '<div class="list-group-item text-center text-muted">هیچ کاری برای آینده برنامه‌ریزی نشده است</div>';
                            } else {
                                foreach ($upcomingTasks as $task) {
                                    $priorityClass = $task['priority'] == 'high' ? 'text-danger' : ($task['priority'] == 'medium' ? 'text-warning' : 'text-success');
                                    echo '<div class="list-group-item">';
                                    echo '<div class="d-flex justify-content-between align-items-center">';
                                    echo '<div>';
                                    echo '<h6 class="mb-1">' . $task['note_text'] . '</h6>';
                                    if ($task['category_name']) {
                                        echo '<span class="badge me-2" style="background-color: ' . $task['category_color'] . '">' . $task['category_name'] . '</span>';
                                    }
                                    echo '<small class="' . $priorityClass . '">' . ucfirst($task['priority']) . '</small>';
                                    echo '</div>';
                                    echo '<div class="text-end">';
                                    echo '<small class="text-muted">' . date('Y/m/d', strtotime($task['due_date'])) . '</small>';
                                    echo '<div class="mt-1">';
                                    echo '<a href="inc/functions.php?done=' . $task['id'] . '" class="btn btn-sm btn-success">تکمیل</a>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="box quick-access shadow-md">
                        <h2><i class="fas fa-circle-plus"></i>افزودن کار جدید</h2>
                        <form action="inc/functions.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">دسته‌بندی</label>
                                <select name="category" class="form-control">
                                <option value="">بدون دسته‌بندی</option>
                                <?php
                                $categories = getUserCategories();
                                foreach ($categories as $category) {
                                    echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                                }
                                ?>
                            </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">متن کار</label>
                                <textarea name="user-note" class="form-control" rows="3" placeholder="توضیح کار..." required></textarea>
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
                            <button type="submit" class="btn btn-primary w-100">افزودن کار</button>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<?php require_once 'sections/footer.php'; ?>