<?php
declare(strict_types=1);
use App\Services\ClientService;
use App\Services\ProductService;
use App\Services\OrderService;
use App\Repositories\OrderRepository;
use App\Services\AuthService;

final class OrderServiceTest extends TestCase {
    public function testCreateOrderAndList(): void {
        $cs = new ClientService();
        $ps = new ProductService();
        $os = new OrderService();

        // Cria usuário e obtém o ID para logs
        $auth = new AuthService();
        $reg = $auth->register('Tester', 'tester@example.com', '123456');
        $this->assertNotNull($reg);
        $userId = $reg['user']['id'];

        // Esses dois não são mais necessários para o create(), mas mantive o produto
        // O cliente será resolvido via consumer_* no OrderService::create
        $product = $ps->create([
            'code' => 'P100',
            'description' => 'String Box',
            'status' => 'active',
            'warranty_months' => 6
        ]);

        $order = $os->create([
            'order_number'     => 'OS-0001',
            'opened_at'        => '2025-08-14 12:00:00',
            'consumer_name'    => 'João da Silva',
            'consumer_cpf'     => '12345678909',
            'consumer_address' => 'Av. X, 100',
            'product_id'       => $product['id'],
            'quantity'         => 2,
            'notes'            => 'Instalar até sexta'
        ], $userId);

        $this->assertIsInt($order['id']);

        $repo = new OrderRepository();
        $list = $repo->list([]);
        $this->assertCount(1, $list);
        $this->assertSame('OS-0001', $list[0]['order_number']);
    }
}
