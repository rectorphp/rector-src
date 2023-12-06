<?php

declare(strict_types=1);

namespace PHPUnit\Framework;

use PHPUnit\Framework\MockObject\MockObject;

if (! class_exists('PHPUnit\Framework\TestCase')) {
    abstract class TestCase
    {
        protected function createMock(string $originalClassName): MockObject
        {
        }
    }
}
