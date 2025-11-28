<?php

declare(strict_types=1);

namespace Rector\PhpParser\Enum;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\Node\Stmt\While_;

final class NodeGroup
{
    /**
     * These nodes have Stmt[] $stmts iterable public property
     *
     * If https://github.com/nikic/PHP-Parser/pull/1113 gets merged, can replace those.
     *
     * @var array<class-string>
     */
    public const STMTS_AWARE = [
        \PhpParser\Node\Expr\Closure::class,
        \PhpParser\Node\Stmt\Case_::class,
        \PhpParser\Node\Stmt\Catch_::class,
        \PhpParser\Node\Stmt\ClassMethod::class,
        \PhpParser\Node\Stmt\Do_::class,
        \PhpParser\Node\Stmt\Else_::class,
        \PhpParser\Node\Stmt\ElseIf_::class,
        \PhpParser\Node\Stmt\Finally_::class,
        \PhpParser\Node\Stmt\For_::class,
        \PhpParser\Node\Stmt\Foreach_::class,
        \PhpParser\Node\Stmt\Function_::class,
        \PhpParser\Node\Stmt\If_::class,
        \PhpParser\Node\Stmt\Namespace_::class,
        \PhpParser\Node\Stmt\TryCatch::class,
        \PhpParser\Node\Stmt\While_::class,
    ];

    /**
     * @var array<class-string<Node>>
     */
    public const STMTS_TO_HAVE_NEXT_NEWLINE = [
        ClassMethod::class,
        Function_::class,
        Property::class,
        If_::class,
        Foreach_::class,
        Do_::class,
        While_::class,
        For_::class,
        ClassConst::class,
        TryCatch::class,
        Class_::class,
        Trait_::class,
        Interface_::class,
        Switch_::class,
    ];
}
