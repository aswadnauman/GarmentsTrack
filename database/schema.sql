-- GarmentsTrack ERP Database Schema
-- Created: 2025-08-22

CREATE DATABASE IF NOT EXISTS garments_erp;
USE garments_erp;

-- Users table for authentication and role management
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    role ENUM('admin', 'manager', 'supervisor', 'worker') NOT NULL DEFAULT 'worker',
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Workers table for detailed worker information
CREATE TABLE workers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    employee_id VARCHAR(20) UNIQUE NOT NULL,
    department VARCHAR(50),
    position VARCHAR(50),
    hire_date DATE,
    hourly_rate DECIMAL(10,2),
    piece_rate DECIMAL(10,2),
    phone VARCHAR(15),
    address TEXT,
    emergency_contact VARCHAR(100),
    emergency_phone VARCHAR(15),
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Customers table
CREATE TABLE customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    company VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(15),
    address TEXT,
    city VARCHAR(50),
    state VARCHAR(50),
    postal_code VARCHAR(10),
    country VARCHAR(50) DEFAULT 'India',
    gst_number VARCHAR(20),
    credit_limit DECIMAL(12,2) DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Product categories
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products/Items master
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    sku VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    unit VARCHAR(20) DEFAULT 'pcs',
    cost_price DECIMAL(10,2),
    selling_price DECIMAL(10,2),
    min_stock_level INT DEFAULT 0,
    max_stock_level INT DEFAULT 0,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Product variants (colors, sizes, etc.)
CREATE TABLE product_variants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    variant_name VARCHAR(50) NOT NULL,
    variant_value VARCHAR(50) NOT NULL,
    additional_cost DECIMAL(10,2) DEFAULT 0,
    sku_suffix VARCHAR(20),
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_variant (product_id, variant_name, variant_value)
);

-- Inventory/Stock management
CREATE TABLE inventory (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    variant_id INT,
    location VARCHAR(50) DEFAULT 'Main Warehouse',
    quantity_on_hand INT DEFAULT 0,
    quantity_reserved INT DEFAULT 0,
    quantity_available INT GENERATED ALWAYS AS (quantity_on_hand - quantity_reserved) STORED,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE SET NULL,
    UNIQUE KEY unique_inventory (product_id, variant_id, location)
);

-- Orders table
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(20) UNIQUE NOT NULL,
    customer_id INT NOT NULL,
    order_date DATE NOT NULL,
    delivery_date DATE,
    status ENUM('pending', 'confirmed', 'in_production', 'completed', 'cancelled') DEFAULT 'pending',
    subtotal DECIMAL(12,2) DEFAULT 0,
    tax_amount DECIMAL(12,2) DEFAULT 0,
    discount_amount DECIMAL(12,2) DEFAULT 0,
    total_amount DECIMAL(12,2) DEFAULT 0,
    notes TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Order items
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    variant_id INT,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) GENERATED ALWAYS AS (quantity * unit_price) STORED,
    notes TEXT,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE SET NULL
);

-- Production orders
CREATE TABLE production_orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    production_number VARCHAR(20) UNIQUE NOT NULL,
    start_date DATE,
    target_completion_date DATE,
    actual_completion_date DATE,
    status ENUM('planned', 'in_progress', 'completed', 'cancelled') DEFAULT 'planned',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    notes TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Production tasks
CREATE TABLE production_tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    production_order_id INT NOT NULL,
    task_name VARCHAR(100) NOT NULL,
    description TEXT,
    assigned_worker_id INT,
    estimated_hours DECIMAL(5,2),
    actual_hours DECIMAL(5,2),
    piece_rate DECIMAL(10,2),
    quantity_target INT,
    quantity_completed INT DEFAULT 0,
    status ENUM('pending', 'in_progress', 'completed', 'on_hold') DEFAULT 'pending',
    start_time TIMESTAMP NULL,
    end_time TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (production_order_id) REFERENCES production_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_worker_id) REFERENCES workers(id) ON DELETE SET NULL
);

-- Worker time tracking
CREATE TABLE time_entries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    worker_id INT NOT NULL,
    task_id INT,
    clock_in TIMESTAMP NOT NULL,
    clock_out TIMESTAMP,
    break_minutes INT DEFAULT 0,
    total_hours DECIMAL(5,2) GENERATED ALWAYS AS (
        CASE 
            WHEN clock_out IS NOT NULL 
            THEN TIMESTAMPDIFF(MINUTE, clock_in, clock_out) / 60.0 - (break_minutes / 60.0)
            ELSE 0 
        END
    ) STORED,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (worker_id) REFERENCES workers(id) ON DELETE CASCADE,
    FOREIGN KEY (task_id) REFERENCES production_tasks(id) ON DELETE SET NULL
);

-- Payroll records
CREATE TABLE payroll (
    id INT PRIMARY KEY AUTO_INCREMENT,
    worker_id INT NOT NULL,
    pay_period_start DATE NOT NULL,
    pay_period_end DATE NOT NULL,
    regular_hours DECIMAL(5,2) DEFAULT 0,
    overtime_hours DECIMAL(5,2) DEFAULT 0,
    piece_work_amount DECIMAL(10,2) DEFAULT 0,
    gross_pay DECIMAL(10,2) DEFAULT 0,
    deductions DECIMAL(10,2) DEFAULT 0,
    net_pay DECIMAL(10,2) DEFAULT 0,
    status ENUM('draft', 'approved', 'paid') DEFAULT 'draft',
    paid_date DATE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (worker_id) REFERENCES workers(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Sample management
CREATE TABLE samples (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sample_code VARCHAR(20) UNIQUE NOT NULL,
    customer_id INT,
    product_name VARCHAR(100) NOT NULL,
    description TEXT,
    colors TEXT,
    sizes TEXT,
    fabric_details TEXT,
    estimated_cost DECIMAL(10,2),
    status ENUM('requested', 'in_development', 'completed', 'approved', 'rejected') DEFAULT 'requested',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Inventory movements/transactions
CREATE TABLE inventory_movements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    variant_id INT,
    location VARCHAR(50),
    movement_type ENUM('in', 'out', 'transfer', 'adjustment') NOT NULL,
    quantity INT NOT NULL,
    reference_type ENUM('purchase', 'sale', 'production', 'adjustment', 'transfer') NOT NULL,
    reference_id INT,
    notes TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, full_name, email, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin@garmentstrack.com', 'admin');

-- Insert sample categories
INSERT INTO categories (name, description) VALUES 
('Shirts', 'All types of shirts'),
('Pants', 'Trousers and pants'),
('Dresses', 'Ladies dresses'),
('Accessories', 'Buttons, zippers, threads etc.');

-- Insert sample customer
INSERT INTO customers (name, company, email, phone, address, city, state, gst_number) VALUES 
('John Doe', 'Fashion Hub Ltd', 'john@fashionhub.com', '+91-9876543210', '123 Fashion Street', 'Mumbai', 'Maharashtra', '27ABCDE1234F1Z5');

-- Create indexes for better performance
CREATE INDEX idx_orders_customer ON orders(customer_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_date ON orders(order_date);
CREATE INDEX idx_inventory_product ON inventory(product_id);
CREATE INDEX idx_production_tasks_worker ON production_tasks(assigned_worker_id);
CREATE INDEX idx_time_entries_worker ON time_entries(worker_id);
CREATE INDEX idx_payroll_worker ON payroll(worker_id);
