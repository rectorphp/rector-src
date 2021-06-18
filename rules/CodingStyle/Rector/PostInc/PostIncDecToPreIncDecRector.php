<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\PostInc;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\PostDec;
use PhpParser\Node\Expr\PostInc;
use PhpParser\Node\Expr\PreDec;
use PhpParser\Node\Expr\PreInc;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\For_;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodingStyle\Rector\PostInc\PostIncDecToPreIncDecRector\PostIncDecToPreIncDecRectorTest
 */
final class PostIncDecToPreIncDecRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use ++$value or --$value  instead of `$value++` or `$value--`',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($value = 1)
    {
        $value++; echo $value;
        $value--; echo $value;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($value = 1)
    {
        ++$value; echo $value;
        --$value; echo $value;
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [PostInc::class, PostDec::class];
    }

    /**
     * @param PostInc|PostDec $node
     */
    public function refactor(Node $node): ?Node
    {
        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
        if ($this->isAnExpression($parentNode)) {
            return $this->processPrePost($node);
        }

        if ($parentNode instanceof ArrayDimFetch && $this->nodeComparator->areNodesEqual($parentNode->dim, $node)) {
            return $this->processPreArray($node, $parentNode);
        }
        if (! $parentNode instanceof For_) {
            return null;
        }
        if (count($parentNode->loop) !== 1) {
            return null;
        }
        if (! $this->nodeComparator->areNodesEqual($parentNode->loop[0], $node)) {
            return null;
        }
        return $this->processPreFor($node, $parentNode);
    }

    private function isAnExpression(?Node $node = null): bool
    {
        if (! $node instanceof Node) {
            return false;
        }
        return $node instanceof Expression;
    }

    /**
     * @param PostInc|PostDec $node
     */
    private function processPrePost(Node $node): PreInc | PreDec
    {
        if ($node instanceof PostInc) {
            return new PreInc($node->var);
        }

        return new PreDec($node->var);
    }

    /**
     * @param PostInc|PostDec $node
     */
    private function processPreArray(Node $node, ArrayDimFetch $arrayDimFetch): ?Expr
    {
        $parentOfArrayDimFetch = $arrayDimFetch->getAttribute(AttributeKey::PARENT_NODE);
        if (! $this->isAnExpression($parentOfArrayDimFetch)) {
            return null;
        }

        $arrayDimFetch->dim = $node->var;
        $this->addNodeAfterNode($this->processPrePost($node), $arrayDimFetch);

        return $arrayDimFetch->dim;
    }

    /**
     * @param PostInc|PostDec $node
     */
    private function processPreFor(Node $node, For_ $for): PreDec | PreInc
    {
        $for->loop = [$this->processPrePost($node)];
        return $for->loop[0];
    }
}
