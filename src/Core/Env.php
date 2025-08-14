<?php
namespace App\Core;
use Dotenv\Dotenv;
class Env {
    private static bool $loaded = false;
    public static function load(): void {
        if (!self::$loaded) {
            $dir = dirname(__DIR__, 2);
            if (file_exists($dir.'/.env')) Dotenv::createImmutable($dir)->load();
            self::$loaded = true;
        }
    }
    public static function get(string $k, $d=null){ self::load(); return $_ENV[$k] ?? getenv($k) ?? $d; }
}
