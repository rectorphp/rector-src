<?php

declare(strict_types=1);

namespace Rector\NodeAnalyzer;

use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Expr\UnaryPlus;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\InterpolatedString;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\UnionType;
use Rector\Enum\ObjectReference;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class ExprAnalyzer
{
    public function isNonTypedFromParam(Expr $expr): bool
    {
        if (! $expr instanceof Variable) {
            return false;
        }

        $scope = $expr->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            // uncertainty when scope not yet filled/overlapped on just refactored
            return true;
        }

        $nativeType = $scope->getNativeType($expr);
        $type = $scope->getType($expr);
        if (
            ($nativeType instanceof MixedType && ! $nativeType->isExplicitMixed())
            ||
            ($nativeType instanceof MixedType && ! $type instanceof MixedType)
        ) {
            return true;
        }

        if ($nativeType instanceof ObjectWithoutClassType && ! $type instanceof ObjectWithoutClassType) {
            return true;
        }

        if ($nativeType instanceof UnionType) {
            return ! $nativeType->equals($type);
        }

        return ! $nativeType->isSuperTypeOf($type)
            ->yes();
    }

    public function isDynamicExpr(Expr $expr): bool
    {
        // Unwrap UnaryPlus and UnaryMinus
        if ($expr instanceof UnaryPlus || $expr instanceof UnaryMinus) {
            $expr = $expr->expr;
        }

        if ($expr instanceof Array_) {
            return $this->isDynamicArray($expr);
        }

        if ($expr instanceof Scalar) {
            // string interpolation is true, otherwise false
            return $expr instanceof InterpolatedString;
        }

        return ! $this->isAllowedConstFetchOrClassConstFetch($expr);
    }

    public function isDynamicArray(Array_ $array): bool
    {
        foreach ($array->items as $item) {
            if (! $item instanceof ArrayItem) {
                continue;
            }

            if (! $this->isAllowedArrayKey($item->key)) {
                return true;
            }

            if (! $this->isAllowedArrayValue($item->value)) {
                return true;
            }
        }

        return false;
    }

    private function isAllowedConstFetchOrClassConstFetch(Expr $expr): bool
    {
        if ($expr instanceof ConstFetch) {
            return true;
        }

        if ($expr instanceof ClassConstFetch) {
            if (! $expr->class instanceof Name) {
                return false;
            }

            if (! $expr->name instanceof Identifier) {
                return false;
            }

            // static::class cannot be used for compile-time class name resolution
            return $expr->class->toString() !== ObjectReference::STATIC;
        }

        return false;
    }

    private function isAllowedArrayKey(?Expr $expr): bool
    {
        if (! $expr instanceof Expr) {
            return true;
        }

        if ($expr instanceof String_) {
            return true;
        }

        return $expr instanceof Int_;
    }

    private function isAllowedArrayValue(Expr $expr): bool
    {
        if ($expr instanceof Array_) {
            return ! $this->isDynamicArray($expr);
        }

        return ! $this->isDynamicExpr($expr);
    }
}
