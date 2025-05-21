<?php

namespace Rector\Php84\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\AttributeGroup;
use PHPStan\PhpDocParser\Ast\PhpDoc\DeprecatedTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class DeprecatedAnnotationToDeprecatedAttributeRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocTagRemover $phpDocTagRemover,
        private readonly PhpAttributeGroupFactory $phpAttributeGroupFactory,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
    )
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change @deprecated annotation to Deprecated attribute', [
            new CodeSample(<<<'CODE_SAMPLE'
/**
 * @deprecated 1.0 Use SomeOtherClass instead
 */
class SomeClass
{
}
CODE_SAMPLE,
            <<<'CODE_SAMPLE'
#[\Deprecated('1.0', 'Use SomeOtherClass instead')]
class SomeClass
{
}
CODE_SAMPLE

)
        ]);
    }

    public function getNodeTypes(): array
    {
        return [
            Node\Stmt\Class_::class,
            Node\Stmt\Interface_::class,
            Node\Stmt\Trait_::class,
            Node\Stmt\Function_::class,
            Node\Stmt\ClassMethod::class,
            Node\Stmt\Property::class,
            Node\Stmt\Const_::class,
        ];
    }

    /**
     * @param Node\Stmt\Class_|Node\Stmt\Interface_|Node\Stmt\Trait_|Node\Stmt\Function_|Node\Stmt\ClassMethod|Node\Stmt\Property|Node\Stmt\Const_ $node
     * @return Node\Stmt\Class_|Node\Stmt\Interface_|Node\Stmt\Trait_|Node\Stmt\Function_|Node\Stmt\ClassMethod|Node\Stmt\Property|Node\Stmt\Const_
     */
    public function refactor(Node $node): ?Node
    {
        $hasChanged = false;
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if ($phpDocInfo instanceof PhpDocInfo) {
            $requiresAttributeGroups = $this->handleDeprecated($phpDocInfo);
            if ($requiresAttributeGroups !== []) {
                $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);
                $node->attrGroups = array_merge($node->attrGroups, $requiresAttributeGroups);
                $this->removeDeprecatedAnnotations($phpDocInfo);
                $hasChanged = true;
            }
        }

        return $hasChanged ? $node : null;
    }

    /**
     * @return array<string, AttributeGroup|null>
     */
    private function handleDeprecated(PhpDocInfo $phpDocInfo): array
    {
        $attributeGroups = [];
        $desiredTagValueNodes = $phpDocInfo->getTagsByName('deprecated');
        foreach ($desiredTagValueNodes as $desiredTagValueNode) {
            if (! $desiredTagValueNode->value instanceof DeprecatedTagValueNode) {
                continue;
            }

            $attributeGroups[0] = $this->createAttributeGroup($desiredTagValueNode->value->description);
            $this->phpDocTagRemover->removeTagValueFromNode($phpDocInfo, $desiredTagValueNode);
        }

        return $attributeGroups;
    }

    private function createAttributeGroup(string $annotationValue): ?AttributeGroup
    {
        $pattern = '/^(?:(\d+\.\d+\.\d+)\s+)?(.*)$/';
        preg_match($pattern, $annotationValue, $matches);

        $since = $matches[1] ?? null;
        $message = $matches[2] ?? null;

        return $this->phpAttributeGroupFactory->createFromClassWithItems('Deprecated', array_filter([
            'message' => $message,
            'since' => $since,
        ]));
    }

    private function removeDeprecatedAnnotations(PhpDocInfo $phpDocInfo): bool
    {
        $hasChanged = false;

        $desiredTagValueNodes = $phpDocInfo->getTagsByName('deprecated');
        foreach ($desiredTagValueNodes as $desiredTagValueNode) {
            if (! $desiredTagValueNode->value instanceof GenericTagValueNode) {
                continue;
            }

            $this->phpDocTagRemover->removeTagValueFromNode($phpDocInfo, $desiredTagValueNode);
            $hasChanged = true;
        }

        return $hasChanged;
    }
}
