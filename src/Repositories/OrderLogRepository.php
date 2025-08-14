<?php
namespace App\Repositories;
class OrderLogRepository extends BaseRepository {
    public function log(int $order_id, int $user_id, string $action, ?array $before, ?array $after): void {
        $st=$this->db->prepare("INSERT INTO order_logs(order_id, user_id, action, before_data, after_data, created_at) VALUES(:order_id,:user_id,:action,:before_data,:after_data, NOW())");
        $st->execute([':order_id'=>$order_id,':user_id'=>$user_id,':action'=>$action,':before_data'=>$before?json_encode($before,JSON_UNESCAPED_UNICODE):null,':after_data'=>$after?json_encode($after,JSON_UNESCAPED_UNICODE):null]);
    }
}
