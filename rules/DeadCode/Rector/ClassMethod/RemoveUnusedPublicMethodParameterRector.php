<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Configuration\Parameter\FeatureFlags;
use Rector\DeadCode\NodeManipulator\ClassMethodParamRemover;
use Rector\NodeAnalyzer\MagicClassMethodAnalyzer;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\RemoveUnusedPublicMethodParameterRectorTest
 */
final class RemoveUnusedPublicMethodParameterRector extends AbstractRector
{
    public function __construct(
        private readonly ClassMethodParamRemover $classMethodParamRemover,
        private readonly MagicClassMethodAnalyzer $magicClassMethodAnalyzer,
        private readonly PhpAttributeAnalyzer $phpAttributeAnalyzer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove unused parameter in public method on final class without extends and interface',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run($a, $b)
    {
        echo $a;
    }
}
CODE_SAMPLE

                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run($a)
    {
        echo $a;
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
        // may have child, or override parent that needs to follow the signature
        if (! $node->isFinal() && FeatureFlags::treatClassesAsFinal($node) === false) {
            return null;
        }

        if ($node->extends instanceof FullyQualified || $node->implements !== []) {
            return null;
        }

        $hasChanged = false;
        foreach ($node->getMethods() as $classMethod) {
            if ($this->shouldSkipClassMethod($classMethod, $node)) {
                continue;
            }

            $changedMethod = $this->classMethodParamRemover->processRemoveParams($classMethod);
            if (! $changedMethod instanceof ClassMethod) {
                continue;
            }

            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function shouldSkipClassMethod(ClassMethod $classMethod, Class_ $class): bool
    {
        // private method is handled by different rule
        if (! $classMethod->isPublic()) {
            return true;
        }

        if ($classMethod->params === []) {
            return true;
        }

        // parameter is required for contract coupling
        if ($this->isName($classMethod->name, '__invoke') && $this->phpAttributeAnalyzer->hasPhpAttribute(
            $class,
            'Symfony\Component\Messenger\Attribute\AsMessageHandler'
        )) {
            return true;
        }

        return $this->magicClassMethodAnalyzer->isUnsafeOverridden($classMethod);
    }
}
