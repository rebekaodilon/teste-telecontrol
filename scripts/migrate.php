<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Core\Database; use App\Core\Env;
Env::load(); $db = Database::pdo();

$db->exec("CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->exec("CREATE TABLE IF NOT EXISTS clients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  cpf CHAR(11) NOT NULL UNIQUE,
  address VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->exec("CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(60) NOT NULL UNIQUE,
  description VARCHAR(255) NOT NULL,
  status ENUM('active','inactive') NOT NULL DEFAULT 'inactive',
  warranty_months INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->exec("CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_number VARCHAR(60) NOT NULL,
  opened_at DATE NOT NULL,
  client_id INT NOT NULL,
  product_id INT NOT NULL,
  CONSTRAINT fk_orders_client FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE RESTRICT,
  CONSTRAINT fk_orders_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->exec("CREATE TABLE IF NOT EXISTS order_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  user_id INT NOT NULL,
  action ENUM('created','updated','deleted') NOT NULL,
  before_data JSON NULL,
  after_data JSON NULL,
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_logs_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo "Migrations applied.\n";
