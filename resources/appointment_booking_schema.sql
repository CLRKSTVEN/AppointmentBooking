-- Quick schema to get the Appointment Booking template running.
-- Run in MySQL, then update application/config/database.php to point to this DB.

CREATE DATABASE IF NOT EXISTS appointment_booking
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;
USE appointment_booking;

-- Offices an employee can belong to
CREATE TABLE IF NOT EXISTS offices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  abbreviation VARCHAR(50),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Address lookup (province > city > barangay)
CREATE TABLE IF NOT EXISTS address (
  AddID INT AUTO_INCREMENT PRIMARY KEY,
  Province VARCHAR(100) NOT NULL,
  City VARCHAR(100) NOT NULL,
  Brgy VARCHAR(150) NOT NULL
) ENGINE=InnoDB;

-- Staff directory (basic fields to satisfy login/registration flow)
CREATE TABLE IF NOT EXISTS staff (
  staff_id INT AUTO_INCREMENT PRIMARY KEY,
  staff_code VARCHAR(64) UNIQUE,
  first_name VARCHAR(80) NOT NULL,
  middle_name VARCHAR(80),
  last_name VARCHAR(80) NOT NULL,
  suffix VARCHAR(20),
  position_title VARCHAR(120),
  office_id INT,
  address_id INT,
  photo VARCHAR(255),
  short_bio TEXT,
  is_active TINYINT(1) DEFAULT 1,
  is_public TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (office_id) REFERENCES offices(id) ON DELETE SET NULL,
  FOREIGN KEY (address_id) REFERENCES address(AddID) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Application users mapped to staff
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  staff_id INT,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','staff') DEFAULT 'staff',
  status TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (staff_id) REFERENCES staff(staff_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Accomplishments / appointment log entries
CREATE TABLE IF NOT EXISTS accomplishments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  staff_id INT NOT NULL,
  title VARCHAR(180) NOT NULL,
  category VARCHAR(120),
  location VARCHAR(150),
  description TEXT,
  start_date DATE,
  end_date DATE,
  is_public TINYINT(1) DEFAULT 1,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (staff_id) REFERENCES staff(staff_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Seed minimal data (change password_hash to your own hash if desired)
INSERT IGNORE INTO offices (id, name, abbreviation) VALUES
  (1, 'Head Office', 'HQ'),
  (2, 'Support Center', 'SUP');

INSERT IGNORE INTO address (AddID, Province, City, Brgy) VALUES
  (1, 'Sample Province', 'Sample City', 'Barangay Uno'),
  (2, 'Sample Province', 'Sample City', 'Barangay Dos');

INSERT IGNORE INTO staff (staff_id, staff_code, first_name, last_name, position_title, office_id, address_id)
VALUES (1, 'ADM-001', 'System', 'Administrator', 'Admin', 1, 1);

-- Password for admin user below is: admin123
INSERT IGNORE INTO users (id, staff_id, username, password_hash, role, status)
VALUES (1, 1, 'admin', '$2y$12$mT9QvbukCbZUKB.GV5PVL.Yy6EnezimGxCqtg1PppRSU/9uUnvESO', 'admin', 1);
