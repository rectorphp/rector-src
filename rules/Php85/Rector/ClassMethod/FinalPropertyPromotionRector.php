<?php

declare(strict_types=1);

namespace Rector\Php85\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\MethodName;
use Rector\ValueObject\PhpVersionFeature;
use Rector\ValueObject\Visibility;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php85\Rector\ClassMethod\FinalPropertyPromotionRector\FinalPropertyPromotionRectorTest
 */
final class FinalPropertyPromotionRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var string
     */
    private const TAGNAME = 'final';

    public function __construct(
        private readonly VisibilityManipulator $visibilityManipulator,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Promotes constructor properties in final classes', [
            new CodeSample(
                <<<'CODE_SAMPLE'
public function __construct(
    /** @final */
    public string $id
) {}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
public function __construct(
    final public string $id
) {}
CODE_SAMPLE
            ),
        ]);
    }
    
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?ClassMethod
    {

        if (! $this->isName($node, MethodName::CONSTRUCT)) {
            return null;
        }

        $hasChanged = false;
        foreach ($node->params as $param) {
            if (! $param->isPromoted()) {
                continue;
            }

            if (! $this->visibilityManipulator->hasVisibility($param, Visibility::PUBLIC)) {
                continue;
            }
        
            $this->removePhpDocTag($param);
            $this->visibilityManipulator->makeFinal($param);

            $hasChanged = true;

        }

        if($hasChanged){
            return $node;
        }

        return null;
    }

    private function removePhpDocTag(Property|Param $node): bool
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        if (!$phpDocInfo->hasByName(self::TAGNAME)) {
            return false;
        }

        $phpDocInfo->removeByName(self::TAGNAME);
        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
        return true;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATED_ATTRIBUTE_ON_CONSTANT;
    }
}
