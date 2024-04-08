<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\DeadCode\PhpDoc\DeadParamTagValueNodeAnalyzer;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\ValueObject\Type\NonExistingObjectType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\RemoveUselessTypeInParamTagRectorTest
 */
final class RemoveUselessTypeInParamTagRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DeadParamTagValueNodeAnalyzer $deadParamTagValueNodeAnalyzer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove the type of the @param docblock if the parameter type is defined in the function signature',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @param string $a
     * @param string $b description
     */
    public function foo(string $a, string $b)
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @param $a
     * @param $b description
     */
    public function foo(string $a, string $b)
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
        return [ClassMethod::class, Function_::class];
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        $params = $node->getParams();
        $hasChanged = false;
        foreach ($params as $signatureParam) {
            if ($signatureParam->type === null) {
                continue;
            }

            $paramName = $this->getName($signatureParam->var);
            if ($paramName === null) {
                continue;
            }

            $paramTag = $phpDocInfo->getParamTagValueByName($paramName);
            if ($paramTag === null) {
                continue;
            }

            if (! $this->deadParamTagValueNodeAnalyzer->isDead($paramTag, $node, false)) {
                continue;
            }

            $this->phpDocTypeChanger->changeParamType(
                $node,
                $phpDocInfo,
                new NonExistingObjectType(''),
                $signatureParam,
                $paramName
            );

            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }
}
