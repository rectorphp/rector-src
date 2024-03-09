<?php

declare(strict_types=1);

namespace Rector\DeadCode\SideEffect;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;

final readonly class SideEffectNodeDetector
{
    /**
     * @var array<class-string<Expr>>
     */
    private const CALL_EXPR_SIDE_EFFECT_NODE_TYPES = [
        MethodCall::class,
        New_::class,
        NullsafeMethodCall::class,
        StaticCall::class,
    ];

    public function __construct(
        private PureFunctionDetector $pureFunctionDetector
    ) {
    }

    public function detect(Expr $expr, Scope $scope): bool
    {
        if ($expr instanceof Assign) {
            return true;
        }

        if ($this->detectCallExpr($expr, $scope)) {
            return true;
        }

        if ($expr instanceof Cast) {
            return $this->detect($expr->expr, $scope);
        }

        if ($expr instanceof Variable || $expr instanceof ArrayDimFetch) {
            $variable = $this->resolveVariable($expr);
            // variables don't have side effects
            return ! $variable instanceof Variable;
        }

        if ($expr instanceof Array_ && $this->isDetectedInArray($expr, $scope)) {
            return true;
        }

        if ($expr instanceof BinaryOp) {
            if ($this->detect($expr->left, $scope)) {
                return true;
            }

            return $this->detect($expr->right, $scope);
        }

        return false;
    }

    public function detectCallExpr(Node $node, Scope $scope): bool
    {
        if (! $node instanceof Expr) {
            return false;
        }

        if ($node instanceof StaticCall && $this->isClassCallerThrowable($node)) {
            return false;
        }

        if ($node instanceof New_ && $this->isPhpParser($node)) {
            return false;
        }

        $exprClass = $node::class;
        if (in_array($exprClass, self::CALL_EXPR_SIDE_EFFECT_NODE_TYPES, true)) {
            return true;
        }

        if ($node instanceof FuncCall) {
            return ! $this->pureFunctionDetector->detect($node, $scope);
        }

        return false;
    }

    private function isDetectedInArray(Array_ $array, Scope $scope): bool
    {
        foreach ($array->items as $item) {
            if (! $item instanceof ArrayItem) {
                continue;
            }

            if ($this->detect($item->value, $scope)) {
                return true;
            }
        }

        return false;
    }

    private function isPhpParser(New_ $new): bool
    {
        if (! $new->class instanceof FullyQualified) {
            return false;
        }

        $className = $new->class->toString();
        $namespace = Strings::before($className, '\\', 1);

        return $namespace === 'PhpParser';
    }

    private function isClassCallerThrowable(StaticCall $staticCall): bool
    {
        $class = $staticCall->class;
        if (! $class instanceof Name) {
            return false;
        }

        $throwableType = new ObjectType('Throwable');
        $type = new ObjectType($class->toString());

        return $throwableType->isSuperTypeOf($type)
            ->yes();
    }

    private function resolveVariable(ArrayDimFetch|Variable $expr): ?Variable
    {
        while ($expr instanceof ArrayDimFetch) {
            $expr = $expr->var;
        }

        if (! $expr instanceof Variable) {
            return null;
        }

        return $expr;
    }
}
