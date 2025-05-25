<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "jktn";

// Connect without specifying a database so we can run the CREATE DATABASE command
$conn = mysqli_connect($host, $username, $password);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create the database if it doesn't exist
$createDBQuery = "CREATE DATABASE IF NOT EXISTS $database";
if (!mysqli_query($conn, $createDBQuery)) {
    die("Database creation failed: " . mysqli_error($conn));
}

// Now select the database
mysqli_select_db($conn, $database);
?>