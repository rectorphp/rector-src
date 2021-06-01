<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
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
        return [MethodCall::class, FuncCall::class];
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
     * @param MethodCall|FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $args = $node->args;
        if ($this->shouldSkip($args)) {
            return null;
        }

        return $node;
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
