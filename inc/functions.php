<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db.php';
session_start();

//echo "functions.php loaded";
//die();

// add new user
if (isset($_POST['do-register'])) {
    $displayName = $_POST['display-name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passConf = $_POST['pass-conf'];

    $check_username = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");

    if (mysqli_num_rows($check_username) > 0) {
        setMessage('کاربری با این نام کاربری قبلا ثبت نام کرده است...');
        header("Location: ../register.php");
    } else {
        if ($password != $passConf) {
            setMessage('رمز عبور و تکرار آن باهم برابر نیستند');
            header("Location: ../register.php");
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_query($db, "INSERT INTO users (display_name, username, password) VALUES ('$displayName', '$username', '$hashed_password')");

            if ($insert) {
                setMessage('ثبت نام با موفقیت انجام شد. هم اکنون وارد شوید');
                header("Location: ../login.php");
            } else {
                echo 'error';
            }
        }
    }
}

// check login
if (isset($_POST['do-login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $is_hashed = (bool) preg_match('/^\$2y\$/', $user['password']);

        if ($is_hashed) {
            // New system: Password is a hash
            if (password_verify($password, $user['password'])) {
                $_SESSION['loggedin'] = $username;
                header("Location: ../index.php");
            } else {
                setMessage('نام کاربری یا کلمه عبور اشتباه است.');
                header("Location: ../login.php");
            }
        } else {
            // Old system: Plaintext password
            if ($password === $user['password']) {
                // Password is correct, so let's hash it and update the database
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $userId = $user['id'];
                mysqli_query($db, "UPDATE users SET password='$hashed_password' WHERE id='$userId'");
                
                $_SESSION['loggedin'] = $username;
                header("Location: ../index.php");
            } else {
                setMessage('نام کاربری یا کلمه عبور اشتباه است.');
                header("Location: ../login.php");
            }
        }
    } else {
        setMessage('نام کاربری یا کلمه عبور اشتباه است.');
        header("Location: ../login.php");
    }
}

// do logout
if (isset($_GET['logout'])) {
    unset($_SESSION['loggedin']);
    header("Location: login.php");
}

// add note
if (isset($_POST['user-note'])) {
    $userNote = $_POST['user-note'];
    $categoryId = isset($_POST['category']) ? $_POST['category'] : null;
    $dueDate = isset($_POST['due_date']) ? $_POST['due_date'] : null;
    $reminderTime = isset($_POST['reminder_time']) ? $_POST['reminder_time'] : null;
    $priority = isset($_POST['priority']) ? $_POST['priority'] : 'medium';
    $estimatedTime = isset($_POST['estimated_time']) ? $_POST['estimated_time'] : null;
    $userId = getUserId();

    $addNote = mysqli_query($db, "INSERT INTO notes (
        note_text, 
        user_id, 
        category_id, 
        due_date, 
        reminder_time, 
        priority, 
        estimated_time
    ) VALUES (
        '$userNote', 
        '$userId', 
        " . ($categoryId ? "'$categoryId'" : "NULL") . ",
        " . ($dueDate ? "'$dueDate'" : "NULL") . ",
        " . ($reminderTime ? "'$reminderTime'" : "NULL") . ",
        '$priority',
        " . ($estimatedTime ? "'$estimatedTime'" : "NULL") . "
    )");

    if ($addNote) {
        header("Location: ../index.php");
    }
}

// set message
function setMessage($message)
{
    $_SESSION['message'] = $message;
}

// show message
function showMessage()
{
    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-warning m-3'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }
}

// check login
function checkLogin()
{
    if (!isset($_SESSION['loggedin'])) {
        header("Location: login.php");
        exit(); // CRITICAL: Stop script execution after redirect
    }
}

// get user notes
function getUserNotes($limit = false)
{
    global $db;
    $userId = getUserId();

    $query = "SELECT notes.*, categories.name as category_name, categories.color as category_color 
              FROM notes 
              LEFT JOIN categories ON notes.category_id = categories.id 
              WHERE notes.user_id='$userId' AND notes.is_done='0' 
              ORDER BY notes.due_date ASC, notes.priority DESC, notes.id DESC";

    if ($limit) {
        $query .= " LIMIT $limit";
    }

    $getNotes = mysqli_query($db, $query);

    $userNotes = [];
    while ($notes = mysqli_fetch_array($getNotes)) {
        $userNotes[] = $notes;
    }

    return $userNotes;
}

// get done notes
function getDoneNotes()
{
    global $db;
    $userId = getUserId();

    $getNotes = mysqli_query($db, "SELECT * FROM notes WHERE user_id='$userId' AND is_done='1' ORDER BY id DESC");

    $userNotes = [];
    while ($notes = mysqli_fetch_array($getNotes)) {
        $userNotes[] = $notes;
    }

    return $userNotes;
}

// get user id from username
function getUserId()
{
    // Ensure user is logged in before proceeding
    if (!isset($_SESSION['loggedin'])) {
        return null; // Return null if not logged in
    }

    global $db;
    $username = $_SESSION['loggedin'];
    $getUser = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");

    if ($getUser && mysqli_num_rows($getUser) > 0) {
        $userArray = mysqli_fetch_array($getUser);
        return $userArray['id'];
    }

    return null; // Return null if user not found
}

// get user display name
function getUserDisplayname()
{
    global $db;
    $username = $_SESSION['loggedin'];
    $getUser = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");
    $userArray = mysqli_fetch_array($getUser);
    return $userArray['display_name'];
}

// make note done
if (isset($_GET['done'])) {
    $noteId = $_GET['done'];
    $updateNote = mysqli_query($db, "UPDATE notes SET is_done='1' WHERE id='$noteId'");
    if ($updateNote) {
        header("Location: notes.php");
    }
}

// delete note
if (isset($_GET['delete'])) {
    $noteId = $_GET['delete'];
    $deleteNote = mysqli_query($db, "DELETE FROM notes WHERE id='$noteId'");
    if ($deleteNote) {
        header("Location: notes.php");
    }
}

// search
if (isset($_GET['search'])) {
    function getSearchResult()
    {
        global $db;
        $searchInput = $_GET['search'];
        $userId = getUserId();

        $search = mysqli_query($db, "SELECT * FROM notes WHERE note_text LIKE '%$searchInput%' AND user_id=$userId AND is_done=0");

        $searchResults = [];
        while ($result = mysqli_fetch_array($search)) {
            $searchResults[] = $result;
        }

        return $searchResults;
    }
}

// Get user data for setting page
function getUserData()
{
    global $db;
    $userId = getUserId();
    $getUsername = mysqli_query($db, "SELECT * FROM users WHERE id='$userId'");
    $userData = mysqli_fetch_array($getUsername);
    return $userData;
}

// update user data
if (isset($_POST['do-update'])) {
    $newDisplayName = $_POST['display-name'];
    $newTitle = $_POST['title'];
    $newSubTitle = $_POST['subtitle'];

    $userId = getUserId();
    $updateSetting = mysqli_query($db, "UPDATE users SET display_name='$newDisplayName', title='$newTitle', subtitle='$newSubTitle' WHERE id='$userId'");

    if ($updateSetting) {
        setMessage('اطلاعات با موفقیت بروزرسانی شد');
        header("Location: ../setting.php");
    }
}

// change user password
if (isset($_POST['change-password'])) {
    // Check if user is logged in
    if (!isset($_SESSION['loggedin'])) {
        setMessage('لطفاً ابتدا وارد شوید');
        header("Location: ../login.php");
        exit();
    }
    
    $currentPassword = trim($_POST['current-password']);
    $newPassword = trim($_POST['new-password']);
    $confirmPassword = trim($_POST['confirm-password']);
    
    $userId = getUserId();
    $username = $_SESSION['loggedin'];
    
    // Get user data to check current password
    $result = mysqli_query($db, "SELECT password FROM users WHERE id='$userId'");
    $user = mysqli_fetch_assoc($result);
    $currentHashedPassword = $user['password'];

    // Check if current password is correct
    if (!password_verify($currentPassword, $currentHashedPassword)) {
        setMessage('رمز عبور فعلی اشتباه است');
        header("Location: ../setting.php");
        exit();
    }
    
    // Check if new password and confirm password match
    if ($newPassword != $confirmPassword) {
        setMessage('رمز عبور جدید و تکرار آن باهم برابر نیستند');
        header("Location: ../setting.php");
        exit();
    }
    
    // Check if new password is not empty
    if (empty($newPassword)) {
        setMessage('رمز عبور جدید نمی‌تواند خالی باشد');
        header("Location: ../setting.php");
        exit();
    }
    
    // Check if new password is different from current password
    if ($newPassword === $currentPassword) {
        setMessage('رمز عبور جدید باید با رمز عبور فعلی متفاوت باشد');
        header("Location: ../setting.php");
        exit();
    }
    
    // Check password length (minimum 6 characters)
    if (strlen($newPassword) < 6) {
        setMessage('رمز عبور جدید باید حداقل 6 کاراکتر باشد');
        header("Location: ../setting.php");
        exit();
    }
    
    // Hash the new password and update it
    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $updatePassword = mysqli_query($db, "UPDATE users SET password='$newHashedPassword' WHERE id='$userId'");
    
    if ($updatePassword) {
        setMessage('رمز عبور با موفقیت تغییر یافت');
    } else {
        setMessage('خطا در تغییر رمز عبور');
    }
    header("Location: ../setting.php");
}

// Get all categories for current user
function getUserCategories()
{
    global $db;
    $userId = getUserId();

    $getCategories = mysqli_query($db, "SELECT * FROM categories WHERE user_id='$userId' ORDER BY name ASC");

    $categories = [];
    while ($category = mysqli_fetch_array($getCategories)) {
        $categories[] = $category;
    }

    return $categories;
}

// Add new category
if (isset($_POST['add-category'])) {
    $categoryName = trim($_POST['category-name']);
    $categoryColor = $_POST['category-color'];
    $userId = getUserId();

    // Debug log
    file_put_contents(__DIR__.'/cat_debug.log', 'userId: '.$userId.' | name: '.$categoryName.' | color: '.$categoryColor.PHP_EOL, FILE_APPEND);

    // Basic validation
    if (empty($categoryName)) {
        setMessage('نام دسته‌بندی نمی‌تواند خالی باشد.');
        header("Location: ../setting.php");
        exit();
    }

    $query = "INSERT INTO categories (name, color, user_id) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);

    if (!$stmt) {
        setMessage('خطا در آماده‌سازی کوئری: ' . mysqli_error($db));
        header("Location: ../setting.php");
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'ssi', $categoryName, $categoryColor, $userId);

    if (mysqli_stmt_execute($stmt)) {
        setMessage('دسته‌بندی با موفقیت اضافه شد.');
    } else {
        setMessage('خطا در افزودن دسته‌بندی: ' . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    header("Location: ../setting.php");
    exit();
}

// Delete category
if (isset($_GET['delete-category'])) {
    $categoryId = $_GET['delete-category'];
    $userId = getUserId();

    // First check if category belongs to user
    $checkCategory = mysqli_query($db, "SELECT * FROM categories WHERE id='$categoryId' AND user_id='$userId'");

    if (mysqli_num_rows($checkCategory) > 0) {
        $deleteCategory = mysqli_query($db, "DELETE FROM categories WHERE id='$categoryId'");
        if ($deleteCategory) {
            setMessage('دسته‌بندی با موفقیت حذف شد');
        }
    }
    header("Location: ../setting.php");
}

// Get upcoming tasks
function getUpcomingTasks($limit = 5)
{
    global $db;
    $userId = getUserId();

    $query = "SELECT notes.*, categories.name as category_name, categories.color as category_color 
              FROM notes 
              LEFT JOIN categories ON notes.category_id = categories.id 
              WHERE notes.user_id='$userId' 
              AND notes.is_done='0' 
              AND notes.due_date IS NOT NULL 
              AND notes.due_date > NOW()
              ORDER BY notes.due_date ASC 
              LIMIT $limit";

    $getTasks = mysqli_query($db, $query);

    $tasks = [];
    while ($task = mysqli_fetch_array($getTasks)) {
        $tasks[] = $task;
    }

    return $tasks;
}

// Get overdue tasks
function getOverdueTasks()
{
    global $db;
    $userId = getUserId();

    $query = "SELECT notes.*, categories.name as category_name, categories.color as category_color 
              FROM notes 
              LEFT JOIN categories ON notes.category_id = categories.id 
              WHERE notes.user_id='$userId' 
              AND notes.is_done='0' 
              AND notes.due_date IS NOT NULL 
              AND notes.due_date < NOW()
              ORDER BY notes.due_date ASC";

    $getTasks = mysqli_query($db, $query);

    $tasks = [];
    while ($task = mysqli_fetch_array($getTasks)) {
        $tasks[] = $task;
    }

    return $tasks;
}

// Get today's tasks
function getTodayTasks()
{
    global $db;
    $userId = getUserId();
    $today = date('Y-m-d');

    $query = "SELECT notes.*, categories.name as category_name, categories.color as category_color 
              FROM notes 
              LEFT JOIN categories ON notes.category_id = categories.id 
              WHERE notes.user_id='$userId' 
              AND notes.is_done='0' 
              AND DATE(notes.due_date) = '$today'
              ORDER BY notes.priority DESC, notes.due_date ASC";

    $getTasks = mysqli_query($db, $query);

    $tasks = [];
    while ($task = mysqli_fetch_array($getTasks)) {
        $tasks[] = $task;
    }

    return $tasks;
}

// Get tasks by date range
function getTasksByDateRange($startDate, $endDate)
{
    global $db;
    $userId = getUserId();

    $query = "SELECT notes.*, categories.name as category_name, categories.color as category_color 
              FROM notes 
              LEFT JOIN categories ON notes.category_id = categories.id 
              WHERE notes.user_id='$userId' 
              AND notes.is_done='0' 
              AND notes.due_date BETWEEN '$startDate' AND '$endDate'
              ORDER BY notes.due_date ASC, notes.priority DESC";

    $getTasks = mysqli_query($db, $query);

    $tasks = [];
    while ($task = mysqli_fetch_array($getTasks)) {
        $tasks[] = $task;
    }

    return $tasks;
}

// Admin Functions
// Check if user is admin
function checkAdmin()
{
    if (!isset($_SESSION['loggedin'])) {
        header("Location: login.php");
        exit();
    }
    
    global $db;
    $username = $_SESSION['loggedin'];
    $getUser = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");
    $userArray = mysqli_fetch_array($getUser);
    
    if (!$userArray['is_admin']) {
        header("Location: index.php");
        exit();
    }
}

// Get total users count
function getTotalUsers()
{
    global $db;
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM users");
    $row = mysqli_fetch_array($result);
    return $row['total'];
}

// Get total notes count
function getTotalNotes()
{
    global $db;
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM notes");
    $row = mysqli_fetch_array($result);
    return $row['total'];
}

// Get completed notes count
function getCompletedNotes()
{
    global $db;
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM notes WHERE is_done='1'");
    $row = mysqli_fetch_array($result);
    return $row['total'];
}

// Get total categories count
function getTotalCategories()
{
    global $db;
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM categories");
    $row = mysqli_fetch_array($result);
    return $row['total'];
}

// Get all users
function getAllUsers()
{
    global $db;
    $result = mysqli_query($db, "SELECT * FROM users ORDER BY created_at DESC");
    if ($result === false) {
        die('SQL Error in getAllUsers: ' . mysqli_error($db));
    }
    $users = [];
    while ($user = mysqli_fetch_array($result)) {
        $users[] = $user;
    }
    return $users;
}

// Get user notes count
function getUserNotesCount($userId)
{
    global $db;
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM notes WHERE user_id='$userId'");
    $row = mysqli_fetch_array($result);
    return $row['total'];
}

// Get recent notes
function getRecentNotes($limit = 5)
{
    global $db;
    $query = "SELECT notes.*, users.display_name 
              FROM notes 
              LEFT JOIN users ON notes.user_id = users.id 
              ORDER BY notes.created_at DESC 
              LIMIT $limit";
    $result = mysqli_query($db, $query);
    $notes = [];
    while ($note = mysqli_fetch_array($result)) {
        $notes[] = $note;
    }
    return $notes;
}

// Get today tasks for admin
function getTodayTasksAdmin()
{
    global $db;
    $today = date('Y-m-d');
    $query = "SELECT notes.*, users.display_name 
              FROM notes 
              LEFT JOIN users ON notes.user_id = users.id 
              WHERE DATE(notes.due_date) = '$today' 
              AND notes.is_done = '0'
              ORDER BY notes.priority DESC, notes.due_date ASC";
    $result = mysqli_query($db, $query);
    $tasks = [];
    while ($task = mysqli_fetch_array($result)) {
        $tasks[] = $task;
    }
    return $tasks;
}

// Add new user (admin function)
if (isset($_POST['do-add-user'])) {
    $displayName = $_POST['display-name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $userType = $_POST['user-type'];
    
    $check_username = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");
    
    if (mysqli_num_rows($check_username) > 0) {
        setAdminMessage('کاربری با این نام کاربری قبلا ثبت نام کرده است...', 'danger');
        header("Location: ../admin-users.php");
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert = mysqli_query($db, "INSERT INTO users (display_name, username, password, is_admin) VALUES ('$displayName', '$username', '$hashed_password', '$userType')");
        
        if ($insert) {
            setAdminMessage('کاربر با موفقیت اضافه شد', 'success');
            header("Location: ../admin-users.php");
        } else {
            setAdminMessage('خطا در افزودن کاربر', 'danger');
            header("Location: ../admin-users.php");
        }
    }
}

// Delete user (admin function)
if (isset($_GET['delete-user'])) {
    $userId = $_GET['delete-user'];
    
    // Don't allow admin to delete themselves
    if ($userId == getUserId()) {
        setAdminMessage('شما نمی‌توانید حساب کاربری خود را حذف کنید', 'danger');
        header("Location: ../admin.php");
        exit();
    }
    
    $deleteUser = mysqli_query($db, "DELETE FROM users WHERE id='$userId'");
    if ($deleteUser) {
        setAdminMessage('کاربر با موفقیت حذف شد', 'success');
    } else {
        setAdminMessage('خطا در حذف کاربر', 'danger');
    }
    header("Location: ../admin.php");
}

// Update user password (admin function)
if (isset($_POST['update-user-password'])) {
    $userId = $_POST['user-id'];
    $newPassword = $_POST['new-password'];
    
    if (empty($newPassword)) {
        setAdminMessage('رمز عبور نمی‌تواند خالی باشد', 'danger');
        header("Location: ../admin-users.php");
        exit();
    }
    
    $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
    $updatePassword = mysqli_query($db, "UPDATE users SET password='$hashed_password' WHERE id='$userId'");
    
    if ($updatePassword) {
        setAdminMessage('رمز عبور کاربر با موفقیت بروزرسانی شد', 'success');
    } else {
        setAdminMessage('خطا در بروزرسانی رمز عبور', 'danger');
    }
    header("Location: ../admin-users.php");
}

// Edit user (admin function)
if (isset($_POST['do-edit-user'])) {
    // Check if user is admin
    if (!isset($_SESSION['loggedin'])) {
        header("Location: ../login.php");
        exit();
    }
    
    global $db;
    $username = $_SESSION['loggedin'];
    $getUser = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");
    $userArray = mysqli_fetch_array($getUser);
    
    if (!$userArray['is_admin']) {
        setAdminMessage('شما دسترسی به این بخش را ندارید', 'danger');
        header("Location: ../index.php");
        exit();
    }

    $userId = $_POST['user-id'];
    $displayName = trim($_POST['display-name']);
    $username = trim($_POST['username']);
    $userType = $_POST['user-type'];
    
    // Check if username exists (excluding current user)
    $check_username = mysqli_query($db, "SELECT * FROM users WHERE username='$username' AND id != '$userId'");
    
    if (mysqli_num_rows($check_username) > 0) {
        setAdminMessage('این نام کاربری قبلاً استفاده شده است', 'danger');
        header("Location: ../admin-users.php");
        exit();
    }
    
    // Don't allow changing the last admin to regular user
    if ($userType == 0) {
        $adminCount = mysqli_query($db, "SELECT COUNT(*) as count FROM users WHERE is_admin='1' AND id != '$userId'");
        $adminCountRow = mysqli_fetch_array($adminCount);
        if ($adminCountRow['count'] == 0) {
            setAdminMessage('حداقل یک ادمین باید در سیستم باقی بماند', 'danger');
            header("Location: ../admin-users.php");
            exit();
        }
    }
    
    // Update user
    $updateUser = mysqli_query($db, "UPDATE users SET 
        display_name='$displayName', 
        username='$username', 
        is_admin='$userType' 
        WHERE id='$userId'");
    
    if ($updateUser) {
        setAdminMessage('اطلاعات کاربر با موفقیت بروزرسانی شد', 'success');
    } else {
        setAdminMessage('خطا در بروزرسانی اطلاعات کاربر', 'danger');
    }
    header("Location: ../admin-users.php");
    exit();
}

// Get user data for editing (admin function)
if (isset($_GET['get-user'])) {
    // Check if user is admin
    if (!isset($_SESSION['loggedin'])) {
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }
    
    global $db;
    $username = $_SESSION['loggedin'];
    $getUser = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");
    $userArray = mysqli_fetch_array($getUser);
    
    if (!$userArray['is_admin']) {
        echo json_encode(['error' => 'Unauthorized']);
        exit();
    }

    $userId = $_GET['get-user'];
    $result = mysqli_query($db, "SELECT id, display_name, username, is_admin FROM users WHERE id='$userId'");
    $user = mysqli_fetch_assoc($result);
    
    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
    exit();
}

// Get user statistics
function getUserStats($userId)
{
    global $db;
    
    $stats = [];
    
    // Total notes
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM notes WHERE user_id='$userId'");
    $row = mysqli_fetch_array($result);
    $stats['total_notes'] = $row['total'];
    
    // Completed notes
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM notes WHERE user_id='$userId' AND is_done='1'");
    $row = mysqli_fetch_array($result);
    $stats['completed_notes'] = $row['total'];
    
    // Pending notes
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM notes WHERE user_id='$userId' AND is_done='0'");
    $row = mysqli_fetch_array($result);
    $stats['pending_notes'] = $row['total'];
    
    // Overdue tasks
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM notes WHERE user_id='$userId' AND is_done='0' AND due_date < NOW()");
    $row = mysqli_fetch_array($result);
    $stats['overdue_tasks'] = $row['total'];
    
    return $stats;
}

// Get priority tasks
function getPriorityTasks($priority = 'high', $limit = 10)
{
    global $db;
    $userId = getUserId();
    
    $query = "SELECT notes.*, categories.name as category_name, categories.color as category_color 
              FROM notes 
              LEFT JOIN categories ON notes.category_id = categories.id 
              WHERE notes.user_id='$userId' 
              AND notes.is_done='0' 
              AND notes.priority='$priority'
              ORDER BY notes.due_date ASC 
              LIMIT $limit";
    
    $result = mysqli_query($db, $query);
    $tasks = [];
    while ($task = mysqli_fetch_array($result)) {
        $tasks[] = $task;
    }
    
    return $tasks;
}

// Get admin users count
function getAdminUsersCount()
{
    global $db;
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM users WHERE is_admin='1'");
    $row = mysqli_fetch_array($result);
    return $row['total'];
}

// Get today tasks count
function getTodayTasksCount()
{
    global $db;
    $today = date('Y-m-d');
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM notes WHERE due_date='$today' AND is_done='0'");
    $row = mysqli_fetch_array($result);
    return $row['total'];
}

// Get overdue tasks count
function getOverdueTasksCount()
{
    global $db;
    $today = date('Y-m-d');
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM notes WHERE due_date < '$today' AND is_done='0'");
    $row = mysqli_fetch_array($result);
    return $row['total'];
}

// Get upcoming tasks count
function getUpcomingTasksCount()
{
    global $db;
    $today = date('Y-m-d');
    $nextWeek = date('Y-m-d', strtotime('+7 days'));
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM notes WHERE due_date BETWEEN '$today' AND '$nextWeek' AND is_done='0'");
    $row = mysqli_fetch_array($result);
    return $row['total'];
}

// Get high priority tasks count
function getHighPriorityTasksCount()
{
    global $db;
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM notes WHERE priority='high' AND is_done='0'");
    $row = mysqli_fetch_array($result);
    return $row['total'];
}

// Set admin message
function setAdminMessage($message, $type = 'info')
{
    $_SESSION['admin_message'] = $message;
    $_SESSION['admin_message_type'] = $type;
}

// دریافت همه دسته‌بندی‌ها
function getAllCategories()
{
    global $db;
    $result = mysqli_query($db, "SELECT * FROM categories ORDER BY name ASC");
    $categories = [];
    while ($cat = mysqli_fetch_array($result)) {
        $categories[] = $cat;
    }
    return $categories;
}

// دریافت همه یادداشت‌ها برای ادمین (با نام کاربر و دسته‌بندی)
function getAllNotesAdmin()
{
    global $db;
    $query = "SELECT notes.*, users.display_name, categories.name as category_name, categories.id as category_id
              FROM notes
              LEFT JOIN users ON notes.user_id = users.id
              LEFT JOIN categories ON notes.category_id = categories.id
              ORDER BY notes.created_at DESC";
    $result = mysqli_query($db, $query);
    $notes = [];
    while ($note = mysqli_fetch_array($result)) {
        $notes[] = $note;
    }
    return $notes;
}

/**
 * Get filtered notes for the admin panel with prepared statements.
 *
 * @param string $userId
 * @param string $categoryId
 * @param string $priority
 * @param string $status
 * @param string $searchTerm
 * @return array
 */
function getFilteredNotesAdmin($userId = '', $categoryId = '', $priority = '', $status = '', $searchTerm = '') {
    global $db;

    $query = "SELECT notes.*, users.display_name, categories.name as category_name
              FROM notes
              LEFT JOIN users ON notes.user_id = users.id
              LEFT JOIN categories ON notes.category_id = categories.id
              WHERE 1=1";

    $params = [];
    $types = '';

    if (!empty($userId)) {
        $query .= " AND notes.user_id = ?";
        $params[] = $userId;
        $types .= 'i';
    }
    if (!empty($categoryId)) {
        $query .= " AND notes.category_id = ?";
        $params[] = $categoryId;
        $types .= 'i';
    }
    if (!empty($priority)) {
        $query .= " AND notes.priority = ?";
        $params[] = $priority;
        $types .= 's';
    }
    if (!empty($status)) {
        $is_done = ($status === 'done') ? 1 : 0;
        $query .= " AND notes.is_done = ?";
        $params[] = $is_done;
        $types .= 'i';
    }
    if (!empty($searchTerm)) {
        $query .= " AND notes.note_text LIKE ?";
        $params[] = "%" . $searchTerm . "%";
        $types .= 's';
    }

    $query .= " ORDER BY notes.created_at DESC";

    $stmt = mysqli_prepare($db, $query);

    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $notes = [];
    while ($note = mysqli_fetch_array($result)) {
        $notes[] = $note;
    }

    mysqli_stmt_close($stmt);
    return $notes;
}

// تعداد یادداشت‌های تکمیل شده یک کاربر
function getUserCompletedNotesCount($userId)
{
    global $db;
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM notes WHERE user_id='$userId' AND is_done='1'");
    $row = mysqli_fetch_array($result);
    return $row['total'];
}

// تعداد کارهای تأخیر افتاده یک کاربر
function getUserOverdueTasksCount($userId)
{
    global $db;
    $today = date('Y-m-d');
    $result = mysqli_query($db, "SELECT COUNT(*) as total FROM notes WHERE user_id='$userId' AND is_done='0' AND due_date < '$today'");
    $row = mysqli_fetch_array($result);
    return $row['total'];
}
?>
