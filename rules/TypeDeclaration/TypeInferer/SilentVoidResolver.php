<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeInferer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Finally_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\Throw_;
use PhpParser\Node\Stmt\TryCatch;
use PHPStan\Reflection\ClassReflection;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Reflection\ReflectionResolver;

final readonly class SilentVoidResolver
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private ReflectionResolver $reflectionResolver,
    ) {
    }

    public function hasExclusiveVoid(ClassMethod | Closure | Function_ $functionLike): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($functionLike);
        if ($classReflection instanceof ClassReflection && $classReflection->isInterface()) {
            return false;
        }

        if ($this->betterNodeFinder->hasInstancesOfInFunctionLikeScoped(
            $functionLike,
            [Yield_::class, YieldFrom::class]
        )) {
            return false;
        }

        $return = $this->betterNodeFinder->findFirstInFunctionLikeScoped(
            $functionLike,
            static fn (Node $node): bool => $node instanceof Return_ && $node->expr instanceof Expr
        );
        return ! $return instanceof Return_;
    }

    public function hasSilentVoid(FunctionLike $functionLike): bool
    {
        if ($functionLike instanceof ArrowFunction) {
            return false;
        }

        $stmts = (array) $functionLike->getStmts();
        if ($this->hasStmtsAlwaysReturn($stmts)) {
            return false;
        }

        foreach ($stmts as $stmt) {
            // has switch with always return
            if ($stmt instanceof Switch_ && $this->isSwitchWithAlwaysReturn($stmt)) {
                return false;
            }

            // is part of try/catch
            if ($stmt instanceof TryCatch && $this->isTryCatchAlwaysReturn($stmt)) {
                return false;
            }

            if ($stmt instanceof Throw_) {
                return false;
            }

            if (! $stmt instanceof If_) {
                continue;
            }

            if (! $stmt->else instanceof Else_) {
                continue;
            }

            if (! $this->hasStmtsAlwaysReturn($stmt->stmts)) {
                continue;
            }

            if (! $this->hasStmtsAlwaysReturn($stmt->else->stmts)) {
                continue;
            }

            return false;
        }

        return true;
    }

    /**
     * @param Stmt[]|Expression[] $stmts
     */
    private function hasStmtsAlwaysReturn(array $stmts): bool
    {
        foreach ($stmts as $stmt) {
            if ($stmt instanceof Expression) {
                $stmt = $stmt->expr;
            }

            // is 1st level return
            if ($stmt instanceof Return_) {
                return true;
            }

            if ($stmt instanceof Catch_ && $this->hasStmtsAlwaysReturn($stmt->stmts)) {
                return true;
            }

            if ($stmt instanceof TryCatch && $this->hasStmtsAlwaysReturn($stmt->stmts) && $this->hasStmtsAlwaysReturn(
                $stmt->catches
            )) {
                if ($stmt->finally instanceof Finally_ && ! $this->hasStmtsAlwaysReturn($stmt->finally->stmts)) {
                    continue;
                }

                return true;
            }
        }

        return false;
    }

    private function isSwitchWithAlwaysReturn(Switch_ $switch): bool
    {
        $hasDefault = false;

        foreach ($switch->cases as $case) {
            if (! $case->cond instanceof Expr) {
                $hasDefault = true;
                break;
            }
        }

        if (! $hasDefault) {
            return false;
        }

        $casesWithReturnCount = $this->resolveReturnCount($switch);

        // has same amount of returns as switches
        return count($switch->cases) === $casesWithReturnCount;
    }

    private function isTryCatchAlwaysReturn(TryCatch $tryCatch): bool
    {
        if (! $this->hasStmtsAlwaysReturn($tryCatch->stmts)) {
            return false;
        }

        foreach ($tryCatch->catches as $catch) {
            if (! $this->hasStmtsAlwaysReturn($catch->stmts)) {
                return false;
            }
        }

        return ! ($tryCatch->finally instanceof Finally_ && ! $this->hasStmtsAlwaysReturn($tryCatch->finally->stmts));
    }

    private function resolveReturnCount(Switch_ $switch): int
    {
        $casesWithReturnCount = 0;

        foreach ($switch->cases as $case) {
            foreach ($case->stmts as $caseStmt) {
                if (! $caseStmt instanceof Return_) {
                    continue;
                }

                ++$casesWithReturnCount;
                break;
            }
        }

        return $casesWithReturnCount;
    }
}
