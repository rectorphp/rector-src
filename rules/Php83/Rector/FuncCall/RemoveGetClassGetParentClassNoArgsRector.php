<?php

declare(strict_types=1);

namespace Rector\Php83\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\VarLikeIdentifier;
use PHPStan\Reflection\ClassReflection;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;

/**
 * @see \Rector\Tests\Php83\Rector\FuncCall\RemoveGetClassGetParentClassNoArgsRector\RemoveGetClassGetParentClassNoArgsRectorTest
 */
final class RemoveGetClassGetParentClassNoArgsRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace calls to `get_class()` and `get_parent_class()` without arguments with `self::class` and `parent::class`',
            [
                new CodeSample(
                    <<<'OLD_CODE_SAMPLE'
                    class Example extends StdClass {
                        public function whoAreYou() {
                            return get_class() . ' daughter of ' . get_parent_class();
                        }
                    }
                    OLD_CODE_SAMPLE
                    ,
                    <<<'NEW_CODE_SAMPLE'
                    class Example extends StdClass {
                        public function whoAreYou() {
                            return self::class . ' daughter of ' . parent::class;
                        }
                    }
                    NEW_CODE_SAMPLE
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
    public function refactor(Node $node): ?Node
    {
        if ($node->isFirstClassCallable()) {
            return null;
        }

        if (count($node->getArgs()) !== 0) {
            return null;
        }

        $target = null;
        if ($this->isName($node, 'get_class')) {
            $target = 'self';
        }

        if ($this->isName($node, 'get_parent_class')) {
            $target = 'parent';
        }

        if ($target === null) {
            return null;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        // parent::class is a fatal error in a class without parent
        if ($target === 'parent' && ! $classReflection->getParentClass() instanceof ClassReflection) {
            return null;
        }

        return new ClassConstFetch(new Name([$target]), new VarLikeIdentifier('class'));
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_83;
    }
}
