<?php
// Seeder for importing Edoc sample data into current schema
require_once 'config.php';
require_once 'models.php';

// Disable foreign key checks, truncate tables
$db->exec('SET FOREIGN_KEY_CHECKS=0');
foreach (['appointments','schedules','patients','doctors','users'] as $tbl) {
    $db->exec("TRUNCATE TABLE $tbl");
}
$db->exec('SET FOREIGN_KEY_CHECKS=1');

// Helper to create user
function create_user($email, $password, $type) {
    return User::create([
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'user_type' => $type
    ]);
}

// 1. Admin user
$adminId = create_user('admin@gmail.com', '123', 'admin');

// 2. Doctor user and profile
$docUserId = create_user('doctor@gmail.com', '123', 'doctor');
$docProfileId = Doctor::create([
    'user_id' => $docUserId,
    'first_name' => 'Test',
    'last_name' => 'Doctor',
    'specialty' => 'Cardiology',
    'qualification' => 'MBBS',
    'experience_years' => 5,
    'bio' => 'Experienced cardiologist.',
    'consultation_fee' => 100.00
]);

// 3. Patient user and profile
$patUserId = create_user('patient@gmail.com', '123', 'patient');
Patient::create([
    'user_id' => $patUserId,
    'first_name' => 'Test',
    'last_name' => 'Patient',
    'date_of_birth' => '2000-01-01',
    'gender' => 'Female',
    'phone' => '0123456789',
    'address' => '123 Main St'
]);

// 4. Sample schedule for doctor
$schedules = [
    ['day_of_week'=>'Monday','start_time'=>'09:00:00','end_time'=>'12:00:00','max_patients'=>10],
    ['day_of_week'=>'Wednesday','start_time'=>'14:00:00','end_time'=>'18:00:00','max_patients'=>8]
];
foreach ($schedules as $sch) {
    Schedule::create(array_merge(['doctor_id' => $docProfileId], $sch));
}

// 5. Sample appointment for patient
Appointment::create([
    'doctor_id' => $docProfileId,
    'patient_id' => Patient::all()[0]['id'],
    'appointment_date' => date('Y-m-d', strtotime('+1 day')),
    'appointment_time' => '10:00:00',
    'status' => 'Pending',
    'reason' => 'General Checkup'
]);

echo "Edoc sample data seeded successfully.\n";
