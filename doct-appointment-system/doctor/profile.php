<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
require_once '../models.php';

if(!is_doctor()) {
    header('Location: ../login.php'); exit();
}

global $db;
$user = User::find($_SESSION['user_id']);
$stmt = $db->prepare('SELECT * FROM doctors WHERE user_id = :uid');
$stmt->execute([':uid' => $_SESSION['user_id']]);
$doc = $stmt->fetch(PDO::FETCH_ASSOC);

ob_start();
?>
<div class="container py-5">
    <h2 class="mb-4">My Profile</h2>
    <div class="text-center mb-4">
        <i class="fas fa-user-circle fa-5x text-primary"></i>
    </div>
    <table class="table table-bordered">
        <tr><th>Email</th><td><?php echo htmlspecialchars($user['email']); ?></td></tr>
        <tr><th>Name</th><td><?php echo htmlspecialchars($doc['first_name'].' '.$doc['last_name']); ?></td></tr>
        <tr><th>Specialty</th><td><?php echo htmlspecialchars($doc['specialty']); ?></td></tr>
        <tr><th>Qualification</th><td><?php echo htmlspecialchars($doc['qualification']); ?></td></tr>
        <tr><th>Experience (years)</th><td><?php echo htmlspecialchars($doc['experience_years']); ?></td></tr>
        <tr><th>Consultation Fee</th><td><?php echo htmlspecialchars($doc['consultation_fee']); ?></td></tr>
    </table>
</div>
<?php
$content = ob_get_clean();
$title = 'Profile - Doctor';
require_once '../base.php';
