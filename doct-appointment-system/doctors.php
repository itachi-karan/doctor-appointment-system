<?php
session_start();
require_once 'config.php';
require_once 'functions.php';
require_once 'models.php';

if (isset($_GET['id'])) {
    $doc = Doctor::find(intval($_GET['id']));
    if (!$doc) {
        set_flash_message('danger', 'Doctor not found.');
        header('Location: doctors.php'); exit();
    }
    $schedules = array_filter(Schedule::all(), fn($s) => $s['doctor_id'] === $doc['id']);
    ob_start();
?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <img src="assets/img/doctors/<?php echo htmlspecialchars($doc['profile_image'] ?: 'doctor-placeholder.jpg'); ?>" class="img-fluid rounded mb-3">
        </div>
        <div class="col-md-8">
            <h2>Dr. <?php echo htmlspecialchars($doc['first_name'].' '.$doc['last_name']); ?></h2>
            <p><strong>Specialty:</strong> <?php echo htmlspecialchars($doc['specialty']); ?></p>
            <p><?php echo htmlspecialchars($doc['bio']); ?></p>
            <h4>Schedule</h4>
            <ul>
                <?php foreach ($schedules as $s): ?>
                <li><?php echo htmlspecialchars($s['day_of_week'].' '.$s['start_time'].' - '.$s['end_time']); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type']==='patient'): ?>
            <a href="patient/book_appointment.php" class="btn btn-primary-custom">Book Appointment</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php
    $content = ob_get_clean();
    $title = 'Doctor Profile';
    require_once 'base.php';
    exit();
}
// List all doctors
$doctors = Doctor::all();
ob_start();
?>
<div class="container py-5">
    <h2 class="mb-4">All Doctors</h2>
    <div class="row">
        <?php foreach ($doctors as $doc): ?>
        <div class="col-md-4 mb-4">
            <div class="card doctor-card h-100">
                <div class="card-body text-center">
                    <img src="assets/img/doctors/<?php echo htmlspecialchars($doc['profile_image'] ?: 'doctor-placeholder.jpg'); ?>" class="rounded-circle mb-3" width="120">
                    <h5 class="card-title">Dr. <?php echo htmlspecialchars($doc['first_name'].' '.$doc['last_name']); ?></h5>
                    <p class="doctor-specialty"><?php echo htmlspecialchars($doc['specialty']); ?></p>
                    <a href="doctors.php?id=<?php echo $doc['id']; ?>" class="btn btn-outline-primary">View Profile</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
$title = 'Find Doctors';
require_once 'base.php';
