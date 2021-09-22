<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\Ternary;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Ternary;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/get_debug_type
 *
 * @see \Rector\Tests\Php80\Rector\Ternary\GetDebugTypeRector\GetDebugTypeRectorTest
 */
final class GetDebugTypeRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change ternary type resolve to get_debug_type()',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($value)
    {
        return is_object($value) ? get_class($value) : gettype($value);
    }
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($value)
    {
        return get_debug_type($value);
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Ternary::class];
    }

    /**
     * @param Ternary $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $this->areValuesIdentical($node)) {
            return null;
        }

        /** @var FuncCall $funcCall */
        $funcCall = $node->if;
        $firstExpr = $funcCall->getArgs()[0]->value;

        return $this->nodeFactory->createFuncCall('get_debug_type', [$firstExpr]);
    }

    private function shouldSkip(Ternary $ternary): bool
    {
        if (! $ternary->cond instanceof FuncCall) {
            return true;
        }

        if (! $this->nodeNameResolver->isName($ternary->cond, 'is_object')) {
            return true;
        }

        if (! $ternary->if instanceof FuncCall) {
            return true;
        }

        if (! $this->nodeNameResolver->isName($ternary->if, 'get_class')) {
            return true;
        }

        if (! $ternary->else instanceof FuncCall) {
            return true;
        }

        return ! $this->nodeNameResolver->isName($ternary->else, 'gettype');
    }

    private function areValuesIdentical(Ternary $ternary): bool
    {
        /** @var FuncCall $isObjectFuncCall */
        $isObjectFuncCall = $ternary->cond;
        $firstExpr = $isObjectFuncCall->getArgs()[0]->value;

        /** @var FuncCall $getClassFuncCall */
        $getClassFuncCall = $ternary->if;
        $secondExpr = $getClassFuncCall->getArgs()[0]->value;

        /** @var FuncCall $gettypeFuncCall */
        $gettypeFuncCall = $ternary->else;
        $thirdExpr = $gettypeFuncCall->getArgs()[0]->value;

        if (! $this->nodeComparator->areNodesEqual($firstExpr, $secondExpr)) {
            return false;
        }

        return $this->nodeComparator->areNodesEqual($firstExpr, $thirdExpr);
    }
}
