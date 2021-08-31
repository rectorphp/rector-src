<?php

declare(strict_types=1);

namespace Rector\Php81\Rector\ClassConst;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassConst;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://php.watch/versions/8.1/final-class-const
 *
 * @see \Rector\Tests\Php81\Rector\ClassConst\FinalizePublicClassConstantRector\FinalizePublicClassConstantRectorTest
 */
final class FinalizePublicClassConstantRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add final to constants that', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public const NAME = 'value';
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public final const NAME = 'value';
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
        return [ClassConst::class];
    }

    /**
     * @param ClassConst $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->isPrivate()) {
            return null;
        }
        if ($node->isProtected()) {
            return null;
        }
        if ($node->isFinal()) {
            return null;
        }

        $this->visibilityManipulator->makeFinal($node);
        return $node;
    }
}
