<?php

declare(strict_types=1);

namespace Rector\ReadWrite\ReadNodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\ReadWrite\Contract\ReadNodeAnalyzerInterface;

/**
 * @implements ReadNodeAnalyzerInterface<Variable>
 */
final class VariableReadNodeAnalyzer implements ReadNodeAnalyzerInterface
{
    public function __construct(
        private readonly JustReadExprAnalyzer $justReadExprAnalyzer
    ) {
    }

    public function supports(Expr $expr): bool
    {
        return $expr instanceof Variable;
    }

    /**
     * @param Variable $expr
     */
    public function isRead(Expr $expr): bool
    {
        if ($this->justReadExprAnalyzer->isReadContext($expr)) {
            return true;
        }

        $scope = $expr->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof \PHPStan\Analyser\Scope) {
            return true;
        }

        if ($expr->name instanceof Expr) {
            return true;
        }

        return $scope->hasVariableType($expr->name)->yes();
    }
}
