<?php
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

set_error_handler(function($severity,$message,$file,$line){
  http_response_code(500);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['error'=>"PHP[$severity] $message in $file:$line"], JSON_UNESCAPED_UNICODE);
  exit;
});
set_exception_handler(function($e){
  http_response_code(500);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['error'=>$e->getMessage()], JSON_UNESCAPED_UNICODE);
  exit;
});


use App\Core\Env;
use App\Middleware\AuthMiddleware;

Env::load();
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$protectedPrefixes = ['/api/clients','/api/products','/api/orders'];
$openPaths = ['/api/login','/api/register','/ping'];
$isProtected = !in_array($path, $openPaths, true) && array_reduce($protectedPrefixes, fn($c,$p)=>$c||str_starts_with($path,$p), false);
if ($isProtected) { if (!AuthMiddleware::verify()) { exit; } }

$router = require __DIR__ . '/../src/Routes/routes.php';
$router->dispatch($method, $path);
