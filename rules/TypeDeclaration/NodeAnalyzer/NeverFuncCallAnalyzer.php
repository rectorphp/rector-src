<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer;

use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\NeverType;
use Rector\NodeTypeResolver\NodeTypeResolver;

final readonly class NeverFuncCallAnalyzer
{
    public function __construct(
        private NodeTypeResolver $nodeTypeResolver,
    ) {
    }

    public function hasNeverFuncCall(ClassMethod | Closure | Function_ $functionLike): bool
    {
        return array_any((array) $functionLike->stmts, fn (Stmt $stmt): bool => $this->isWithNeverTypeExpr($stmt));
    }

    public function isWithNeverTypeExpr(Stmt $stmt, bool $withNativeNeverType = true): bool
    {
        if ($stmt instanceof Expression) {
            $stmt = $stmt->expr;
        }

        if ($stmt instanceof Stmt) {
            return false;
        }

        $stmtType = $withNativeNeverType
            ? $this->nodeTypeResolver->getNativeType($stmt)
            : $this->nodeTypeResolver->getType($stmt);

        return $stmtType instanceof NeverType;
    }
}
