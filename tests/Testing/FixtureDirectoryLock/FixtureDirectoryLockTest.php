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

    public function testLocksFixtureDirectoryWhateverTheTempDirectory(): void
    {
        if ($this->isWindows()) {
            $this->markTestSkipped('PHP cannot open a directory on Windows');
        }

        $this->doTestFile(__DIR__ . '/Fixture/some_function.php.inc');

        // a worker with its own TMPDIR opens the same directory, so it must see the lock
        $otherProcessHandle = fopen(__DIR__ . '/Fixture', 'r');
        $this->assertNotFalse($otherProcessHandle);

        $this->assertFalse(flock($otherProcessHandle, LOCK_EX | LOCK_NB));

        fclose($otherProcessHandle);
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
