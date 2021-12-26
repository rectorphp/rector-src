<?php

declare(strict_types=1);

namespace Rector\DowngradePhp56\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/manual/en/function.array-filter.php
 *
 * @see \Rector\Tests\DowngradePhp56\Rector\CallLike\DowngradeArrayFilterUseConstantRector\DowngradeArrayFilterUseConstantRectorTest
 */
final class DowngradeArrayFilterUseConstantRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace use ARRAY_FILTER_USE_BOTH and ARRAY_FILTER_USE_KEY to loop to filter it', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$arr = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];

var_dump(array_filter($arr, function($v, $k) {
    return $k == 'b' || $v == 4;
}, ARRAY_FILTER_USE_BOTH));
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$arr = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];

$result = [];
foreach ($arr as $k => $v) {
    if ($v === 4 || $k === 'b') {
        $result[$k] = $v;
    }
}

var_dump($result);
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $args = $node->getArgs();
        if ($this->shouldSkip($node, $args)) {
            return null;
        }

        return $node;
    }

    private function shouldSkip(FuncCall $funcCall, array $args): bool
    {
        if (! $this->nodeNameResolver->isName($funcCall->name, 'array_filter')) {
            return true;
        }

        if (! isset($args[2])) {
            return true;
        }

        if (! $args[2]->value instanceof ConstFetch) {
            return true;
        }

        return ! $this->nodeNameResolver->isNames(
            $args[2]->value->name,
            ['ARRAY_FILTER_USE_KEY', 'ARRAY_FILTER_USE_BOTH']
        );
    }
}
