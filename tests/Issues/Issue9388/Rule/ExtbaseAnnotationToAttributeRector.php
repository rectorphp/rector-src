<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\Issue9388\Rule;

use PhpParser\Node;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Use_;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\Php80\NodeFactory\AttrGroupsFactory;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Php80\ValueObject\DoctrineTagAndAnnotationToAttribute;
use Rector\Rector\AbstractRector;
use Rector\Tests\Issues\Issue9388\AnnotationToAttribute\AttributeDecorator;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ExtbaseAnnotationToAttributeRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var AnnotationToAttribute[]
     */
    private array $annotationsToAttributes;

    public function __construct(
        private readonly AttributeDecorator $attributeDecorator,
        private readonly AttrGroupsFactory $attrGroupsFactory,
        private readonly PhpDocTagRemover $phpDocTagRemover,
        private readonly UseImportsResolver $useImportsResolver,
        private readonly PhpAttributeAnalyzer $phpAttributeAnalyzer,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly PhpDocInfoFactory $phpDocInfoFactory
    ) {
        $this->annotationsToAttributes = [
            new AnnotationToAttribute('TYPO3\CMS\Extbase\Annotation\Validate'),
        ];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change annotation to attribute', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Annotation as Extbase;

class MyEntity
{
    /**
     * @Extbase\ORM\Transient()
     */
    protected string $myProperty;
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Annotation as Extbase;

class MyEntity
{
    #[Extbase\ORM\Transient()]
    protected string $myProperty;
}
CODE_SAMPLE
        )]);
    }

    public function getNodeTypes(): array
    {
        return [Property::class];
    }

    /**
     * @param Property $node
     */
    public function refactor(Node $node): ?Node
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        $uses = $this->useImportsResolver->resolveBareUses();
        $annotationAttributeGroups = $this->processDoctrineAnnotationClasses($phpDocInfo, $uses);
        if ($annotationAttributeGroups === []) {
            return null;
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        foreach ($annotationAttributeGroups as $attributeGroup) {
            foreach ($attributeGroup->attrs as $attr) {
                $phpAttributeName = $attr->name->getAttribute(AttributeKey::PHP_ATTRIBUTE_NAME);
                $this->attributeDecorator->decorate($phpAttributeName, $attr);
            }
        }

        $node->attrGroups = \array_merge($node->attrGroups, $annotationAttributeGroups);
        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ATTRIBUTES;
    }

    /**
     * @param Use_[] $uses
     * @return AttributeGroup[]
     */
    private function processDoctrineAnnotationClasses(PhpDocInfo $phpDocInfo, array $uses): array
    {
        if ($phpDocInfo->getPhpDocNode()->children === []) {
            return [];
        }

        $doctrineTagAndAnnotationToAttributes = [];
        $doctrineTagValueNodes = [];
        foreach ($phpDocInfo->getPhpDocNode()->children as $phpDocChildNode) {
            if (! $phpDocChildNode instanceof PhpDocTagNode) {
                continue;
            }

            if (! $phpDocChildNode->value instanceof DoctrineAnnotationTagValueNode) {
                continue;
            }

            $doctrineTagValueNode = $phpDocChildNode->value;
            $annotationToAttribute = $this->matchAnnotationToAttribute($doctrineTagValueNode);
            if (! $annotationToAttribute instanceof AnnotationToAttribute) {
                continue;
            }

            // Fix the missing leading slash in most of the wild use cases
            if (str_starts_with($doctrineTagValueNode->identifierTypeNode->name, '@TYPO3\CMS')) {
                $doctrineTagValueNode->identifierTypeNode->name = str_replace(
                    '@TYPO3\CMS',
                    '@\\TYPO3\CMS',
                    $doctrineTagValueNode->identifierTypeNode->name
                );
            }

            $doctrineTagAndAnnotationToAttributes[] = new DoctrineTagAndAnnotationToAttribute(
                $doctrineTagValueNode,
                $annotationToAttribute
            );
            $doctrineTagValueNodes[] = $doctrineTagValueNode;
        }

        $attributeGroups = $this->attrGroupsFactory->create($doctrineTagAndAnnotationToAttributes, $uses);
        if ($this->phpAttributeAnalyzer->hasRemoveArrayState($attributeGroups)) {
            return [];
        }

        foreach ($doctrineTagValueNodes as $doctrineTagValueNode) {
            $this->phpDocTagRemover->removeTagValueFromNode($phpDocInfo, $doctrineTagValueNode);
        }

        return $attributeGroups;
    }

    private function matchAnnotationToAttribute(
        DoctrineAnnotationTagValueNode $doctrineAnnotationTagValueNode
    ): ?AnnotationToAttribute {
        foreach ($this->annotationsToAttributes as $annotationToAttribute) {
            if (! $doctrineAnnotationTagValueNode->hasClassName($annotationToAttribute->getTag())) {
                continue;
            }

            return $annotationToAttribute;
        }

        return null;
    }
}
