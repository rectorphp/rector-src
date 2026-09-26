<?php

declare(strict_types=1);

namespace Rector\Analyzer\SimpleScope;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use Rector\Analyzer\SimpleType\ArrayType;
use Rector\Analyzer\SimpleType\BooleanType;
use Rector\Analyzer\SimpleType\Contract\SimpleTypeInterface;
use Rector\Analyzer\SimpleType\IntegerType;
use Rector\Analyzer\SimpleType\MixedType;
use Rector\Analyzer\SimpleType\NullType;
use Rector\Analyzer\SimpleType\ObjectType;
use Rector\Analyzer\SimpleType\StringType;

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

    public function isObjectType(Expr $expr, string ...$classNames): bool
    {
        $simpleType = $this->getType($expr);
        if (! $simpleType instanceof ObjectType) {
            return false;
        }

        return $simpleType->isInstanceOf(...$classNames);
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
