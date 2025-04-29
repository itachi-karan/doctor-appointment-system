<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
require_once '../models.php';

if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php'); exit();
}
// Fetch all emergencies
$accidents = Accident::all();

ob_start();
?>
<div class="container py-5">
    <h2 class="mb-4">Emergency Reports</h2>
    <?php if($accidents): ?>
    <div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr><th>Victim</th><th>Location</th><th>Severity</th><th>Time</th><th>Status</th><th>Assigned Doctor</th></tr>
        </thead>
        <tbody>
        <?php foreach($accidents as $a):
            $docName = '';
            if($a['assigned_doctor']) {
                $d = Doctor::find($a['assigned_doctor']);
                $docName = htmlspecialchars($d['first_name'].' '.$d['last_name']);
            }
        ?>
            <tr>
                <td><?php echo htmlspecialchars($a['victim_name']); ?></td>
                <td><?php echo htmlspecialchars($a['location']); ?></td>
                <td><?php echo htmlspecialchars($a['severity']); ?></td>
                <td><?php echo htmlspecialchars($a['reported_time']); ?></td>
                <td><?php echo htmlspecialchars($a['status']); ?></td>
                <td><?php echo $docName; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php else: ?>
        <p>No emergencies reported.</p>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
$title = 'Emergency Reports - Admin';
require_once '../base.php';
