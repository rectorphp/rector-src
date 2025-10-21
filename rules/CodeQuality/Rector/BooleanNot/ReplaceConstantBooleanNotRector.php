<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\BooleanNot;

use PhpParser\Node;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replace negated boolean literals with their simplified equivalents
 *
 * @see \Rector\Tests\CodeQuality\Rector\BooleanNot\ReplaceConstantBooleanNotRector\ReplaceConstantBooleanNotRectorTest
 */
final class ReplaceConstantBooleanNotRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace negated boolean literals (!false, !true) with their simplified equivalents (true, false)',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
if (!false) {
    return 'always true';
}

if (!true) {
    return 'never reached';
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
if (true) {
    return 'always true';
}

if (false) {
    return 'never reached';
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
        return [BooleanNot::class];
    }

    /**
     * @param BooleanNot $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node instanceof BooleanNot) {
            return null;
        }

        if (! $node->expr instanceof ConstFetch) {
            return null;
        }

        $constantName = $this->getName($node->expr);

        if ($constantName === 'false') {
            return new ConstFetch(new Name('true'));
        }

        if ($constantName === 'true') {
            return new ConstFetch(new Name('false'));
        }

        return null;
    }
}
