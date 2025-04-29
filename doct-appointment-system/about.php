<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

ob_start();
?>
<div class="container py-5">
    <h2 class="mb-4">About Us</h2>
    <p>Welcome to Doc Appointment System, your one-stop solution for managing healthcare appointments efficiently.</p>
    <p>Our mission is to connect patients with qualified doctors, streamline booking processes, and ensure seamless communication between all parties.</p>
    <p>With our platform, you can:</p>
    <ul>
        <li>Browse and find doctors by specialty.</li>
        <li>Book, reschedule, or cancel appointments easily.</li>
        <li>Manage medical records and treatment history.</li>
        <li>Receive timely reminders and notifications.</li>
    </ul>
    <p>Join us in improving healthcare accessibility and patient satisfaction.</p>
</div>
<?php
$content = ob_get_clean();
$title = 'About Us';
require_once 'base.php';
?>
