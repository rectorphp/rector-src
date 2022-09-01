<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\InfiniteLoop;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class InfiniteLoopTest extends AbstractRectorTestCase
{
    public function testException(): void
    {
        $this->doTestFile(__DIR__ . '/Fixture/some_method_call_infinity.php.inc');
    }

    public function testPass(): void
    {
        $this->doTestFile(__DIR__ . '/Fixture/de_morgan.php.inc');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/infinite_loop.php';
    }
}
