<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- base tag removed to avoid URL path issues -->
    <title><?php echo isset($title) ? $title : 'Doctor Appointment System'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link href="<?php echo SITE_URL; ?>/assets/css/style.css" rel="stylesheet">
    
    <?php if(isset($extra_css)) echo $extra_css; ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo SITE_URL; ?>/index.php">DocAppointment</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/doctors.php">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/contact.php">Contact</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <?php if($_SESSION['user_type'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo SITE_URL; ?>/admin/dashboard.php">Dashboard</a>
                            </li>
                        <?php elseif($_SESSION['user_type'] == 'doctor'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo SITE_URL; ?>/doctor/dashboard.php">Dashboard</a>
                            </li>
                        <?php elseif($_SESSION['user_type'] == 'patient'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo SITE_URL; ?>/patient/dashboard.php">Dashboard</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>/register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        <?php if(isset($_SESSION['flash_messages'])): ?>
            <?php foreach($_SESSION['flash_messages'] as $type => $message): ?>
                <div class="alert alert-<?php echo $type; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash_messages']); ?>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <main class="container py-4">
        <div class="row">
            <div class="col-md-12">
                <?php echo $content; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Doc Appointment System</h5>
                    <p>Improving healthcare access, diagnosis, treatment, and patient monitoring.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>/index.php">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/doctors.php">Find Doctors</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/services.php">Services</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/about.php">About Us</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <address>
                        <i class="fas fa-map-marker-alt"></i> 123 Medical Ave, Health City<br>
                        <i class="fas fa-phone"></i> +1 (123) 456-7890<br>
                        <i class="fas fa-envelope"></i> info@docappointment.com
                    </address>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> Doc Appointment System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo SITE_URL; ?>/assets/js/script.js"></script>
    
    <?php if(isset($extra_js)) echo $extra_js; ?>
</body>
</html>