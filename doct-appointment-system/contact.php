<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $message = sanitize($_POST['message']);
    if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$message) {
        $errors[] = 'All fields are required and email must be valid.';
    } else {
        $body = "<p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>"
              . "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>"
              . "<p>" . nl2br(htmlspecialchars($message)) . "</p>";
        if (send_email(ADMIN_EMAIL, 'Contact Form Message', $body)) {
            $success = true;
        } else {
            $errors[] = 'Unable to send your message. Please try again later.';
        }
    }
}

ob_start();
?>
<div class="container py-5">
    <h2 class="mb-4">Contact Us</h2>
    <?php if ($success): ?>
        <div class="alert alert-success">Thank you! Your message has been sent.</div>
    <?php else: ?>
        <?php if ($errors): ?><div class="alert alert-danger"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Message</label><textarea name="message" class="form-control" rows="5" required></textarea></div>
            <button type="submit" class="btn btn-primary-custom">Send Message</button>
        </form>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
$title = 'Contact Us';
require_once 'base.php';
?>
