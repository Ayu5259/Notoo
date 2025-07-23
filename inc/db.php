<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'notess';

$db = mysqli_connect($servername, $username, $password, $dbname);
mysqli_query($db, 'SET NAMES utf8');

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// تابع بررسی وجود ستون در جدول
function columnExists($db, $table, $column, $dbname)
{
    $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = '$dbname' 
            AND TABLE_NAME = '$table' 
            AND COLUMN_NAME = '$column'";
    $result = mysqli_query($db, $sql);
    return mysqli_num_rows($result) > 0;
}

// حذف ستون priority_order از notes اگر وجود دارد
if (columnExists($db, 'notes', 'priority_order', $dbname)) {
    mysqli_query($db, "ALTER TABLE notes DROP COLUMN priority_order");
}

// افزودن ستون is_admin به users اگر وجود ندارد
if (!columnExists($db, 'users', 'is_admin', $dbname)) {
    mysqli_query($db, "ALTER TABLE users ADD COLUMN is_admin TINYINT(1) DEFAULT 0");
}

// افزودن ستون‌های زمان‌بندی به notes اگر وجود ندارند
$note_columns = [
    'due_date' => "DATE NULL",
    'reminder_time' => "DATETIME NULL",
    'priority' => "ENUM('low', 'medium', 'high') DEFAULT 'medium'",
    'estimated_time' => "INT NULL"
];

foreach ($note_columns as $column => $type) {
    if (!columnExists($db, 'notes', $column, $dbname)) {
        mysqli_query($db, "ALTER TABLE notes ADD COLUMN $column $type");
    }
}

// نکته: ستون category_id قبلاً در اسکریپت SQL شما ایجاد شده و foreign key هم اضافه شده.
// پس نیازی به بررسی یا افزودن مجدد نیست.
