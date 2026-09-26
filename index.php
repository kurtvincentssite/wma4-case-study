<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'classes.php';

$app = new Appointment();
$stats = $app->getDashboardStats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CareLink - Clinic Appointment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>CareLink Dashboard</h2>
            <span class="badge bg-primary fs-6">Midterm Phase</span>
        </div>
        
        <nav class="mb-4">
            <a href="index.php" class="btn btn-primary me-2">Dashboard</a>
            <a href="book.php" class="btn btn-success me-2">Book Appointment</a>
            <a href="appointments.php" class="btn btn-info text-white">View Appointments</a>
        </nav>

        <div class="row">
            <div class="col-md-6">
                <div class="card text-white bg-primary mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Appointments</h5>
                        <p class="card-text fs-1 fw-bold"><?= $stats['total'] ?? 0; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-dark bg-warning mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Pending Appointments</h5>
                        <p class="card-text fs-1 fw-bold"><?= $stats['pending'] ?? 0; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>