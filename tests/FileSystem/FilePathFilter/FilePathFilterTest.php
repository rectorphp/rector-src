<?php

declare(strict_types=1);

namespace Rector\Tests\FileSystem\FilePathFilter;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\FileSystem\FilePathFilter;

final class FilePathFilterTest extends TestCase
{
    private FilePathFilter $filePathFilter;

    protected function setUp(): void
    {
        $this->filePathFilter = new FilePathFilter();
    }

    /**
     * @param string[] $patterns
     * @param string[] $expectedFilePaths
     */
    #[DataProvider('provideData')]
    public function test(array $patterns, array $expectedFilePaths): void
    {
        $filePaths = [
            '/project/src/Controller/HomeController.php',
            '/project/src/Repository/UserRepository.php',
            '/project/tests/Unit/SomeTest.php',
            '/project/tests/AbstractTestCase.php',
        ];

        $this->assertSame($expectedFilePaths, $this->filePathFilter->filter($filePaths, $patterns));
    }

    public static function provideData(): Iterator
    {
        yield 'no patterns keeps everything' => [[], [
            '/project/src/Controller/HomeController.php',
            '/project/src/Repository/UserRepository.php',
            '/project/tests/Unit/SomeTest.php',
            '/project/tests/AbstractTestCase.php',
        ]];

        yield 'path substring' => [['/Controller/'], ['/project/src/Controller/HomeController.php']];

        yield 'basename glob' => [['*Repository.php'], ['/project/src/Repository/UserRepository.php']];

        yield 'tests keyword' => [['tests'], [
            '/project/tests/Unit/SomeTest.php',
            '/project/tests/AbstractTestCase.php',
        ]];

        yield 'patterns combine with AND' => [['/tests/', '*Test.php'], ['/project/tests/Unit/SomeTest.php']];

        yield 'no match yields empty' => [['*Missing.php'], []];
    }

    /**
     * @param string[] $expectedPatterns
     */
    #[DataProvider('provideParseData')]
    public function testParsePatterns(string $rawFilter, array $expectedPatterns): void
    {
        $this->assertSame($expectedPatterns, $this->filePathFilter->parsePatterns($rawFilter));
    }

    public static function provideParseData(): Iterator
    {
        yield 'empty string yields no patterns' => ['', []];
        yield 'single pattern' => ['/Controller/', ['/Controller/']];
        yield 'comma separated, trimmed' => [' /Controller/ , *Repository.php ', ['/Controller/', '*Repository.php']];
        yield 'blank parts dropped' => ['tests,,', ['tests']];
    }
}
