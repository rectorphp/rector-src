<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\PhpParser\NodeFinder\LocalMethodCallFinder;
use Rector\Rector\AbstractRector;
use Rector\TypeDeclaration\NodeAnalyzer\CallTypesResolver;
use Rector\TypeDeclaration\NodeAnalyzer\ClassMethodParamTypeCompleter;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/symplify/phpstan-rules/blob/master/docs/rules_overview.md#checktypehintcallertyperule
 *
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\AddMethodCallBasedStrictParamTypeRectorTest
 */
final class AddMethodCallBasedStrictParamTypeRector extends AbstractRector
{
    /**
     * @var int
     */
    private const MAX_UNION_TYPES = 3;

    public function __construct(
        private readonly CallTypesResolver $callTypesResolver,
        private readonly ClassMethodParamTypeCompleter $classMethodParamTypeCompleter,
        private readonly LocalMethodCallFinder $localMethodCallFinder,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change private classMethod param type to strict type, based on passed strict types',
            [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run(int $value)
    {
        $this->resolve($value);
    }

    private function resolve($value)
    {
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run(int $value)
    {
        $this->resolve($value);
    }

    private function resolve(int $value)
    {
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
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $hasChanged = false;

        foreach ($node->getMethods() as $classMethod) {
            if ($classMethod->params === []) {
                continue;
            }

            if (! $this->isClassMethodPrivate($node, $classMethod)) {
                continue;
            }

            if ($classMethod->isPublic()) {
                continue;
            }

            $methodCalls = $this->localMethodCallFinder->match($node, $classMethod);
            $classMethodParameterTypes = $this->callTypesResolver->resolveStrictTypesFromCalls($methodCalls);

            $classMethod = $this->classMethodParamTypeCompleter->complete(
                $classMethod,
                $classMethodParameterTypes,
                self::MAX_UNION_TYPES
            );

            if ($classMethod instanceof ClassMethod) {
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function isClassMethodPrivate(Class_ $class, ClassMethod $classMethod): bool
    {
        if ($classMethod->isPrivate()) {
            return true;
        }

        if ($classMethod->isFinal() && ! $class->extends instanceof Name && $class->implements === []) {
            return true;
        }

        return $class->isFinal() && ! $class->extends instanceof Name && $class->implements === [] && $classMethod->isProtected();
    }
}
