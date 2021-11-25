<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\Rector\AbstractRector;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveUnusedParamInRequiredAutowireRector extends AbstractRector
{
    public function __construct(private PhpAttributeAnalyzer $phpAttributeAnalyzer)
    {
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

        return $node;
    }

    private function shouldSkip(ClassMethod $classMethod): bool
    {
        $phpDocInfo            = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
        $hasRequiredAnnotation = $phpDocInfo->hasByName('required');
        $hasRequiredAttribute  = $this->phpAttributeAnalyzer->hasPhpAttribute($classMethod, 'Symfony\Contracts\Service\Attribute\Required');

        return ! $hasRequiredAnnotation && ! $hasRequiredAttribute;
    }
}
