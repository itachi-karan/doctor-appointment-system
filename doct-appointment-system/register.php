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

// Handle registration form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_type = sanitize($_POST['user_type']);
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    
    // Validate input
    if(empty($email)) {
        $errors[] = "Email is required";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if(empty($password)) {
        $errors[] = "Password is required";
    } elseif(strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }
    
    if($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    if(!in_array($user_type, ['doctor', 'patient'])) {
        $errors[] = "Invalid user type";
    }
    
    if(empty($first_name)) {
        $errors[] = "First name is required";
    }
    
    if(empty($last_name)) {
        $errors[] = "Last name is required";
    }
    
    // Check if email already exists
    try {
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if($stmt->fetch()) {
            $errors[] = "Email already exists";
        }
    } catch(PDOException $e) {
        error_log("Email check error: " . $e->getMessage());
        $errors[] = "An error occurred. Please try again later.";
    }
    
    if(empty($errors)) {
        try {
            // Create user via Model
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $user_id = User::create([
                'email' => $email,
                'password' => $hashed_password,
                'user_type' => $user_type
            ]);
            // Create doctor or patient record via Model
            if($user_type === 'doctor') {
                Doctor::create([
                    'user_id' => $user_id,
                    'first_name' => $first_name,
                    'last_name' => $last_name
                ]);
            } else {
                Patient::create([
                    'user_id' => $user_id,
                    'first_name' => $first_name,
                    'last_name' => $last_name
                ]);
            }
            
            // Set flash message
            set_flash_message('success', 'Registration successful! Please login.');
            
            // Redirect to login page
            header('Location: login.php');
            exit();
            
        } catch(PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            $errors[] = "An error occurred. Please try again later.";
        }
    }
}

// Start output buffering
ob_start();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Create an Account</h2>
                    
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
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="user_type" class="form-label">I am a</label>
                            <select class="form-select" id="user_type" name="user_type" required>
                                <option value="">Select type</option>
                                <option value="doctor" <?php echo isset($_POST['user_type']) && $_POST['user_type'] == 'doctor' ? 'selected' : ''; ?>>Doctor</option>
                                <option value="patient" <?php echo isset($_POST['user_type']) && $_POST['user_type'] == 'patient' ? 'selected' : ''; ?>>Patient</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="form-text">Password must be at least 6 characters long.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Register</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p>Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$title = 'Register - Doctor Appointment System';
require_once 'base.php';
?>