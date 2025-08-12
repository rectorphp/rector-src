<?php

declare(strict_types=1);

namespace Rector\Privatization\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PHPStan\ScopeFetcher;
use Rector\Privatization\Guard\OverrideByParentClassGuard;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\Privatization\VisibilityGuard\ClassMethodVisibilityGuard;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\PrivatizeFinalClassMethodRectorTest
 * @see \Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\CallbackTest
 */
final class PrivatizeFinalClassMethodRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const SHOULD_SKIP_CALLBACK = 'should_skip_callback';

    /**
     * @var ?callable(ClassMethod, ClassReflection): bool
     */
    private $shouldSkipCallback = null;

    public function __construct(
        private readonly ClassMethodVisibilityGuard $classMethodVisibilityGuard,
        private readonly VisibilityManipulator $visibilityManipulator,
        private readonly OverrideByParentClassGuard $overrideByParentClassGuard,
        private readonly BetterNodeFinder $betterNodeFinder,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change protected class method to private if possible',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
final class SomeOtherClass
{
    protected function someMethod()
    {
    }
}
final class SomeClass
{
    protected function someMethod()
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeOtherClass
{
    protected function someMethod()
    {
    }
}
final class SomeClass
{
    private function someMethod()
    {
    }
}
CODE_SAMPLE
                    ,
                    [
                        self::SHOULD_SKIP_CALLBACK => static function (
                            ClassMethod $classMethod,
                            ClassReflection $classReflection,
                        ): bool {
                            return $classReflection->is('SomeOtherClass');
                        },
                    ],
                ),
            ]
        );
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $this->shouldSkipCallback = $configuration[self::SHOULD_SKIP_CALLBACK] ?? null;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->isFinal()) {
            return null;
        }

        if (! $this->overrideByParentClassGuard->isLegal($node)) {
            return null;
        }

        $scope = ScopeFetcher::fetch($node);
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->getMethods() as $classMethod) {
            if ($this->shouldSkipClassMethod($classMethod)) {
                continue;
            }

            if ($this->classMethodVisibilityGuard->isClassMethodVisibilityGuardedByParent(
                $classMethod,
                $classReflection
            )) {
                continue;
            }

            if ($this->classMethodVisibilityGuard->isClassMethodVisibilityGuardedByTrait(
                $classMethod,
                $classReflection
            )) {
                continue;
            }

            if (
                is_callable($this->shouldSkipCallback)
                && call_user_func($this->shouldSkipCallback, $classMethod, $classReflection)
            ) {
                continue;
            }

            $this->visibilityManipulator->makePrivate($classMethod);
            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function shouldSkipClassMethod(ClassMethod $classMethod): bool
    {
        // edge case in nette framework
        /** @var string $methodName */
        $methodName = $this->getName($classMethod->name);
        if (str_starts_with($methodName, 'createComponent')) {
            return true;
        }

        if (! $classMethod->isProtected()) {
            return true;
        }

        if ($classMethod->isMagic()) {
            return true;
        }

        // if has parent call, its probably overriding parent one → skip it
        $hasParentCall = (bool) $this->betterNodeFinder->findFirst(
            (array) $classMethod->stmts,
            function (Node $node): bool {
                if (! $node instanceof StaticCall) {
                    return false;
                }

                return $this->isName($node->class, 'parent');
            }
        );

        return $hasParentCall;
    }
}
