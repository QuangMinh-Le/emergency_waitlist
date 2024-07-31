<?php
// Database connection
$host = 'localhost';
$db = 'hospital_triage';
$user = 'your_db_username';  // replace with your database username
$pass = 'your_db_password';  // replace with your database password

try {
    $conn = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $code = $_POST['code'];
    $severity = $_POST['severity'];

    // Verify user credentials
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username AND code = :code");
    $stmt->execute(['username' => $username, 'code' => $code]);
    $user_id = $stmt->fetchColumn();

    if ($user_id) {
        // Insert sign-in record into the database with severity
        $stmt = $conn->prepare("INSERT INTO patients (user_id, severity) VALUES (:user_id, :severity)");
        $stmt->execute(['user_id' => $user_id, 'severity' => $severity]);

        echo "Sign-in successful!";
    } else {
        echo "Invalid username or code.";
    }
}
?>
