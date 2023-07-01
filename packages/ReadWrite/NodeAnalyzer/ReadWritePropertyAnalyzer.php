<?php

declare(strict_types=1);

namespace Rector\ReadWrite\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PHPStan\Analyser\Scope;
use Rector\Core\NodeManipulator\AssignManipulator;
use Rector\DeadCode\SideEffect\PureFunctionDetector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\ReadWrite\Contract\ParentNodeReadAnalyzerInterface;
use Rector\ReadWrite\ParentNodeReadAnalyzer\ArgParentNodeReadAnalyzer;
use Rector\ReadWrite\ParentNodeReadAnalyzer\ArrayDimFetchParentNodeReadAnalyzer;
use Rector\ReadWrite\ParentNodeReadAnalyzer\IncDecParentNodeReadAnalyzer;

/**
 * Possibly re-use the same logic from PHPStan rule:
 * https://github.com/phpstan/phpstan-src/blob/8f16632f6ccb312159250bc06df5531fa4a1ff91/src/Rules/DeadCode/UnusedPrivatePropertyRule.php#L64-L116
 */
final class ReadWritePropertyAnalyzer
{
    /**
     * @var ParentNodeReadAnalyzerInterface[]
     */
    private array $parentNodeReadAnalyzers = [];

    public function __construct(
        private readonly AssignManipulator $assignManipulator,
        private readonly ReadExprAnalyzer $readExprAnalyzer,
        private readonly PureFunctionDetector $pureFunctionDetector,
        ArgParentNodeReadAnalyzer $argParentNodeReadAnalyzer,
        IncDecParentNodeReadAnalyzer $incDecParentNodeReadAnalyzer,
        ArrayDimFetchParentNodeReadAnalyzer $arrayDimFetchParentNodeReadAnalyzer,
    ) {
        $this->parentNodeReadAnalyzers = [
            $argParentNodeReadAnalyzer,
            $incDecParentNodeReadAnalyzer,
            $arrayDimFetchParentNodeReadAnalyzer,
        ];
    }

    public function isRead(PropertyFetch | StaticPropertyFetch $node, Node $parentNode, Scope $scope): bool
    {
        foreach ($this->parentNodeReadAnalyzers as $parentNodeReadAnalyzer) {
            if ($parentNodeReadAnalyzer->isRead($node, $parentNode)) {
                return true;
            }
        }

        if ($parentNode instanceof AssignOp) {
            return true;
        }

        if (! $parentNode instanceof ArrayDimFetch) {
            return ! $this->assignManipulator->isLeftPartOfAssign($node);
        }

        if ($parentNode->dim === $node && $this->isNotInsideIssetUnset($parentNode)) {
            return $this->isArrayDimFetchRead($parentNode);
        }

        if ($this->assignManipulator->isLeftPartOfAssign($parentNode)) {
            return false;
        }

        if (! $this->isArrayDimFetchInImpureFunction($parentNode, $node, $scope)) {
            return $this->isNotInsideIssetUnset($parentNode);
        }

        return false;
    }

    private function isArrayDimFetchInImpureFunction(ArrayDimFetch $arrayDimFetch, Node $node, Scope $scope): bool
    {
        if ($arrayDimFetch->var === $node) {
            $fromFuncCallName = $arrayDimFetch->getAttribute(AttributeKey::FROM_FUNC_CALL_NAME);
            if ($fromFuncCallName !== null) {
                return ! $this->pureFunctionDetector->detect($fromFuncCallName, $scope);
            }
        }

        return false;
    }

    private function isNotInsideIssetUnset(ArrayDimFetch $arrayDimFetch): bool
    {
        if ($arrayDimFetch->getAttribute(AttributeKey::IS_ISSET_VAR) === true) {
            return false;
        }

        return $arrayDimFetch->getAttribute(AttributeKey::IS_UNSET_VAR) !== true;
    }

    private function isArrayDimFetchRead(ArrayDimFetch $arrayDimFetch): bool
    {
        if (! $this->assignManipulator->isLeftPartOfAssign($arrayDimFetch)) {
            return false;
        }

        if ($arrayDimFetch->var instanceof ArrayDimFetch) {
            return true;
        }

        // the array dim fetch is assing here only; but the variable might be used later
        return $this->readExprAnalyzer->isExprRead($arrayDimFetch->var);
    }
}
