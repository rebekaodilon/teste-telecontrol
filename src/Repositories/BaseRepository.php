<?php
namespace App\Repositories;
use App\Core\Database;
use PDO;
abstract class BaseRepository { protected PDO $db; public function __construct(){ $this->db = Database::pdo(); } }
