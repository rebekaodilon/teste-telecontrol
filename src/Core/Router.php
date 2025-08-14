<?php
namespace App\Core;
class Router {
    private array $routes = [];
    public function add(string $method, string $pattern, callable $handler): void {
        $this->routes[] = [$method, $this->convertPattern($pattern), $handler];
    }
    private function convertPattern(string $pattern): string {
        $pattern = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $pattern);
        return '#^' . rtrim($pattern, '/') . '$#';
    }
    public function dispatch(string $method, string $path): void {
        foreach ($this->routes as [$m, $regex, $handler]) {
            if (strcasecmp($m, $method) === 0 && preg_match($regex, rtrim($path, '/'), $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $handler($params);
                return;
            }
        }
        Response::json(['error'=>'Not Found'], 404);
    }
}
