<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Hardcoded admin credentials
    $admin_username = "admin";
    $admin_password = "admin";

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION["admin"] = "yes";
        echo json_encode([
            "type" => "success",
            "message" => "Signed in successfully"
        ]);
    } else {
        echo json_encode([
            "type" => "danger",
            "message" => "Invalid username or password"
        ]);
    }
}

