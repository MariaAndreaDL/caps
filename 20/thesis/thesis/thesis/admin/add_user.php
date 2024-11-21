<?php
require 'config.php';

$errors = [];
$success_message = "";
$form_data = [
    'user_type' => '',
    'username' => '',
    'full_name' => '',
    'password' => '',
    'confirm_password' => ''
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_data = $_POST; // Retain form data

    $user_type = $_POST['user_type'];
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Split full_name into components
    $name_parts = explode(' ', $full_name);
    $first_name = $name_parts[0];
    $last_name = end($name_parts);
    $middle_initial = count($name_parts) > 2 ? $name_parts[1][0] . '.' : '';
    $formatted_full_name = "$first_name $middle_initial $last_name";

    // Validation
    if ($user_type !== 'admin' && $user_type !== 'doctor') {
        $errors[] = "User type must be either 'admin' or 'doctor'.";
    }

    if ($user_type === 'admin' && !preg_match('/^a/', $username)) {
        $errors[] = "Admin usernames must start with 'a'.";
    }

    if ($user_type === 'doctor' && !preg_match('/^d/', $username)) {
        $errors[] = "Doctor usernames must start with 'd'.";
    }

    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/', $password)) {
        $errors[] = "Password must be at least 8 characters long, contain uppercase, lowercase, a number, and a special character.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Password and Confirm Password do not match.";
    }

    if (empty($errors)) {
        // Insert into database
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("
            INSERT INTO users (user_type, username, full_name, password)
            VALUES (:user_type, :username, :full_name, :password)
        ");

        try {
            $stmt->execute([
                ':user_type' => $user_type,
                ':username' => $username,
                ':full_name' => $formatted_full_name,
                ':password' => $hashed_password
            ]);

            $success_message = "User added successfully.";
            $form_data = []; // Clear form data on success
        } catch (PDOException $e) {
            $errors[] = "Error adding user: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #ffffff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        select, input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add User</h1>
        <form action="add_user.php" method="POST">
            <label for="user_type">User Type:</label>
            <select name="user_type" id="user_type" required>
                <option value="admin" <?php echo $form_data['user_type'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="doctor" <?php echo $form_data['user_type'] === 'doctor' ? 'selected' : ''; ?>>Doctor</option>
            </select>

            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($form_data['username']); ?>" required>

            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($form_data['full_name']); ?>" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <button type="submit">Add User</button>
        </form>
    </div>

    <script>
        // Display SweetAlert messages
        <?php if (!empty($errors)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?>',
                confirmButtonText: 'Okay'
            });
        <?php elseif (!empty($success_message)): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?php echo htmlspecialchars($success_message); ?>',
                confirmButtonText: 'Okay'
            });
        <?php endif; ?>
    </script>
</body>
</html>
 