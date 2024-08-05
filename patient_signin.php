<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    require_once "database.php";
    $sql = "SELECT * FROM users WHERE email ='$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($user) {
        if (password_verify($password, $user["password"])) {
            $_SESSION["user"] = "yes";
            $_SESSION["user_email"] = $email;
            echo json_encode([
                "type" => "success",
                "message" => "Signed in successfully"
            ]);
        } else {
            echo json_encode([
                "type" => "danger",
                "message" => "Password is incorrect"
            ]);
        }
    } else {
        echo json_encode([
            "type" => "danger",
            "message" => "Email is not registered"
        ]);
    }
}
