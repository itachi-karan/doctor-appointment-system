<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
require_once '../models.php';

if(!is_doctor()) {
    header('Location: ../login.php'); exit();
}

global $db;
// Find doctor profile ID
$stmt = $db->prepare('SELECT id FROM doctors WHERE user_id = :uid');
$stmt->execute([':uid' => $_SESSION['user_id']]);
$docRec = $stmt->fetch(PDO::FETCH_ASSOC);

// Get this doctor's schedules and appointments
$schedules = array_filter(Schedule::all(), fn($s) => $s['doctor_id'] == $docRec['id']);
$appointments = array_filter(Appointment::all(), fn($a) => $a['doctor_id'] == $docRec['id']);

ob_start();
?>
<div class="container py-5">
    <h2 class="mb-4">Doctor Dashboard</h2>
    <a href="profile.php" class="btn btn-secondary mb-4 float-end">My Profile</a>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">My Schedules</h5>
                    <p class="card-text"><?php echo count($schedules); ?> active sessions</p>
                    <a href="manage_schedule.php" class="btn btn-primary-custom">Manage Schedules</a>
                    <a href="manage_sessions.php" class="btn btn-primary-custom">Manage Sessions</a>
                    <a href="../admin/emergencies.php" class="btn btn-danger">View Emergencies</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Appointments</h5>
                    <p class="card-text"><?php echo count($appointments); ?> requests</p>
                    <a href="view_requests.php" class="btn btn-primary-custom">View Requests</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'Dashboard - Doctor';
?>
<link rel="stylesheet" href="../assets/notification.css">
<script src="../assets/notification.js"></script>
<script>
    let alertedEmergencies = new Set();
    setInterval(() => {
        fetch('../api/fetch_emergencies.php')
            .then(res => res.json())
            .then(data => {
                data.new.forEach(e => {
                    if (!alertedEmergencies.has(e.id)) {
                        // auto-acknowledge before showing
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
            });
    }, 5000);
</script>
<?php
require_once '../base.php';
