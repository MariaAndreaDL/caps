<?php
include 'config.php';
include 'sidebar.php';
include 'styles.css';
$output = shell_exec("python3 sales_analytics.py");
echo "<pre>$output</pre>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure this path is correct -->
</head>
