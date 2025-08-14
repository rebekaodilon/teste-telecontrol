<?php
namespace App\Services;
use App\Repositories\UserRepository;
use App\Core\JWT;

class AuthService {
    private UserRepository $users;
    public function __construct(){ $this->users = new UserRepository(); }

    public function login(string $email, string $password): ?array {
    $u = $this->users->findByEmail($email);
    if (!$u || !password_verify($password, $u['password_hash'])) return null;

    $token = JWT::encode([
        'sub' => $u['id'],
        'role' => $u['role'],
        'email' => $u['email']
    ]);

    if (!$token) {
        file_put_contents('/tmp/jwt_error.log', 'Erro ao gerar token'.PHP_EOL, FILE_APPEND);
    }

    return [
        'token' => $token,
        'user' => [
            'id' => $u['id'],
            'name' => $u['name'],
            'email' => $u['email'],
            'role' => $u['role']
        ]
    ];
}


    public function register(string $name, string $email, string $password): ?array {
        if ($this->users->findByEmail($email)) return null;
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $id = $this->users->create($name, $email, $hash, 'user');
        $token = JWT::encode(['sub'=>$id,'role'=>'user','email'=>$email]);
        return ['token'=>$token,'user'=>['id'=>$id,'name'=>$name,'email'=>$email,'role'=>'user']];
    }
}
