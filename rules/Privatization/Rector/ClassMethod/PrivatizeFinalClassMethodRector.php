<?php

declare(strict_types=1);

namespace Rector\Privatization\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PHPStan\ScopeFetcher;
use Rector\Privatization\Guard\OverrideByParentClassGuard;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\Privatization\VisibilityGuard\ClassMethodVisibilityGuard;
use Rector\Rector\AbstractRector;
use Rector\Util\StringUtils;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\PrivatizeFinalClassMethodRectorTest
 */
final class PrivatizeFinalClassMethodRector extends AbstractRector
{
    /**
     * @var string
     * @see https://regex101.com/r/Dx0WN5/2
     */
    private const LARAVEL_MODEL_ATTRIBUTE_REGEX = '/^[gs]et.+Attribute$/';
    /**
     * @var string
     * @see https://regex101.com/r/hxOGeN/2
     */
    private const LARAVEL_MODEL_SCOPE_REGEX = '/^scope.+$/';

    public function __construct(
        private readonly ClassMethodVisibilityGuard $classMethodVisibilityGuard,
        private readonly VisibilityManipulator $visibilityManipulator,
        private readonly OverrideByParentClassGuard $overrideByParentClassGuard,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly PhpAttributeAnalyzer $phpAttributeAnalyzer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change protected class method to private if possible',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    protected function someMethod()
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    private function someMethod()
    {
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

        if ($this->shouldSkipClassMethodLaravel($classMethod)) {
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

    private function shouldSkipClassMethodLaravel(ClassMethod $classMethod): bool
    {
        $classReflection = ScopeFetcher::fetch($classMethod)->getClassReflection();

        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        if (! $classReflection->is('Illuminate\Database\Eloquent\Model')) {
            return false;
        }

        $name = (string) $this->getName($classMethod->name);
        $returnType = $classMethod->returnType;

        // Model attributes should be protected
        if (
            StringUtils::isMatch($name, self::LARAVEL_MODEL_ATTRIBUTE_REGEX)
            || ($returnType instanceof Node && $this->isObjectType(
                $returnType,
                new ObjectType('Illuminate\Database\Eloquent\Casts\Attribute')
            ))
        ) {
            return true;
        }

        // Model scopes should be protected
        if (
            StringUtils::isMatch($name, self::LARAVEL_MODEL_SCOPE_REGEX)
            || $this->phpAttributeAnalyzer->hasPhpAttribute(
                $classMethod,
                'Illuminate\Database\Eloquent\Attributes\Scope'
            )
        ) {
            return true;
        }

        return false;
    }
}
