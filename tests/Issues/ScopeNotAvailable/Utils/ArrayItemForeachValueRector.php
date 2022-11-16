<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\ScopeNotAvailable\Utils;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use PHPStan\Analyser\Scope;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ArrayItemForeachValueRector extends AbstractScopeAwareRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Hello!', [new CodeSample('', '')]);
    }

    /**
     * @return array<class-string<Expr>>
     */
    public function getNodeTypes(): array
    {
        return [Variable::class];
    }

    public function refactorWithScope(Node $node, Scope $scope): Node
    {
        return $node;
    }
}
