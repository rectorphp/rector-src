<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\Throw_;

use PhpParser\Node;
use PhpParser\Node\Expr\Throw_;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/throw_expression
 *
 * @see \Rector\Tests\DowngradePhp80\Rector\Throw_\DowngradeThrowExprRector\DowngradeThrowExprRectorTest
 */
final class DowngradeThrowExprRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Downgrade throw as expr', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $id = $somethingNonexistent ?? throw new RuntimeException();
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        if (!isset($somethingNonexistent)) {
            throw new RuntimeException();
        }
        $id = $somethingNonexistent;
    }
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
        return [Throw_::class];
    }

    /**
     * @param Throw_ $node
     */
    public function refactor(Node $node): ?Node
    {
        return $node;
    }
}
