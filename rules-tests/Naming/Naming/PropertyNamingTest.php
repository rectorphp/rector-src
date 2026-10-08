<?php

declare(strict_types=1);

namespace Rector\Tests\Naming\Naming;

use Iterator;
use PHPStan\Type\ObjectType;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Naming\Naming\PropertyNaming;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class PropertyNamingTest extends AbstractLazyTestCase
{
    private PropertyNaming $propertyNaming;

    protected function setUp(): void
    {
        parent::setUp();

        $this->propertyNaming = $this->make(PropertyNaming::class);
    }

    #[DataProvider('provideDataPropertyName')]
    public function testPropertyName(string $objectName, string $expectedVariableName): void
    {
        $variableName = $this->propertyNaming->fqnToVariableName(new ObjectType($objectName));
        $this->assertSame($expectedVariableName, $variableName);
    }

    /**
     * @return Iterator<array<int, string>>
     */
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
