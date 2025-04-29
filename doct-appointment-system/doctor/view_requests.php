<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
require_once '../models.php';

global $db;
// Find doctor profile for this user
$stmt = $db->prepare('SELECT id FROM doctors WHERE user_id = :uid');
$stmt->execute([':uid' => $_SESSION['user_id']]);
$doc = $stmt->fetch(PDO::FETCH_ASSOC);

if(!is_doctor()) { header('Location: ../login.php'); exit(); }

// Handle actions
if(isset($_GET['id'], $_GET['action'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    if(in_array($action, ['Confirmed','Completed','Cancelled'])) {
        Appointment::update($id, ['status' => $action]);
        set_flash_message('success', "Appointment status updated to $action.");
    }
    header('Location: view_requests.php'); exit();
}

// Fetch appointments for this doctor
$appointments = array_filter(Appointment::all(), fn($a) => $a['doctor_id'] == $doc['id']);

ob_start();
?>
<div class="container py-5">
    <h2 class="mb-4">Appointment Requests</h2>
    <?php if($appointments): ?>
    <table class="table">
        <thead><tr><th>Patient</th><th>Date</th><th>Time</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach($appointments as $a):
            $pat = Patient::find($a['patient_id']); ?>
            <tr>
                <td><?php echo htmlspecialchars($pat['first_name'].' '.$pat['last_name']); ?></td>
                <td><?php echo htmlspecialchars($a['appointment_date']); ?></td>
                <td><?php echo htmlspecialchars($a['appointment_time']); ?></td>
                <td><?php echo htmlspecialchars($a['status']); ?></td>
                <td>
                    <?php if($a['status']=='Pending'): ?>
                        <a href="?id=<?php echo $a['id']; ?>&action=Confirmed" class="btn btn-success btn-sm">Confirm</a>
                        <a href="?id=<?php echo $a['id']; ?>&action=Cancelled" class="btn btn-danger btn-sm">Cancel</a>
                    <?php elseif($a['status']=='Confirmed'): ?>
                        <a href="?id=<?php echo $a['id']; ?>&action=Completed" class="btn btn-primary btn-sm">Complete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?><p>No appointment requests.</p><?php endif; ?>
</div>
<?php
$content = ob_get_clean();
$title = 'View Requests - Doctor';
require_once '../base.php';
