<?php

declare(strict_types=1);

namespace Rector\Tests\Naming\Naming;

use Iterator;
use PHPStan\Type\ObjectType;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Naming\Naming\PropertyNaming;
use Rector\Naming\ValueObject\ExpectedName;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class PropertyNamingTest extends AbstractLazyTestCase
{
    private PropertyNaming $propertyNaming;

    protected function setUp(): void
    {
        parent::setUp();

        $this->propertyNaming = $this->make(PropertyNaming::class);
    }

    #[DataProvider('getExpectedNameFromMethodNameDataProvider')]
    public function testGetExpectedNameFromMethodName(string $methodName, ?string $expectedPropertyName): void
    {
        $expectedName = $this->propertyNaming->getExpectedNameFromMethodName($methodName);

        if ($expectedPropertyName === null) {
            $this->assertNotInstanceOf(ExpectedName::class, $expectedName);
        } else {
            $this->assertInstanceOf(ExpectedName::class, $expectedName);
            $this->assertSame($expectedPropertyName, $expectedName->getSingularized());
        }
    }

    /**
     * @return Iterator<mixed>
     */
    public static function getExpectedNameFromMethodNameDataProvider(): Iterator
    {
        yield ['getMethods', 'method'];
        yield ['getUsedTraits', 'usedTrait'];
        yield ['getPackagesData', 'packageData'];
        yield ['getPackagesInfo', 'packageInfo'];
        yield ['getAnythingElseData', 'anythingElseData'];
        yield ['getAnythingElseInfo', 'anythingElseInfo'];
        yield ['getSpaceshipsInfo', 'spaceshipInfo'];
        yield ['resolveDependencies', null];
    }

    #[DataProvider('provideDataPropertyName')]
    public function testPropertyName(string $objectName, string $expectedVariableName): void
    {
        $variableName = $this->propertyNaming->fqnToVariableName(new ObjectType($objectName));
        $this->assertSame($expectedVariableName, $variableName);
    }

    public static function provideDataPropertyName(): Iterator
    {
        yield ['SomeVariable', 'someVariable'];
        yield ['IControl', 'control'];
        yield ['AbstractValueClass', 'valueClass'];
        yield ['App\AbstractValueClass', 'valueClass'];
        yield ['Twig_Extension', 'twigExtension'];
        yield ['NodeVisitorAbstract', 'nodeVisitor'];
        yield ['AbstractNodeVisitor', 'nodeVisitor'];
        yield ['Twig_ExtensionInterface', 'twigExtension'];
    }
}
