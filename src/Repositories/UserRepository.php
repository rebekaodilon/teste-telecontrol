<?php
namespace App\Repositories;
class UserRepository extends BaseRepository {
    public function findByEmail(string $email): ?array {
        $st=$this->db->prepare("SELECT * FROM users WHERE email=? LIMIT 1"); $st->execute([$email]); $r=$st->fetch(); return $r?:null;
    }
    public function findById(int $id): ?array {
        $st=$this->db->prepare("SELECT * FROM users WHERE id=?"); $st->execute([$id]); $r=$st->fetch(); return $r?:null;
    }
    public function create(string $name, string $email, string $password_hash, string $role='user'): int {
        $st=$this->db->prepare("INSERT INTO users(name,email,password_hash,role) VALUES(?,?,?,?)");
        $st->execute([$name,$email,$password_hash,$role]);
        return (int)$this->db->lastInsertId();
    }
}
