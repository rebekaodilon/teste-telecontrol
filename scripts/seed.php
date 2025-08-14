<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Core\Database;
$db = Database::pdo();

// Admin user
$st=$db->prepare("SELECT COUNT(*) FROM users WHERE email=?"); $st->execute(['admin@admin.com']);
if ($st->fetchColumn()==0){
  $st=$db->prepare("INSERT INTO users(name,email,password_hash,role) VALUES(?,?,?, 'admin')");
  $st->execute(['Admin','admin@admin.com', password_hash('123456', PASSWORD_BCRYPT)]);
  echo "Admin user created.\n";
} else { echo "Admin user exists.\n"; }

// Products
$st=$db->query("SELECT COUNT(*) FROM products"); if((int)$st->fetchColumn()==0){
  $db->exec("INSERT INTO products(code,description,status,warranty_months) VALUES
    ('P-100','Produto 100','active',12),
    ('P-200','Produto 200','inactive',6)");
  echo "Seed products inserted.\n";
} else { echo "Products already exist.\n"; }
