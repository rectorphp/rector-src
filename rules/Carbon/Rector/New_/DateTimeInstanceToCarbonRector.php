<?php

declare(strict_types=1);

namespace Rector\Carbon\Rector\New_;

use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/briannesbitt/Carbon/issues/231
 *
 * @see \Rector\Tests\Carbon\Rector\New_\DateTimeInstanceToCarbonRector\DateTimeInstanceToCarbonRectorTest
 */
final class DateTimeInstanceToCarbonRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert new DateTime() to Carbon::*()', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $date = new \DateTime('today');
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $date = \Carbon::today();
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
        return [New_::class];
    }

    /**
     * @param New_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->class, 'DateTime')) {
            return null;
        }

        // no arg? ::now()
        $carbonFullyQualified = new FullyQualified('Carbon\Carbon');
        if ($node->args === []) {
            return new StaticCall($carbonFullyQualified, new Identifier('now'));
        }

        if (count($node->getArgs()) === 1) {
            $firstArg = $node->getArgs()[0];

            if ($firstArg->value instanceof Node\Scalar\String_) {
                if ($firstArg->value->value === 'now') {
                    return new StaticCall($carbonFullyQualified, new Identifier('now'));
                }

                if ($firstArg->value->value === 'today') {
                    return new StaticCall($carbonFullyQualified, new Identifier('today'));
                }
            }
        }

        return null;
    }
}
