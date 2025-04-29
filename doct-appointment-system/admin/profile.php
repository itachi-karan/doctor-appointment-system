<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
require_once '../models.php';

if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$user = User::find($_SESSION['user_id']);

ob_start();
?>
<div class="container py-5">
    <h2 class="mb-4">My Profile</h2>
    <div class="text-center mb-4">
        <i class="fas fa-user-circle fa-5x text-primary"></i>
    </div>
    <table class="table table-bordered">
        <tr><th>Email</th><td><?php echo htmlspecialchars($user['email']); ?></td></tr>
        <tr><th>Account Type</th><td>Admin</td></tr>
        <tr><th>Member Since</th><td><?php echo htmlspecialchars($user['created_at']); ?></td></tr>
    </table>
</div>
<?php
$content = ob_get_clean();
$title = 'Profile - Admin';
require_once '../base.php';
