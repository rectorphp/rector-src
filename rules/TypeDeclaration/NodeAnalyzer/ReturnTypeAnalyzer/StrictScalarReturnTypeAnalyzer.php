<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer\ReturnTypeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\TypeDeclaration\TypeAnalyzer\AlwaysStrictScalarExprAnalyzer;

final class StrictScalarReturnTypeAnalyzer
{
    public function __construct(
        private readonly AlwaysStrictReturnAnalyzer $alwaysStrictReturnAnalyzer,
        private readonly AlwaysStrictScalarExprAnalyzer $alwaysStrictScalarExprAnalyzer,
        private readonly TypeFactory $typeFactory,
    ) {
    }

    public function matchAlwaysScalarReturnType(ClassMethod|Closure|Function_ $functionLike): ?Type
    {
        $returns = $this->alwaysStrictReturnAnalyzer->matchAlwaysStrictReturns($functionLike);
        if ($returns === null) {
            return null;
        }

        $scalarTypes = [];

        foreach ($returns as $return) {
            // we need exact expr return
            if (! $return->expr instanceof Expr) {
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
}
