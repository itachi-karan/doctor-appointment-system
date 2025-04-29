<?php
session_start();
require_once '../config.php';
require_once '../functions.php';
require_once '../models.php';

if($_SESSION['user_type']!=='admin'){header('Location: ../login.php');exit();}
$patients = Patient::all();
ob_start();
?>
<div class="container py-5">
    <h2>Patients</h2>
    <?php if($patients): ?>
    <table class="table table-striped">
        <thead><tr><th>Name</th><th>Email</th><th>DOB</th><th>Gender</th><th>Phone</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach($patients as $p): $u=User::find($p['user_id']); ?>
        <tr>
            <td><?php echo htmlspecialchars($p['first_name'].' '.$p['last_name']); ?></td>
            <td><?php echo htmlspecialchars($u['email']); ?></td>
            <td><?php echo htmlspecialchars($p['date_of_birth']); ?></td>
            <td><?php echo htmlspecialchars($p['gender']); ?></td>
            <td><?php echo htmlspecialchars($p['phone']); ?></td>
            <td><a href="view_patients.php?delete=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm">Delete</a></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?><p>No patients found.</p><?php endif; ?>
</div>
<?php
// handle delete
if(isset($_GET['delete'])){Patient::delete(intval($_GET['delete']));User::delete(intval($patients[$_GET['delete']]['user_id']));set_flash_message('success','Patient removed');header('Location:view_patients.php');}
$content=ob_get_clean();$title='View Patients';require_once '../base.php';
