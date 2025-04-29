-- Create database
CREATE DATABASE IF NOT EXISTS doc_appointment;
USE doc_appointment;

-- Users table (for both doctors and patients)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('admin', 'doctor', 'patient') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Doctors table
CREATE TABLE doctors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    qualification VARCHAR(255) NOT NULL,
    experience_years INT NOT NULL,
    bio TEXT,
    consultation_fee DECIMAL(10,2) NOT NULL,
    profile_image VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Doctor schedules
CREATE TABLE schedules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    doctor_id INT NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    max_patients INT NOT NULL DEFAULT 20,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);

-- Patients table
CREATE TABLE patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    blood_group VARCHAR(5),
    medical_history TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Appointments table
CREATE TABLE appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    doctor_id INT NOT NULL,
    patient_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('Pending', 'Confirmed', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Pending',
    reason TEXT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);

-- Medical records table
CREATE TABLE medical_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    appointment_id INT NOT NULL,
    diagnosis TEXT NOT NULL,
    prescription TEXT NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
);

-- Insert admin user
INSERT INTO users (email, password, user_type) VALUES 
('admin@docappointment.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample specialties
CREATE TABLE specialties (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(50)
);

INSERT INTO specialties (name, description, icon) VALUES
('Cardiology', 'Deals with disorders of the heart and blood vessels', 'fa-heart'),
('Neurology', 'Deals with disorders of the nervous system', 'fa-brain'),
('Pediatrics', 'Medical care of infants, children, and adolescents', 'fa-child'),
('Orthopedics', 'Deals with conditions involving the musculoskeletal system', 'fa-bone'),
('Dermatology', 'Deals with the skin and its diseases', 'fa-allergies'),
('Ophthalmology', 'Deals with the anatomy and diseases of the eye', 'fa-eye'); 