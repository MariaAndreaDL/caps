<?php
// Database connection configuration
$host = 'localhost'; // Change if needed
$db   = 'drolah';
$user = 'root'; // Change if needed
$pass = ''; // Change if needed

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

