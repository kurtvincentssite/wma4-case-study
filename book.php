<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'classes.php';

$patientObj = new Patient();
$appObj = new Appointment();
$patients = $patientObj->getAllPatients();
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_id = htmlspecialchars(trim($_POST['patient_id']));
    $d_id = 1; // Default assigned doctor
    $date = htmlspecialchars(trim($_POST['appointment_date']));
    $status = 'Pending';

    if (empty($p_id) || empty($date)) {
        $message = "<div class='alert alert-danger'>All fields are required.</div>";
    } else {
        if ($appObj->create($p_id, $d_id, $date, $status)) {
            $message = "<div class='alert alert-success'>Appointment Booked Successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Failed to book appointment.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment - CareLink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 600px;">
        <h2>Book Clinic Appointment</h2>
        <a href="index.php" class="btn btn-secondary mb-3">Back to Dashboard</a>
        
        <?= $message; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Patient</label>
                        <select name="patient_id" class="form-select" required>
                            <option value="">-- Choose Patient --</option>
                            <?php foreach ($patients as $p): ?>
                                <option value="<?= $p['patient_id'] ?>">
                                    <?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Appointment Date & Time</label>
                        <input type="datetime-local" name="appointment_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Book Appointment</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>