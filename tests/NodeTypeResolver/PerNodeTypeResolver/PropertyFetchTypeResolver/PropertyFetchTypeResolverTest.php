<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\PropertyFetchTypeResolver;

use Iterator;
use Nette\Utils\FileSystem;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\Fixture\FixtureFileFinder;
use Rector\Testing\Fixture\FixtureSplitter;
use Rector\Testing\Fixture\FixtureTempFileDumper;
use Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\AbstractNodeTypeResolverTestCase;

final class PropertyFetchTypeResolverTest extends AbstractNodeTypeResolverTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return FixtureFileFinder::yieldDirectory(__DIR__ . '/Fixture');
    }

    private function doTestFile(string $filePath): void
    {
        [$inputFileContents, $expectedType] = FixtureSplitter::split($filePath);
        $inputFilePath = FixtureTempFileDumper::dump($inputFileContents);

        $propertyFetchNodes = $this->getNodesForFileOfType($inputFilePath, PropertyFetch::class);
        $resolvedType = $this->nodeTypeResolver->getType($propertyFetchNodes[0]);

        // this file actually containts PHP for type
        $typeFilePath = FixtureTempFileDumper::dump($expectedType);
        $expectedType = include $typeFilePath;

        $expectedTypeAsString = $this->getStringFromType($expectedType);
        $resolvedTypeAsString = $this->getStringFromType($resolvedType);

        $this->assertSame($expectedTypeAsString, $resolvedTypeAsString);

        FileSystem::delete($inputFilePath);
        FileSystem::delete($typeFilePath);
    }

    private function getStringFromType(Type $type): string
    {
        return $type->describe(VerbosityLevel::precise());
    }
}
