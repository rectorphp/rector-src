<?php

declare(strict_types=1);

namespace Rector\Tests\PhpAttribute\Printer;

use PhpParser\Node\Arg;
use PhpParser\Node\AttributeGroup;
use Rector\BetterPhpDocParser\PhpDoc\ArrayItemNode;
use Rector\BetterPhpDocParser\PhpDoc\StringNode;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class PhpAttributeGroupFactoryTest extends AbstractLazyTestCase
{
    private PhpAttributeGroupFactory $phpAttributeGroupFactory;

    protected function setUp(): void
    {
        $this->phpAttributeGroupFactory = $this->make(PhpAttributeGroupFactory::class);
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
            new ArrayItemNode(new StringNode('/path'), 'path'),
            new ArrayItemNode(new StringNode('action'), 'name'),
        ]);

        $this->assertCount(2, $args);
        $this->assertContainsOnlyInstancesOf(Arg::class, $args);
    }
}
