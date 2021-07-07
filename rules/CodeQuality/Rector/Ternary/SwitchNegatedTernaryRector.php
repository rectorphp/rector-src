<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Ternary;

use PhpParser\Node;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Name;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Ternary\SwitchNegatedTernaryRector\SwitchNegatedTernaryRectorTest
 */
final class SwitchNegatedTernaryRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Switch negated ternary condition rector',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(bool $upper, string $name)
    {
        return ! $upper
            ? $name
            : strtoupper($name);
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(bool $upper, string $name)
    {
        return $upper
            ? strtoupper($name)
            : $name;
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
        return [Ternary::class];
    }

    /**
     * @param Ternary $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->cond instanceof BooleanNot) {
            return null;
        }

        if ($node->if === null) {
            return null;
        }

        $node->cond = $node->cond->expr;
        $if = $node->if;
        $else = $node->else;

        $if = $if instanceof Ternary
            ? new ConstFetch(new Name('(' . $this->betterStandardPrinter->print($if) . ')'))
            : $if;
        $else = $else instanceof Ternary
            ? new ConstFetch(new Name('(' . $this->betterStandardPrinter->print($else) . ')'))
            : $else;

        $node->if = $else;
        $node->else = $if;

        return $node;
    }
}
