<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\TernaryNarrowedScopeRefresh\Source;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Cast\Int_;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\RuleDefinition;

final class CastFirstArgToIntRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('', []);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?MethodCall
    {
        $firstArg = $node->getArgs()[0] ?? null;
        if (! $firstArg instanceof Arg || $firstArg->value instanceof Int_) {
            return null;
        }

        $firstArg->value = new Int_($firstArg->value);

        return $node;
    }
}
