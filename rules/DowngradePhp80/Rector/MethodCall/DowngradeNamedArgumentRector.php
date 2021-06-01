<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DowngradePhp80\Rector\MethodCall\DowngradeNamedArgumentRector\DowngradeNamedArgumentRectorTest
 */
final class DowngradeNamedArgumentRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove named argument',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class Foo
{
    public function __construct(
        public ?array $a = null,
        public ?array $b = null
    ) {
    }
}

class SomeClass extends Foo
{
    public function __construct(string $name = null, array $attributes = [])
    {
        parent::__construct(b: [[$name ?? 0 => $attributes]]);
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class Foo
{
    public function __construct(
        public ?array $a = null,
        public ?array $b = null
    ) {
    }
}

class SomeClass extends Foo
{
    public function __construct(string $name = null, array $attributes = [])
    {
        parent::__construct(null, [[$name ?? 0 => $attributes]]);
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $args = $node->args;
        if ($this->shouldSkip($args)) {
            return null;
        }

        $this->applyRemoveNamedArgument($node, $args);
        return $node;
    }

    /**
     * @param MethodCall|StaticCall $node
     * @param Arg[] $args
     */
    private function applyRemoveNamedArgument(Node $node, array $args): void
    {
        $caller = $node instanceof StaticCall
            ? $this->nodeRepository->findClassMethodByStaticCall($node)
            : $this->nodeRepository->findClassMethodByMethodCall($node);

        if (! $caller instanceof ClassMethod) {
            return;
        }

        $params = $caller->params;
        foreach ($params as $keyParam => $param) {
            /** @var string $name */
            $paramName = $this->getName($param);

            foreach ($args as $keyArg => $arg) {
                if (! $arg->name instanceof Identifier) {
                    continue;
                }

                /** @var string $argName */
                $argName = $this->getName($arg);

                if ($paramName !== $argName) {
                    continue;
                }

                if ($keyParam === $keyArg) {
                    $arg->name = null;
                }
            }
        }
    }

    /**
     * @param Arg[] $args
     */
    private function shouldSkip(array $args): bool
    {
        if ($args === []) {
            return true;
        }

        foreach ($args as $arg) {
            if ($arg->name instanceof Identifier) {
                return false;
            }
        }

        return true;
    }
}
