<?php
require_once 'db.php';
session_start();


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
            $insert = mysqli_query($db, "INSERT INTO users (display_name, username, password) VALUES ('$displayName', '$username', '$password')");

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

    $checkUser = mysqli_query($db, "SELECT * FROM users WHERE username='$username' AND password='$password'");

    if (mysqli_num_rows($checkUser) > 0) {
        // session_start();
        $_SESSION['loggedin'] = $username;
        header("Location: ../index.php");
    } else {
        setMessage('نام کاربری یا کلمه عبور اشتباه است.');
        header("Location: ../login.php");
    }
}

// do logout
if (isset($_GET['logout'])) {
    // session_start();
    unset($_SESSION['loggedin']);
    header("Loaction: login.php");
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
    // session_start();
    $_SESSION['message'] = $message;
}

// show message
function showMessage()
{
    // session_start();
    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-warning m-3'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }
}


// check login
function checkLogin()
{
    // session_start();
    if (!isset($_SESSION['loggedin'])) {
        header("Location: login.php");
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
              ORDER BY notes.id DESC";

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
    global $db;

    // session_start();
    $username = $_SESSION['loggedin'];

    $getUser = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");
    $userArray = mysqli_fetch_array($getUser);
    return $userArray['id'];
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
    // echo $_GET['done'];
    $noteId = $_GET['done']; // note id
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

// serach
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
    $categoryName = $_POST['category-name'];
    $categoryColor = $_POST['category-color'];
    $userId = getUserId();

    $addCategory = mysqli_query($db, "INSERT INTO categories (name, color, user_id) VALUES ('$categoryName', '$categoryColor', '$userId')");

    if ($addCategory) {
        setMessage('دسته‌بندی با موفقیت اضافه شد');
        header("Location: ../setting.php");
    }
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
