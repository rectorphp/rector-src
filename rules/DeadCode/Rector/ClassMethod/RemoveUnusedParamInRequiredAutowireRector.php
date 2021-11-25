<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\Rector\AbstractRector;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedParamInRequiredAutowireRector\RemoveUnusedParamInRequiredAutowireRectorTest
 */
final class RemoveUnusedParamInRequiredAutowireRector extends AbstractRector
{
    public function __construct(
        private PhpAttributeAnalyzer $phpAttributeAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove unused parameter in required autowire method', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use Symfony\Contracts\Service\Attribute\Required;

final class SomeService
{
    private $visibilityManipulator;

    #[Required]
    public function autowireSomeService(VisibilityManipulator $visibilityManipulator)
    {
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
use Symfony\Contracts\Service\Attribute\Required;

final class SomeService
{
    private $visibilityManipulator;

    #[Required]
    public function autowireSomeService()
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $params = $node->params;
        if ($params === []) {
            return null;
        }

        /** @var Variable[] $variables */
        $variables = $this->betterNodeFinder->findInstanceOf((array) $node->getStmts(), Variable::class);
        $hasRemovedParam = false;

        foreach ($params as $param) {
            $paramVar = $param->var;
            foreach ($variables as $variable) {
                if ($this->nodeComparator->areNodesEqual($variable, $paramVar)) {
                    continue 2;
                }
            }

            $this->removeNode($param);
            $hasRemovedParam = true;
        }

        if (! $hasRemovedParam) {
            return null;
        }

        return $node;
    }

    private function shouldSkip(ClassMethod $classMethod): bool
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
        $hasRequiredAnnotation = $phpDocInfo->hasByName('required');
        $hasRequiredAttribute = $this->phpAttributeAnalyzer->hasPhpAttribute(
            $classMethod,
            'Symfony\Contracts\Service\Attribute\Required'
        );

        return ! $hasRequiredAnnotation && ! $hasRequiredAttribute;
    }
}
