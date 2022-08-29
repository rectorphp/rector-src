<?php

declare(strict_types=1);

namespace Rector\Tests\PhpDocParser\PhpDocParser\PhpDocNodeVisitor;

use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeVisitor\ParentConnectingPhpDocNodeVisitor;
use Rector\PhpDocParser\PhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class ParentConnectingPhpDocNodeVisitorTest extends AbstractTestCase
{
    private PhpDocNodeTraverser $phpDocNodeTraverser;

    protected function setUp(): void
    {
        $this->boot();

        $this->phpDocNodeTraverser = $this->getService(PhpDocNodeTraverser::class);

        /** @var ParentConnectingPhpDocNodeVisitor $parentConnectingPhpDocNodeVisitor */
        $parentConnectingPhpDocNodeVisitor = $this->getService(ParentConnectingPhpDocNodeVisitor::class);
        $this->phpDocNodeTraverser->addPhpDocNodeVisitor($parentConnectingPhpDocNodeVisitor);
    }

    public function testTypeNode(): void
    {
        $phpDocNode = $this->createPhpDocNode();
        $this->phpDocNodeTraverser->traverse($phpDocNode);

        /** @var PhpDocTagNode $phpDocChildNode */
        $phpDocChildNode = $phpDocNode->children[0];

        $returnTagValueNode = $phpDocChildNode->value;

        $this->assertInstanceOf(ReturnTagValueNode::class, $returnTagValueNode);

        /** @var ReturnTagValueNode $returnTagValueNode */
        $returnParent = $returnTagValueNode->getAttribute(PhpDocAttributeKey::PARENT);
        $this->assertSame($phpDocChildNode, $returnParent);

        $returnTypeParent = $returnTagValueNode->type->getAttribute(PhpDocAttributeKey::PARENT);
        $this->assertSame($returnTagValueNode, $returnTypeParent);

        // test child + parent node
        $phpDocChildNode = $phpDocNode->children[0];
        $this->assertInstanceOf(PhpDocTagNode::class, $phpDocChildNode);

        $childParent = $phpDocChildNode->getAttribute(PhpDocAttributeKey::PARENT);
        $this->assertSame($phpDocNode, $childParent);
    }

    private function createPhpDocNode(): PhpDocNode
    {
        $returnTagValueNode = new ReturnTagValueNode(new IdentifierTypeNode('string'), '');

        return new PhpDocNode([
            new PhpDocTagNode('@return', $returnTagValueNode),
            new PhpDocTextNode('some text'),
        ]);
    }
}
