<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\Class_;

use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Property;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocParser\ClassAnnotationMatcher;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Doctrine\NodeAnalyzer\AttributeFinder;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php80\Rector\Class_\DoctrineTargetEntityStringToClassConstantRector\DoctrineTargetEntityStringToClassConstantRectorTest
 */
final class DoctrineTargetEntityStringToClassConstantRector extends AbstractRector implements MinPhpVersionInterface
{
    private const ATTRIBUTE_NAME__TARGET_ENTITY = 'targetEntity';

    private const ATTRIBUTE_NAME__CLASS = 'class';

    /**
     * @var array<class-string<OneToMany|ManyToOne|OneToOne|ManyToMany|Embedded>, string>
     */
    private const VALID_DOCTRINE_CLASSES = [
        OneToMany::class => self::ATTRIBUTE_NAME__TARGET_ENTITY,
        ManyToOne::class => self::ATTRIBUTE_NAME__TARGET_ENTITY,
        OneToOne::class => self::ATTRIBUTE_NAME__TARGET_ENTITY,
        ManyToMany::class => self::ATTRIBUTE_NAME__TARGET_ENTITY,
        Embedded::class => self::ATTRIBUTE_NAME__CLASS,
    ];

    public function __construct(
        private readonly ClassAnnotationMatcher $classAnnotationMatcher,
        private readonly AttributeFinder $attributeFinder
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert targetEntities defined as String to <class>::class Constants in Doctrine Entities.',
            [
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

            ]
        );
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

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Property) {
            return null;
        }

        $hasChanged = false;
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($node);
        if ($phpDocInfo !== null) {
            $changedNode = $this->changeTypeInAnnotationTypes($node, $phpDocInfo);
            $hasChanged = !($changedNode === null) || $phpDocInfo->hasChanged();
        }

        return $this->changeTypeInAttributeTypes($node, $hasChanged);
    }

    private function changeTypeInAttributeTypes(Property $node, bool $hasChanged): ?Property
    {
        $attribute = $this->attributeFinder->findAttributeByClasses($node, $this->getAttributeClasses());

        if ($attribute === null) {
            return $hasChanged ? $node : null;
        }

        $attributeName = $this->getAttributeName($attribute);
        foreach ($attribute->args as $arg) {
            $argName = $arg->name;
            if (! $argName instanceof Identifier) {
                continue;
            }

            if (! $this->isName($argName, $attributeName)) {
                continue;
            }

            $value = $this->valueResolver->getValue($arg->value);
            $fullyQualified = $this->classAnnotationMatcher->resolveTagFullyQualifiedName($value, $node);

            if ($fullyQualified === $value) {
                continue;
            }

            $arg->value = $this->nodeFactory->createClassConstFetch($fullyQualified, 'class');

            return $node;
        }

        return $hasChanged ? $node : null;
    }

    private function changeTypeInAnnotationTypes(Property $node, PhpDocInfo $phpDocInfo): ?Property
    {
        $doctrineAnnotationTagValueNode = $phpDocInfo->getByAnnotationClasses($this->getAttributeClasses());

        if (! $doctrineAnnotationTagValueNode instanceof DoctrineAnnotationTagValueNode) {
            return null;
        }

        return $this->processDoctrineToMany($doctrineAnnotationTagValueNode, $node);
    }

    private function processDoctrineToMany(
        DoctrineAnnotationTagValueNode $doctrineAnnotationTagValueNode,
        Property $node
    ): ?Property {
        $key = $doctrineAnnotationTagValueNode->hasClassName(
            Embedded::class
        ) ? self::ATTRIBUTE_NAME__CLASS : self::ATTRIBUTE_NAME__TARGET_ENTITY;

        $targetEntity = $doctrineAnnotationTagValueNode->getValueWithoutQuotes($key);
        if ($targetEntity === null) {
            return null;
        }

        // resolve to FQN
        $tagFullyQualifiedName = $this->classAnnotationMatcher->resolveTagFullyQualifiedName($targetEntity, $node);

        if ($tagFullyQualifiedName === $targetEntity) {
            return null;
        }

        $doctrineAnnotationTagValueNode->removeValue($key);
        $doctrineAnnotationTagValueNode->values[$key] = '\\' . $tagFullyQualifiedName . '::class';

        return $node;
    }

    /**
     * @return class-string[]
     */
    private function getAttributeClasses(): array
    {
        return array_keys(self::VALID_DOCTRINE_CLASSES);
    }

    private function getAttributeName(Attribute $attribute): string
    {
        return self::VALID_DOCTRINE_CLASSES[$attribute->name->toString()];
    }
}
