-- Cars24 Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS cars24_db;
USE cars24_db;

-- Users table for registration and login
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(15),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Cars table to store car information
CREATE TABLE cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    year INT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    features JSON,
    rating DECIMAL(3,2) DEFAULT 0.00,
    reviews_count INT DEFAULT 0,
    status ENUM('available', 'sold', 'reserved') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Purchases table to track car purchases
CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50),
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    delivery_address TEXT,
    delivery_date DATE,
    notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
);

-- Insert sample car data
INSERT INTO cars (name, brand, model, year, price, image, description, features, rating, reviews_count) VALUES
('Tesla Model S', 'Tesla', 'Model S', 2023, 1830000.00, 'assets/deals-1.png', 'Electric luxury sedan with autopilot', '{"seats": 4, "transmission": "Autopilot", "range": "400km", "fuel_type": "Electric"}', 4.5, 550),
('Tesla Model E', 'Tesla', 'Model E', 2023, 2530000.00, 'assets/deals-2.png', 'Premium electric vehicle', '{"seats": 4, "transmission": "Autopilot", "range": "400km", "fuel_type": "Electric"}', 4.4, 450),
('Tesla Model Y', 'Tesla', 'Model Y', 2023, 1530000.00, 'assets/deals-3.png', 'Electric SUV with advanced features', '{"seats": 4, "transmission": "Autopilot", "range": "400km", "fuel_type": "Electric"}', 4.5, 550),
('Mirage', 'Mitsubishi', 'Mirage', 2023, 530000.00, 'assets/deals-4.png', 'Compact and efficient', '{"seats": 4, "transmission": "Manual", "mileage": "18km/l", "fuel_type": "Diesel"}', 4.3, 350),
('Xpander', 'Mitsubishi', 'Xpander', 2023, 1000000.00, 'assets/deals-5.png', 'Spacious family vehicle', '{"seats": 4, "transmission": "Manual", "mileage": "18km/l", "fuel_type": "Diesel"}', 4.2, 250),
('Pajero Sports', 'Mitsubishi', 'Pajero Sports', 2023, 1500000.00, 'assets/deals-6.png', 'Rugged SUV for adventure', '{"seats": 4, "transmission": "Manual", "mileage": "18km/l", "fuel_type": "Diesel"}', 4.1, 150),
('Mazda CX5', 'Mazda', 'CX5', 2023, 800000.00, 'assets/deals-7.png', 'Stylish crossover SUV', '{"seats": 4, "transmission": "Manual", "mileage": "18km/l", "fuel_type": "Diesel"}', 4.0, 200),
('Mazda CX-30', 'Mazda', 'CX-30', 2023, 1230000.00, 'assets/deals-8.png', 'Compact luxury SUV', '{"seats": 4, "transmission": "Manual", "mileage": "18km/l", "fuel_type": "Diesel"}', 4.0, 100),
('Mazda CX-9', 'Mazda', 'CX-9', 2023, 1510000.00, 'assets/deals-9.png', 'Large family SUV', '{"seats": 4, "transmission": "Manual", "mileage": "18km/l", "fuel_type": "Diesel"}', 4.1, 180),
('Corolla', 'Toyota', 'Corolla', 2023, 1530000.00, 'assets/deals-10.png', 'Reliable sedan', '{"seats": 4, "transmission": "Manual", "mileage": "18km/l", "fuel_type": "Diesel"}', 4.2, 250),
('Innova', 'Toyota', 'Innova', 2023, 2050000.00, 'assets/deals-11.png', 'Premium MPV', '{"seats": 4, "transmission": "Manual", "mileage": "18km/l", "fuel_type": "Diesel"}', 4.5, 550),
('Fortuner', 'Toyota', 'Fortuner', 2023, 2530000.00, 'assets/deals-12.png', 'Luxury SUV', '{"seats": 4, "transmission": "Manual", "mileage": "18km/l", "fuel_type": "Diesel"}', 4.1, 180),
('Amaze', 'Honda', 'Amaze', 2023, 860000.00, 'assets/deals-13.png', 'Compact sedan', '{"seats": 4, "transmission": "Manual", "mileage": "18km/l", "fuel_type": "Diesel"}', 4.0, 200),
('Elevate', 'Honda', 'Elevate', 2023, 900000.00, 'assets/deals-14.png', 'Modern SUV', '{"seats": 4, "transmission": "Manual", "mileage": "18km/l", "fuel_type": "Diesel"}', 4.3, 350),
('City', 'Honda', 'City', 2023, 1000000.00, 'assets/deals-15.png', 'Premium sedan', '{"seats": 4, "transmission": "Manual", "mileage": "18km/l", "fuel_type": "Diesel"}', 4.3, 300);

-- Create indexes for better performance
CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_user_username ON users(username);
CREATE INDEX idx_car_brand ON cars(brand);
CREATE INDEX idx_car_status ON cars(status);
CREATE INDEX idx_purchase_user ON purchases(user_id);
CREATE INDEX idx_purchase_car ON purchases(car_id);
CREATE INDEX idx_purchase_date ON purchases(purchase_date); 