<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PHPStan\Type\MixedType;
use Rector\Core\NodeManipulator\IfManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/manual/en/function.array-filter.php#refsect1-function.array-filter-changelog
 *
 * @see \Rector\Tests\DowngradePhp80\Rector\FuncCall\DowngradeArrayFilterNullableCallbackRector\DowngradeArrayFilterNullableCallbackRectorTest
 */
final class DowngradeArrayFilterNullableCallbackRector extends AbstractRector
{
    public function __construct(
        private readonly IfManipulator $ifManipulator
    ) {
    }

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
        var_dump(array_filter($data, null, ARRAY_FILTER_USE_KEY));
        var_dump(array_filter($data, null, ARRAY_FILTER_USE_BOTH));
        var_dump(array_filter($data, $callback));
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
        var_dump(array_filter($data));
        var_dump(array_filter($data));
        var_dump(array_filter($data, fn($v, $k): bool => !empty($v), ARRAY_FILTER_USE_BOTH));
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
    public function refactor(Node $node): ?FuncCall
    {
        $args = $node->getArgs();
        if (! $this->isName($node, 'array_filter')) {
            return null;
        }

        if (! isset($args[1])) {
            return null;
        }

        $type = $this->nodeTypeResolver->getType($args[1]->value);
        // need exact compare null to handle variable
        if ($this->valueResolver->isNull($args[1]->value)) {
            return null;
        }
        if (! $type instanceof MixedType) {
            return null;
        }

        if ($args[1]->value instanceof ConstFetch) {
            $args = [$args[0]];
            $node->args = $args;
            return $node;
        }

        $currentStatement = $node->getAttribute(AttributeKey::CURRENT_STATEMENT);
        if (! $currentStatement instanceof Stmt) {
            return null;
        }

        $node->args[1] = new Arg(
            new ArrowFunction(
                [
                    'params' => [new Param(new Variable('v')), new Param(new Variable('k'))],
                    'returnType' => new Identifier('bool'),
                    'expr' => new BooleanNot(new Empty_(new Variable('v'))),
                ]
            )
        );

        $node->args[2] = new ConstFetch(new Name('ARRAY_FILTER_USE_BOTH'));
        return $node;
    }
}
