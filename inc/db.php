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

// Create categories table if it doesn't exist
$create_categories_table = "CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    color VARCHAR(7) DEFAULT '#293462',
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

mysqli_query($db, $create_categories_table);

// Add category_id to notes table if it doesn't exist
$alter_notes_table = "ALTER TABLE notes ADD COLUMN IF NOT EXISTS category_id INT,
    ADD FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL";

mysqli_query($db, $alter_notes_table);

// Remove priority_order column
$remove_priority_order = "ALTER TABLE notes DROP COLUMN IF EXISTS priority_order";
mysqli_query($db, $remove_priority_order);
