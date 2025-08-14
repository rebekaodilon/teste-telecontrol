<?php
declare(strict_types=1);
use App\Services\ClientService;
use App\Repositories\ClientRepository;

final class ClientServiceTest extends TestCase {
    public function testCreateUpdateDeleteClient(): void {
        $svc = new ClientService();
        $created = $svc->create(['name'=>'Maria','cpf'=>'52998224725','address'=>'Rua A, 123']);
        $this->assertIsInt($created['id']);

        $repo = new ClientRepository();
        $c = $repo->find($created['id']);
        $this->assertSame('Maria', $c['name']);

        $svc->update($created['id'], ['name'=>'Maria Silva','address'=>'Rua B, 456']);
        $c2 = $repo->find($created['id']);
        $this->assertSame('Maria Silva', $c2['name']);

        $svc->delete($created['id']);
        $this->assertNull($repo->find($created['id']));
    }
}
