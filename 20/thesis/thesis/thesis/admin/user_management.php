<?php
// Include database connection
include 'config.php';

// Fetch all users
$result = $conn->query("SELECT * FROM users ORDER BY user_type, full_name");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f27d9d;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .btn-add {
            background-color: #f27d9d;
            color: white;
            margin-bottom: 20px;
            display: block;
            width: 100%;
            text-align: center;
            padding: 10px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-add:hover {
            background-color: #f27d9d;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>User Management</h2>

    <!-- Add New User Button -->
    <a href="add_user.php" class="btn-add">Add New User</a>

    <!-- User List Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Username</th>
                <th>Full Name</th>
                <th>User Type</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td><?php echo ucfirst($user['user_type']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
