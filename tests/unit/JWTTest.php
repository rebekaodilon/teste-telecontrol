<?php
declare(strict_types=1);
use App\Core\JWT as AppJWT;

final class JWTTest extends TestCase {
    public function testEncodeDecode(): void {
        $token = AppJWT::encode(['sub'=>42,'role'=>'admin']);
        $decoded = AppJWT::decode($token);
        $this->assertSame(42, $decoded['sub']);
        $this->assertSame('admin', $decoded['role']);
        $this->assertArrayHasKey('exp', $decoded);
    }
}
