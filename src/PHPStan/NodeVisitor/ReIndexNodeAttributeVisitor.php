<?php

declare(strict_types=1);

namespace Rector\PHPStan\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\MatchArm;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\NodeVisitorAbstract;
use Rector\Application\NodeAttributeReIndexer;

final class ReIndexNodeAttributeVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node): ?Node
    {
        return NodeAttributeReIndexer::reIndexNodeAttributes($node);
    }
}
