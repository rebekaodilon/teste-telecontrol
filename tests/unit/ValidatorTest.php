<?php
declare(strict_types=1);
use App\Core\Validator;

final class ValidatorTest extends TestCase {
    public function testSanitizeString(): void {
        $this->assertSame('hello', Validator::sanitizeString('  <b>hello</b>  '));
        $this->assertSame('', Validator::sanitizeString(null));
    }

    public function testValidCPF(): void {
        // Known valid CPFs for testing
        $this->assertTrue(Validator::isValidCPF('529.982.247-25'));
        $this->assertTrue(Validator::isValidCPF('12345678909'));
    }

    public function testInvalidCPF(): void {
        $this->assertFalse(Validator::isValidCPF('111.111.111-11'));
        $this->assertFalse(Validator::isValidCPF('123.456.789-00'));
        $this->assertFalse(Validator::isValidCPF(''));
    }
}
