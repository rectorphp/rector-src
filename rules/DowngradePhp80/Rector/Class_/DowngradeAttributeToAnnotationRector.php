<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\DowngradePhp80\ValueObject\DowngradeAttributeToAnnotation;
use Rector\PhpAttribute\NodeFactory\DoctrineAnnotationFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @changelog https://php.watch/articles/php-attributes#syntax
 *
 * @see \Rector\Tests\DowngradePhp80\Rector\Class_\DowngradeAttributeToAnnotationRector\DowngradeAttributeToAnnotationRectorTest
 */
final class DowngradeAttributeToAnnotationRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var DowngradeAttributeToAnnotation[]
     */
    private array $attributesToAnnotations = [];

    private bool $isDowngraded = false;

    public function __construct(
        private readonly DoctrineAnnotationFactory $doctrineAnnotationFactory
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor PHP attribute markers to annotations notation', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
use Symfony\Component\Routing\Annotation\Route;

class SymfonyRoute
{
    #[Route(path: '/path', name: 'action')]
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
    /**
     * @Route("/path", name="action")
     */
    public function action()
    {
    }
}
CODE_SAMPLE
                ,
                [new DowngradeAttributeToAnnotation('Symfony\Component\Routing\Annotation\Route')]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class, ClassMethod::class, Property::class, Interface_::class, Param::class, Function_::class];
    }

    /**
     * @param Class_|ClassMethod|Property|Interface_|Param|Function_  $node
     */
    public function refactor(Node $node): ?Node
    {
        $this->isDowngraded = false;
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $key => $attribute) {
                $attributeToAnnotation = $this->matchAttributeToAnnotation($attribute, $this->attributesToAnnotations);
                if (! $attributeToAnnotation instanceof DowngradeAttributeToAnnotation) {
                    continue;
                }

                unset($attrGroup->attrs[$key]);

                if (! \str_contains($attributeToAnnotation->getTag(), '\\')) {
                    $phpDocInfo->addPhpDocTagNode(
                        new PhpDocTagNode('@' . $attributeToAnnotation->getTag(), new GenericTagValueNode(''))
                    );
                } else {
                    $doctrineAnnotation = $this->doctrineAnnotationFactory->createFromAttribute(
                        $attribute,
                        $attributeToAnnotation->getTag()
                    );
                    $phpDocInfo->addTagValueNode($doctrineAnnotation);
                }

                $this->isDowngraded = true;
            }
        }

        // cleanup empty attr groups
        $this->cleanupEmptyAttrGroups($node);

        if (! $this->isDowngraded) {
            return null;
        }

        return $node;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, DowngradeAttributeToAnnotation::class);

        $this->attributesToAnnotations = $configuration;
    }

    private function cleanupEmptyAttrGroups(
        ClassMethod | Property | Class_ | Interface_ | Param | Function_ $node
    ): void {
        foreach ($node->attrGroups as $key => $attrGroup) {
            if ($attrGroup->attrs !== []) {
                continue;
            }

            unset($node->attrGroups[$key]);
            $this->isDowngraded = true;
        }
    }

    /**
     * @param DowngradeAttributeToAnnotation[] $attributesToAnnotations
     */
    private function matchAttributeToAnnotation(
        Attribute $attribute,
        array $attributesToAnnotations
    ): ?DowngradeAttributeToAnnotation {
        foreach ($attributesToAnnotations as $attributeToAnnotation) {
            if (! $this->isName($attribute->name, $attributeToAnnotation->getAttributeClass())) {
                continue;
            }

            return $attributeToAnnotation;
        }

        return null;
    }
}
