<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\NodeAnalyzer\ParamAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedParamInRequiredAutowireRector\RemoveUnusedParamInRequiredAutowireRectorTest
 */
final class RemoveUnusedParamInRequiredAutowireRector extends AbstractRector
{
    public function __construct(
        private ParamAnalyzer $paramAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove unused parameter in required autowire method', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    private $visibilityManipulator;

    public function autowireAbstractRector(VisibilityManipulator $visibilityManipulator)
    {
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function autowireAbstractRector()
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
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $methodName = (string) $this->nodeNameResolver->getName($node);
        if (! str_starts_with($methodName, 'autowire')) {
            return null;
        }

        return $node;
    }
}
