<?php
namespace App\Services;
use App\Core\Validator;
use App\Repositories\ClientRepository;
class ClientService {
    private ClientRepository $repo;
    public function __construct(){ $this->repo = new ClientRepository(); }
    public function create(array $in): array {
        $name=Validator::sanitizeString($in['name']??''); $cpf=preg_replace('/\D/','',$in['cpf']??''); $addr=Validator::sanitizeString($in['address']??'');
        if(!$name||!$cpf||!$addr) throw new \InvalidArgumentException('name, cpf, address required');
        if(!Validator::isValidCPF($cpf)) throw new \InvalidArgumentException('invalid cpf');
        if($this->repo->findByCPF($cpf)) throw new \InvalidArgumentException('cpf already exists');
        $id=$this->repo->create(['name'=>$name,'cpf'=>$cpf,'address'=>$addr]);
        return $this->repo->find($id);
    }
    public function update(int $id, array $in): ?array {
        $cur=$this->repo->find($id); if(!$cur) return null;
        $name=Validator::sanitizeString($in['name']??$cur['name']);
        $addr=Validator::sanitizeString($in['address']??$cur['address']);
        $this->repo->update($id, ['name'=>$name,'address'=>$addr]);
        return $this->repo->find($id);
    }
    public function delete(int $id): bool { return $this->repo->delete($id); }
    public function find(int $id): ?array { return $this->repo->find($id); }
    public function list(array $f=[]): array { return $this->repo->list($f); }
    public function findOrCreateByConsumer(string $name, string $cpf, string $addr): array {
        $cpf = preg_replace('/\D/','',$cpf);
        $found = $this->repo->findByCPF($cpf);
        if ($found) return $found;
        return $this->create(['name'=>$name,'cpf'=>$cpf,'address'=>($addr ?: 'Não informado')]);
    }

}
