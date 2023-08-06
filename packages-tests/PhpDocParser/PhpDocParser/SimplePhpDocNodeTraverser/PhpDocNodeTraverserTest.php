<?php

declare(strict_types=1);

namespace Rector\Tests\PhpDocParser\PhpDocParser\SimplePhpDocNodeTraverser;

use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class PhpDocNodeTraverserTest extends AbstractLazyTestCase
{
    /**
     * @var string
     */
    private const SOME_DESCRIPTION = 'some description';

    private PhpDocNodeTraverser $phpDocNodeTraverser;

    protected function setUp(): void
    {
        $this->phpDocNodeTraverser = $this->make(PhpDocNodeTraverser::class);
    }

    public function test(): void
    {
        $varTagValueNode = new VarTagValueNode(new IdentifierTypeNode('string'), '', '');
        $phpDocNode = new PhpDocNode([new PhpDocTagNode('@var', $varTagValueNode)]);

        $this->phpDocNodeTraverser->traverseWithCallable($phpDocNode, '', static function (Node $node): Node {
            if (! $node instanceof VarTagValueNode) {
                return $node;
            }

            $node->description = self::SOME_DESCRIPTION;
            return $node;
        });

        $varTagValueNodes = $phpDocNode->getVarTagValues();
        $this->assertSame(self::SOME_DESCRIPTION, $varTagValueNodes[0]->description);
    }
}
