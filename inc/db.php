<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'notes';

$db = mysqli_connect($servername, $username, $password, $dbname);
mysqli_query($db, 'SET NAME utf8');
// $db = mysqli_connect('localhost', 'root','', 'notes');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
