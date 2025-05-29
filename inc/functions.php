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
    $userId = getUserId();

    // vars are Case sensitive
    $addNote = mysqli_query($db, "INSERT INTO notess (note_text, user_id) VALUES ('$userNote', '$userId')");
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
    if ($limit) { // if limit was not false
        $getNotes = mysqli_query($db, "SELECT * FROM notess WHERE user_id='$userId' AND is_done='0' ORDER BY id DESC LIMIT $limit");
    } else {
        $getNotes = mysqli_query($db, "SELECT * FROM notess WHERE user_id='$userId' AND is_done='0' ORDER BY id DESC");
    }

    $userNotes = [];
    while ($notes = mysqli_fetch_array($getNotes)) {
        $userNotes[] = $notes;
    }

    // return as an array
    return $userNotes;
}

// get done notes
function getDoneNotes()
{
    global $db;
    $userId = getUserId();

    $getNotes = mysqli_query($db, "SELECT * FROM notess WHERE user_id='$userId' AND is_done='1' ORDER BY id DESC");

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
    $updateNote = mysqli_query($db, "UPDATE notess SET is_done='1' WHERE id='$noteId'");
    if ($updateNote) {
        header("Location: notes.php");
    }
}

// delete note
if (isset($_GET['delete'])) {
    $noteId = $_GET['delete'];
    $deleteNote = mysqli_query($db, "DELETE FROM notess WHERE id='$noteId'");
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
