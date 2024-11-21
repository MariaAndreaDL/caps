<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient</title>
    
    <!-- Include SweetAlert CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        .main-content {
            margin-left: 250px;
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

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        form input, form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-bottom: 20px;
        }

        .sidebar {
            width: 250px;
            height: 100%;
            position: fixed;
            background: #343a40;
            color: white;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
        }

        .sidebar a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            display: block;
        }

        .sidebar a:hover {
            background: #495057;
        }

        .toggle-btn {
            cursor: pointer;
            position: fixed;
            top: 10px;
            left: 10px;
            background: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Links -->
        <a href="#">Dashboard</a>
        <a href="#">Patients</a>
        <a href="#">Appointments</a>
        <a href="#">Settings</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h1>Add New Patient</h1>

            <!-- Display errors if any -->
            <?php if (!empty($errors)): ?>
                <div class="error"><?php echo $errors; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="text" name="first_name" placeholder="First Name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                <input type="text" name="last_name" placeholder="Last Name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                <input type="text" name="middle_initial" placeholder="Middle Initial" value="<?php echo htmlspecialchars($middle_initial); ?>" maxlength="1">

                <input type="text" name="address" placeholder="Address" value="<?php echo htmlspecialchars($address); ?>" required>
                <input type="number" name="age" placeholder="Age" value="<?php echo htmlspecialchars($age); ?>" required>
                
                <select name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
                    <option value="Other" <?php if ($gender == 'Other') echo 'selected'; ?>>Other</option>
                </select>

                <input type="text" name="contact_number" placeholder="Contact Number" value="<?php echo htmlspecialchars($contact_number); ?>" required>
                
                <select name="marital_status" required>
                    <option value="" disabled selected>Select Marital Status</option>
                    <option value="Single" <?php if ($marital_status == 'Single') echo 'selected'; ?>>Single</option>
                    <option value="Married" <?php if ($marital_status == 'Married') echo 'selected'; ?>>Married</option>
                    <option value="Divorced" <?php if ($marital_status == 'Divorced') echo 'selected'; ?>>Divorced</option>
                </select>

                <input type="text" name="remarks" placeholder="Remarks (if any)" value="<?php echo htmlspecialchars($remarks); ?>">

                <button type="submit">Add Patient</button>
            </form>
        </div>
    </div>

    <script>
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.querySelector('.toggle-btn');

        // Toggle sidebar collapse
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>
</html>
