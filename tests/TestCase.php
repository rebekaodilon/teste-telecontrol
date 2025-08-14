<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
    protected function setUp(): void {
        parent::setUp();
        if (function_exists('tc_truncate_all')) {
            tc_truncate_all();
        }
    }
}
