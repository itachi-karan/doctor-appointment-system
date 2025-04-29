<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
require_once '../models.php';
global $db;

if(!is_patient()) {
    header('Location: ../login.php'); exit();
}
$errors = [];

// Fetch active schedules
$schedules = Schedule::all();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = intval($_POST['doctor_id']);
    $appointment_date = sanitize($_POST['appointment_date']);
    $appointment_time = sanitize($_POST['appointment_time']);
    $reason = sanitize($_POST['reason']);

    if(!$doctor_id || !$appointment_date || !$appointment_time || !$reason) {
        $errors[] = 'All fields are required.';
    }
    if(empty($errors)) {
        // Resolve patient profile ID
        $stmt = $db->prepare('SELECT id FROM patients WHERE user_id = :uid');
        $stmt->execute([':uid' => $_SESSION['user_id']]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);
        Appointment::create([
            'doctor_id' => $doctor_id,
            'patient_id' => $patient['id'],
            'appointment_date' => $appointment_date,
            'appointment_time' => $appointment_time,
            'reason' => $reason
        ]);
        // Send notification emails
        // Fetch doctor and patient emails
        $doctorRec = Doctor::find($doctor_id);
        $docUser = User::find($doctorRec['user_id']);
        $docEmail = $docUser['email'];
        $patientUser = User::find($_SESSION['user_id']);
        $patientEmail = $patientUser['email'];
        // Email to doctor
        $subjectDoc = 'New Appointment Request';
        $messageDoc = "You have a new appointment request from {$patientUser['email']} on {$appointment_date} at {$appointment_time}. Reason: {$reason}.";
        send_email($docEmail, $subjectDoc, $messageDoc);
        // Email to patient
        $subjectPat = 'Appointment Request Submitted';
        $messagePat = "Your appointment request with Dr. {$doctorRec['first_name']} {$doctorRec['last_name']} on {$appointment_date} at {$appointment_time} has been submitted.";
        send_email($patientEmail, $subjectPat, $messagePat);
        set_flash_message('success', 'Appointment request submitted.');
        header('Location: dashboard.php'); exit();
    }
}

ob_start();
?>
<div class="container py-5">
    <h2 class="mb-4">Book Appointment</h2>
    <?php if($errors): ?>
        <div class="alert alert-danger"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="doctor_id" class="form-label">Doctor</label>
            <select id="doctor_id" name="doctor_id" class="form-select" required>
                <option value="">Select doctor</option>
                <?php foreach($schedules as $sch):
                    $doc = Doctor::find($sch['doctor_id']); ?>
                    <option value="<?php echo $doc['id']; ?>">
                        Dr. <?php echo htmlspecialchars($doc['first_name'].' '.$doc['last_name']); ?>
                        (<?php echo htmlspecialchars($sch['day_of_week'].' '.$sch['start_time'].'-'.$sch['end_time']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="appointment_date" class="form-label">Date</label>
                <input type="date" id="appointment_date" name="appointment_date" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="appointment_time" class="form-label">Time</label>
                <input type="time" id="appointment_time" name="appointment_time" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="reason" class="form-label">Reason</label>
            <textarea id="reason" name="reason" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary-custom">Submit Request</button>
    </form>
</div>
<?php
$content = ob_get_clean();
$title = 'Book Appointment';
require_once '../base.php';
