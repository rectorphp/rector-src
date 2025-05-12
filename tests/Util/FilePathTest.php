<?php

declare(strict_types=1);

namespace Rector\Tests\Util;

use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Util\FilePath;

final class FilePathTest extends AbstractLazyTestCase
{
    protected function tearDown(): void
    {
        SimpleParameterProvider::setParameter(Option::SOURCE, []);
        SimpleParameterProvider::setParameter(Option::PATHS, []);
    }

    /**
     * @param list<string> $source
     * @param list<string> $paths
     */
    #[DataProvider('provideValidDataForFileIsInRectorPathOrSource')]
    public function testFileIsInRectorPathOrSource(string $file, array $source, array $paths): void
    {
        SimpleParameterProvider::setParameter(Option::SOURCE, $source);
        SimpleParameterProvider::setParameter(Option::PATHS, $paths);
        $this->assertTrue(FilePath::fileIsInRectorPathOrSource($file));
    }

    /**
     * @param list<string> $source
     * @param list<string> $paths
     */
    #[DataProvider('provideInvalidDataForFileIsInRectorPathOrSource')]
    public function testFileIsNotInRectorPathOrSource(string $file, array $source, array $paths): void
    {
        SimpleParameterProvider::setParameter(Option::SOURCE, $source);
        SimpleParameterProvider::setParameter(Option::PATHS, $paths);
        $this->assertFalse(FilePath::fileIsInRectorPathOrSource($file));
    }

    /**
     * @return iterable<string, array<string|list<string>>>
     */
    public static function provideValidDataForFileIsInRectorPathOrSource(): iterable
    {
        yield 'file is in source' => [__DIR__ . '/file.php', [__DIR__ . '/file.php'], []];
        yield 'file directory is in path' => [__DIR__ . '/file.php', [], [__DIR__]];
    }

    /**
     * @return iterable<string, array<string|list<string>>>
     */
    public static function provideInvalidDataForFileIsInRectorPathOrSource(): iterable
    {
        yield 'file is in not in source' => [__DIR__ . '/file.php', [__DIR__ . '/file2.php'], []];
        yield 'file directory is not in path' => [__DIR__ . '/file.php', [], [__DIR__ . 'WithSuffix']];
    }
}
