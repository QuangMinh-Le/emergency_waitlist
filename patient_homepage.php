<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: patient_signin.html");
}
require_once "database.php";
$email = $_SESSION["user_email"];
$sql = "SELECT full_name, email, severity, status FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "User not found!";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Hospital Triage System - Patient Homepage</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   <link rel="stylesheet" href="style.css">
</head>
<body>
   <header>
      <h1>Hospital Triage System - Patient Homepage</h1>
   </header>
   <main class="container mt-5">
      <div class="row">
         <div class="col-md-6 offset-md-3">
            <div class="card">
               <div class="card-body">
                  <h2 class="card-title text-center">Welcome, <?php echo htmlspecialchars($user['full_name']); ?></h2>
                  <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                  <p><strong>Severity Level:</strong> <?php echo htmlspecialchars($user['severity']); ?></p>
                  <p><strong>Status:</strong> <?php echo htmlspecialchars($user['status']); ?></p>
               </div>
            </div>
         </div>
      </div>
      <a href="logout.php" class="btn btn-warning mt-3">Logout</a>
   </main>
   <footer class="footer">
      <p>&copy; 2024 Hospital Triage System</p>
   </footer>
</body>
</html>