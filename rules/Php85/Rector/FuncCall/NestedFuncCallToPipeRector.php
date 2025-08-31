<?php

declare(strict_types=1);

namespace Rector\Php85\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Pipe;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\VariadicPlaceholder;
use Rector\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Rector\ValueObject\PhpVersionFeature;

/**
 * @see \Rector\Tests\Php85\Rector\FuncCall\NestedFuncCallToPipeRector\NestedFuncCallToPipeRectorTest
 */
final class NestedFuncCallToPipeRector extends AbstractRector implements MinPhpVersionInterface
{
    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::PIPE_OPERATOR;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert nested single-argument function calls to |> pipe operator',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$result = trim(strtoupper("Hello World"));
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
$result = "Hello World" |> strtoupper(...) |> trim(...);
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Assign::class, FuncCall::class];
    }

    public function refactor(Node $node): ?Node
    {  
        // match assignment: $result = FuncCall
        if (! $node instanceof Assign) {
            return null;
        }

        if (! $node->expr instanceof FuncCall) {
            return null;
        }

        [$seedArgs, $calls] = $this->collectChain($node->expr);

        if (! $seedArgs || count($calls) < 2) {
            return null;
        }

        $calls = array_reverse($calls);

        $firstCall = array_shift($calls);
        $expr = new FuncCall(clone $firstCall->name, $seedArgs);

        foreach ($calls as $call) {
            $expr = new Pipe(
                $expr,
                new FuncCall(clone $call->name, [new VariadicPlaceholder()])
            );
        }

        return new Assign($node->var, $expr);
    }

    protected function collectChain(FuncCall $func): array
    {
        $calls = [];
        $current = $func;
        while ($current instanceof FuncCall && count($current->args) === 1) {
            $calls[] = $current;
            $arg = $current->args[0]->value;
            if ($arg instanceof FuncCall && count($arg->args) === 1) {
                $current = $arg;
                continue;
            }
            return [$current->args, $calls];
        }
        return [null, []];
    }

   
}
