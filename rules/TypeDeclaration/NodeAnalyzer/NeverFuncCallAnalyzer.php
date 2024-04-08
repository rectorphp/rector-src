<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer;

use PhpParser\Node\Expr\Closure;
use PhpParser\Node\FunctionLike;
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

    /**
     * @param ClassMethod|Closure|Function_|Stmt[] $functionLike
     */
    public function hasNeverFuncCall(ClassMethod | Closure | Function_ | array $functionLike): bool
    {
        $hasNeverType = false;
        $stmts = $functionLike instanceof FunctionLike
            ? (array) $functionLike->stmts
            : $functionLike;

        foreach ($stmts as $stmt) {
            if ($stmt instanceof Expression) {
                $stmt = $stmt->expr;
            }

            if ($stmt instanceof Stmt) {
                continue;
            }

            $stmtType = $this->nodeTypeResolver->getNativeType($stmt);
            if ($stmtType instanceof NeverType) {
                $hasNeverType = true;
            }
        }

        return $hasNeverType;
    }
}
