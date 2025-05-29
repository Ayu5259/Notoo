<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'notess';

$db = mysqli_connect($servername, $username, $password, $dbname);
mysqli_query($db, 'SET NAME utf8');
// $db = mysqli_connect('localhost', 'root','', 'notess');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
