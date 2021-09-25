<?php

declare(strict_types=1);

namespace Rector\Php80\MatchAndRefactor\StrStartsWithMatchAndRefactor;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use Rector\Core\NodeAnalyzer\ArgsAnalyzer;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\Php80\Contract\StrStartWithMatchAndRefactorInterface;
use Rector\Php80\NodeFactory\StrStartsWithFuncCallFactory;
use Rector\Php80\ValueObject\StrStartsWith;

final class StrposMatchAndRefactor implements StrStartWithMatchAndRefactorInterface
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private ValueResolver $valueResolver,
        private StrStartsWithFuncCallFactory $strStartsWithFuncCallFactory,
        private ArgsAnalyzer $argsAnalyzer
    ) {
    }

    /**
     * @param Identical|NotIdentical $binaryOp
     */
    public function match(BinaryOp $binaryOp): ?StrStartsWith
    {
        $isPositive = $binaryOp instanceof Identical;

        if ($binaryOp->left instanceof FuncCall && $this->nodeNameResolver->isName($binaryOp->left, 'strpos')) {
            return $this->processBinaryOpLeft($binaryOp, $isPositive);
        }

        if (! $binaryOp->right instanceof FuncCall) {
            return null;
        }

        if (! $this->nodeNameResolver->isName($binaryOp->right, 'strpos')) {
            return null;
        }

        return $this->processBinaryOpRight($binaryOp, $isPositive);
    }

    /**
     * @return FuncCall|BooleanNot
     */
    public function refactorStrStartsWith(StrStartsWith $strStartsWith): Node
    {
        $strposFuncCall = $strStartsWith->getFuncCall();
        $strposFuncCall->name = new Name('str_starts_with');

        return $this->strStartsWithFuncCallFactory->createStrStartsWith($strStartsWith);
    }

    private function processBinaryOpLeft(BinaryOp $binaryOp, bool $isPositive): ?StrStartsWith
    {
        if (! $this->valueResolver->isValue($binaryOp->right, 0)) {
            return null;
        }
        $funcCall = $binaryOp->left;
        if (! $this->argsAnalyzer->isArgsInstanceInArgsPositions($funcCall->args, [0, 1])) {
            return null;
        }

        /** @var Arg $firstArg */
        $firstArg = $funcCall->args[0];
        $haystack = $firstArg->value;
        /** @var Arg $secondArg */
        $secondArg = $funcCall->args[1];
        $needle = $secondArg->value;

        return new StrStartsWith($funcCall, $haystack, $needle, $isPositive);
    }

    private function processBinaryOpRight(BinaryOp $binaryOp, bool $isPositive): ?StrStartsWith
    {
        if (! $this->valueResolver->isValue($binaryOp->left, 0)) {
            return null;
        }

        /** @var FuncCall $funcCall */
        $funcCall = $binaryOp->right;
        if (! $this->argsAnalyzer->isArgsInstanceInArgsPositions($funcCall->args, [0, 1])) {
            return null;
        }

        /** @var Arg $firstArg */
        $firstArg = $funcCall->args[0];
        $haystack = $firstArg->value;
        /** @var Arg $secondArg */
        $secondArg = $funcCall->args[1];
        $needle = $secondArg->value;

        return new StrStartsWith($funcCall, $haystack, $needle, $isPositive);
    }
}
