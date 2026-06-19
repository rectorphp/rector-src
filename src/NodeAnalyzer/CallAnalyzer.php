<?php

declare(strict_types=1);

namespace Rector\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\If_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\Node\AttributeKey;

final readonly class CallAnalyzer
{
    /**
     * @var array<class-string<Expr>>
     */
    private const array OBJECT_CALL_TYPES = [MethodCall::class, NullsafeMethodCall::class, StaticCall::class];

    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function isObjectCall(Expr $expr): bool
    {
        if ($expr instanceof BooleanNot) {
            $expr = $expr->expr;
        }

        if ($expr instanceof BinaryOp) {
            $isObjectCallLeft = $this->isObjectCall($expr->left);
            $isObjectCallRight = $this->isObjectCall($expr->right);

            return $isObjectCallLeft || $isObjectCallRight;
        }

        return array_any(
            self::OBJECT_CALL_TYPES,
            fn (string $objectCallType): bool => $expr instanceof $objectCallType
        );
    }

    /**
     * @param If_[] $ifs
     */
    public function doesIfHasObjectCall(array $ifs): bool
    {
        return array_any($ifs, fn (If_ $if): bool => $this->isObjectCall($if->cond));
    }

    public function isNewInstance(Variable $variable): bool
    {
        $scope = $variable->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return false;
        }

        $type = $scope->getNativeType($variable);
        if (! $type instanceof ObjectType) {
            return false;
        }

        $className = $type->getClassName();
        if (! $this->reflectionProvider->hasClass($className)) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($className);
        return $classReflection->getNativeReflection()
            ->isInstantiable();
    }
}
