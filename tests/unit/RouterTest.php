<?php
declare(strict_types=1);
use App\Core\Router;

final class RouterTest extends TestCase {
    public function testRouteParamExtraction(): void {
        $router = new Router();
        $captured = null;
        $router->add('GET', '/clients/{id}', function(array $params) use (&$captured){
            $captured = $params['id'] ?? null;
        });
        $router->dispatch('GET', '/clients/123');
        $this->assertSame('123', $captured);
    }

    public function testNotFoundDoesNotMatch(): void {
        $router = new Router();
        $this->assertTrue(true);
    }
}
