<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_signin.html');
    exit;
}

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

// Function to calculate waiting time in minutes
function calculate_waiting_time($sign_in_time) {
    $sign_in_datetime = new DateTime($sign_in_time);
    $current_datetime = new DateTime();
    $interval = $sign_in_datetime->diff($current_datetime);
    return $interval->days * 24 * 60 + $interval->h * 60 + $interval->i;
}

// Retrieve patients sorted by severity (highest first) and sign-in time (earliest first)
$stmt = $conn->query("SELECT u.username, p.age, p.sign_in_time, p.severity FROM patients p JOIN users u ON p.user_id = u.id ORDER BY p.severity DESC, p.sign_in_time ASC");
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Triage System - Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header class="bg-primary text-white text-center py-3">
        <h1>Hospital Triage System - Admin Dashboard</h1>
    </header>
    <main class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h2>Patient List</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Sign-In Time</th>
                            <th>Severity</th>
                            <th>Waiting Time (minutes)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patients as $patient): ?>
                            <tr>
                                <td><?= htmlspecialchars($patient['username']) ?></td>
                                <td><?= htmlspecialchars($patient['age']) ?></td>
                                <td><?= htmlspecialchars($patient['sign_in_time']) ?></td>
                                <td><?= htmlspecialchars($patient['severity']) ?></td>
                                <td><?= calculate_waiting_time($patient['sign_in_time']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <footer class="text-center mt-5 mb-3">
        <p>&copy; 2024 Hospital Triage System</p>
    </footer>
</body>
</html>
