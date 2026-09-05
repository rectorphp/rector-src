<?php

declare(strict_types=1);

namespace Rector\SimpleScope;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use Rector\SimpleType\ArrayType;
use Rector\SimpleType\BooleanType;
use Rector\SimpleType\Contract\SimpleTypeInterface;
use Rector\SimpleType\IntegerType;
use Rector\SimpleType\MixedType;
use Rector\SimpleType\NullType;
use Rector\SimpleType\ObjectType;
use Rector\SimpleType\StringType;

// PHPStan-free scope; holds variable types resolved by SimpleScopeResolver
final class SimpleScope
{
    /**
     * @var array<string, SimpleTypeInterface>
     */
    private array $variableTypes = [];

    public function setVariableType(string $name, SimpleTypeInterface $simpleType): void
    {
        $this->variableTypes[$name] = $simpleType;
    }

    public function getType(Expr $expr): SimpleTypeInterface
    {
        if ($expr instanceof String_) {
            return new StringType();
        }

        if ($expr instanceof Int_) {
            return new IntegerType();
        }

        if ($expr instanceof Array_) {
            return new ArrayType();
        }

        if ($expr instanceof ConstFetch) {
            return $this->resolveConstFetchType($expr);
        }

        if ($expr instanceof New_ && $expr->class instanceof Name) {
            return new ObjectType($expr->class->toString());
        }

        if ($expr instanceof Variable && is_string($expr->name)) {
            return $this->variableTypes[$expr->name] ?? new MixedType();
        }

        return new MixedType();
    }

    private function resolveConstFetchType(ConstFetch $constFetch): SimpleTypeInterface
    {
        $constantName = strtolower($constFetch->name->toString());

        if ($constantName === 'null') {
            return new NullType();
        }

        if ($constantName === 'true' || $constantName === 'false') {
            return new BooleanType();
        }

        return new MixedType();
    }
}
