<?php

declare(strict_types=1);

namespace Rector\PhpParser;

final class NodeGroups
{
    /**
     * @var array<class-string<\PhpParser\Node>>
     */
    public const STMTS_AWARE_NODES = [
        \PhpParser\Node\Expr\Closure::class,
        \PhpParser\Node\Stmt\Block::class,
        \PhpParser\Node\Stmt\Case_::class,
        \PhpParser\Node\Stmt\Catch_::class,
        \PhpParser\Node\Stmt\ClassMethod::class,
        \PhpParser\Node\Stmt\Do_::class,
        \PhpParser\Node\Stmt\ElseIf_::class,
        \PhpParser\Node\Stmt\Else_::class,
        \PhpParser\Node\Stmt\Finally_::class,
        \PhpParser\Node\Stmt\For_::class,
        \PhpParser\Node\Stmt\Foreach_::class,
        \PhpParser\Node\Stmt\Function_::class,
        \PhpParser\Node\Stmt\If_::class,
        \PhpParser\Node\Stmt\Namespace_::class,
        \PhpParser\Node\Stmt\TryCatch::class,
        \PhpParser\Node\Stmt\While_::class,
    ];
}
