<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser;

use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use Rector\BetterPhpDocParser\Contract\BasePhpDocNodeVisitorInterface;
use Rector\BetterPhpDocParser\DataProvider\CurrentTokenIteratorProvider;
use Rector\BetterPhpDocParser\ValueObject\Parser\BetterTokenIterator;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeVisitor\CloningPhpDocNodeVisitor;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeVisitor\ParentConnectingPhpDocNodeVisitor;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\BetterPhpDocParser\PhpDocNodeMapperTest
 */
final class PhpDocNodeMapper
{
    /**
     * @param BasePhpDocNodeVisitorInterface[] $phpDocNodeVisitors
     */
    public function __construct(
        private readonly CurrentTokenIteratorProvider $currentTokenIteratorProvider,
        private readonly ParentConnectingPhpDocNodeVisitor $parentConnectingPhpDocNodeVisitor,
        private readonly CloningPhpDocNodeVisitor $cloningPhpDocNodeVisitor,
        private readonly array $phpDocNodeVisitors
    ) {
        Assert::notEmpty($phpDocNodeVisitors);
    }

    public function transform(PhpDocNode $phpDocNode, BetterTokenIterator $betterTokenIterator): void
    {
        $this->currentTokenIteratorProvider->setBetterTokenIterator($betterTokenIterator);

        $connectingAndCloningPhpDocNodeTraverser = new PhpDocNodeTraverser();
        $connectingAndCloningPhpDocNodeTraverser->addPhpDocNodeVisitor($this->parentConnectingPhpDocNodeVisitor);
        $connectingAndCloningPhpDocNodeTraverser->addPhpDocNodeVisitor($this->cloningPhpDocNodeVisitor);
        $connectingAndCloningPhpDocNodeTraverser->traverse($phpDocNode);

        $phpDocNodeTraverser = new PhpDocNodeTraverser();
        foreach ($this->phpDocNodeVisitors as $phpDocNodeVisitor) {
            $phpDocNodeTraverser->addPhpDocNodeVisitor($phpDocNodeVisitor);
        }

        $phpDocNodeTraverser->traverse($phpDocNode);
    }
}
