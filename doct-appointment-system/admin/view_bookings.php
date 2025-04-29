<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
require_once '../models.php';

if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Fetch all appointments
$appointments = Appointment::all();

ob_start();
?>
<div class="container py-5">
    <h2 class="mb-4">All Bookings</h2>
    <?php if($appointments): ?>
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Patient</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($appointments as $a):
            $doc = Doctor::find($a['doctor_id']);
            $pat = Patient::find($a['patient_id']);
        ?>
            <tr>
                <td>Dr. <?php echo htmlspecialchars($doc['first_name'].' '.$doc['last_name']); ?></td>
                <td><?php echo htmlspecialchars($pat['first_name'].' '.$pat['last_name']); ?></td>
                <td><?php echo htmlspecialchars($a['appointment_date']); ?></td>
                <td><?php echo htmlspecialchars($a['appointment_time']); ?></td>
                <td><?php echo htmlspecialchars($a['status']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
$title = 'View Bookings - Admin';
require_once '../base.php';
