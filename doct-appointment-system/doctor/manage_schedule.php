<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
require_once '../models.php';

if(!is_doctor()) {
    header('Location: ../login.php'); exit();
}
$errors = [];

// Handle add schedule
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day = sanitize($_POST['day_of_week']);
    $start = sanitize($_POST['start_time']);
    $end = sanitize($_POST['end_time']);
    $max = intval($_POST['max_patients']);

    if(!$day || !$start || !$end || !$max) {
        $errors[] = 'All fields are required.';
    }
    if(empty($errors)) {
        Schedule::create([
            'doctor_id' => $_SESSION['user_id'],
            'day_of_week' => $day,
            'start_time' => $start,
            'end_time' => $end,
            'max_patients' => $max
        ]);
        set_flash_message('success', 'Schedule added.');
        header('Location: manage_schedule.php'); exit();
    }
}

// Handle delete
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    Schedule::delete($id);
    set_flash_message('success', 'Schedule removed.');
    header('Location: manage_schedule.php'); exit();
}

// Fetch doctor schedules
$schedules = array_filter(Schedule::all(), fn($s) => $s['doctor_id'] == $_SESSION['user_id']);

ob_start();
?>
<div class="container py-5">
    <h2 class="mb-4">Manage Schedules</h2>
    <?php if($errors): ?><div class="alert alert-danger"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div><?php endif; ?>
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="day_of_week" class="form-select" required>
                <option value="">Day of Week</option>
                <?php foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $d) echo "<option value='$d'>$d</option>"; ?>
            </select>
        </div>
        <div class="col-md-2"><input type="time" name="start_time" class="form-control" required></div>
        <div class="col-md-2"><input type="time" name="end_time" class="form-control" required></div>
        <div class="col-md-2"><input type="number" name="max_patients" class="form-control" placeholder="Max Patients" required></div>
        <div class="col-md-3"><button type="submit" class="btn btn-primary-custom">Add Schedule</button></div>
    </form>
    <?php if($schedules): ?>
    <table class="table table-bordered">
        <thead><tr><th>Day</th><th>Start</th><th>End</th><th>Max</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach($schedules as $s): ?>
        <tr>
            <td><?php echo htmlspecialchars($s['day_of_week']); ?></td>
            <td><?php echo htmlspecialchars($s['start_time']); ?></td>
            <td><?php echo htmlspecialchars($s['end_time']); ?></td>
            <td><?php echo htmlspecialchars($s['max_patients']); ?></td>
            <td><a href="manage_schedule.php?delete=<?php echo $s['id']; ?>" class="btn btn-danger btn-sm">Delete</a></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?><p>No schedules set yet.</p><?php endif; ?>
</div>
<?php
$content = ob_get_clean();
$title = 'Manage Schedule - Doctor';
require_once '../base.php';
