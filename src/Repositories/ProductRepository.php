<?php
namespace App\Repositories;

class ProductRepository extends BaseRepository {

    public function create(array $d): int {
        $st = $this->db->prepare("INSERT INTO products(code, description, status, warranty_months) 
                                  VALUES(:code, :description, :status, :warranty_months)");
        $st->execute([
            ':code' => $d['code'],
            ':description' => $d['description'],
            ':status' => $d['status'],
            ':warranty_months' => $d['warranty_months']
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $d): bool {
        $st = $this->db->prepare("UPDATE products 
                                  SET description = :description, status = :status, warranty_months = :warranty_months 
                                  WHERE id = :id");
        return $st->execute([
            ':description' => $d['description'],
            ':status' => $d['status'],
            ':warranty_months' => $d['warranty_months'],
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $st = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $st->execute([$id]);
    }

    public function find(int $id): ?array {
        $st = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $st->execute([$id]);
        $r = $st->fetch();
        return $r ?: null;
    }

    public function findByCode(string $code): ?array {
        $st = $this->db->prepare("SELECT * FROM products WHERE code = ?");
        $st->execute([$code]);
        $r = $st->fetch();
        return $r ?: null;
    }

    public function list(array $filters = []): array {
        $sql = "SELECT * FROM products WHERE 1=1";
        $args = [];

        if (!empty($filters['q'])) {
            $sql .= " AND (code LIKE :q OR description LIKE :q)";
            $args[':q'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $args[':status'] = $filters['status'];
        }

        if (isset($filters['warranty_min'])) {
            $sql .= " AND warranty_months >= :wmin";
            $args[':wmin'] = (int) $filters['warranty_min'];
        }

        if (isset($filters['warranty_max'])) {
            $sql .= " AND warranty_months <= :wmax";
            $args[':wmax'] = (int) $filters['warranty_max'];
        }

        $sql .= " ORDER BY id DESC";

        $st = $this->db->prepare($sql);
        $st->execute($args);
        return $st->fetchAll();
    }
}
