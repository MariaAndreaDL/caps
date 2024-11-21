<?php
// Hashing passwords for new users
$admin_password = password_hash('admin', PASSWORD_DEFAULT);
$doctor_password = password_hash('doctor', PASSWORD_DEFAULT);

echo "Admin Hashed Password: " . $admin_password . "<br>";
echo "Doctor Hashed Password: " . $doctor_password . "<br>";
?>
