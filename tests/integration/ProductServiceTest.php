<?php
declare(strict_types=1);
use App\Services\ProductService;
use App\Repositories\ProductRepository;

final class ProductServiceTest extends TestCase {
    public function testCreateAndListProducts(): void {
        $svc = new ProductService();
        $p1 = $svc->create(['code'=>'P001','description'=>'Modulo 550W','status'=>'active','warranty_months'=>24]);
        $p2 = $svc->create(['code'=>'P002','description'=>'Inversor 3kW','status'=>'inactive','warranty_months'=>12]);

        $repo = new ProductRepository();
        $all = $repo->list([]);
        $this->assertCount(2, $all);

        $active = $repo->list(['status'=>'active']);
        $this->assertCount(1, $active);
        $this->assertSame('P001', $active[0]['code']);

        $q = $repo->list(['q'=>'Inversor']);
        $this->assertCount(1, $q);
        $this->assertSame('P002', $q[0]['code']);
    }
}
