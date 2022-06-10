<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\ClassLike;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\ClassLike\MoveAttributeUnderCommentRector\MoveAttributeUnderCommentRectorTest
 */
final class MoveAttributeUnderCommentRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Move attributes under comment', [
            new CodeSample(
                <<<'CODE_SAMPLE'
#[Attribute]
/**
 * @property-read $name
 */
class SomeClass
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
/**
 * @property-read $name
 */
#[Attribute]
class SomeClass
{
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassLike::class];
    }

    /**
     * @param ClassLike $node
     */
    public function refactor(Node $node): ?Node
    {
        return null;
    }
}
