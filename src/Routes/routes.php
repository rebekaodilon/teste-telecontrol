<?php
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\ClientController;
use App\Controllers\ProductController;
use App\Controllers\OrderController;

$router = new Router();

$router->add('GET', '/ping', function(){ \App\Core\Response::json(['pong'=>true,'time'=>date('c')]); });

$auth = new AuthController();
$router->add('POST', '/api/login', fn() => $auth->login());
$router->add('POST', '/api/register', fn() => $auth->register());

$clients = new ClientController();
$router->add('GET', '/api/clients', fn() => $clients->index());
$router->add('GET', '/api/clients/{id}', fn($p) => $clients->show($p));
$router->add('POST', '/api/clients', fn() => $clients->store());
$router->add('PUT', '/api/clients/{id}', fn($p) => $clients->update($p));
$router->add('DELETE', '/api/clients/{id}', fn($p) => $clients->destroy($p));

$products = new ProductController();
$router->add('GET', '/api/products', fn() => $products->index());
$router->add('GET', '/api/products/{id}', fn($p) => $products->show($p));
$router->add('POST', '/api/products', fn() => $products->store());
$router->add('PUT', '/api/products/{id}', fn($p) => $products->update($p));
$router->add('DELETE', '/api/products/{id}', fn($p) => $products->destroy($p));

$orders = new OrderController();
$router->add('GET', '/api/orders', fn() => $orders->index());
$router->add('GET', '/api/orders/{id}', fn($p) => $orders->show($p));
$router->add('POST', '/api/orders', fn() => $orders->store());
$router->add('PUT', '/api/orders/{id}', fn($p) => $orders->update($p));
$router->add('DELETE', '/api/orders/{id}', fn($p) => $orders->destroy($p));

return $router;
