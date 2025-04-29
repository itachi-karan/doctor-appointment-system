<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// Start output buffering
ob_start();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto text-center">
            <h1 class="display-4 mb-4">Our Services</h1>
            <p class="lead mb-4">We provide comprehensive healthcare services through our innovative appointment management system.</p>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check fa-3x text-primary mb-3"></i>
                    <h3 class="card-title">Online Appointments</h3>
                    <p class="card-text">Book appointments with your preferred doctors anytime, anywhere. Easy scheduling and rescheduling at your convenience.</p>
                    <ul class="list-unstyled mt-3">
                        <li><i class="fas fa-check text-success me-2"></i>24/7 booking availability</li>
                        <li><i class="fas fa-check text-success me-2"></i>Instant confirmation</li>
                        <li><i class="fas fa-check text-success me-2"></i>Email reminders</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user-md fa-3x text-primary mb-3"></i>
                    <h3 class="card-title">Doctor Consultations</h3>
                    <p class="card-text">Access to a wide network of qualified healthcare professionals across various specialties.</p>
                    <ul class="list-unstyled mt-3">
                        <li><i class="fas fa-check text-success me-2"></i>Multiple specialties</li>
                        <li><i class="fas fa-check text-success me-2"></i>Experienced doctors</li>
                        <li><i class="fas fa-check text-success me-2"></i>Detailed doctor profiles</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-notes-medical fa-3x text-primary mb-3"></i>
                    <h3 class="card-title">Medical Records</h3>
                    <p class="card-text">Secure storage and management of your medical history, treatments, and diagnoses.</p>
                    <ul class="list-unstyled mt-3">
                        <li><i class="fas fa-check text-success me-2"></i>Digital record keeping</li>
                        <li><i class="fas fa-check text-success me-2"></i>Easy access</li>
                        <li><i class="fas fa-check text-success me-2"></i>Secure storage</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-pills fa-3x text-primary mb-3"></i>
                    <h3 class="card-title">Treatment Tracking</h3>
                    <p class="card-text">Monitor your ongoing treatments and medications with our comprehensive tracking system.</p>
                    <ul class="list-unstyled mt-3">
                        <li><i class="fas fa-check text-success me-2"></i>Treatment history</li>
                        <li><i class="fas fa-check text-success me-2"></i>Progress monitoring</li>
                        <li><i class="fas fa-check text-success me-2"></i>Medication reminders</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-file-medical fa-3x text-primary mb-3"></i>
                    <h3 class="card-title">Health Reports</h3>
                    <p class="card-text">Access and manage your health reports and test results in one secure location.</p>
                    <ul class="list-unstyled mt-3">
                        <li><i class="fas fa-check text-success me-2"></i>Digital reports</li>
                        <li><i class="fas fa-check text-success me-2"></i>Historical data</li>
                        <li><i class="fas fa-check text-success me-2"></i>Easy sharing</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-comments fa-3x text-primary mb-3"></i>
                    <h3 class="card-title">Support Services</h3>
                    <p class="card-text">Get assistance whenever you need it with our dedicated support team.</p>
                    <ul class="list-unstyled mt-3">
                        <li><i class="fas fa-check text-success me-2"></i>24/7 support</li>
                        <li><i class="fas fa-check text-success me-2"></i>Quick response</li>
                        <li><i class="fas fa-check text-success me-2"></i>Multiple channels</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card bg-primary text-white">
                <div class="card-body text-center py-5">
                    <h2 class="mb-4">Ready to Get Started?</h2>
                    <p class="lead mb-4">Join thousands of patients managing their healthcare more effectively.</p>
                    <?php if(!isset($_SESSION['user_id'])): ?>
                    <a href="register.php" class="btn btn-light btn-lg">Register Now</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Our Services - Doctor Appointment System';
require_once 'base.php';
?> 