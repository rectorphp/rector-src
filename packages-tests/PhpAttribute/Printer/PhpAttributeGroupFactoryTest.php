<?php

declare(strict_types=1);

namespace Rector\Tests\PhpAttribute\Printer;

use PhpParser\Node\Arg;
use PhpParser\Node\AttributeGroup;
use Rector\PhpAttribute\Printer\PhpAttributeGroupFactory;
use Rector\Testing\PHPUnit\AbstractTestCase;
use ReflectionMethod;

final class PhpAttributeGroupFactoryTest extends AbstractTestCase
{
    private PhpAttributeGroupFactory $phpAttributeGroupFactory;

    protected function setUp(): void
    {
        $this->boot();

        $this->phpAttributeGroupFactory = $this->getService(PhpAttributeGroupFactory::class);
    }

    public function testCreateFromClassWithItems(): void
    {
        $attributeGroup = $this->phpAttributeGroupFactory->createFromClassWithItems(
            'Symfony\Component\Routing\Annotation\Route',
            [
                'path' => '/path',
                'name' => 'action',
            ]
        );

        $this->assertInstanceOf(AttributeGroup::class, $attributeGroup);
    }

    public function testCreateArgsFromItems(): void
    {
        $reflectionMethod = new ReflectionMethod($this->phpAttributeGroupFactory, 'createArgsFromItems');
        $reflectionMethod->setAccessible(true);

        $args = $reflectionMethod->invokeArgs($this->phpAttributeGroupFactory, [[
            'path' => '/path',
            'name' => 'action',
        ]]);

        $this->assertIsArray($args);
        $this->assertCount(2, $args);
        $this->assertContainsOnlyInstancesOf(Arg::class, $args);
    }
}
