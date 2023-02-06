<?php

declare(strict_types=1);

namespace Rector\Tests\Restoration\Rector\ClassLike\UpdateFileNameByClassNameFileSystemRector;

use Nette\Utils\FileSystem;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class UpdateFileNameByClassNameFileSystemRectorTest extends AbstractRectorTestCase
{
    public function test(): void
    {
        $this->doTestFile(__DIR__ . '/Fixture/skip_different_class_name.php.inc');

        $this->assertFileWasAdded(
            __DIR__ . '/Fixture/SkipDifferentClassName.php',
            FileSystem::read(__DIR__ . '/Fixture/skip_different_class_name.php.inc')
        );
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
