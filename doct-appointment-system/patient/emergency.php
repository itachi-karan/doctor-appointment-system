<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
require_once '../models.php';
global $db;

if(!is_patient()) {
    header('Location: ../login.php'); exit();
}
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $victim = sanitize($_POST['victim_name']);
    $location = sanitize($_POST['location']);
    $severity = sanitize($_POST['severity']);
    if(!$victim || !$location || !$severity) {
        $errors[] = 'All fields are required.';
    }
    if(empty($errors)) {
        $stmt = $db->prepare("INSERT INTO accidents (victim_name, location, severity, reported_time, status) VALUES (:v, :loc, :sev, NOW(), 'pending')");
        $stmt->execute([':v'=>$victim, ':loc'=>$location, ':sev'=>$severity]);
        // Notify admin
        $adminEmail = ADMIN_EMAIL;
        send_email($adminEmail, 'Emergency Alert', "New emergency reported: $victim at $location (Severity: $severity)");
        // Notify all doctors
        $docs = Doctor::all();
        foreach($docs as $d) {
            $u = User::find($d['user_id']);
            send_email($u['email'], 'Emergency Alert', "New emergency: $victim at $location (Severity: $severity)");
        }
        set_flash_message('success','Emergency reported successfully.');
        header('Location: dashboard.php'); exit();
    }
}
ob_start();
?>
<div class="container py-5">
    <h2 class="mb-4 text-danger">Report Emergency</h2>
    <?php if($errors): ?><div class="alert alert-danger"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div><?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Victim Name</label>
            <input type="text" name="victim_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Severity</label>
            <select name="severity" class="form-select" required>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
        </div>
        <button type="submit" class="btn btn-danger">Report Emergency</button>
    </form>
</div>
<?php
$content=ob_get_clean();
$title='Emergency Report';
require_once '../base.php';
