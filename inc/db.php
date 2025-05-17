<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'notes';

$db = mysqli_connect($servername, $username, $password, $dbname);
// $db = mysqli_connect('localhost', 'root','', 'notes');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['do-register'])) {
    $displayName = $_POST['display_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passConf = $_POST['pass-conf'];

    echo $displayName . $username . $password . $passConf;
}
