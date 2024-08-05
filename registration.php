<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $severity = $_POST["severity"];
    $errors = array();

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Validate input fields
    if (empty($fullname) || empty($email) || empty($password) || empty($severity)) {
        array_push($errors, "All fields are required!");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must contain at least 8 characters");
    }

    require_once "database.php";
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $rowCount = mysqli_num_rows($result);
    if ($rowCount > 0) {
        array_push($errors, "Email already registered!");
    }

    if (count($errors) > 0) {
        echo json_encode([
            "type" => "danger",
            "message" => implode("<br>", $errors)
        ]);
    } else {
        // Insert User to database
        $sql = "INSERT INTO users (full_name, email, password, severity) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
        if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt, "ssss", $fullname, $email, $password_hash, $severity);
            mysqli_stmt_execute($stmt);
            echo json_encode([
                "type" => "success",
                "message" => "Registered successfully"
            ]);
        } else {
            echo json_encode([
                "type" => "danger",
                "message" => "Failed to register to database"
            ]);
        }
    }
}
