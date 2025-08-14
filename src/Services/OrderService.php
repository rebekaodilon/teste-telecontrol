<?php
namespace App\Services;
use App\Repositories\OrderRepository;
use App\Repositories\OrderLogRepository;
use App\Services\ClientService;
use App\Core\Validator;

class OrderService {
    private OrderRepository $repo;
    private OrderLogRepository $logs;
    private ClientService $clients;
    public function __construct(){ $this->repo=new OrderRepository(); $this->logs=new OrderLogRepository(); $this->clients=new ClientService(); }

    public function create(array $in, int $user_id): array {
        $order_number=Validator::sanitizeString($in['order_number']??''); $opened_at=Validator::sanitizeString($in['opened_at']??'');
        $cname=Validator::sanitizeString($in['consumer_name']??''); $ccpf=Validator::sanitizeString($in['consumer_cpf']??'');
        $caddr=Validator::sanitizeString($in['consumer_address']??'Não informado'); $product_id=(int)($in['product_id']??0);
        if(!$order_number||!$opened_at||!$cname||!$ccpf||!$product_id) throw new \InvalidArgumentException('missing fields');
        $client=$this->clients->findOrCreateByConsumer($cname,$ccpf,$caddr);
        $id=$this->repo->create(['order_number'=>$order_number,'opened_at'=>$opened_at,'client_id'=>(int)$client['id'],'product_id'=>$product_id]);
        $created=$this->repo->find($id);
        $this->logs->log($id,$user_id,'created',null,$created);
        return $created;
    }
    public function update(int $id, array $in, int $user_id): ?array {
        $before=$this->repo->find($id); if(!$before) return null;
        $data=['order_number'=>Validator::sanitizeString($in['order_number']??$before['order_number']),
               'opened_at'=>Validator::sanitizeString($in['opened_at']??$before['opened_at']),
               'client_id'=>(int)($in['client_id']??$before['client_id']),
               'product_id'=>(int)($in['product_id']??$before['product_id'])];
        $this->repo->update($id,$data);
        $after=$this->repo->find($id);
        $this->logs->log($id,$user_id,'updated',$before,$after);
        return $after;
    }
    public function delete(int $id, int $user_id): bool {
        $before=$this->repo->find($id); $ok=$this->repo->delete($id);
        if($ok && $before) $this->logs->log($id,$user_id,'deleted',$before,null);
        return $ok;
    }
    public function find(int $id): ?array { return $this->repo->find($id); }
    public function list(array $f=[]): array { return $this->repo->list($f); }
}
