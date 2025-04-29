<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
require_once '../models.php';

if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php'); exit();
}
// Stats
$doctors = Doctor::all();
$patients = Patient::all();
$appointments = Appointment::all();
$schedules = Schedule::all();

ob_start();
?>
<div class="container py-5">
    <h2 class="mb-4">Admin Dashboard</h2>
    <a href="profile.php" class="btn btn-secondary mb-4 float-end">My Profile</a>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <h3><?php echo count($doctors); ?></h3>
                <p>Doctors</p>
                <a href="add_doctor.php" class="btn btn-primary-custom">Add Doctor</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <h3><?php echo count($patients); ?></h3>
                <p>Patients</p>
                <a href="view_patients.php" class="btn btn-primary-custom">View Patients</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <h3><?php echo count($appointments); ?></h3>
                <p>Appointments</p>
                <a href="view_bookings.php" class="btn btn-primary-custom">View Bookings</a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center">
                <h3><?php echo count($schedules); ?></h3>
                <p>Sessions</p>
                <a href="manage_sessions.php" class="btn btn-primary-custom">Manage Sessions</a>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="../assets/notification.css">
<script src="../assets/notification.js"></script>
<script>
    let lastEmergencyCheck = '<?php echo date("Y-m-d H:i:s"); ?>';
    let alertedEmergencies = new Set();
    setInterval(() => {
        fetch('../api/fetch_emergencies.php?since=' + encodeURIComponent(lastEmergencyCheck))
            .then(res => res.json())
            .then(data => {
                data.new.forEach(e => {
                    if (!alertedEmergencies.has(e.id)) {
                        // auto-acknowledge before showing to avoid repeats
                        fetch('../api/acknowledge_emergency.php', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify({id: e.id})
                        });
                        showNotification(
                            'New Emergency Reported',
                            `<b>Victim:</b> ${e.victim_name}<br><b>Location:</b> ${e.location}<br><b>Severity:</b> ${e.severity}`,
                            '🚨'
                        );
                        alertedEmergencies.add(e.id);
                    }
                });
                lastEmergencyCheck = data.timestamp;
            });
    }, 5000);
</script>
<?php
$content = ob_get_clean();
$title = 'Admin Dashboard';
require_once '../base.php';
