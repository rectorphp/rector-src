<?php

declare(strict_types=1);

namespace Rector\Core\PHPStan\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Case_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\EnumCase;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Finally_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Global_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Static_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\Node\Stmt\While_;
use PhpParser\NodeVisitorAbstract;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class ImprovedParentConnectingVisitor extends NodeVisitorAbstract
{
    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof StmtsAwareInterface && ! $node instanceof ClassLike && ! $node instanceof Declare_) {
            $this->processInsideStmt($node);
            return null;
        }

        if ($node->stmts === null) {
            return null;
        }

        foreach ($node->stmts as $stmt) {
            $stmt->setAttribute(AttributeKey::PARENT_NODE, $node);

            if ($stmt instanceof FunctionLike) {
                foreach ($stmt->getParams() as $param) {
                    $param->setAttribute(AttributeKey::PARENT_NODE, $param);
                }
            }

            if ($stmt instanceof Echo_) {
                foreach ($stmt->exprs as $expr) {
                    $expr->setAttribute(AttributeKey::PARENT_NODE, $stmt);
                }
            }

            if ($stmt instanceof Global_ || $stmt instanceof Static_) {
                foreach ($stmt->vars as $var) {
                    $var->setAttribute(AttributeKey::PARENT_NODE, $stmt);
                }
            }

            if ($stmt instanceof Switch_) {
                foreach ($stmt->cases as $case) {
                    $case->setAttribute(AttributeKey::PARENT_NODE, $stmt);
                }
            }

            if ((
                $stmt instanceof Expression ||
                $stmt instanceof Return_ ||
                $stmt instanceof EnumCase ||
                $stmt instanceof Cast
            ) && $stmt->expr instanceof Expr) {
                $stmt->expr->setAttribute(AttributeKey::PARENT_NODE, $stmt);
            }

            if (
                $stmt instanceof If_
                || $stmt instanceof While_
                || $stmt instanceof Do_
                || $stmt instanceof Switch_
                || $stmt instanceof ElseIf_
                || $stmt instanceof Case_) {
                $stmt->cond->setAttribute(AttributeKey::PARENT_NODE, $stmt);
            }

            if ($stmt instanceof Foreach_) {
                $stmt->expr->setAttribute(AttributeKey::PARENT_NODE, $stmt);

                if ($stmt->keyVar instanceof Expr) {
                    $stmt->keyVar->setAttribute(AttributeKey::PARENT_NODE, $stmt);
                }

                $stmt->valueVar->setAttribute(AttributeKey::PARENT_NODE, $stmt);
            }

            if ($stmt instanceof If_) {
                foreach ($stmt->elseifs as $elseIf) {
                    $elseIf->setAttribute(AttributeKey::PARENT_NODE, $stmt);
                }

                if ($stmt->else instanceof Else_) {
                    $stmt->else->setAttribute(AttributeKey::PARENT_NODE, $stmt);
                }
            }

            if ($stmt instanceof TryCatch) {
                foreach ($stmt->catches as $catch) {
                    $catch->setAttribute(AttributeKey::PARENT_NODE, $stmt);
                }

                if ($stmt->finally instanceof Finally_) {
                    $stmt->finally->setAttribute(AttributeKey::PARENT_NODE, $stmt);
                }
            }

            if ($stmt instanceof For_) {
                foreach ($stmt->loop as $loop) {
                    $loop->setAttribute(AttributeKey::PARENT_NODE, $stmt);
                }

                foreach ($stmt->cond as $cond) {
                    $cond->setAttribute(AttributeKey::PARENT_NODE, $stmt);
                }

                foreach ($stmt->init as $init) {
                    $init->setAttribute(AttributeKey::PARENT_NODE, $stmt);
                }
            }
        }

        return null;
    }

    private function processInsideStmt(Node $node): void
    {
        if ($node instanceof BinaryOp) {
            $node->left->setAttribute(AttributeKey::PARENT_NODE, $node);
            $node->right->setAttribute(AttributeKey::PARENT_NODE, $node);
        }

        if ($node instanceof Assign || $node instanceof AssignOp) {
            $node->var->setAttribute(AttributeKey::PARENT_NODE, $node);
            $node->expr->setAttribute(AttributeKey::PARENT_NODE, $node);
        }

        if ($node instanceof Array_) {
            foreach ($node->items as $item) {
                if ($item instanceof ArrayItem) {
                    $item->setAttribute(AttributeKey::PARENT_NODE, $node);
                }
            }
        }

        if ($node instanceof Isset_) {
            foreach ($node->vars as $var) {
                $var->setAttribute(AttributeKey::PARENT_NODE, $node);
            }
        }

        if ($node instanceof ArrayDimFetch) {
            $node->var->setAttribute(AttributeKey::PARENT_NODE, $node);
            if ($node->dim instanceof Expr) {
                $node->dim->setAttribute(AttributeKey::PARENT_NODE, $node);
            }
        }

        if ($node instanceof FuncCall || $node instanceof MethodCall || $node instanceof StaticCall || $node instanceof New_ || $node instanceof NullsafeMethodCall || $node instanceof Attribute) {
            foreach ($node->args as $arg) {
                $arg->setAttribute(AttributeKey::PARENT_NODE, $node);
            }
        }

        if ($node instanceof Arg) {
            $node->value->setAttribute(AttributeKey::PARENT_NODE, $node);
        }

        if ($node instanceof Param) {
            $node->var->setAttribute(AttributeKey::PARENT_NODE, $node);
        }
    }
}
