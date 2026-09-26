<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'classes.php';

$app = new Appointment();

// Handle Delete
if (isset($_GET['delete'])) {
    $app->delete($_GET['delete']);
    header("Location: appointments.php");
    exit;
}

// Handle Status Confirmation
if (isset($_GET['confirm'])) {
    $app->updateStatus($_GET['confirm'], 'Confirmed');
    header("Location: appointments.php");
    exit;
}

$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';
$records = $app->getAppointments($search, $status_filter);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Appointments - CareLink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2>Manage Appointments</h2>
        <a href="index.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <!-- Search and Filter Controls -->
        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search patient name..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Confirmed" <?= $status_filter == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-1">Filter / Search</button>
                <a href="appointments.php" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <!-- Records Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Patient Name</th>
                            <th>Doctor</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($records)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-3">No appointments found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($records as $row): ?>
                            <tr>
                                <td><?= $row['appointment_id'] ?></td>
                                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                <td><?= htmlspecialchars($row['doctor']) ?></td>
                                <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                                <td>
                                    <span class="badge <?= $row['status'] == 'Confirmed' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($row['status'] == 'Pending'): ?>
                                        <a href="?confirm=<?= $row['appointment_id'] ?>" class="btn btn-sm btn-success">Confirm</a>
                                    <?php endif; ?>
                                    <a href="?delete=<?= $row['appointment_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>