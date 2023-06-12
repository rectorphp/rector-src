<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Configuration\ValueObjectInliner;

use PHPStan\Type\UnionType;
use PHPUnit\Framework\TestCase;
use Rector\Core\Kernel\RectorKernel;
use Rector\Core\Tests\Configuration\ValueObjectInliner\Source\ServiceWithValueObject;

final class ConfigFactoryNestedTest extends TestCase
{
    private ServiceWithValueObject $serviceWithValueObject;

    protected function setUp(): void
    {
        $rectorKernel = new RectorKernel();
        $containerBuilder = $rectorKernel->createFromConfigs(
            [__DIR__ . '/config/config_with_nested_union_type_value_objects.php']
        );

        $this->serviceWithValueObject = $containerBuilder->get(ServiceWithValueObject::class);
    }

    public function testInlineValueObjectFunction(): void
    {
        $withType = $this->serviceWithValueObject->getWithType();
        $this->assertInstanceOf(UnionType::class, $withType->getType());
    }
}
