-- =====================================
-- Database: pharmacloud_new
-- =====================================
CREATE DATABASE IF NOT EXISTS pharmacloud;
USE pharmacloud_new;

-- =====================================
-- Table: users
-- =====================================
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('Admin','Pharmacist','Customer') NOT NULL,
    is_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample Users
INSERT INTO users (username, password, full_name, email, role, is_verified) VALUES
('admin', '$2y$10$dummyhashedpassword', 'Admin User', 'admin@example.com', 'Admin', 1),
('pharma1', '$2y$10$dummyhashedpassword', 'Pharmacist One', 'pharma1@example.com', 'Pharmacist', 1),
('customer1', '$2y$10$dummyhashedpassword', 'Arshani Muthumali', 'customer1@example.com', 'Customer', 1);

-- =====================================
-- Table: medicines
-- =====================================
CREATE TABLE IF NOT EXISTS medicines (
    medicine_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    brand_name VARCHAR(100),
    description TEXT,
    unit_price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    expiry_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample Medicines
INSERT INTO medicines (name, brand_name, description, unit_price, quantity, expiry_date) VALUES
('Paracetamol', 'Panadol', 'Pain reliever', 4.39, 1272, '2025-12-31'),
('Amoxicillin', 'Ranoxyl', 'Antibiotic', 2.48, 29, '2025-11-15'),
('Ibuprofen', 'Ibut', 'Anti-inflammatory', 3.98, 59, '2025-10-20'),
('Salbutamol', 'Asthalin', 'Bronchodilator', 295, 26, '2026-01-15'),
('Cetirizine', 'Zyncet syrup', 'Antihistamine', 651, 59, '2025-09-30');

-- =====================================
-- Table: orders
-- =====================================
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    medicine_id INT NOT NULL,
    quantity INT NOT NULL,
    status ENUM('Delivered','Pending','Cancelled') DEFAULT 'Pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
);

-- Sample Orders
INSERT INTO orders (user_id, medicine_id, quantity, status) VALUES
(3, 1, 10, 'Delivered'),
(3, 2, 5, 'Pending'),
(3, 3, 2, 'Delivered');

-- =====================================
-- Table: health_tips
-- =====================================
CREATE TABLE IF NOT EXISTS health_tips (
    tip_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    background_color VARCHAR(20) DEFAULT '#ffffff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample Health Tips
INSERT INTO health_tips (title, description, background_color) VALUES
('💧 Stay Hydrated', 'Drink at least 8 glasses of water daily to maintain optimal hydration.', '#d0f0fd'),
('💊 Take Medicines on Time', 'Follow your prescribed schedule and never skip medicines.', '#fff3cd'),
('🥦 Eat Healthy', 'Include fruits and vegetables in your diet daily for balanced nutrition.', '#d4edda');

-- =====================================
-- Table: stock_alerts (optional)
-- =====================================
CREATE TABLE IF NOT EXISTS stock_alerts (
    alert_id INT AUTO_INCREMENT PRIMARY KEY,
    medicine_id INT NOT NULL,
    alert_type ENUM('Low Stock','Nearly Expired') NOT NULL,
    alert_message VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
);

-- Sample Stock Alerts
INSERT INTO stock_alerts (medicine_id, alert_type, alert_message) VALUES
(2, 'Low Stock', 'Amoxicillin stock is very low!'),
(3, 'Nearly Expired', 'Ibuprofen is expiring within 30 days.');

