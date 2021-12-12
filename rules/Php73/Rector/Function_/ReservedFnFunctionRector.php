<?php

declare(strict_types=1);

namespace Rector\Php73\Rector\Function_;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Function_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use PHPStan\Reflection\ReflectionProvider;

/**
 * @changelog https://github.com/php/php-src/pull/3941/files#diff-7e3a1a5df28a1cbd8c0fb6db68f243da
 * @see \Rector\Tests\Php73\Rector\Function_\ReservedFnFunctionRector\ReservedFnFunctionRectorTest
 */
final class ReservedFnFunctionRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::RESERVED_FN_FUNCTION_NAME - 1;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change fn() function name to f(), since it will be reserved keyword', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        function fn($value)
        {
            return $value;
        }

        fn(5);
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        function f($value)
        {
            return $value;
        }

        f(5);
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Function_::class, FuncCall::class];
    }

    /**
     * @param Function_|FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->name, 'fn')) {
            return null;
        }

        $newOriginalName = 'f';
        $newName = $newOriginalName;

        $count = 1;
        while ($this->reflectionProvider->hasFunction($newName)) {
            $newName = $newOriginalName . $count;
        }

        $node->name = $node instanceof FuncCall ? new Name($newName) : new Identifier($newName);
        return $node;
    }
}
