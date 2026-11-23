<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\Expression;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast\Void_;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Nop;
use PhpParser\NodeVisitor;
use PHPStan\Reflection\Php\PhpPropertyReflection;
use Rector\DeadCode\NodeAnalyzer\NoDiscardCallAnalyzer;
use Rector\DeadCode\NodeManipulator\LivingCodeManipulator;
use Rector\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\RemoveDeadStmtRectorTest
 */
final class RemoveDeadStmtRector extends AbstractRector
{
    public function __construct(
        private readonly LivingCodeManipulator $livingCodeManipulator,
        private readonly NoDiscardCallAnalyzer $noDiscardCallAnalyzer,
        private readonly PropertyFetchAnalyzer $propertyFetchAnalyzer,
        private readonly ReflectionResolver $reflectionResolver,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove dead code statements', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$value = 5;
$value;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$value = 5;
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     * @return Node[]|Node|null|NodeVisitor::REMOVE_NODE
     */
    public function refactor(Node $node): array|Node|null|int
    {
        if ($this->hasGetMagic($node)) {
            return null;
        }

        if ($this->isVoidCastNoDiscardCall($node)) {
            return null;
        }

        if ($this->isShortCircuitIssetWithSideEffect($node)) {
            return null;
        }

        $livingCode = $this->livingCodeManipulator->keepLivingCodeFromExpr($node->expr);
        if ($livingCode === []) {
            return $this->removeNodeAndKeepComments($node);
        }

        if ($livingCode === [$node->expr]) {
            return null;
        }

        $newNode = clone $node;
        $newNode->expr = array_shift($livingCode);

        $newNodes = [$newNode];
        foreach ($livingCode as $singleLivingCode) {
            $newNodes[] = new Expression($singleLivingCode);
        }

        return $newNodes;
    }

    private function hasGetMagic(Expression $expression): bool
    {
        if (! $this->propertyFetchAnalyzer->isPropertyFetch($expression->expr)) {
            return false;
        }

        $propertyFetch = $expression->expr;
        $phpPropertyReflection = $this->reflectionResolver->resolvePropertyReflectionFromPropertyFetch($propertyFetch);

        /**
         *  property not found assume has class has __get method
         *  that can call non-defined property, that can have some special handling, eg: throw on special case
         */
        return ! $phpPropertyReflection instanceof PhpPropertyReflection;
    }

    /**
     * isset() with more args stops on first unset one, so side effects in later args are conditional
     */
    private function isShortCircuitIssetWithSideEffect(Expression $expression): bool
    {
        if (! $expression->expr instanceof Isset_) {
            return false;
        }

        $vars = $expression->expr->vars;
        array_shift($vars);

        return array_any($vars, fn (Expr $var): bool => $this->livingCodeManipulator->keepLivingCodeFromExpr($var) !== []);
    }

    private function isVoidCastNoDiscardCall(Expression $expression): bool
    {
        if (! $expression->expr instanceof Void_) {
            return false;
        }

        return $this->noDiscardCallAnalyzer->isNoDiscardCall($expression->expr->expr);
    }

    /**
     * @return NodeVisitor::REMOVE_NODE|Node
     */
    private function removeNodeAndKeepComments(Expression $expression): int|Node
    {
        if ($expression->getDocComment() instanceof Doc) {
            $nop = new Nop();
            $nop->setDocComment($expression->getDocComment());

            return $nop;
        }

        return NodeVisitor::REMOVE_NODE;
    }
}
