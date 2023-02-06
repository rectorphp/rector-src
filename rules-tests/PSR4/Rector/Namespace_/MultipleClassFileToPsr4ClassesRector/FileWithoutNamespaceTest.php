<?php

declare(strict_types=1);

namespace Rector\Tests\PSR4\Rector\Namespace_\MultipleClassFileToPsr4ClassesRector;

use Nette\Utils\FileSystem;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class FileWithoutNamespaceTest extends AbstractRectorTestCase
{
    public function test(): void
    {
        $this->doTestFile(__DIR__ . '/FixtureFileWithoutNamespace/some_without_namespace.php.inc');

        $this->assertFileWasAdded(
            __DIR__ . '/FixtureFileWithoutNamespace/SkipWithoutNamespace.php',
            FileSystem::read(__DIR__ . '/Expected/SkipWithoutNamespace.php')
        );

        $this->assertFileWasAdded(
            __DIR__ . '/FixtureFileWithoutNamespace/JustTwoExceptionWithoutNamespace.php',
            FileSystem::read(__DIR__ . '/Expected/JustTwoExceptionWithoutNamespace.php')
        );

        // the file has to be without the ".inc" suffix, as the real path used in Rector
        $isFileRemoved = $this->removedAndAddedFilesCollector->isFileRemoved(
            __DIR__ . '/FixtureFileWithoutNamespace/some_without_namespace.php'
        );
        $this->assertTrue($isFileRemoved);
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
