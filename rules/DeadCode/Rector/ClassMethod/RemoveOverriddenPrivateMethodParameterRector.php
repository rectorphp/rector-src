<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\DeadCode\NodeCollector\OverriddenParameterResolver;
use Rector\DeadCode\NodeManipulator\PrivateMethodParamRemover;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\MethodName;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\RemoveOverriddenPrivateMethodParameterRectorTest
 */
final class RemoveOverriddenPrivateMethodParameterRector extends AbstractRector
{
    public function __construct(
        private readonly OverriddenParameterResolver $overriddenParameterResolver,
        private readonly PrivateMethodParamRemover $privateMethodParamRemover,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove parameter of private method, that is overridden by direct assign before its first use',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        return $this->create(new Value());
    }

    private function create($value)
    {
        $value = new AnotherValue();

        return $value;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        return $this->create();
    }

    private function create()
    {
        $value = new AnotherValue();

        return $value;
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
        $hasChanged = false;

        foreach ($node->getMethods() as $classMethod) {
            if (! $classMethod->isPrivate()) {
                continue;
            }

            // constructor is called via new, that is not covered by caller args cleanup
            if ($this->isName($classMethod, MethodName::CONSTRUCT)) {
                continue;
            }

            $overriddenParameters = $this->overriddenParameterResolver->resolve($classMethod);
            if ($overriddenParameters === []) {
                continue;
            }

            if ($this->privateMethodParamRemover->removeParams($node, $classMethod, $overriddenParameters)) {
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }
}
