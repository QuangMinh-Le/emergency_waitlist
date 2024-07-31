<?php
session_start();

// Hard-coded admin credentials (in a real-world scenario, use a hashed password)
$admin_username = 'admin';
$admin_password = 'admin_password'; // This should be a hashed password in a real-world scenario

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check credentials
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_id'] = $username;
        echo 'success';
    } else {
        echo 'Invalid username or password.';
    }
}
?>
