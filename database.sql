CREATE DATABASE IF NOT EXISTS expensex;
USE expensex;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    profile_picture VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    icon VARCHAR(50) DEFAULT 'fa-circle',
    color VARCHAR(50) DEFAULT 'bg-slate-500',
    is_default BOOLEAN DEFAULT FALSE,
    user_id INT NULL, -- NULL if global category
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Budgets Table
CREATE TABLE IF NOT EXISTS budgets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    month TINYINT NOT NULL,
    year YEAR NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY budget_unique (user_id, category_id, month, year),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Transactions Table
CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    title VARCHAR(255) NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    transaction_date DATE NOT NULL,
    note TEXT,
    is_recurring BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Notifications / Activity Log Table combined
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('activity', 'alert') DEFAULT 'activity',
    title VARCHAR(100) NOT NULL,
    description TEXT,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert Default Categories (Global)
INSERT IGNORE INTO categories (name, type, icon, color, is_default) VALUES 
('Salary', 'income', 'fa-money-bill-wave', 'emerald', TRUE),
('Freelance', 'income', 'fa-laptop-code', 'blue', TRUE),
('Investment', 'income', 'fa-chart-line', 'indigo', TRUE),
('Food & Dining', 'expense', 'fa-utensils', 'orange', TRUE),
('Transportation', 'expense', 'fa-car', 'sky', TRUE),
('Shopping', 'expense', 'fa-shopping-bag', 'pink', TRUE),
('Housing & Bills', 'expense', 'fa-home', 'rose', TRUE),
('Entertainment', 'expense', 'fa-film', 'purple', TRUE),
('Health', 'expense', 'fa-heartbeat', 'red', TRUE);
