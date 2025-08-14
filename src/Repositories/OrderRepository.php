<?php
namespace App\Repositories;
class OrderRepository extends BaseRepository {
    public function create(array $d): int {
        $st=$this->db->prepare("INSERT INTO orders(order_number,opened_at,client_id,product_id) VALUES(:order_number,:opened_at,:client_id,:product_id)");
        $st->execute([':order_number'=>$d['order_number'],':opened_at'=>$d['opened_at'],':client_id'=>$d['client_id'],':product_id'=>$d['product_id']]);
        return (int)$this->db->lastInsertId();
    }
    public function update(int $id, array $d): bool {
        $st=$this->db->prepare("UPDATE orders SET order_number=:order_number, opened_at=:opened_at, client_id=:client_id, product_id=:product_id WHERE id=:id");
        return $st->execute([':order_number'=>$d['order_number'],':opened_at'=>$d['opened_at'],':client_id'=>$d['client_id'],':product_id'=>$d['product_id'],':id'=>$id]);
    }
    public function delete(int $id): bool { $st=$this->db->prepare("DELETE FROM orders WHERE id=?"); return $st->execute([$id]); }
    public function find(int $id): ?array {
        $st=$this->db->prepare("SELECT o.*, c.name as client_name, c.cpf as client_cpf, p.code as product_code, p.description as product_description
                                FROM orders o JOIN clients c ON c.id=o.client_id JOIN products p ON p.id=o.product_id WHERE o.id=?");
        $st->execute([$id]); $r=$st->fetch(); return $r?:null;
    }
    public function list(array $filters=[]): array {
        $sql="SELECT o.*, c.name as client_name, p.code as product_code FROM orders o
              JOIN clients c ON c.id=o.client_id JOIN products p ON p.id=o.product_id WHERE 1=1";
        $args=[];
        if (!empty($filters['q'])) { $sql.=" AND (o.order_number LIKE :q OR c.name LIKE :q OR p.code LIKE :q)"; $args[':q']='%'.$filters['q'].'%'; }
        $sql.=" ORDER BY o.id DESC"; $st=$this->db->prepare($sql); $st->execute($args); return $st->fetchAll();
    }
}
