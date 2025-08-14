<?php
namespace App\Repositories;
class ClientRepository extends BaseRepository {
    public function create(array $d): int {
        $st=$this->db->prepare("INSERT INTO clients(name,cpf,address) VALUES(:name,:cpf,:address)");
        $st->execute([':name'=>$d['name'],':cpf'=>$d['cpf'],':address'=>$d['address']]);
        return (int)$this->db->lastInsertId();
    }
    public function update(int $id, array $d): bool {
        $st=$this->db->prepare("UPDATE clients SET name=:name,address=:address WHERE id=:id");
        return $st->execute([':name'=>$d['name'],':address'=>$d['address'],':id'=>$id]);
    }
    public function delete(int $id): bool { $st=$this->db->prepare("DELETE FROM clients WHERE id=?"); return $st->execute([$id]); }
    public function find(int $id): ?array { $st=$this->db->prepare("SELECT * FROM clients WHERE id=?"); $st->execute([$id]); $r=$st->fetch(); return $r?:null; }
    public function findByCPF(string $cpf): ?array { $st=$this->db->prepare("SELECT * FROM clients WHERE cpf=?"); $st->execute([$cpf]); $r=$st->fetch(); return $r?:null; }
    public function list(array $f=[]): array {
        $sql="SELECT * FROM clients WHERE 1=1"; $args=[];
        if (!empty($f['q'])){ $sql.=" AND (name LIKE :q OR address LIKE :q)"; $args[':q']='%'.$f['q'].'%'; }
        $sql.=" ORDER BY id DESC"; $st=$this->db->prepare($sql); $st->execute($args); return $st->fetchAll();
    }
}
