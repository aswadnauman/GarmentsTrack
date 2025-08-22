-- Demo Data for GarmentsTrack ERP
-- This file adds sample data for demonstration purposes

USE garments_erp;

-- Insert additional customers
INSERT INTO customers (name, company, email, phone, address, city, state, gst_number) VALUES 
('Rajesh Kumar', 'Fashion Forward Pvt Ltd', 'rajesh@fashionforward.com', '+91-9876543211', '456 Style Avenue', 'Delhi', 'Delhi', '07ABCDE1234F2Z6'),
('Priya Sharma', 'Trendy Threads', 'priya@trendythreads.com', '+91-9876543212', '789 Design Street', 'Bangalore', 'Karnataka', '29ABCDE1234F3Z7'),
('Amit Patel', 'Global Garments Ltd', 'amit@globalgarments.com', '+91-9876543213', '321 Export Road', 'Ahmedabad', 'Gujarat', '24ABCDE1234F4Z8'),
('Sunita Gupta', 'Elite Fashion House', 'sunita@elitefashion.com', '+91-9876543214', '654 Premium Plaza', 'Pune', 'Maharashtra', '27ABCDE1234F5Z9'),
('Vikram Singh', 'Royal Textiles', 'vikram@royaltextiles.com', '+91-9876543215', '987 Heritage Lane', 'Jaipur', 'Rajasthan', '08ABCDE1234F6Z0'),
('Meera Joshi', 'Modern Apparel Co', 'meera@modernapparel.com', '+91-9876543216', '147 Innovation Hub', 'Chennai', 'Tamil Nadu', '33ABCDE1234F7Z1'),
('Arjun Reddy', 'Stylish Solutions', 'arjun@stylishsolutions.com', '+91-9876543217', '258 Creative Corner', 'Hyderabad', 'Telangana', '36ABCDE1234F8Z2'),
('Kavita Mehta', 'Designer Dreams', 'kavita@designerdreams.com', '+91-9876543218', '369 Artistic Avenue', 'Kolkata', 'West Bengal', '19ABCDE1234F9Z3');

-- Insert sample products
INSERT INTO products (category_id, sku, name, description, cost_price, selling_price, min_stock_level, max_stock_level) VALUES 
(1, 'SHT-001', 'Cotton Casual Shirt', 'Comfortable cotton shirt for daily wear', 250.00, 450.00, 50, 500),
(1, 'SHT-002', 'Formal White Shirt', 'Professional white shirt for office wear', 300.00, 550.00, 30, 300),
(1, 'SHT-003', 'Printed T-Shirt', 'Trendy printed t-shirt for casual occasions', 150.00, 299.00, 100, 1000),
(2, 'PNT-001', 'Denim Jeans', 'Classic blue denim jeans', 400.00, 799.00, 25, 250),
(2, 'PNT-002', 'Formal Trousers', 'Black formal trousers for office', 350.00, 699.00, 40, 400),
(3, 'DRS-001', 'Summer Dress', 'Light and breezy summer dress', 500.00, 999.00, 20, 200),
(3, 'DRS-002', 'Party Dress', 'Elegant dress for special occasions', 800.00, 1599.00, 15, 150),
(4, 'ACC-001', 'Plastic Buttons', 'Standard plastic buttons - pack of 100', 50.00, 99.00, 500, 5000),
(4, 'ACC-002', 'Metal Zippers', 'Heavy duty metal zippers - 12 inch', 25.00, 49.00, 200, 2000),
(4, 'ACC-003', 'Cotton Thread', 'High quality cotton thread - 1000m spool', 75.00, 149.00, 100, 1000);

-- Insert product variants
INSERT INTO product_variants (product_id, variant_name, variant_value, sku_suffix) VALUES 
-- Shirt sizes
(1, 'Size', 'S', 'S'), (1, 'Size', 'M', 'M'), (1, 'Size', 'L', 'L'), (1, 'Size', 'XL', 'XL'),
(2, 'Size', 'S', 'S'), (2, 'Size', 'M', 'M'), (2, 'Size', 'L', 'L'), (2, 'Size', 'XL', 'XL'),
(3, 'Size', 'S', 'S'), (3, 'Size', 'M', 'M'), (3, 'Size', 'L', 'L'), (3, 'Size', 'XL', 'XL'),
-- Pants sizes
(4, 'Size', '30', '30'), (4, 'Size', '32', '32'), (4, 'Size', '34', '34'), (4, 'Size', '36', '36'),
(5, 'Size', '30', '30'), (5, 'Size', '32', '32'), (5, 'Size', '34', '34'), (5, 'Size', '36', '36'),
-- Dress sizes
(6, 'Size', 'S', 'S'), (6, 'Size', 'M', 'M'), (6, 'Size', 'L', 'L'),
(7, 'Size', 'S', 'S'), (7, 'Size', 'M', 'M'), (7, 'Size', 'L', 'L'),
-- Colors for shirts
(1, 'Color', 'Blue', 'BLU'), (1, 'Color', 'White', 'WHT'), (1, 'Color', 'Black', 'BLK'),
(2, 'Color', 'White', 'WHT'), (2, 'Color', 'Light Blue', 'LBL'),
(3, 'Color', 'Red', 'RED'), (3, 'Color', 'Green', 'GRN'), (3, 'Color', 'Yellow', 'YEL');

-- Insert inventory for products
INSERT INTO inventory (product_id, variant_id, quantity_on_hand, quantity_reserved) VALUES 
-- Cotton Casual Shirts
(1, 1, 45, 5), (1, 2, 60, 10), (1, 3, 40, 8), (1, 4, 25, 3),
-- Formal White Shirts  
(2, 5, 35, 5), (2, 6, 50, 12), (2, 7, 30, 7), (2, 8, 20, 2),
-- Printed T-Shirts
(3, 9, 120, 20), (3, 10, 150, 25), (3, 11, 100, 15), (3, 12, 80, 10),
-- Denim Jeans
(4, 13, 30, 5), (4, 14, 40, 8), (4, 15, 35, 6), (4, 16, 25, 4),
-- Formal Trousers
(5, 17, 45, 7), (5, 18, 55, 10), (5, 19, 40, 8), (5, 20, 30, 5),
-- Summer Dress
(6, 21, 25, 3), (6, 22, 30, 5), (6, 23, 20, 2),
-- Party Dress
(7, 24, 18, 2), (7, 25, 22, 4), (7, 26, 15, 1),
-- Accessories (no variants)
(8, NULL, 2500, 100), (9, NULL, 800, 50), (10, NULL, 450, 25);

-- Insert sample workers
INSERT INTO workers (employee_id, department, position, hire_date, hourly_rate, piece_rate, phone, address) VALUES 
('EMP001', 'Cutting', 'Senior Cutter', '2023-01-15', 180.00, 25.00, '+91-9876543301', '123 Worker Colony, Mumbai'),
('EMP002', 'Sewing', 'Machine Operator', '2023-02-20', 160.00, 20.00, '+91-9876543302', '456 Textile Area, Mumbai'),
('EMP003', 'Sewing', 'Senior Tailor', '2022-11-10', 200.00, 30.00, '+91-9876543303', '789 Garment Street, Mumbai'),
('EMP004', 'Finishing', 'Quality Inspector', '2023-03-05', 170.00, 22.00, '+91-9876543304', '321 Industrial Zone, Mumbai'),
('EMP005', 'Cutting', 'Pattern Maker', '2022-12-01', 190.00, 28.00, '+91-9876543305', '654 Factory Road, Mumbai'),
('EMP006', 'Sewing', 'Machine Operator', '2023-04-12', 155.00, 18.00, '+91-9876543306', '987 Workers Nagar, Mumbai'),
('EMP007', 'Packaging', 'Packing Supervisor', '2023-01-30', 165.00, 20.00, '+91-9876543307', '147 Export Hub, Mumbai'),
('EMP008', 'Quality Control', 'QC Inspector', '2023-02-15', 175.00, 24.00, '+91-9876543308', '258 Quality Lane, Mumbai');

-- Insert sample orders (minimum 10 as requested)
INSERT INTO orders (order_number, customer_id, order_date, delivery_date, status, subtotal, tax_amount, total_amount, notes, created_by) VALUES 
('ORD-20250101-001', 1, '2025-01-15', '2025-02-15', 'completed', 15000.00, 2700.00, 17700.00, 'Bulk order for retail chain', 1),
('ORD-20250102-002', 2, '2025-01-18', '2025-02-20', 'in_production', 8500.00, 1530.00, 10030.00, 'Corporate uniform order', 1),
('ORD-20250103-003', 3, '2025-01-22', '2025-02-25', 'confirmed', 12000.00, 2160.00, 14160.00, 'Summer collection pre-order', 1),
('ORD-20250104-004', 4, '2025-01-25', '2025-03-01', 'pending', 6500.00, 1170.00, 7670.00, 'Premium dress collection', 1),
('ORD-20250105-005', 5, '2025-01-28', '2025-02-28', 'confirmed', 9800.00, 1764.00, 11564.00, 'Traditional wear order', 1),
('ORD-20250106-006', 6, '2025-02-01', '2025-03-05', 'in_production', 7200.00, 1296.00, 8496.00, 'Casual wear collection', 1),
('ORD-20250107-007', 7, '2025-02-05', '2025-03-10', 'pending', 11500.00, 2070.00, 13570.00, 'Export order - urgent', 1),
('ORD-20250108-008', 8, '2025-02-08', '2025-03-12', 'confirmed', 5800.00, 1044.00, 6844.00, 'Small batch custom order', 1),
('ORD-20250109-009', 9, '2025-02-10', '2025-03-15', 'pending', 13200.00, 2376.00, 15576.00, 'Wedding collection order', 1),
('ORD-20250110-010', 1, '2025-02-12', '2025-03-18', 'confirmed', 8900.00, 1602.00, 10502.00, 'Repeat order from Fashion Hub', 1),
('ORD-20250111-011', 2, '2025-02-15', '2025-03-20', 'pending', 7600.00, 1368.00, 8968.00, 'Office wear collection', 1),
('ORD-20250112-012', 3, '2025-02-18', '2025-03-25', 'confirmed', 10400.00, 1872.00, 12272.00, 'Trendy youth collection', 1);

-- Insert order items for the orders
INSERT INTO order_items (order_id, product_id, variant_id, quantity, unit_price) VALUES 
-- Order 1 items
(1, 1, 2, 50, 450.00), (1, 2, 6, 30, 550.00), (1, 3, 10, 40, 299.00),
-- Order 2 items  
(2, 2, 5, 25, 550.00), (2, 5, 18, 20, 699.00),
-- Order 3 items
(3, 3, 9, 60, 299.00), (3, 6, 22, 15, 999.00),
-- Order 4 items
(4, 7, 25, 8, 1599.00),
-- Order 5 items
(5, 1, 1, 35, 450.00), (5, 4, 14, 15, 799.00),
-- Order 6 items
(6, 3, 11, 45, 299.00), (6, 4, 13, 10, 799.00),
-- Order 7 items
(7, 2, 7, 40, 550.00), (7, 5, 19, 25, 699.00),
-- Order 8 items
(8, 6, 21, 12, 999.00),
-- Order 9 items
(9, 7, 24, 15, 1599.00), (9, 6, 23, 8, 999.00),
-- Order 10 items
(10, 1, 3, 30, 450.00), (10, 3, 12, 25, 299.00),
-- Order 11 items
(11, 2, 8, 20, 550.00), (11, 5, 17, 18, 699.00),
-- Order 12 items
(12, 3, 9, 55, 299.00), (12, 4, 15, 12, 799.00);

-- Insert production orders for some of the orders
INSERT INTO production_orders (order_id, production_number, start_date, target_completion_date, status, priority, created_by) VALUES 
(1, 'PROD-2025-001', '2025-01-16', '2025-02-10', 'completed', 'medium', 1),
(2, 'PROD-2025-002', '2025-01-20', '2025-02-15', 'in_progress', 'high', 1),
(3, 'PROD-2025-003', '2025-01-25', '2025-02-20', 'planned', 'medium', 1),
(6, 'PROD-2025-004', '2025-02-02', '2025-03-01', 'in_progress', 'low', 1),
(7, 'PROD-2025-005', '2025-02-06', '2025-03-05', 'planned', 'urgent', 1);

-- Insert production tasks
INSERT INTO production_tasks (production_order_id, task_name, description, assigned_worker_id, estimated_hours, piece_rate, quantity_target, quantity_completed, status) VALUES 
-- Tasks for PROD-2025-001 (completed)
(1, 'Cutting', 'Cut fabric pieces for shirts', 1, 8.0, 25.00, 120, 120, 'completed'),
(1, 'Sewing', 'Sew shirt pieces together', 2, 15.0, 20.00, 120, 120, 'completed'),
(1, 'Finishing', 'Final finishing and quality check', 4, 6.0, 22.00, 120, 120, 'completed'),
-- Tasks for PROD-2025-002 (in progress)
(2, 'Pattern Making', 'Create patterns for uniforms', 5, 4.0, 28.00, 45, 45, 'completed'),
(2, 'Cutting', 'Cut uniform pieces', 1, 6.0, 25.00, 45, 30, 'in_progress'),
(2, 'Sewing', 'Sew uniform pieces', 3, 12.0, 30.00, 45, 15, 'pending'),
-- Tasks for PROD-2025-003 (planned)
(3, 'Design Review', 'Review summer collection designs', 8, 2.0, 24.00, 75, 0, 'pending'),
(3, 'Cutting', 'Cut summer collection pieces', 1, 10.0, 25.00, 75, 0, 'pending'),
-- Tasks for PROD-2025-004 (in progress)
(4, 'Cutting', 'Cut casual wear pieces', 5, 8.0, 28.00, 55, 40, 'in_progress'),
(4, 'Sewing', 'Sew casual pieces', 6, 14.0, 18.00, 55, 20, 'in_progress');

-- Insert time entries for workers
INSERT INTO time_entries (worker_id, task_id, clock_in, clock_out, break_minutes) VALUES 
-- Recent time entries for active workers
(1, 1, '2025-02-20 08:00:00', '2025-02-20 17:00:00', 60),
(1, 2, '2025-02-21 08:00:00', '2025-02-21 16:30:00', 45),
(2, 2, '2025-02-20 08:30:00', '2025-02-20 17:30:00', 60),
(2, 6, '2025-02-21 08:00:00', '2025-02-21 17:00:00', 60),
(3, 6, '2025-02-20 09:00:00', '2025-02-20 18:00:00', 60),
(4, 3, '2025-02-19 08:00:00', '2025-02-19 16:00:00', 45),
(5, 4, '2025-02-18 08:00:00', '2025-02-18 17:00:00', 60),
(5, 9, '2025-02-21 08:00:00', '2025-02-21 16:00:00', 45),
(6, 10, '2025-02-20 08:30:00', '2025-02-20 17:30:00', 60),
(8, 7, '2025-02-19 09:00:00', '2025-02-19 17:00:00', 60);

-- Insert sample data
INSERT INTO samples (sample_code, customer_id, product_name, description, colors, sizes, fabric_details, estimated_cost, status, created_by) VALUES 
('SMPL-001', 1, 'Premium Cotton Shirt', 'High-quality cotton shirt with custom embroidery', 'Navy Blue, White, Light Gray', 'S, M, L, XL', '100% Premium Cotton, 180 GSM', 650.00, 'approved', 1),
('SMPL-002', 2, 'Corporate Blazer', 'Formal blazer for corporate uniforms', 'Charcoal Gray, Navy Blue', 'M, L, XL, XXL', 'Wool Blend, 250 GSM', 1200.00, 'in_development', 1),
('SMPL-003', 3, 'Summer Casual Dress', 'Light and comfortable summer dress', 'Floral Print, Solid Colors', 'S, M, L', 'Cotton Voile, 120 GSM', 850.00, 'completed', 1),
('SMPL-004', 4, 'Designer Evening Gown', 'Elegant evening gown for special occasions', 'Black, Burgundy, Emerald', 'S, M, L', 'Silk Crepe, 200 GSM', 2500.00, 'requested', 1),
('SMPL-005', 5, 'Traditional Kurta', 'Traditional Indian kurta with modern fit', 'White, Cream, Light Blue', 'M, L, XL', 'Cotton Khadi, 160 GSM', 750.00, 'approved', 1);

-- Insert payroll records for demonstration
INSERT INTO payroll (worker_id, pay_period_start, pay_period_end, regular_hours, overtime_hours, piece_work_amount, gross_pay, deductions, net_pay, status, created_by) VALUES 
(1, '2025-02-01', '2025-02-15', 120.0, 8.0, 1250.00, 23090.00, 2309.00, 20781.00, 'paid', 1),
(2, '2025-02-01', '2025-02-15', 115.0, 5.0, 980.00, 19580.00, 1958.00, 17622.00, 'paid', 1),
(3, '2025-02-01', '2025-02-15', 118.0, 6.0, 1440.00, 26040.00, 2604.00, 23436.00, 'approved', 1),
(4, '2025-02-01', '2025-02-15', 110.0, 4.0, 1100.00, 20580.00, 2058.00, 18522.00, 'paid', 1),
(5, '2025-02-01', '2025-02-15', 125.0, 10.0, 1680.00, 27430.00, 2743.00, 24687.00, 'approved', 1),
(6, '2025-02-01', '2025-02-15', 112.0, 3.0, 720.00, 18085.00, 1809.00, 16276.00, 'draft', 1),
(7, '2025-02-01', '2025-02-15', 108.0, 2.0, 800.00, 18630.00, 1863.00, 16767.00, 'paid', 1),
(8, '2025-02-01', '2025-02-15', 114.0, 6.0, 960.00, 21410.00, 2141.00, 19269.00, 'approved', 1);

-- Insert inventory movements for tracking
INSERT INTO inventory_movements (product_id, variant_id, location, movement_type, quantity, reference_type, reference_id, notes, created_by) VALUES 
(1, 2, 'Main Warehouse', 'out', 50, 'sale', 1, 'Order ORD-20250101-001', 1),
(2, 6, 'Main Warehouse', 'out', 30, 'sale', 1, 'Order ORD-20250101-001', 1),
(3, 10, 'Main Warehouse', 'out', 40, 'sale', 1, 'Order ORD-20250101-001', 1),
(2, 5, 'Main Warehouse', 'out', 25, 'sale', 2, 'Order ORD-20250102-002', 1),
(5, 18, 'Main Warehouse', 'out', 20, 'sale', 2, 'Order ORD-20250102-002', 1),
(8, NULL, 'Main Warehouse', 'in', 1000, 'purchase', NULL, 'Stock replenishment', 1),
(9, NULL, 'Main Warehouse', 'in', 500, 'purchase', NULL, 'Stock replenishment', 1),
(10, NULL, 'Main Warehouse', 'in', 200, 'purchase', NULL, 'Stock replenishment', 1);
