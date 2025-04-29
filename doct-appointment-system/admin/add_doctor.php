<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
require_once '../models.php';

if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php'); exit();
}
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $first = sanitize($_POST['first_name']);
    $last = sanitize($_POST['last_name']);
    $spec = sanitize($_POST['specialty']);
    $qual = sanitize($_POST['qualification']);
    $exp = intval($_POST['experience_years']);
    $fee = floatval($_POST['consultation_fee']);

    if(!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[]='Valid email required';
    if(strlen($password)<6) $errors[]='Password min 6 chars';
    if($password!==$confirm) $errors[]='Passwords do not match';
    if(!$first||!$last) $errors[]='Name required';
    if(!$spec) $errors[]='Specialty required';
    if(empty($errors)) {
        $hashed = password_hash($password,PASSWORD_DEFAULT);
        $uid = User::create(['email'=>$email,'password'=>$hashed,'user_type'=>'doctor']);
        Doctor::create(['user_id'=>$uid,'first_name'=>$first,'last_name'=>$last,'specialty'=>$spec,'qualification'=>$qual,'experience_years'=>$exp,'bio'=>'','consultation_fee'=>$fee]);
        set_flash_message('success','Doctor added');
        header('Location: dashboard.php'); exit();
    }
}
ob_start();
?>
<div class="container py-5">
    <h2>Add Doctor</h2>
    <?php if($errors): ?><div class="alert alert-danger"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div><?php endif; ?>
    <form method="POST" class="row g-3">
        <div class="col-md-6"><label>Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="col-md-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
        <div class="col-md-3"><label>Confirm</label><input type="password" name="confirm_password" class="form-control" required></div>
        <div class="col-md-6"><label>First Name</label><input type="text" name="first_name" class="form-control" required></div>
        <div class="col-md-6"><label>Last Name</label><input type="text" name="last_name" class="form-control" required></div>
        <div class="col-md-6"><label>Specialty</label><input type="text" name="specialty" class="form-control" required></div>
        <div class="col-md-6"><label>Qualification</label><input type="text" name="qualification" class="form-control"></div>
        <div class="col-md-3"><label>Experience Years</label><input type="number" name="experience_years" class="form-control" min="0"></div>
        <div class="col-md-3"><label>Fee</label><input type="number" step="0.01" name="consultation_fee" class="form-control" required></div>
        <div class="col-12"><button type="submit" class="btn btn-primary-custom">Add Doctor</button></div>
    </form>
</div>
<?php
$content = ob_get_clean();
$title = 'Add Doctor';
require_once '../base.php';
