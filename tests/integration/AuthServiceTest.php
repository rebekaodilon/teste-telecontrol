<?php
declare(strict_types=1);
use App\Services\AuthService;

final class AuthServiceTest extends TestCase {
    public function testRegisterAndLogin(): void {
        $svc = new AuthService();
        $reg = $svc->register('Admin', 'admin@test.local', '123456');
        $this->assertArrayHasKey('token', $reg);

        $login = $svc->login('admin@test.local', '123456');
        $this->assertNotNull($login);
        $this->assertArrayHasKey('token', $login);
    }
}
