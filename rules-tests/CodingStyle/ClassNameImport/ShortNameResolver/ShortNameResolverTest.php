<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\ClassNameImport\ShortNameResolver;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\CodingStyle\ClassNameImport\ShortNameResolver;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Testing\TestingParser\TestingParser;

final class ShortNameResolverTest extends AbstractLazyTestCase
{
    private ShortNameResolver $shortNameResolver;

    private TestingParser $testingParser;

    protected function setUp(): void
    {
        // @todo let dynamic source locator know about parsed files
        parent::setUp();

        $this->shortNameResolver = $this->make(ShortNameResolver::class);
        $this->testingParser = $this->make(TestingParser::class);
    }

    /**
     * @param array<string, class-string<SomeFile>|string> $expectedShortNames
     */
    #[DataProvider('provideData')]
    public function test(string $filePath, array $expectedShortNames): void
    {
        $file = $this->testingParser->parseFilePathToFile($filePath);
        $shortNames = $this->shortNameResolver->resolveFromFile($file);

        $this->assertSame($expectedShortNames, $shortNames);
    }

    /**
     * @return Iterator<array<array<int, mixed>, mixed>>
     */
    public static function provideData(): Iterator
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
