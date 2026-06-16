<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver;

use Rector\CodingStyle\ClassNameImport\UsedImportsResolver;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Testing\TestingParser\TestingParser;

final class UsedImportsResolverTest extends AbstractLazyTestCase
{
    private UsedImportsResolver $usedImportsResolver;

    private TestingParser $testingParser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->usedImportsResolver = $this->make(UsedImportsResolver::class);
        $this->testingParser = $this->make(TestingParser::class);
    }

    public function testResolvesUseFunctionAndConstantImports(): void
    {
        $stmts = $this->testingParser->parseFileToDecoratedNodes(__DIR__ . '/Fixture/with_imports.php.inc');

        $usedImports = $this->usedImportsResolver->resolveForStmts($stmts);

        // the class itself, the normal use and the aliased use
        $useImportNames = array_map(
            static fn ($objectType): string => $objectType->getClassName(),
            $usedImports->getUseImports()
        );

        $this->assertSame([
            'Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver\Fixture\WithImports',
            'Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver\Source\FirstType',
            'AliasedType',
        ], $useImportNames);

        $aliasedType = $usedImports->getUseImports()[2];
        $this->assertInstanceOf(AliasedObjectType::class, $aliasedType);
        $this->assertSame(
            'Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver\Source\SecondType',
            $aliasedType->getFullyQualifiedName()
        );

        $functionImportNames = array_map(
            static fn ($objectType): string => $objectType->getClassName(),
            $usedImports->getFunctionImports()
        );
        $this->assertSame(
            ['Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver\Source\someFunction'],
            $functionImportNames
        );

        $constantImportNames = array_map(
            static fn ($objectType): string => $objectType->getClassName(),
            $usedImports->getConstantImports()
        );
        $this->assertSame(
            ['Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver\Source\SOME_CONSTANT'],
            $constantImportNames
        );
    }

    public function testResolvesClassOnlyWhenNoImports(): void
    {
        $stmts = $this->testingParser->parseFileToDecoratedNodes(__DIR__ . '/Fixture/no_imports.php.inc');

        $usedImports = $this->usedImportsResolver->resolveForStmts($stmts);

        $useImportNames = array_map(
            static fn ($objectType): string => $objectType->getClassName(),
            $usedImports->getUseImports()
        );

        $this->assertSame([
            'Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver\Fixture\NoImports',
        ], $useImportNames);

        $this->assertSame([], $usedImports->getFunctionImports());
        $this->assertSame([], $usedImports->getConstantImports());
    }

    public function testResolvesImportsInNonNamespacedFile(): void
    {
        $stmts = $this->testingParser->parseFileToDecoratedNodes(__DIR__ . '/Fixture/no_namespace.php.inc');

        $usedImports = $this->usedImportsResolver->resolveForStmts($stmts);

        $useImportNames = array_map(
            static fn ($objectType): string => $objectType->getClassName(),
            $usedImports->getUseImports()
        );

        $this->assertSame([
            'NoNamespace',
            'Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver\Source\FirstType',
        ], $useImportNames);

        $functionImportNames = array_map(
            static fn ($objectType): string => $objectType->getClassName(),
            $usedImports->getFunctionImports()
        );
        $this->assertSame(
            ['Rector\Tests\CodingStyle\ClassNameImport\UsedImportsResolver\Source\someFunction'],
            $functionImportNames
        );
    }
}
