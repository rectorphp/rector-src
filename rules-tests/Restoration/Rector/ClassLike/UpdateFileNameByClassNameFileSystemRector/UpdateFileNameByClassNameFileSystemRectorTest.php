<?php

declare(strict_types=1);

namespace Rector\Tests\Restoration\Rector\ClassLike\UpdateFileNameByClassNameFileSystemRector;

use Iterator;
use Nette\Utils\FileSystem;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Webmozart\Assert\Assert;

final class UpdateFileNameByClassNameFileSystemRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);

        Assert::string($this->originalTempFilePath);
        $originalDirectory = dirname($this->originalTempFilePath);

        $expectedAddedFileWithContent = new AddedFileWithContent(
            $originalDirectory . '/SkipDifferentClassName.php',
            FileSystem::read(__DIR__ . '/Fixture/skip_different_class_name.php.inc')
        );
        $this->assertFileWasAdded($expectedAddedFileWithContent);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
