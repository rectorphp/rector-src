<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Clone_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\If_;
use Rector\Core\PhpParser\Comparing\NodeComparator;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class CallAnalyzer
{
    /**
     * @var array<class-string<Expr>>
     */
    private const OBJECT_CALL_TYPES = [MethodCall::class, NullsafeMethodCall::class, StaticCall::class];

    public function __construct(
        private readonly NodeComparator $nodeComparator
    ) {
    }

    public function isObjectCall(Expr $expr): bool
    {
        if ($expr instanceof BooleanNot) {
            $expr = $expr->expr;
        }

        if ($expr instanceof BinaryOp) {
            $isObjectCallLeft = $this->isObjectCall($expr->left);
            $isObjectCallRight = $this->isObjectCall($expr->right);

            return $isObjectCallLeft || $isObjectCallRight;
        }

        foreach (self::OBJECT_CALL_TYPES as $objectCallType) {
            if ($expr instanceof $objectCallType) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param If_[] $ifs
     */
    public function doesIfHasObjectCall(array $ifs): bool
    {
        foreach ($ifs as $if) {
            if ($this->isObjectCall($if->cond)) {
                return true;
            }
        }

        return false;
    }

    public function isNewInstance(Expr $expr): bool
    {
        if ($expr instanceof Clone_ || $expr instanceof New_) {
            return true;
        }

        return $expr->getAttribute(AttributeKey::IS_NEW_INSTANCE_FROM_ASSIGN) === true;
    }
}
