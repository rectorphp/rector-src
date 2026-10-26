<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser;

use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use Rector\BetterPhpDocParser\Contract\BasePhpDocNodeVisitorInterface;
use Rector\BetterPhpDocParser\DataProvider\CurrentTokenIteratorProvider;
use Rector\BetterPhpDocParser\ValueObject\Parser\BetterTokenIterator;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeVisitor\CloningPhpDocNodeVisitor;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeVisitor\ParentConnectingPhpDocNodeVisitor;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\BetterPhpDocParser\PhpDocNodeMapperTest
 */
final readonly class PhpDocNodeMapper
{
    private NodeTraverser $phpDocNodeTraverser;

    /**
     * @param BasePhpDocNodeVisitorInterface[] $phpDocNodeVisitors
     */
    public function __construct(
        private CurrentTokenIteratorProvider $currentTokenIteratorProvider,
        ParentConnectingPhpDocNodeVisitor $parentConnectingPhpDocNodeVisitor,
        CloningPhpDocNodeVisitor $cloningPhpDocNodeVisitor,
        private array $phpDocNodeVisitors
    ) {
        Assert::notEmpty($phpDocNodeVisitors);

        $this->phpDocNodeTraverser = new NodeTraverser([
            $parentConnectingPhpDocNodeVisitor,
            $cloningPhpDocNodeVisitor,
            ...array_values($this->phpDocNodeVisitors),
        ]);
    }

    public function transform(PhpDocNode $phpDocNode, BetterTokenIterator $betterTokenIterator): PhpDocNode
    {
        $this->currentTokenIteratorProvider->setBetterTokenIterator($betterTokenIterator);

        [$phpDocNode] = $this->phpDocNodeTraverser->traverse([$phpDocNode]);
        Assert::isInstanceOf($phpDocNode, PhpDocNode::class);

        return $phpDocNode;
    }
}
