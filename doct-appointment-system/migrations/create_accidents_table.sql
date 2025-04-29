-- Migration: create accidents table
CREATE TABLE `accidents` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `victim_name` VARCHAR(100) NOT NULL,
  `location` TEXT NOT NULL,
  `severity` VARCHAR(50) NOT NULL,
  `reported_time` DATETIME NOT NULL,
  `assigned_doctor` INT DEFAULT NULL,
  `status` VARCHAR(50) DEFAULT 'pending',
  FOREIGN KEY (`assigned_doctor`) REFERENCES `doctors`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
