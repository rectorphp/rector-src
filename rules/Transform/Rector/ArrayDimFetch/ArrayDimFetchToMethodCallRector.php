<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\ArrayDimFetch;

use PhpParser\Node;
use PhpParser\NodeVisitor;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Unset_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\Transform\ValueObject\ArrayDimFetchToMethodCall;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector\ArrayDimFetchToMethodCallRectorTest
 */
class ArrayDimFetchToMethodCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var ArrayDimFetchToMethodCall[]
     */
    private array $arrayDimFetchToMethodCalls;

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change array dim fetch to method call', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
$object['key'];
$object['key'] = 'value';
isset($object['key']);
unset($object['key']);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$object->get('key');
$object->set('key', 'value');
$object->has('key');
$object->unset('key');
CODE_SAMPLE
                ,
                [new ArrayDimFetchToMethodCall(new ObjectType('SomeClass'), 'get', 'set', 'has', 'unset')],
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [AssignOp::class, Assign::class, Isset_::class, Unset_::class, ArrayDimFetch::class];
    }

    /**
     * @template TNode of ArrayDimFetch|Assign|Isset_|Unset_
     * @param TNode $node
     * @return ($node is Unset_ ? Stmt[]|int : ($node is Isset_ ? Expr|int : MethodCall|int|null))
     */
    public function refactor(Node $node): array|Expr|null|int
    {
        if ($node instanceof AssignOp) {
            return NodeVisitor::DONT_TRAVERSE_CHILDREN;
        }

        if ($node instanceof Unset_) {
            return $this->handleUnset($node);
        }

        if ($node instanceof Isset_) {
            return $this->handleIsset($node);
        }

        if ($node instanceof Assign) {
            if (!$node->var instanceof ArrayDimFetch) {
                return null;
            }

            return $this->getMethodCall($node->var, 'set', $node->expr)
                ?? NodeVisitor::DONT_TRAVERSE_CHILDREN;
        }

        return $this->getMethodCall($node, 'get');
    }

    public function configure(array $configuration): void
    {
        Assert::allIsInstanceOf($configuration, ArrayDimFetchToMethodCall::class);

        $this->arrayDimFetchToMethodCalls = $configuration;
    }

    private function handleIsset(Isset_ $node): Expr|int|null
    {
        $issets = [];
        $exprs = [];

        foreach ($node->vars as $var) {
            if ($var instanceof ArrayDimFetch) {
                $methodCall = $this->getMethodCall($var, 'exists');

                if ($methodCall !== null) {
                    $exprs[] = $methodCall;
                    continue;
                }
            }

            $issets[] = $var;
        }

        if ($exprs === []) {
            return NodeVisitor::DONT_TRAVERSE_CHILDREN;
        }

        if ($issets !== []) {
            $node->vars = $issets;
            array_unshift($exprs, $node);
        }

        return array_reduce(
            $exprs,
            fn (?Expr $carry, Expr $expr) => $carry === null ? $expr : new BooleanAnd($carry, $expr),
            null,
        );
    }

    /**
     * @return Stmt[]|int
     */
    private function handleUnset(Unset_ $node): array|int
    {
        $unsets = [];
        $stmts = [];

        foreach ($node->vars as $var) {
            if ($var instanceof ArrayDimFetch) {
                $methodCall = $this->getMethodCall($var, 'unset');

                if ($methodCall !== null) {
                    $stmts[] = new Expression($methodCall);
                    continue;
                }
            }

            $unsets[] = $var;
        }

        if ($stmts === []) {
            return NodeVisitor::DONT_TRAVERSE_CHILDREN;
        }

        if ($unsets !== []) {
            $node->vars = $unsets;
            array_unshift($stmts, $node);
        }

        return $stmts;
    }

    /**
     * @param 'get'|'set'|'exists'|'unset' $action
     */
    private function getMethodCall(ArrayDimFetch $fetch, string $action, ?Expr $value = null): ?MethodCall
    {
        if (!$fetch->dim instanceof Node) {
            return null;
        }

        foreach ($this->arrayDimFetchToMethodCalls as $arrayDimFetchToMethodCall) {
            if (!$this->isObjectType($fetch->var, $arrayDimFetchToMethodCall->getObjectType())) {
                continue;
            }

            $method = match ($action) {
                'get' => $arrayDimFetchToMethodCall->getMethod(),
                'set' => $arrayDimFetchToMethodCall->getSetMethod(),
                'exists' => $arrayDimFetchToMethodCall->getExistsMethod(),
                'unset' => $arrayDimFetchToMethodCall->getUnsetMethod(),
            };

            if ($method === null) {
                continue;
            }

            $args = [new Arg($fetch->dim)];

            if ($value instanceof Expr) {
                $args[] = new Arg($value);
            }

            return new MethodCall($fetch->var, $method, $args);
        }

        return null;
    }
}
