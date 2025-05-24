<?php
require_once 'db.php';


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
        session_start();
        $_SESSION['loggedin'] = $username;
        header("Location: ../index.php");
    } else {
        setMessage('نام کاربری یا کلمه عبور اشتباه است.');
        header("Location: ../login.php");
    }
}

// do logout
if (isset($_GET['logout'])) {
    session_start();
    unset($_SESSION['loggedin']);
    header("Loaction: login.php");
}
// set message
function setMessage($message)
{
    session_start();
    $_SESSION['message'] = $message;
}

// show message
function showMessage()
{
    session_start();
    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-warning m-3'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }
}

// check login
function checkLogin()
{
    session_start();
    if (!isset($_SESSION['loggedin'])) {
        header("Location: login.php");
    }
}
