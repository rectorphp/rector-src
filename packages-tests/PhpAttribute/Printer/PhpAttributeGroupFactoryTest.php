<?php

declare(strict_types=1);

namespace Rector\Tests\PhpAttribute\Printer;

use PhpParser\Node\Arg;
use PhpParser\Node\AttributeGroup;
use Rector\BetterPhpDocParser\PhpDoc\ArrayItemNode;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Rector\Testing\PHPUnit\AbstractTestCase;

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
        $args = $this->phpAttributeGroupFactory->createArgsFromItems([
            new ArrayItemNode('/path', 'path'),
            new ArrayItemNode('action', 'name'),
        ], 'SomeClass');

        $this->assertCount(2, $args);
        $this->assertContainsOnlyInstancesOf(Arg::class, $args);
    }
}
