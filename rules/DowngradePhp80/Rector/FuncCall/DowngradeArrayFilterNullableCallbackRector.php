<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PHPStan\Type\MixedType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/manual/en/function.array-filter.php#refsect1-function.array-filter-changelog
 *
 * @see \Rector\Tests\DowngradePhp80\Rector\FuncCall\DowngradeArrayFilterNullableCallbackRector\DowngradeArrayFilterNullableCallbackRectorTest
 */
final class DowngradeArrayFilterNullableCallbackRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Unset nullable callback on array_filter',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($callback = null)
    {
        $data = [[]];
        var_dump(array_filter($data, null));
    }
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run($callback = null)
    {
        $data = [[]];
        var_dump(array_filter($data));
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
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): FuncCall|Ternary|null
    {
        $args = $node->getArgs();

        if (! $this->isName($node, 'array_filter')) {
            return null;
        }

        if ($this->hasNamedArg($args)) {
            return null;
        }

        if (! isset($args[1])) {
            return null;
        }

        // direct null check ConstFetch
        if ($args[1]->value instanceof ConstFetch && $this->valueResolver->isNull($args[1]->value)) {
            $args = [$args[0]];
            $node->args = $args;
            return $node;
        }

        $type = $this->nodeTypeResolver->getType($args[1]->value);
        if (! $type instanceof MixedType) {
            return null;
        }

        $node->args[1] = $this->createNewArgFirst($args);
        $node->args[2] = $this->createNewArgSecond($args);

        return $node;
    }

    /**
     * @param Arg[] $args
     */
    private function createNewArgFirst(array $args): Arg
    {
        return new Arg(new Ternary(
            new Identical($args[1]->value, $this->nodeFactory->createNull()),
            new ArrowFunction(
                [
                    'params' => [new Param(new Variable('v')), new Param(new Variable('k'))],
                    'returnType' => new Identifier('bool'),
                    'expr' => new BooleanNot(new Empty_(new Variable('v'))),
                ]
            ),
            $args[1]->value
        ));
    }

    /**
     * @param Arg[] $args
     */
    private function createNewArgSecond(array $args): Arg
    {
        return new Arg(new Ternary(
            new Identical($args[1]->value, $this->nodeFactory->createNull()),
            new ConstFetch(new Name('ARRAY_FILTER_USE_BOTH')),
            isset($args[2]) ? $args[2]->value : new ConstFetch(new Name('0'))
        ));
    }

    /**
     * @param Arg[] $args
     */
    private function hasNamedArg(array $args): bool
    {
        foreach ($args as $arg) {
            if ($arg->name instanceof Identifier) {
                return true;
            }
        }

        return false;
    }
}
