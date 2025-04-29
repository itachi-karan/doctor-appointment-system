<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
require_once '../models.php';

if(!is_patient()) {
    header('Location: ../login.php'); exit();
}

$user = User::find($_SESSION['user_id']);
$stmt = $db->prepare('SELECT * FROM patients WHERE user_id = :uid');
$stmt->execute([':uid' => $_SESSION['user_id']]);
$pat = $stmt->fetch(PDO::FETCH_ASSOC);

ob_start();
?>
<div class="container py-5">
    <h2 class="mb-4">My Profile</h2>
    <div class="text-center mb-4">
        <i class="fas fa-user-circle fa-5x text-primary"></i>
    </div>
    <table class="table table-bordered">
        <tr><th>Email</th><td><?php echo htmlspecialchars($user['email']); ?></td></tr>
        <tr><th>Name</th><td><?php echo htmlspecialchars($pat['first_name'].' '.$pat['last_name']); ?></td></tr>
        <tr><th>Date of Birth</th><td><?php echo htmlspecialchars($pat['date_of_birth']); ?></td></tr>
        <tr><th>Gender</th><td><?php echo htmlspecialchars($pat['gender']); ?></td></tr>
        <tr><th>Phone</th><td><?php echo htmlspecialchars($pat['phone']); ?></td></tr>
        <tr><th>Address</th><td><?php echo htmlspecialchars($pat['address']); ?></td></tr>
    </table>
</div>
<?php
$content = ob_get_clean();
$title = 'Profile - Patient';
require_once '../base.php';
