<?php
namespace App\Controllers;
use App\Core\Response;
use App\Core\Validator;
use App\Services\AuthService;

class AuthController {
    public function login() {
        $in = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $email = Validator::sanitizeString($in['email'] ?? '');
        $password = $in['password'] ?? '';
        $res = (new AuthService())->login($email, $password);
        if (!$res) return Response::json(['error'=>'Invalid credentials'], 401);
        Response::json($res);
    }

    public function register() {
        $in = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $name = Validator::sanitizeString($in['name'] ?? '');
        $email = Validator::sanitizeString($in['email'] ?? '');
        $password = $in['password'] ?? '';
        if (!$name || !$email || !$password)
            return Response::json(['error'=>'name, email, password required'], 422);

        $res = (new AuthService())->register($name, $email, $password);
        if (!$res) return Response::json(['error'=>'email already exists'], 422);
        Response::json($res, 201);
    }
}

