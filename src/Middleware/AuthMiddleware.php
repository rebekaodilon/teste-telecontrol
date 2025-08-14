<?php
namespace App\Middleware;
use App\Core\Response;
use App\Repositories\UserRepository;

class AuthMiddleware {
    private static function getRawAuthHeader(): string {
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        if (isset($headers['Authorization'])) return $headers['Authorization'];
        if (isset($headers['authorization'])) return $headers['authorization'];
        if (isset($headers['X-Authorization'])) return $headers['X-Authorization'];
        if (isset($headers['X-Auth-Token'])) return 'Bearer '.$headers['X-Auth-Token'];
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) return $_SERVER['HTTP_AUTHORIZATION'];
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) return $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        return '';
    }

    private static function extractToken(): ?string {
        // 1) Header Authorization
        $raw = self::getRawAuthHeader();
        if (preg_match('/Bearer\\s+(.+)/i', $raw, $m)) return trim($m[1]);
        // 2) Querystring/POST fallback (?token=...)
        if (isset($_GET['token']) && is_string($_GET['token']) && $_GET['token'] !== '') return $_GET['token'];
        if (isset($_POST['token']) && is_string($_POST['token']) && $_POST['token'] !== '') return $_POST['token'];
        return null;
    }

    public static function verify(?array $roles=null): ?array {
        $token = self::extractToken();
        if (!$token) { Response::json(['error'=>'Unauthorized'], 401); return null; }
        try {
            $payload = \App\Core\JWT::decode($token);
            $user = (new UserRepository())->findById((int)$payload['sub']);
            if (!$user){ Response::json(['error'=>'User not found'], 401); return null; }
            if ($roles && !in_array($user['role'], $roles, true)){ Response::json(['error'=>'Forbidden'], 403); return null; }
            return $user;
        } catch (\Throwable $e) {
            Response::json(['error' => 'Unauthorized', 'message' => $e->getMessage()], 401);
            return null;
        }
    }
}