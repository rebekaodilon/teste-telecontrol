<?php
namespace App\Models; class User{ public ?int $id=null; public string $name; public string $email; public string $password_hash; public string $role='user'; }
