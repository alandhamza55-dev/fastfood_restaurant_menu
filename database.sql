CREATE DATABASE IF NOT EXISTS fastfood_pro_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE fastfood_pro_db;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  image VARCHAR(255),
  available TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  total DECIMAL(10,2) NOT NULL,
  status ENUM('pending','preparing','completed','cancelled') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  menu_item_id INT,
  quantity INT,
  price DECIMAL(10,2)
);

INSERT INTO admin_users (username, password) VALUES ('admin', '$2y$10$2b0zpGfhk4fHG/KriZbL8eCSmzyf3E/yX2y3yRMXOXq9T8i8Eq8ty')
ON DUPLICATE KEY UPDATE username=username;

INSERT INTO users (username, email, password) VALUES ('user1','user1@example.com','$2y$10$2b0zpGfhk4fHG/KriZbL8eCSmzyf3E/yX2y3yRMXOXq9T8i8Eq8ty')
ON DUPLICATE KEY UPDATE username=username;

INSERT INTO menu_items (name, description, price) VALUES
('Classic Burger','Beef patty, cheddar, lettuce, tomato, house sauce',5.99),
('Double Cheeseburger','Two beef patties, double cheese',7.99),
('Pepperoni Pizza','Thin crust, lots of pepperoni',8.99),
('Crispy Fries','Golden fries with sea salt',2.50),
('Soda Can','Chilled soda 330ml',1.25),
('Chocolate Brownie','Fudgy chocolate brownie',2.99);
