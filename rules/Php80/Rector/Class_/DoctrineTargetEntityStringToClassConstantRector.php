<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocParser\ClassAnnotationMatcher;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class DoctrineTargetEntityStringToClassConstantRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ClassAnnotationMatcher $classAnnotationMatcher
    )
    {
    }

    public function changeTypeInAnnotationTypes(
        Node       $node,
        PhpDocInfo $phpDocInfo
    ): void
    {
        $doctrineAnnotationTagValueNode = $phpDocInfo->getByAnnotationClasses([
            'Doctrine\ORM\Mapping\OneToMany',
            'Doctrine\ORM\Mapping\ManyToOne',
            'Doctrine\ORM\Mapping\OneToOne',
            'Doctrine\ORM\Mapping\ManyToMany',
            'Doctrine\ORM\Mapping\Embedded',
        ]);

        if (!$doctrineAnnotationTagValueNode instanceof DoctrineAnnotationTagValueNode) {
            return;
        }

        $this->processDoctrineToMany($doctrineAnnotationTagValueNode, $node);
    }

    private function processDoctrineToMany(
        DoctrineAnnotationTagValueNode $doctrineAnnotationTagValueNode,
        Node                           $node
    ): void
    {
        $key = $doctrineAnnotationTagValueNode->hasClassName(
            'Doctrine\ORM\Mapping\Embedded'
        ) ? 'class' : 'targetEntity';

        $targetEntity = $doctrineAnnotationTagValueNode->getValueWithoutQuotes($key);
        if ($targetEntity === null) {
            return;
        }

        // resolve to FQN
        $tagFullyQualifiedName = $this->classAnnotationMatcher->resolveTagFullyQualifiedName($targetEntity, $node);

        if ($tagFullyQualifiedName === $targetEntity) {
            return;
        }

        $doctrineAnnotationTagValueNode->removeValue($key);
        $doctrineAnnotationTagValueNode->values[$key] = '\\' . $tagFullyQualifiedName . '::class';
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert targetEntities defined as String to Class Constants in Doctrine Entities.', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @ORM\OneToMany(targetEntity="AnotherClass")
     */
    private readonly ?Collection $items;

    #[ORM\ManyToOne(targetEntity: "AnotherClass")]
    private readonly ?Collection $items2;
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @ORM\OneToMany(targetEntity=\Rector\Tests\Php80\Rector\Class_\DoctrineTargetEntityStringToClassConstantRector\Source\AnotherClass::class)
     */
    private readonly ?Collection $items;

    #[ORM\ManyToOne(targetEntity: \Rector\Tests\Php80\Rector\Class_\DoctrineTargetEntityStringToClassConstantRector\Source\AnotherClass::class)]
    private readonly ?Collection $items2;
}
CODE_SAMPLE
            ),
        ]);
    }

    public function provideMinPhpVersion(): int
    {
        // The minimum Version is PHP 5.5, because we need classname constants,
        // and support Annotations as well as Attributes.
//        return PhpVersionFeature::ATTRIBUTES;
        return PhpVersionFeature::CLASSNAME_CONSTANT;
    }

    public function getNodeTypes(): array
    {
        return [Property::class];
    }

    public function refactor(Node $node)
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $this->changeTypeInAnnotationTypes($node, $phpDocInfo);
    }
}
