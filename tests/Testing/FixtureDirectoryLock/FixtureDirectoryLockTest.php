<?php

declare(strict_types=1);

namespace Rector\Tests\Testing\FixtureDirectoryLock;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class FixtureDirectoryLockTest extends AbstractRectorTestCase
{
    public function testDoesNotWriteLockFileIntoFixtureDirectory(): void
    {
        $this->doTestFile(__DIR__ . '/Fixture/some_function.php.inc');

        $this->assertFileDoesNotExist(__DIR__ . '/Fixture/.fixture.lock');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
