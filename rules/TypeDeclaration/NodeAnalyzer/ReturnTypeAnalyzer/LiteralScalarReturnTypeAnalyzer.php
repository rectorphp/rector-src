<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer\ReturnTypeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\TypeDeclaration\TypeAnalyzer\AlwaysStrictScalarExprAnalyzer;

final class LiteralScalarReturnTypeAnalyzer
{
    public function __construct(
        private readonly AlwaysStrictReturnAnalyzer $alwaysStrictReturnAnalyzer,
        private readonly AlwaysStrictScalarExprAnalyzer $alwaysStrictScalarExprAnalyzer,
        private readonly TypeFactory $typeFactory,
    ) {
    }

    public function matchAlwaysLiteralScalarReturnType(ClassMethod|Closure|Function_ $functionLike): ?Type
    {
        $returns = $this->alwaysStrictReturnAnalyzer->matchAlwaysStrictReturns($functionLike);
        if ($returns === null) {
            return null;
        }

        $scalarTypes = [];

        foreach ($returns as $return) {
            if (! $this->isScalarExpression($return->expr)) {
                return null;
            }

            $scalarType = $this->alwaysStrictScalarExprAnalyzer->matchStrictScalarExpr($return->expr);
            if (! $scalarType instanceof Type) {
                return null;
            }

            $scalarTypes[] = $scalarType;
        }

        return $this->typeFactory->createMixedPassedOrUnionType($scalarTypes);
    }

    /**
     * @phpstan-assert-if-true Scalar|ConstFetch|UnaryMinus $expr
     */
    private function isScalarExpression(?Expr $expr): bool
    {
        // Normal scalar values like strings, integers and floats
        if ($expr instanceof Scalar) {
            return true;
        }

        // true / false / null are constants
        if ($expr instanceof ConstFetch &&
            in_array($expr->name->toLowerString(), ['true', 'false', 'null'], true)) {
            return true;
        }

        // Negative numbers are wrapped in UnaryMinus, so check expression inside it
        if ($expr instanceof UnaryMinus && $expr->expr instanceof Scalar) {
            return true;
        }

        return false;
    }
}
