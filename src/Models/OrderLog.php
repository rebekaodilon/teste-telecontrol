<?php
namespace App\Models; class OrderLog{ public ?int $id=null; public int $order_id; public int $user_id; public string $action; public ?string $before=null; public ?string $after=null; public string $created_at; }
