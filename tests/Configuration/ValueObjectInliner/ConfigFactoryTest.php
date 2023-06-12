<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Configuration\ValueObjectInliner;

use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPUnit\Framework\TestCase;
use Rector\Core\Kernel\RectorKernel;
use Rector\Core\Tests\Configuration\ValueObjectInliner\Source\ServiceWithValueObject;

final class ConfigFactoryTest extends TestCase
{
    private ServiceWithValueObject $serviceWithValueObject;

    protected function setUp(): void
    {
        $rectorKernel = new RectorKernel();
        $containerBuilder = $rectorKernel->createFromConfigs(
            [__DIR__ . '/config/config_with_nested_value_objects.php']
        );

        $this->serviceWithValueObject = $containerBuilder->get(ServiceWithValueObject::class);
    }

    public function testInlineValueObjectFunction(): void
    {
        $withType = $this->serviceWithValueObject->getWithType();

        $this->assertInstanceOf(IntegerType::class, $withType->getType());
    }

    public function testInlineValueObjectsFunction(): void
    {
        $withTypes = $this->serviceWithValueObject->getWithTypes();
        $this->assertCount(1, $withTypes);

        $singleWithType = $withTypes[0];
        $this->assertInstanceOf(StringType::class, $singleWithType->getType());
    }
}
