<?php

declare(strict_types=1);

namespace Rector\Php81\Rector\FuncCall;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php81\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector\ClosureFromCallableToFirstClassCallableRectorTest
 */
final class ClosureFromCallableToFirstClassCallableRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct()
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change `Closure::fromCallable()` to first class callable syntax',
            [
                new CodeSample('Closure::fromCallable([$obj, \'method\']);', '$obj->method(...);'),
                new CodeSample('Closure::fromCallable(\'trim\');', 'trim(...);'),
                new CodeSample(
                    'Closure::fromCallable([\'SomeClass\', \'staticMethod\']);',
                    'SomeClass::staticMethod(...);'
                ),
            ]
        );

    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Expr\StaticCall::class];
    }

    /**
     * @param Node\Expr\StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $arg = $node->args[0];
        if (! $arg instanceof Node\Arg) {
            return null;
        }

        if ($arg->value instanceof Node\Scalar\String_) {
            return new Node\Expr\FuncCall(
                new Node\Name($this->getFunctionName($arg->value->value)),
                [new Node\VariadicPlaceholder()],
            );
        }

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::FIRST_CLASS_CALLABLE_SYNTAX;
    }

    public function shouldSkip(Node\Expr\StaticCall $node): bool
    {
        if (! $node->class instanceof Node\Name) {
            return true;
        }

        if (! $this->isName($node->class, 'Closure')) {
            return true;
        }

        if (! $node->name instanceof Node\Identifier || $node->name->name !== 'fromCallable') {
            return true;
        }

        $args = $node->getArgs();
        if (count($args) !== 1) {
            return true;
        }

        return false;
    }

    public function getFunctionName(string $functionName): string
    {
        // in case there's already a \ prefix, remove it
        $functionName = ltrim($functionName, '\\');

        // prefix with a single \ to ensure there's no namespace problems
        $functionName = '\\' . $functionName;

        return $functionName;
    }
}
