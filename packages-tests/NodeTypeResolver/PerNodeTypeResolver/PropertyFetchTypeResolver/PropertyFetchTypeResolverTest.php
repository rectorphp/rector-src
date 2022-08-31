<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\PropertyFetchTypeResolver;

use Iterator;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;
use Rector\Testing\Fixture\FixtureFileFinder;
use Rector\Testing\Fixture\FixtureSplitter;
use Rector\Testing\Fixture\FixtureTempFileDumper;
use Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\AbstractNodeTypeResolverTest;
use Symplify\SmartFileSystem\SmartFileInfo;

final class PropertyFetchTypeResolverTest extends AbstractNodeTypeResolverTest
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $smartFileInfo): void
    {
        $this->doTestFileInfo($smartFileInfo);
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    public function provideData(): Iterator
    {
        return FixtureFileFinder::yieldDirectory(__DIR__ . '/Fixture');
    }

    private function doTestFileInfo(SmartFileInfo $smartFileInfo): void
    {
        [$inputFileContents, $expectedType] = FixtureSplitter::loadFileAndSplitInputAndExpected(
            $smartFileInfo->getRealPath()
        );

        $inputFilePath = FixtureTempFileDumper::dump($inputFileContents);

        $propertyFetchNodes = $this->getNodesForFileOfType($inputFilePath, PropertyFetch::class);
        $resolvedType = $this->nodeTypeResolver->getType($propertyFetchNodes[0]);

        // this file actually containts PHP for type
        $typeFilePath = FixtureTempFileDumper::dump($expectedType);
        $expectedType = include $typeFilePath;

        $expectedTypeAsString = $this->getStringFromType($expectedType);
        $resolvedTypeAsString = $this->getStringFromType($resolvedType);

        $this->assertSame($expectedTypeAsString, $resolvedTypeAsString);
    }

    private function getStringFromType(Type $type): string
    {
        return $type->describe(VerbosityLevel::precise());
    }
}
