<?php
session_start();
require_once 'config.php';
require_once 'functions.php';
require_once 'models.php';

// Redirect if already logged in
if(is_logged_in()) {
    header('Location: index.php');
    exit();
}

$errors = [];

// Handle login form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    // Validate input
    if(empty($email)) {
        $errors[] = "Email is required";
    }
    if(empty($password)) {
        $errors[] = "Password is required";
    }
    
    if(empty($errors)) {
        try {
            // Get user from database
            $user = User::findByEmail($email);
            
            // Verify password
            if($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = $user['user_type'];
                
                // Set flash message
                set_flash_message('success', 'Welcome back!');
                
                // Redirect based on user type
                switch($user['user_type']) {
                    case 'admin':
                        header('Location: admin/dashboard.php');
                        break;
                    case 'doctor':
                        header('Location: doctor/dashboard.php');
                        break;
                    case 'patient':
                        header('Location: patient/dashboard.php');
                        break;
                    default:
                        header('Location: index.php');
                }
                exit();
            } else {
                $errors[] = "Invalid email or password";
            }
        } catch(PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $errors[] = "An error occurred. Please try again later.";
        }
    }
}

// Start output buffering
ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Login</h2>
                    
                    <?php if(!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Login</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p>Don't have an account? <a href="register.php">Register here</a></p>
                        <p><a href="forgot-password.php">Forgot your password?</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Login - Doctor Appointment System';
require_once 'base.php';
?>