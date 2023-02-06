<?php

declare(strict_types=1);

namespace Rector\Tests\PSR4\Rector\Namespace_\MultipleClassFileToPsr4ClassesRector;

use Nette\Utils\FileSystem;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class MultipleClassFileToPsr4ClassesRectorTest extends AbstractRectorTestCase
{
    public function testMultipleExceptions(): void
    {
        $this->doTestFile(__DIR__ . '/Fixture/nette_exceptions.php.inc');

        $this->assertFileWasAdded(
            __DIR__ . '/Fixture/RegexpException.php',
            FileSystem::read(__DIR__ . '/Expected/RegexpException.php')
        );

        $this->assertFileWasAdded(
            __DIR__ . '/Fixture/UnknownImageFileException.php',
            FileSystem::read(__DIR__ . '/Expected/UnknownImageFileException.php')
        );

        // original file was removed
        $isFileRemoved = $this->removedAndAddedFilesCollector->isFileRemoved(
            __DIR__ . '/Fixture/nette_exceptions.php'
        );
        $this->assertTrue($isFileRemoved);
    }

    public function testClassInterfaceAndTraitSplit(): void
    {
        $this->doTestFile(__DIR__ . '/Fixture/class_trait_and_interface.php.inc');

        $this->assertFileWasAdded(
            __DIR__ . '/Fixture/MyTrait.php',
            FileSystem::read(__DIR__ . '/Expected/MyTrait.php')
        );

        $this->assertFileWasAdded(
            __DIR__ . '/Fixture/ClassTraitAndInterface.php',
            FileSystem::read(__DIR__ . '/Expected/ClassTraitAndInterface.php')
        );

        $this->assertFileWasAdded(
            __DIR__ . '/Fixture/MyInterface.php',
            FileSystem::read(__DIR__ . '/Expected/MyInterface.php')
        );

        $isFileRemoved = $this->removedAndAddedFilesCollector->isFileRemoved(
            __DIR__ . '/Fixture/class_trait_and_interface.php'
        );
        $this->assertTrue($isFileRemoved);
    }

    public function testKeepFileThatMatchesClassName(): void
    {
        $this->doTestFile(__DIR__ . '/Fixture/ClassMatchesFilename.php.inc');

        //$this->assertFileWasAdded(
        //    __DIR__ . '/Fixture/ClassMatchesFilenameException.php',
        //    FileSystem::read(__DIR__ . '/Expected/ClassMatchesFilenameException.php')
        //);

        $this->assertFileWasAdded(
            __DIR__ . '/Fixture/ClassMatchesFilename.php',
            FileSystem::read(__DIR__ . '/Expected/ClassMatchesFilename.php')
        );
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
