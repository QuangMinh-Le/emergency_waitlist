<?php
session_start();
if (!isset($_SESSION["admin"])) {
    echo json_encode(['success' => false]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "database.php";
    $id = $_POST['id'];
    $sql = "UPDATE users SET status = 'ready' WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
