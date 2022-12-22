<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\ClassNameImport\ShortNameResolver;

use Iterator;
use Rector\CodingStyle\ClassNameImport\ShortNameResolver;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Rector\Testing\TestingParser\TestingParser;

final class ShortNameResolverTest extends AbstractTestCase
{
    private ShortNameResolver $shortNameResolver;

    private TestingParser $testingParser;

    protected function setUp(): void
    {
        $this->boot();
        $this->shortNameResolver = $this->getService(ShortNameResolver::class);
        $this->testingParser = $this->getService(TestingParser::class);
    }

    /**
     * @dataProvider provideData()
     * @param array<string, class-string<SomeFile>|string> $expectedShortNames
     */
    public function test(string $filePath, array $expectedShortNames): void
    {
        $file = $this->testingParser->parseFilePathToFile($filePath);
        $shortNames = $this->shortNameResolver->resolveFromFile($file);

        $this->assertSame($expectedShortNames, $shortNames);
    }

    public function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/various_imports.php.inc', [
            'VariousImports' => 'Rector\Tests\CodingStyle\ClassNameImport\ShortNameResolver\Fixture\VariousImports',
            'SomeFile' => 'Rector\Tests\CodingStyle\ClassNameImport\ShortNameResolver\Source\SomeFile',
        ]];

        yield [__DIR__ . '/Fixture/also_aliases.php.inc', [
            'AlsoAliases' => 'Rector\Tests\CodingStyle\ClassNameImport\ShortNameResolver\Fixture\AlsoAliases',
            'AnotherFile' => 'Rector\Tests\CodingStyle\ClassNameImport\ShortNameResolver\Source\SomeFile',
        ]];

        yield [__DIR__ . '/Fixture/partial_names.php.inc', [
            'PartialNames' => 'Rector\Tests\CodingStyle\ClassNameImport\ShortNameResolver\Fixture\PartialNames',
        ]];

        yield [__DIR__ . '/Fixture/union_partial_import.php.inc', [
            'UnionPartialImport' => 'Rector\Tests\CodingStyle\ClassNameImport\ShortNameResolver\Fixture\UnionPartialImport',
            'rand' => 'rand',
            'FirstLog' => 'Rector\Tests\CodingStyle\ClassNameImport\ShortNameResolver\Source\FirstLog',
            'SecondLog' => 'Rector\Tests\CodingStyle\ClassNameImport\ShortNameResolver\Source\SecondLog',
        ]];
    }
}
