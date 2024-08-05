<?php
session_start();
if (!isset($_SESSION["admin"])) {
   header("Location: admin_signin.html");
   exit();
}

require_once "database.php";

// Fetch pending patients
$sql_pending = "SELECT * FROM users WHERE status = 'pending' ORDER BY severity ASC";
$result_pending = mysqli_query($conn, $sql_pending);
$pending_patients = mysqli_fetch_all($result_pending, MYSQLI_ASSOC);

// Fetch ready patients
$sql_ready = "SELECT * FROM users WHERE status = 'ready' ORDER BY severity ASC";
$result_ready = mysqli_query($conn, $sql_ready);
$ready_patients = mysqli_fetch_all($result_ready, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Triage System - Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Hospital Triage System - Admin Dashboard</h1>
    </header>
    <main class="container mt-5">
        <h2>Pending Patients</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Severity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pending_patients as $patient): ?>
                <tr>
                    <td><?php echo htmlspecialchars($patient['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($patient['email']); ?></td>
                    <td><?php echo htmlspecialchars($patient['severity']); ?></td>
                    <td id="status-<?php echo $patient['id']; ?>"><?php echo htmlspecialchars($patient['status']); ?></td>
                    <td>
                        <button class="btn btn-success change-status-btn" data-id="<?php echo $patient['id']; ?>">Mark as Ready</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Ready Patients</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Severity</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ready_patients as $patient): ?>
                <tr>
                    <td><?php echo htmlspecialchars($patient['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($patient['email']); ?></td>
                    <td><?php echo htmlspecialchars($patient['severity']); ?></td>
                    <td><?php echo htmlspecialchars($patient['status']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="logout.php" class="btn btn-warning">Logout</a>
    </main>
    <footer class="footer">
        <p>&copy; 2024 Hospital Triage System</p>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.change-status-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const patientId = this.getAttribute('data-id');
                    fetch('change_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id=' + encodeURIComponent(patientId)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('status-' + patientId).textContent = 'ready';
                            this.closest('tr').remove();
                            location.reload(); // Reload the page to update tables
                        } else {
                            alert('Failed to change status');
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
