<?php

declare(strict_types=1);

namespace Rector\Strict\NodeFactory;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\UnionType;

final class ExactCompareFactory
{
    public function createIdenticalFalsyCompare(Type $exprType, Expr $expr, bool $treatAsNonEmpty = false): Expr|null
    {
        if ($exprType instanceof StringType) {
            return new Identical($expr, new String_(''));
        }

        if ($exprType instanceof IntegerType) {
            return new Identical($expr, new LNumber(0));
        }

        if ($exprType instanceof BooleanType) {
            return new Identical($expr, new ConstFetch(new Name('false')));
        }

        if ($exprType instanceof ArrayType) {
            return new Identical($expr, new Array_([]));
        }

        if (! $exprType instanceof UnionType) {
            return null;
        }

        if (! TypeCombinator::containsNull($exprType)) {
            return null;
        }

        return $this->createTruthyFromUnionType($exprType, $expr, $treatAsNonEmpty);
    }

    public function createNotIdenticalFalsyCompare(Type $exprType, Expr $expr): NotIdentical|Identical|Instanceof_|null
    {
        if ($exprType instanceof StringType) {
            return new NotIdentical($expr, new String_(''));
        }

        if ($exprType instanceof IntegerType) {
            return new NotIdentical($expr, new LNumber(0));
        }

        if ($exprType instanceof ArrayType) {
            return new NotIdentical($expr, new Array_([]));
        }

        if (! $exprType instanceof UnionType) {
            return null;
        }

        if (! TypeCombinator::containsNull($exprType)) {
            return null;
        }

        return $this->createFromUnionType($exprType, $expr);
    }

    private function createFromUnionType(Type|UnionType $exprType, Expr $expr): Identical|Instanceof_|NotIdentical
    {
        $exprType = TypeCombinator::removeNull($exprType);

        if ($exprType instanceof BooleanType) {
            $trueConstFetch = new ConstFetch(new Name('true'));
            return new Identical($expr, $trueConstFetch);
        }

        if ($exprType instanceof TypeWithClassName) {
            return new Instanceof_($expr, new FullyQualified($exprType->getClassName()));
        }

        $nullConstFetch = new ConstFetch(new Name('null'));
        return new NotIdentical($expr, $nullConstFetch);
    }

    private function resolveFalsyTypesCount(UnionType $unionType): int
    {
        $falsyTypesCount = 0;

        foreach ($unionType->getTypes() as $unionedType) {
            if ($unionedType instanceof StringType) {
                ++$falsyTypesCount;
            }

            if ($unionedType instanceof IntegerType) {
                ++$falsyTypesCount;
            }

            if ($unionedType instanceof FloatType) {
                ++$falsyTypesCount;
            }

            if ($unionedType instanceof ArrayType) {
                ++$falsyTypesCount;
            }
        }

        return $falsyTypesCount;
    }

    private function createTruthyFromUnionType(UnionType $unionType, Expr $expr, bool $treatAsNonEmpty): Expr|null
    {
        $unionType = TypeCombinator::removeNull($unionType);

        if ($unionType instanceof UnionType) {
            $falsyTypesCount = $this->resolveFalsyTypesCount($unionType);

            // impossible to refactor to string value compare, as many falsy values can be provided
            if ($falsyTypesCount > 1) {
                return null;
            }
        }

        if ($unionType instanceof BooleanType) {
            $trueConstFetch = new ConstFetch(new Name('true'));
            return new Identical($expr, $trueConstFetch);
        }

        $nullConstFetch = new ConstFetch(new Name('null'));
        $toNullIdentical = new Identical($expr, $nullConstFetch);

        // assume we have to check empty string, integer and bools
        if (! $treatAsNonEmpty) {
            $scalarFalsyIdentical = $this->createIdenticalFalsyCompare($unionType, $expr);
            if (! $scalarFalsyIdentical instanceof Expr) {
                return null;
            }

            return new BooleanOr($toNullIdentical, $scalarFalsyIdentical);
        }

        return $toNullIdentical;
    }
}
