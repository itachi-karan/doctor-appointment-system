<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Get featured doctors
$featured_doctors = get_featured_doctors();

// Start output buffering
ob_start();
?>

<div class="container">
    <!-- Hero Section -->
    <div class="row align-items-center py-5">
        <div class="col-md-6">
            <h1 class="display-4 mb-4">Your Health, Our Priority</h1>
            <p class="lead mb-4">Book appointments with the best doctors in your area. Easy, fast, and secure.</p>
            <?php if(!isset($_SESSION['user_id'])): ?>
            <div class="d-grid gap-2 d-md-flex">
                <a href="register.php" class="btn btn-primary btn-lg">Get Started</a>
                <a href="doctors.php" class="btn btn-outline-primary btn-lg">Find Doctors</a>
            </div>
            <?php else: ?>
            <div class="d-grid gap-2 d-md-flex">
                <?php if($_SESSION['user_type'] == 'patient'): ?>
                <a href="patient/book_appointment.php" class="btn btn-primary btn-lg">Book Appointment</a>
                <?php endif; ?>
                <a href="doctors.php" class="btn btn-outline-primary btn-lg">View All Doctors</a>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <img src="assets/img/hero.svg" alt="Healthcare illustration" class="img-fluid">
        </div>
    </div>

    <!-- Featured Doctors Section -->
    <?php if($featured_doctors): ?>
    <section class="py-5">
        <h2 class="text-center mb-4">Featured Doctors</h2>
        <div class="row">
            <?php foreach($featured_doctors as $doctor): ?>
            <div class="col-md-4">
                <div class="card doctor-card h-100">
                    <div class="card-body text-center">
                        <img src="<?php echo !empty($doctor['profile_image']) ? 'assets/img/doctors/' . $doctor['profile_image'] : 'assets/img/doctor-placeholder.jpg'; ?>" 
                             alt="Dr. <?php echo $doctor['first_name'] . ' ' . $doctor['last_name']; ?>" 
                             class="rounded-circle mb-3">
                        <h5 class="card-title">Dr. <?php echo $doctor['first_name'] . ' ' . $doctor['last_name']; ?></h5>
                        <p class="doctor-specialty"><?php echo $doctor['specialty']; ?></p>
                        <p class="card-text"><?php echo substr($doctor['bio'], 0, 100); ?>...</p>
                        <a href="doctors.php?id=<?php echo $doctor['id']; ?>" class="btn btn-outline-primary">View Profile</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Features Section -->
    <section class="py-5">
        <h2 class="text-center mb-4">Why Choose Us</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-user-md fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Expert Doctors</h5>
                        <p class="card-text">Access to highly qualified and experienced medical professionals.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Easy Scheduling</h5>
                        <p class="card-text">Book appointments at your convenience, 24/7.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-hospital fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Quality Care</h5>
                        <p class="card-text">Comprehensive healthcare services with a focus on patient satisfaction.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
$content = ob_get_clean();
$title = 'Home - Doctor Appointment System';
require_once 'base.php';
?> 