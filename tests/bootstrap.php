<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// Carrega variáveis do .env se existir
$root = dirname(__DIR__);
$envPath = $root.'/.env';
if (file_exists($envPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable($root);
    $dotenv->safeLoad();
}

// Executa migrations (idempotente)
require_once $root . '/scripts/migrate.php';

// Disponibiliza helper para limpar o BD entre testes
function tc_truncate_all(): void {
    $pdo = App\Core\Database::pdo();
    $tables = ['order_logs','orders','products','clients','users'];
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
    foreach ($tables as $t) { $pdo->exec('TRUNCATE TABLE '.$t); }
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
}

require_once __DIR__ . '/TestCase.php';
