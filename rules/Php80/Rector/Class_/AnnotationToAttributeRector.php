<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Use_;
use PHPStan\PhpDocParser\Ast\Node as DocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\Reflection\ReflectionProvider;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Exception\Configuration\InvalidConfigurationException;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\Php80\NodeFactory\AttrGroupsFactory;
use Rector\Php80\NodeManipulator\AttributeGroupNamedArgumentManipulator;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Php80\ValueObject\DoctrineTagAndAnnotationToAttribute;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\AnnotationToAttributeRectorTest
 * @see \Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Php81NestedAttributesRectorTest
 */
final class AnnotationToAttributeRector extends AbstractRector implements ConfigurableRectorInterface, MinPhpVersionInterface
{
    /**
     * @var AnnotationToAttribute[]
     */
    private array $annotationsToAttributes = [];

    public function __construct(
        private readonly PhpAttributeGroupFactory $phpAttributeGroupFactory,
        private readonly AttrGroupsFactory $attrGroupsFactory,
        private readonly PhpDocTagRemover $phpDocTagRemover,
        private readonly AttributeGroupNamedArgumentManipulator $attributeGroupNamedArgumentManipulator,
        private readonly UseImportsResolver $useImportsResolver,
        private readonly PhpAttributeAnalyzer $phpAttributeAnalyzer,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change annotation to attribute', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
use Symfony\Component\Routing\Annotation\Route;

class SymfonyRoute
{
    /**
     * @Route("/path", name="action") api route
     */
    public function action()
    {
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Symfony\Component\Routing\Annotation\Route;

class SymfonyRoute
{
    #[Route(path: '/path', name: 'action')] // api route
    public function action()
    {
    }
}
CODE_SAMPLE
                ,
                [new AnnotationToAttribute('Symfony\Component\Routing\Annotation\Route')]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [
            Class_::class,
            Property::class,
            Param::class,
            ClassMethod::class,
            Function_::class,
            Closure::class,
            ArrowFunction::class,
            Interface_::class,
        ];
    }

    /**
     * @param Class_|Property|Param|ClassMethod|Function_|Closure|ArrowFunction|Interface_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->annotationsToAttributes === []) {
            throw new InvalidConfigurationException(sprintf('The "%s" rule requires configuration.', self::class));
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        $uses = $this->useImportsResolver->resolveBareUses();

        // 1. bare tags without annotation class, e.g. "@require"
        $genericAttributeGroups = $this->processGenericTags($phpDocInfo);

        // 2. Doctrine annotation classes
        $annotationAttributeGroups = $this->processDoctrineAnnotationClasses($phpDocInfo, $uses);

        $attributeGroups = [...$genericAttributeGroups, ...$annotationAttributeGroups];
        if ($attributeGroups === []) {
            return null;
        }

        // 3. Reprint docblock
        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        $this->attributeGroupNamedArgumentManipulator->decorate($attributeGroups);
        $node->attrGroups = array_merge($node->attrGroups, $attributeGroups);

        return $node;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, AnnotationToAttribute::class);
        $this->annotationsToAttributes = $configuration;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ATTRIBUTES;
    }

    /**
     * @return AttributeGroup[]
     */
    private function processGenericTags(PhpDocInfo $phpDocInfo): array
    {
        $attributeGroups = [];

        $phpDocNodeTraverser = new PhpDocNodeTraverser();
        $phpDocNodeTraverser->traverseWithCallable($phpDocInfo->getPhpDocNode(), '', function (DocNode $docNode) use (
            &$attributeGroups,
        ): ?int {
            if (! $docNode instanceof PhpDocTagNode) {
                return null;
            }

            if (! $docNode->value instanceof GenericTagValueNode) {
                return null;
            }

            $tag = trim($docNode->name, '@');

            // not a basic one
            if (str_contains($tag, '\\')) {
                return null;
            }

            foreach ($this->annotationsToAttributes as $annotationToAttribute) {
                $desiredTag = $annotationToAttribute->getTag();
                if ($desiredTag !== $tag) {
                    continue;
                }

                // make sure the attribute class really exists to avoid error on early upgrade
                if (! $this->reflectionProvider->hasClass($annotationToAttribute->getAttributeClass())) {
                    continue;
                }

                $attributeGroups[] = $this->phpAttributeGroupFactory->createFromSimpleTag($annotationToAttribute);
                return PhpDocNodeTraverser::NODE_REMOVE;
            }

            return null;
        });

        return $attributeGroups;
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

            // make sure the attribute class really exists to avoid error on early upgrade
            if (! $this->reflectionProvider->hasClass($annotationToAttribute->getAttributeClass())) {
                continue;
            }

            $doctrineTagAndAnnotationToAttributes[] = new DoctrineTagAndAnnotationToAttribute(
                $doctrineTagValueNode,
                $annotationToAttribute,
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
    ): AnnotationToAttribute|null {
        foreach ($this->annotationsToAttributes as $annotationToAttribute) {
            if (! $doctrineAnnotationTagValueNode->hasClassName($annotationToAttribute->getTag())) {
                continue;
            }

            return $annotationToAttribute;
        }

        return null;
    }
}
