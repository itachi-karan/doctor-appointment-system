<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
require_once '../models.php';
if($_SESSION['user_type']!=='admin'){header('Location: ../login.php');exit();}
$errors=[];
// Handle add session
if($_SERVER['REQUEST_METHOD']==='POST'){
    $doctor_id=intval($_POST['doctor_id']);
    $day=sanitize($_POST['day_of_week']);
    $start=sanitize($_POST['start_time']);
    $end=sanitize($_POST['end_time']);
    $max=intval($_POST['max_patients']);
    if(!$doctor_id||!$day||!$start||!$end||!$max){$errors[]='All fields required.';} else {
        Schedule::create([
            'doctor_id'=>$doctor_id,'day_of_week'=>$day,
            'start_time'=>$start,'end_time'=>$end,'max_patients'=>$max
        ]);
        set_flash_message('success','Session added.');
        header('Location: manage_sessions.php');exit();
    }
}
// Handle delete
if(isset($_GET['delete'])){
    Schedule::delete(intval($_GET['delete']));
    set_flash_message('success','Session removed.');
    header('Location: manage_sessions.php');exit();
}
$doctors=Doctor::all();
$sessions=Schedule::all();
ob_start();
?>
<div class="container py-5">
    <h2>Manage Sessions</h2>
    <?php if($errors): ?>
    <div class="alert alert-danger"><ul><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div>
    <?php endif; ?>
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="doctor_id" class="form-select" required>
                <option value="">Select Doctor</option>
                <?php foreach($doctors as $d): ?>
                <option value="<?php echo $d['id']; ?>"><?php echo 'Dr. '.htmlspecialchars($d['first_name'].' '.$d['last_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="day_of_week" class="form-select" required>
                <option value="">Day</option>
                <?php foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day) echo "<option>$day</option>"; ?>
            </select>
        </div>
        <div class="col-md-2"><input type="time" name="start_time" class="form-control" required></div>
        <div class="col-md-2"><input type="time" name="end_time" class="form-control" required></div>
        <div class="col-md-1"><input type="number" name="max_patients" class="form-control" placeholder="Max" required></div>
        <div class="col-md-2"><button type="submit" class="btn btn-primary-custom">Add Session</button></div>
    </form>
    <?php if($sessions): ?>
    <table class="table table-bordered">
        <thead><tr><th>Doctor</th><th>Day</th><th>Start</th><th>End</th><th>Max</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach($sessions as $s): $doc=Doctor::find($s['doctor_id']); ?>
        <tr>
            <td><?php echo 'Dr. '.htmlspecialchars($doc['first_name'].' '.$doc['last_name']); ?></td>
            <td><?php echo htmlspecialchars($s['day_of_week']); ?></td>
            <td><?php echo htmlspecialchars($s['start_time']); ?></td>
            <td><?php echo htmlspecialchars($s['end_time']); ?></td>
            <td><?php echo htmlspecialchars($s['max_patients']); ?></td>
            <td><a href="manage_sessions.php?delete=<?php echo $s['id']; ?>" class="btn btn-danger btn-sm">Delete</a></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?><p>No sessions available.</p><?php endif; ?>
</div>
<?php
$content=ob_get_clean();$title='Manage Sessions';require_once '../base.php';
?>
