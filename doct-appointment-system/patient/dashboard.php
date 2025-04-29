<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
require_once '../models.php';

if(!is_patient()) {
    header('Location: ../login.php'); exit();
}

global $db;
// Find patient profile ID
$stmt = $db->prepare('SELECT id FROM patients WHERE user_id = :uid');
$stmt->execute([':uid' => $_SESSION['user_id']]);
$patRec = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch patient's appointments
$all = Appointment::all();
$appointments = array_filter($all, fn($a) => $a['patient_id'] == $patRec['id']);

ob_start();
?>
<div class="container py-5">
    <h2 class="mb-4">My Appointments</h2>
    <a href="profile.php" class="btn btn-secondary mb-4 float-end">My Profile</a>
    <a href="book_appointment.php" class="btn btn-primary-custom mb-4">Book New Appointment</a>
    <a href="emergency.php" class="btn btn-danger mb-4">Report Emergency</a>
    <?php if($appointments): ?>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr><th>Doctor</th><th>Date</th><th>Time</th><th>Status</th><th>Reason</th></tr>
        </thead>
        <tbody>
        <?php foreach($appointments as $a):
            $doc = Doctor::find($a['doctor_id']); ?>
            <tr>
                <td>Dr. <?php echo htmlspecialchars($doc['first_name'].' '.$doc['last_name']); ?></td>
                <td><?php echo htmlspecialchars($a['appointment_date']); ?></td>
                <td><?php echo htmlspecialchars($a['appointment_time']); ?></td>
                <td><?php echo htmlspecialchars($a['status']); ?></td>
                <td><?php echo htmlspecialchars($a['reason']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php else: ?>
        <p>You have no appointments yet.</p>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
$title = 'Dashboard - Patient';
require_once '../base.php';
