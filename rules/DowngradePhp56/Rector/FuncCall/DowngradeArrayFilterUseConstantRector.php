<?php

declare(strict_types=1);

namespace Rector\DowngradePhp56\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\Naming\Naming\VariableNaming;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/manual/en/function.array-filter.php
 *
 * @see \Rector\Tests\DowngradePhp56\Rector\CallLike\DowngradeArrayFilterUseConstantRector\DowngradeArrayFilterUseConstantRectorTest
 */
final class DowngradeArrayFilterUseConstantRector extends AbstractRector
{
    public function __construct(
        private readonly VariableNaming $variableNaming
    ) {
    }

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

        if ($args[1]->value instanceof Closure) {
            return $this->processClosure($node, $args);
        }

        return $node;
    }

    private function processClosure(FuncCall $funcCall, array $args): ?Variable
    {
        /** @var Closure $closure */
        $closure = $args[1]->value;
        /** @var Return_[] $returns */
        $returns = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped($closure, Return_::class);

        if ($returns === []) {
            return null;
        }

        $currentStatement = $funcCall->getAttribute(AttributeKey::CURRENT_STATEMENT);
        if (! $currentStatement instanceof Stmt) {
            return null;
        }

        /** @var Variable $key */
        $key = $closure->params[0]->var;
        $ifs = [];
        $scope = $funcCall->getAttribute(AttributeKey::SCOPE);
        $result = new Variable($this->variableNaming->createCountedValueName('result', $scope));

        $this->nodesToAddCollector->addNodeBeforeNode(
            new Expression(
                new Assign(
                    $result,
                    new Array_([])
                )
            ),
            $currentStatement
        );

        foreach ($returns as $return) {
            $ifs[] = new If_($return->expr, [
                'stmts' => [new Expression(new Assign(new ArrayDimFetch($result, $key), new Variable('v')))],
            ]);
        }

        print_node($ifs);

        return new Variable('result');
    }

    private function shouldSkip(FuncCall $funcCall, array $args): bool
    {
        if (! $this->nodeNameResolver->isName($funcCall, 'array_filter')) {
            return true;
        }

        if (! isset($args[2])) {
            return true;
        }

        if (! $args[2]->value instanceof ConstFetch) {
            return true;
        }

        return ! $this->nodeNameResolver->isNames(
            $args[2]->value,
            ['ARRAY_FILTER_USE_KEY', 'ARRAY_FILTER_USE_BOTH']
        );
    }
}
