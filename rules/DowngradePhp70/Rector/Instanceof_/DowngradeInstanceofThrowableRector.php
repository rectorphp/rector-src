<?php

declare(strict_types=1);

namespace Rector\DowngradePhp70\Rector\Instanceof_;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use Rector\Core\Rector\AbstractRector;
use Rector\Naming\Naming\VariableNaming;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Throwable was introduced in PHP 7.0 so to support older versions we need to also check for Exception.
 * @see https://www.php.net/manual/en/class.throwable.php
 * @see \Rector\Tests\DowngradePhp70\Rector\Instanceof_\DowngradeInstanceofThrowableRector\DowngradeInstanceofThrowableRectorTest
 */
final class DowngradeInstanceofThrowableRector extends AbstractRector
{
    public function __construct(
        private readonly VariableNaming $variableNaming,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add `instanceof Exception` check as a fallback to `instanceof Throwable` to support exception hierarchies in PHP 5',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
return $e instanceof \Throwable;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
return ($throwable = $e) instanceof \Throwable || $throwable instanceof \Exception;
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
        return [Instanceof_::class];
    }

    /**
     * @param Instanceof_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! ($node->class instanceof Name && $this->nodeNameResolver->isName($node->class, 'Throwable'))) {
            return null;
        }
        $createdByRule = $node->getAttribute(AttributeKey::CREATED_BY_RULE) ?? [];
        if (in_array(self::class, $createdByRule, true)) {
            return null;
        }

        // Ensure the refactoring is idempotent.
        if ($this->isAlreadyTransformed($node)) {
            return null;
        }

        // Store the value into a temporary variable to prevent running possible side-effects twice
        // when the expression is e.g. function.
        $variable = $this->createVariable($node);
        $throwableCheck = new Instanceof_(new Assign($variable, $node->expr), $node->class);
        $exceptionFallbackCheck = $this->createFallbackCheck($variable);
        $expression = new BooleanOr($throwableCheck, $exceptionFallbackCheck);

        return $expression;
    }

    private function createVariable(Instanceof_ $expr): Variable
    {
        $currentStmt = $expr->getAttribute(AttributeKey::CURRENT_STATEMENT);
        $scope = $currentStmt->getAttribute(AttributeKey::SCOPE);

        return new Variable($this->variableNaming->createCountedValueName('throwable', $scope));
    }

    /**
     * Also checks similar manual transformations.
     */
    private function isAlreadyTransformed(Instanceof_ $instanceof_): bool
    {
        /** @var Node $parentNode */
        $parentNode = $instanceof_->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parentNode instanceof BooleanOr || $parentNode->left !== $instanceof_) {
            return false;
        }

        $hasVariableToFindInRight = ($var = $instanceof_->expr) instanceof Variable || ($instanceof_->expr instanceof Assign && ($var = $instanceof_->expr->var) instanceof Variable);
        if (! $hasVariableToFindInRight) {
            return false;
        }

        $expectedRight = $this->createFallbackCheck($var);
        return $this->nodeComparator->areNodesEqual($expectedRight, $parentNode->right);
    }

    private function createFallbackCheck(Variable $variable): Instanceof_
    {
        return new Instanceof_($variable, new FullyQualified('Exception'));
    }
}
