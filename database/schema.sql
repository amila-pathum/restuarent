-- Grand Restaurant Database Schema (SQLite)
-- This schema creates the database structure for the Grand Restaurant management system

-- Initialization marker to track database setup
CREATE TABLE IF NOT EXISTS initialization_marker (
    initialized_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Menu Items Table
CREATE TABLE IF NOT EXISTS menu_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reservations Table
CREATE TABLE IF NOT EXISTS reservations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20),
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    party_size INTEGER NOT NULL,
    special_requests TEXT,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20),
    delivery_address TEXT,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    order_type VARCHAR(20) DEFAULT 'delivery',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    menu_item_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id)
);

-- Customer Reviews Table
CREATE TABLE IF NOT EXISTS reviews (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    is_approved BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin Users Table
CREATE TABLE IF NOT EXISTS admin_users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Customer Users Table
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    address TEXT,
    date_of_birth DATE,
    email_verified BOOLEAN DEFAULT 0,
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Insert default admin user (password: admin123)
INSERT OR IGNORE INTO admin_users (username, email, password_hash) VALUES
('admin', 'admin@grandrestaurant.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample menu items
INSERT OR IGNORE INTO menu_items (name, description, price, category, image_url) VALUES
('Sri Lankan String Hoppers', 'Traditional Sri Lankan breakfast made from rice flour, served with coconut sambol and curry', 450.00, 'Traditional', 'images/popular/1.png'),
('Seafood Fried Rice', 'Fragrant rice with prawns, calamari, and crab meat', 750.00, 'Rice & Curry', 'images/popular/3.png'),
('Mutton Biryani', 'Aromatic basmati rice cooked with tender mutton and traditional spices', 850.00, 'Rice & Curry', 'images/popular/2.png'),
('Fish Kottu', 'Traditional kottu roti with fresh fish, vegetables, and aromatic spices', 650.00, 'Kottu', 'images/popular/1.png'),
('General Tso Chicken', 'Crispy chicken pieces in sweet and spicy sauce', 720.00, 'Chinese', 'images/popular/2.png');

-- Insert sample reviews (approved)
INSERT OR IGNORE INTO reviews (customer_name, customer_email, rating, review_text, is_approved) VALUES
('John Smith', 'john@email.com', 5, 'Amazing food and great service! The ribeye steak was perfectly cooked.', 1),
('Sarah Johnson', 'sarah@email.com', 4, 'Loved the atmosphere and the salmon was delicious. Will definitely come back!', 1),
('Mike Wilson', 'mike@email.com', 5, 'Best pizza in town! The margherita was authentic and fresh.', 1),
('Emily Davis', 'emily@email.com', 5, 'Great restaurant for special occasions. The lobster ravioli was outstanding.', 1);

-- Mark database as initialized
INSERT OR IGNORE INTO initialization_marker (initialized_at) VALUES (CURRENT_TIMESTAMP);