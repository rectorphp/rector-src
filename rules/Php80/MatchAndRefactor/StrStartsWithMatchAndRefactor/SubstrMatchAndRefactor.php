<?php

declare(strict_types=1);

namespace Rector\Php80\MatchAndRefactor\StrStartsWithMatchAndRefactor;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Php80\Contract\StrStartWithMatchAndRefactorInterface;
use Rector\Php80\NodeFactory\StrStartsWithFuncCallFactory;
use Rector\Php80\ValueObject\StrStartsWith;
use Rector\PhpParser\Comparing\NodeComparator;
use Rector\PhpParser\Node\Value\ValueResolver;

final readonly class SubstrMatchAndRefactor implements StrStartWithMatchAndRefactorInterface
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private ValueResolver $valueResolver,
        private NodeComparator $nodeComparator,
        private StrStartsWithFuncCallFactory $strStartsWithFuncCallFactory,
    ) {
    }

    public function match(Identical|NotIdentical|Equal|NotEqual $binaryOp): ?StrStartsWith
    {
        $isPositive = $binaryOp instanceof Identical || $binaryOp instanceof Equal;

        if ($binaryOp->left instanceof FuncCall && $this->nodeNameResolver->isName($binaryOp->left, 'substr')) {
            $funcCall = $binaryOp->left;

            $stringArg = $funcCall->getArg('string', 0);
            if (! $stringArg instanceof Arg) {
                return null;
            }

            return new StrStartsWith($funcCall, $stringArg->value, $binaryOp->right, $isPositive);
        }

        if ($binaryOp->right instanceof FuncCall && $this->nodeNameResolver->isName($binaryOp->right, 'substr')) {
            $funcCall = $binaryOp->right;

            $stringArg = $funcCall->getArg('string', 0);
            if (! $stringArg instanceof Arg) {
                return null;
            }

            return new StrStartsWith($funcCall, $stringArg->value, $binaryOp->left, $isPositive);
        }

        return null;
    }

    public function refactorStrStartsWith(StrStartsWith $strStartsWith): ?Node
    {
        if ($this->isStrlenWithNeedleExpr($strStartsWith)) {
            return $this->strStartsWithFuncCallFactory->createStrStartsWith($strStartsWith);
        }

        if ($this->isHardcodedStringWithLNumberLength($strStartsWith)) {
            return $this->strStartsWithFuncCallFactory->createStrStartsWith($strStartsWith);
        }

        return null;
    }

    private function isStrlenWithNeedleExpr(StrStartsWith $strStartsWith): bool
    {
        $substrFuncCall = $strStartsWith->getFuncCall();

        if ($substrFuncCall->isFirstClassCallable()) {
            return false;
        }

        $firstArg = $substrFuncCall->getArg('offset', 1);
        if (! $firstArg instanceof Arg) {
            return false;
        }
        if (! $this->valueResolver->isValue($firstArg->value, 0)) {
            return false;
        }

        $lengthArg = $substrFuncCall->getArg('length', 2);
        if (! $lengthArg instanceof Arg) {
            return false;
        }

        $secondFuncCallArgValue = $lengthArg->value;
        if (! $secondFuncCallArgValue instanceof FuncCall) {
            return false;
        }

        if (! $this->nodeNameResolver->isName($secondFuncCallArgValue, 'strlen')) {
            return false;
        }

        $strlenFuncCall = $secondFuncCallArgValue;
        $strlenArg = $strlenFuncCall->getArg('string', 0);
        if (! $strlenArg instanceof Arg) {
            return false;
        }

        $needleExpr = $strlenArg->value;

        $comparedNeedleExpr = $strStartsWith->getNeedleExpr();
        return $this->nodeComparator->areNodesEqual($needleExpr, $comparedNeedleExpr);
    }

    private function isHardcodedStringWithLNumberLength(StrStartsWith $strStartsWith): bool
    {
        $substrFuncCall = $strStartsWith->getFuncCall();

        if ($substrFuncCall->isFirstClassCallable()) {
            return false;
        }

        $secondArg = $substrFuncCall->getArg('offset', 1);
        if (! $secondArg instanceof Arg) {
            return false;
        }
        if (! $this->valueResolver->isValue($secondArg->value, 0)) {
            return false;
        }

        $expr = $strStartsWith->getNeedleExpr();
        if (! $expr instanceof String_) {
            return false;
        }

        if (count($substrFuncCall->getArgs()) < 3) {
            return false;
        }

        $lengthArg = $substrFuncCall->getArg('length', 2);
        if (! $lengthArg instanceof Arg) {
            return false;
        }

        $lNumberLength = $lengthArg->value;
        if (! $lNumberLength instanceof Int_) {
            return false;
        }

        return $lNumberLength->value === strlen($expr->value);
    }
}
