<?php
namespace App\Core;
use PDO, PDOException, RuntimeException;
class Database {
    private static ?PDO $pdo = null;
    public static function pdo(): PDO {
        if (!self::$pdo) {
            $dsn = sprintf("%s:host=%s;port=%s;dbname=%s;charset=utf8mb4",
                Env::get('DB_DRIVER','mysql'),
                Env::get('DB_HOST','mysql'),
                Env::get('DB_PORT','3306'),
                Env::get('DB_DATABASE','telecontrol'));
            try {
                self::$pdo = new PDO($dsn, Env::get('DB_USERNAME','appuser'), Env::get('DB_PASSWORD','appsecret'), [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                throw new RuntimeException('DB connection failed: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
